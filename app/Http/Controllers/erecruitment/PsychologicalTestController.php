<?php

namespace App\Http\Controllers\erecruitment;

use App\Http\Controllers\Controller;
use App\Mail\AdministrativeSelectionPassMail;
use App\Mail\AdministrativeSelectionRejectMail;
use App\Models\erecruitment\table\DtlApplicantVacancy;
use App\Models\erecruitment\table\DtlEducation;
use App\Models\erecruitment\table\DtlHistory;
use App\Models\erecruitment\table\DtlJobExperience;
use App\Models\erecruitment\table\MsDomicile;
use App\Models\erecruitment\table\MsJobDesc;
use App\Models\erecruitment\table\PsychologicalTestResult;
use App\Models\erecruitment\table\PsychologicalTestSubscore;
use App\Models\erecruitment\table\TrxInputApplication;
use App\Models\erecruitment\view\VwInputApplicantion;
use App\Models\erecruitment\view\VwPsychologicalTest;
use DateTime;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PsychologicalTestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $data = VwPsychologicalTest::all();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('erecruitment.psychological-test.action')->with('data', $data);
                })
                ->make(true);
        }
        return view('erecruitment.psychological-test.index');
    }

    public function getApplicantsData($id)
    {
        // Mengambil data pelamar dengan paginasi
        $applicants = VwInputApplicantion::where('vacancy', $id)
            ->where('status', 'In Process')
            ->where('last_stage', 'Psychological Test')
            ->paginate(10);

        // Melakukan perulangan untuk setiap applicant untuk menghitung age dan mengambil domicile
        foreach ($applicants as $applicant) {
            // Menghitung age berdasarkan birth_date
            if ($applicant->birth_date) {
                $birthDate = new DateTime($applicant->birth_date);
                $currentDate = new DateTime();
                $applicant->age = $currentDate->diff($birthDate)->y; // Umur dalam tahun
            } else {
                $applicant->age = 'Not Available';
            }
        }

        return view('erecruitment.psychological-test.indexApplicants', [
            'jobVacancyId' => $id,
            'applicants' => $applicants
        ]);
    }

    public function approvalAdministrative(Request $request)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|string',
            'invite_vacancy' => 'nullable|string',
            'invite_stage' => 'nullable|string',
        ]);

        $status = $request->input('status');
        $applicantIds = $request->input('applicants'); // Ambil applicant IDs
        $vacancyId = $request->input('vacancy_id'); // Ambil ID vacancy
        $inviteVacancy = $request->input('invite_vacancy'); // Ambil data vacancy
        $inviteStage = $request->input('invite_stage'); // Ambil stage undangan

        // Pastikan $applicantIds adalah array dan tidak kosong
        if (!is_array($applicantIds) || empty($applicantIds)) {
            return response()->json(['error' => 'No applicants provided.'], 400);
        }

        // Update status untuk semua pelamar yang dipilih berdasarkan kondisi
        if ($status === 'Initial Interview') {
            DtlApplicantVacancy::whereIn('applicant_id', $applicantIds)
                ->where('vacancy', $vacancyId)
                ->update([
                    'last_stage' => 'Initial Interview',
                    'psychological_status' => 'Pass Psychological Test',
                    'interview_status' => 'Waiting for invitation'
                ]);
        } elseif ($status === 'Failed Psychological Test') {
            DtlApplicantVacancy::whereIn('applicant_id', $applicantIds)
                ->where('vacancy', $vacancyId)
                ->update([
                    'status' => 'Rejected',
                    'psychological_status' => 'Failed Psychological Test'
                ]);
        } elseif ($status === 'Candidate Pooling') {
            DtlApplicantVacancy::whereIn('applicant_id', $applicantIds)
                ->where('vacancy', $vacancyId)
                ->update([
                    'status' => 'Candidate Pooling',
                    'psychological_status' => 'Failed Psychological Test'
                ]);
        } elseif ($status === 'Invited') {
            DtlApplicantVacancy::whereIn('applicant_id', $applicantIds)
                ->where('vacancy', $vacancyId)
                ->update([
                    'status' => 'Invited',
                    'psychological_status' => 'Failed Psychological Test',
                    'invite_status' => 'Not yet confirmed',
                    'invite_vacancy' => $inviteVacancy,
                    'invite_stage' => $inviteStage
                ]);
        } else {
            DtlApplicantVacancy::whereIn('applicant_id', $applicantIds)
                ->where('vacancy', $vacancyId)
                ->update(['last_stage' => $status]);
        }

        return response()->json(['success' => 'Applicant status updated successfully.']);
    }

    public function importBulkProcess(Request $request)
    {
        // Validasi file yang diunggah
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|mimes:xlsx,xls',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Membuka file Excel
            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();

            // Ambil semua baris dari file Excel
            $headers = $worksheet->toArray(null, true, true, true);
            $subtestHeaders = $headers[2]; // Baris kedua untuk nama subtest
            $subtestDetails = $headers[3]; // Baris ketiga untuk Norm Score / Cut Off Score
            $subscoreColumns = [];

            // Iterasi seluruh kolom header pada baris ke-2
            foreach ($subtestHeaders as $key => $value) {
                if (!empty($value) && $value !== 'HASIL' && $value !== null) {
                    $normScoreKey = $key; // Kolom saat ini
                    $cutOffScoreKey = array_search('Cut Off Score', $subtestDetails) ?: null; // Cari pasangan kolom Cut Off Score

                    if ($subtestDetails[$normScoreKey] === 'Norm Score' && $subtestDetails[$cutOffScoreKey] === 'Cut Off Score') {
                        $subscoreColumns[] = [
                            'subtest_name' => $value,
                            'norm_score_column' => $normScoreKey,
                            'cutoff_score_column' => $cutOffScoreKey,
                        ];
                    }
                }
            }

            if (empty($subscoreColumns)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ditemukan subtest yang valid dalam file Excel.',
                ], 400);
            }

            // Data dimulai dari baris ke-4
            $rows = array_slice($headers, 3); // Baris ke-4 ke bawah

            foreach ($rows as $row) {
                $applicantId = $row['B'] ?? null;
                $vacancyId = $row['G'] ?? null;
                $testDate = $row['H'] ?? null;
                $finalResult = $row['Y'] ?? null;

                if (!$applicantId || !$vacancyId || !$testDate || !$finalResult) {
                    continue; // Abaikan baris yang tidak lengkap
                }

                // Konversi tanggal ke format SQL
                try {
                    $testDateFormatted = Carbon::createFromFormat('m/d/Y', $testDate)->format('Y-m-d');
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => "Format tanggal tidak valid untuk test_date: $testDate",
                    ], 422);
                }

                // Membuat atau memperbarui hasil tes
                $testResult = PsychologicalTestResult::updateOrCreate(
                    ['applicant_id' => $applicantId, 'vacancy' => $vacancyId],
                    ['test_date' => $testDateFormatted, 'final_result' => $finalResult]
                );

                // Memproses skor subtest
                foreach ($subscoreColumns as $columns) {
                    $subtestName = $columns['subtest_name'];
                    $normScoreKey = $columns['norm_score_column']; 
                    $cutOffScoreKey = $columns['cutoff_score_column'];

                    $normScore = $row[$normScoreKey] ?? null;
                    $cutOffScore = $row[$cutOffScoreKey] ?? null;

                    // Validasi data skor
                    if (is_numeric($normScore) && is_numeric($cutOffScore)) {
                        PsychologicalTestSubscore::updateOrCreate(
                            ['test_result_id' => $testResult->id, 'subtest_name' => $subtestName],
                            [
                                'norm_score' => $normScore,
                                'cutoff_score' => $cutOffScore,
                                'status' => $normScore >= $cutOffScore ? 'Pass' : 'Fail',
                            ]
                        );
                    }
                }

                // Tambahkan logika untuk memperbarui DtlApplicantVacancy
                DtlApplicantVacancy::where('applicant_id', $applicantId)
                    ->where('vacancy', $vacancyId)
                    ->update([
                        'last_stage' => 'Initial Interview',
                        'psychological_status' => 'Pass Psychological Test',
                        'interview_status' => 'Waiting for invitation',
                    ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Import data psikotes berhasil diproses!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan selama proses impor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}