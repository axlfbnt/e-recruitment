<?php

namespace App\Http\Controllers\erecruitment;

use App\Http\Controllers\Controller;
use App\Models\erecruitment\table\MsManPowerPlanning;
use App\Models\erecruitment\view\VwCompanyStructure;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManPowerPlanningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $data = MsManPowerPlanning::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('erecruitment.man-power-planning.action')->with('data', $data);
                })
                ->make(true);
        }

        return view('erecruitment.man-power-planning.index');
    }

    public function getCompanies()
    {
        $companies = VwCompanyStructure::distinct()->get(['company_name']);
        return response()->json($companies);
    }

    public function getDepartments(Request $request)
    {
        $company_name = $request->input('company_name');
        $departments = VwCompanyStructure::where('company_name', $company_name)
            ->distinct()
            ->pluck('department');
        return response()->json(['departments' => $departments]);
    }

    public function getDivision(Request $request)
    {
        $company_name = $request->input('company_name');
        $department = $request->input('department');
        $division = VwCompanyStructure::where('company_name', $company_name)
            ->where('department', $department)
            ->pluck('division')
            ->first();

        return response()->json(['division' => $division]);
    }

    public function getPositions(Request $request)
    {
        $company_name = $request->input('company_name');
        $department = $request->input('department');
        $division = $request->input('division');

        $titlesString = VwCompanyStructure::where('company_name', $company_name)
            ->where('department', $department)
            ->where('division', $division)
            ->pluck('titles')
            ->first(); // Mengambil string titles dari hasil query

        // Pisahkan titles berdasarkan koma dan buat menjadi array
        $positions = $titlesString ? explode(', ', $titlesString) : [];

        return response()->json(['positions' => $positions]);
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
        // Validasi data jika diperlukan
        $request->validate([
            'company' => 'required|string',
            'department' => 'required|string',
            'division' => 'required|string',
            'position' => 'required|string',
            'position_status' => 'required|integer',
            'source_submission' => 'required|integer',
            'job_position' => 'required|string',
            'total_man_power' => 'required|integer',
            'last_education' => 'required|integer',
            'remarks' => 'nullable|string',
            'due_date' => 'required|date',
            'new_position' => 'nullable|string', // Tambahkan ini untuk memvalidasi new_position
        ]);

        // Ambil data dari request
        $data = $request->only([
            'company',
            'department',
            'division',
            'position',
            'position_status',
            'source_submission',
            'job_position',
            'total_man_power',
            'last_education',
            'remarks',
            'due_date',
            'new_position', // Ambil new_position jika ada
        ]);

        // Periksa apakah posisi adalah "Others"
        if ($request->input('position') === 'Others' && $request->has('new_position')) {
            $data['position'] = $request->input('new_position');
        }

        // Jika id_mpp tidak auto-increment, hitung nilai berikutnya
        $maxId = MsManPowerPlanning::withTrashed()->max('id_mpp');
        $nextId = $maxId ? $maxId + 1 : 1;
        $data['id_mpp'] = $nextId;

        $data['man_power_status'] = 'Open';
        $data['a1_status'] = 'Not Yet';
        $data['created_by'] = Auth::user()->id;

        // Simpan data ke database
        MsManPowerPlanning::create($data);

        // Redirect dengan pesan sukses
        return response()->json(['success' => "Successfully saved data"]);
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
        $data = MsManPowerPlanning::where("id_mpp", $id)->first();
        return response()->json(['result' => $data]);
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
            'company' => 'required|string',
            'department' => 'required|string',
            'division' => 'required|string',
            'position' => 'required|string',
            'position_status' => 'required|integer',
            'source_submission' => 'required|integer',
            'job_position' => 'required|string',
            'total_man_power' => 'required|integer',
            'last_education' => 'required|integer',
            'remarks' => 'nullable|string',
            'due_date' => 'required|date',
        ]);

        $data = $request->only([
            'company',
            'department',
            'division',
            'position',
            'position_status',
            'source_submission',
            'job_position',
            'total_man_power',
            'last_education',
            'remarks',
            'due_date',
        ]);

        $mpp = MsManPowerPlanning::findOrFail($id);
        $mpp->update($data);

        return response()->json([
            'success' => "Successfully updated data"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MsManPowerPlanning::where('id_mpp', $id)->delete();

        // Mengembalikan respons JSON sukses
        return response()->json(['message' => 'Data berhasil dihapus!']);
    }
}