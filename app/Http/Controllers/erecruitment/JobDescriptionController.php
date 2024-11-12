<?php

namespace App\Http\Controllers\erecruitment;

use App\Http\Controllers\Controller;
use App\Models\erecruitment\table\MsJobDesc;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobDescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $data = MsJobDesc::select('satria_erecruitment.ms_jobdesc.*', 'satria.users.name as updated_by_name')
                ->leftJoin('satria.users', 'satria.users.id', '=', 'satria_erecruitment.ms_jobdesc.updated_by');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('erecruitment.job-description.action')->with('data', $data);
                })
                ->filterColumn('updated_by_name', function ($query, $keyword) {
                    $query->whereRaw("LOWER(satria.users.name) like ?", ["%{$keyword}%"]);
                })
                ->make(true);
        }
        return view('erecruitment.job-description.index');
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
            'job_desc' => 'required|string',
            'new_position' => 'nullable|string', // Tambahkan ini untuk memvalidasi new_position
        ]);

        // Ambil data dari request
        $data = $request->only([
            'company',
            'department',
            'division',
            'position',
            'job_desc',
            'new_position',
        ]);

        // Periksa apakah posisi adalah "Others"
        if ($request->input('position') === 'Others' && $request->has('new_position')) {
            $data['position'] = $request->input('new_position');
        }

        // Jika id_jobdesc tidak auto-increment, hitung nilai berikutnya
        $maxId = MsJobDesc::withTrashed()->max('id_jobdesc');
        $nextId = $maxId ? $maxId + 1 : 1;
        $data['id_jobdesc'] = $nextId;

        $data['created_by'] = Auth::user()->id;

        // Simpan data ke database
        MsJobDesc::create($data);

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
        $data = MsJobDesc::where('id_jobdesc', $id)->first();

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
        // Validate incoming data
        $request->validate([
            'company' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'division' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'job_desc' => 'required|string',
        ]);

        // Find the job description by ID
        $jobDescription = MsJobDesc::find($id);

        if (!$jobDescription) {
            return response()->json(['success' => false, 'message' => 'Job description not found.'], 404);
        }

        // Update the job description with new data
        $jobDescription->company = $request->input('company');
        $jobDescription->department = $request->input('department');
        $jobDescription->division = $request->input('division');
        $jobDescription->position = $request->input('position');
        $jobDescription->job_desc = $request->input('job_desc');

        // Save the changes
        if ($jobDescription->save()) {
            return response()->json(['success' => true, 'message' => 'Job description updated successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to update job description.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MsJobDesc::where('id_jobdesc', $id)->delete();

        // Mengembalikan respons JSON sukses
        return response()->json(['message' => 'Data berhasil dihapus!']);
    }
}