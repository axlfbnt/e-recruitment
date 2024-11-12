<?php

namespace App\Http\Controllers\erecruitment;

use App\Http\Controllers\Controller;
use App\Models\erecruitment\table\TrxInputApplication;
use App\Models\erecruitment\view\VwInvitationApplicant;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;

class InvitationApplicantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $data = VwInvitationApplicant::all();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('erecruitment.invitation-applicant.action')->with('data', $data);
                })
                ->make(true);
        }
        return view('erecruitment.invitation-applicant.index');
    }

    public function getApplicantsData(Request $request, $id)
    {
        if ($request->expectsJson()) {
            $data = TrxInputApplication::query()
                ->join('ms_domicile', 'trx_inputapplication.domicile', '=', 'ms_domicile.id')
                ->where('applicant_status', 'Invited')
                ->where('invite_vacancy', $id)
                ->select('trx_inputapplication.*', 'ms_domicile.name as domicile_name');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('domicile', function ($data) {
                    return $data->domicile_name;
                })
                ->filterColumn('domicile', function ($query, $keyword) {
                    $query->whereRaw("LOWER(ms_domicile.name) LIKE ?", ["%" . strtolower($keyword) . "%"]);
                })
                ->make(true);
        }

        return view('erecruitment.invitation-applicant.indexApplicants', ['id_jobvacancy' => $id]);
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