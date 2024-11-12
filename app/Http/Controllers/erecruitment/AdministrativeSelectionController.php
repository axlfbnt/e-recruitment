<?php

namespace App\Http\Controllers\erecruitment;

use App\Http\Controllers\Controller;
use App\Mail\AdministrativeSelectionPassMail;
use App\Mail\AdministrativeSelectionRejectMail;
use App\Models\erecruitment\table\DtlEducation;
use App\Models\erecruitment\table\DtlHistory;
use App\Models\erecruitment\table\DtlJobExperience;
use App\Models\erecruitment\table\MsDomicile;
use App\Models\erecruitment\table\MsJobDesc;
use App\Models\erecruitment\table\TrxInputApplication;
use App\Models\erecruitment\view\VwAdministrativeSelection;
use DateTime;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class AdministrativeSelectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $data = VwAdministrativeSelection::all();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('erecruitment.administrative-selection.action')->with('data', $data);
                })
                ->make(true);
        }
        return view('erecruitment.administrative-selection.index');
    }

    public function getApplicantsData($id)
    {
        // Mengambil data pelamar dengan paginasi
        $applicants = TrxInputApplication::where('vacancy', $id)
            ->where('applicant_status', 'In Process')
            ->where('last_stage', 'Administrative Selection')
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

            // Mengambil nama domicile dari MsDomicile berdasarkan ID
            if ($applicant->domicile) {
                $domicile = MsDomicile::find($applicant->domicile);
                $applicant->domicile_name = $domicile ? $domicile->name : 'Not Available';
            } else {
                $applicant->domicile_name = 'Not Available';
            }
        }

        return view('erecruitment.administrative-selection.indexApplicants', [
            'jobVacancyId' => $id,
            'applicants' => $applicants
        ]);
    }

    public function applicantCV($id)
    {
        $cv = TrxInputApplication::findOrFail($id);
        $pathInfo = pathinfo($cv->cv_path);
        $filename = $pathInfo['basename'];

        // Perbaiki path agar sesuai dengan yang sudah digunakan untuk foto
        $cvPath = storage_path('app/public/cv/' . $filename);

        return Response::file($cvPath);
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
        if ($status === 'Psychological Test') {
            TrxInputApplication::whereIn('id', $applicantIds)
                ->update([
                    'last_stage' => 'Psychological Test',
                    'administrative_status' => 'Pass Administrative',
                    'psychological_status' => 'Waiting for invitation']);
        } elseif ($status === 'Reject Administrative Selection') {
            TrxInputApplication::whereIn('id', $applicantIds)
                ->update([
                    'applicant_status' => 'Rejected',
                    'administrative_status' => 'Reject Administrative']);
        } elseif ($status === 'Candidate Pooling') {
            TrxInputApplication::whereIn('id', $applicantIds)
                ->update([
                    'applicant_status' => 'Candidate Pooling',
                    'administrative_status' => 'Reject Administrative']);
        } elseif ($status === 'Invited') {
            TrxInputApplication::whereIn('id', $applicantIds)
                ->update([
                    'applicant_status' => 'Invited',
                    'administrative_status' => ' Reject Administrative',
                    'invite_status' => 'Not yet confirmed',
                    'invite_vacancy' => $inviteVacancy,
                    'invite_stage' => $inviteStage]);
        } else {
            TrxInputApplication::whereIn('id', $applicantIds)
                ->update(['last_stage' => $status]);
        }

        // Update history untuk setiap applicant
        foreach ($applicantIds as $applicantId) {
            DtlHistory::where('inputapplication_id', $applicantId)
                ->where('vacancy', $vacancyId)
                ->update([
                    'last_stage' => $status,
                    'status' => $status
                ]);
        }

        return response()->json(['success' => 'Applicant status updated successfully.']);
    }

    public function getApplicantHistory($id)
    {
        $history = DtlHistory::where('inputapplication_id', $id)->get();

        return DataTables::of($history)
            ->addIndexColumn()
            ->make(true);
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