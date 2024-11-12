<?php

namespace App\Http\Controllers\erecruitment;

use App\Http\Controllers\Controller;
use App\Models\erecruitment\table\MsFlowRecruitment;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FlowRecruitmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $data = MsFlowRecruitment::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('erecruitment.flow-recruitment.action')->with('data', $data);
                })
                ->make(true);
        }
        return view('erecruitment.flow-recruitment.index');
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
        // Validasi input yang diterima
        $request->validate([
            'template_name' => 'required|string',
            'recruitment_stage' => 'required|string',
        ]);

        // Dapatkan ID terbaru
        $maxId = MsFlowRecruitment::withTrashed()->max('id_flowrecruitment');
        $nextId = $maxId ? $maxId + 1 : 1;

        // Simpan data ke MsFormA1
        MsFlowRecruitment::create([
            'id_flowrecruitment' => $nextId,
            'template_name' => $request->input('template_name'),
            'recruitment_stage' => $request->input('recruitment_stage'),
            'created_by' => Auth::user()->id,
        ]);
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
        // Mengambil data menggunakan where dan first()
        $data = MsFlowRecruitment::where('id_flowrecruitment', $id)->first();

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
        // Validasi input dari request
        $request->validate([
            'template_name' => 'required|string',
            'recruitment_stage' => 'required|string',
        ]);

        $flowRecruitment = MsFlowRecruitment::where('id_flowrecruitment', $id)->first();
        if (!$flowRecruitment) {
            return response()->json(['error' => 'Flow Recruitment not found'], 404);
        }

        $updated = MsFlowRecruitment::where('id_flowrecruitment', $id)->update([
            'template_name' => $request->input('template_name'),
            'recruitment_stage' => $request->input('recruitment_stage'),
            'updated_by' => Auth::user()->id, 
        ]);

        if ($updated) {
            return response()->json(['success' => true, 'message' => 'Flow Recruitment updated successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to update Flow Recruitment.']);
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
        MsFlowRecruitment::where('id_flowrecruitment', $id)->delete();

        // Mengembalikan respons JSON sukses
        return response()->json(['message' => 'Data berhasil dihapus!']);
    }
}