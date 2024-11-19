<?php

namespace App\Http\Controllers\erecruitment;

use App\Http\Controllers\Controller;
use App\Models\erecruitment\table\DtlEducation;
use App\Models\erecruitment\table\DtlInternship;
use App\Models\erecruitment\table\DtlJobExperience;
use App\Models\erecruitment\table\DtlOrganization;
use App\Models\erecruitment\table\MsDegree;
use App\Models\erecruitment\table\MsDomicile;
use App\Models\erecruitment\table\MsFunction;
use App\Models\erecruitment\table\MsIndustry;
use App\Models\erecruitment\table\MsInstitution;
use App\Models\erecruitment\table\MsJobVacancy;
use App\Models\erecruitment\table\MsMajor;
use App\Models\erecruitment\table\TrxInputApplication;
use App\Models\erecruitment\view\VwInputApplicantion;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CandidatePoolingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $data = VwInputApplicantion::query()
                ->where('status', 'Candidate Pooling');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('domicile', function ($data) {
                    return $data->domicile_name;
                })
                ->addColumn('action', function ($data) {
                    return view('erecruitment.candidate-pooling.action')->with('data', $data);
                })
                ->filterColumn('domicile', function ($query, $keyword) {
                    $query->whereRaw("LOWER(ms_domicile.name) LIKE ?", ["%" . strtolower($keyword) . "%"]);
                })
                ->make(true);
        }

        return view('erecruitment.candidate-pooling.index');
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
        $request->validate([
            'vacancy_code' => 'required|string|max:255',
            'recruitment_stage' => 'required|string|max:255',
        ]);

        $application = TrxInputApplication::find($id);

        // Cek apakah data ditemukan
        if (!$application) {
            return response()->json(['error' => 'Application not found.'], 404);
        }

        // Update data di tabel trx_inputapplication
        $application->update([
            'applicant_status' => 'Invited',
            'invite_vacancy' => $request->input('vacancy_code'),
            'invite_stage' => $request->input('recruitment_stage'),
            'invite_status' => 'Not yet confirmed' 
        ]);

        return response()->json(['success' => 'Application data updated successfully.']);
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