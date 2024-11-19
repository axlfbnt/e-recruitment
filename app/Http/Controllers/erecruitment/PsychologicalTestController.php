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
use App\Models\erecruitment\table\TrxInputApplication;
use App\Models\erecruitment\view\VwInputApplicantion;
use App\Models\erecruitment\view\VwPsychologicalTest;
use DateTime;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
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
        // Validasi file
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
        ]);

        // Ambil ID vacancy
        $vacancyId = $request->input('vacancy_id');

        // Ambil file Excel
        $file = $request->file('excel_file');
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();

        // Menggunakan array_slice untuk menghapus baris pertama (header)
        $dataArray = array_slice($worksheet->toArray(null, true, true, true), 1);

        try {
            // Loop melalui data di Excel
            foreach ($dataArray as $row) {
                $applicantId = $row['A']; // Kolom A: Applicant ID
                $applicantName = $row['B']; // Kolom B: Nama Pelamar
                $status = $row['C']; // Kolom C: Status (Pass / Reject)

                // Cari pelamar berdasarkan nama
                $applicant = DtlApplicantVacancy::where('applicant_id', $applicantId)
                    ->where('vacancy', $vacancyId)
                    ->first();

                if ($applicant) {
                    // Pembaruan berdasarkan status
                    if ($status === 'Pass') {
                        // Update status untuk pelamar yang lulus
                        $applicant->update([
                            'last_stage' => 'Initial Interview',
                            'psychological_status' => 'Pass Psychological Test',
                            'interview_status' => 'Waiting for invitation',
                        ]);
                    } elseif ($status === 'Reject') {
                        // Update status untuk pelamar yang ditolak
                        $applicant->update([
                            'applicant_status' => 'Rejected',
                            'psychological_status' => 'Failed Psychological Test',
                        ]);
                    }
                }
            }

            // Setelah semua data diproses, redirect dengan pesan sukses
            return redirect()->route('psychological-test.index')->with('success', 'Bulk psychological test evaluation applied successfully!');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, tampilkan pesan error
            return redirect()->back()->with('error', 'An error occurred while importing data.');
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
