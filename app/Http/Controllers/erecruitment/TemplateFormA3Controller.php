<?php

namespace App\Http\Controllers\erecruitment;

use App\Http\Controllers\Controller;
use App\Models\erecruitment\table\DtlFormA3;
use App\Models\erecruitment\table\MsFormA3;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemplateFormA3Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $data = MsFormA3::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('erecruitment.template-forma3.action')->with('data', $data);
                })
                ->make(true);
        }
        return view('erecruitment.template-forma3.index');
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
        // Validasi data yang dikirim dari form
        $request->validate([
            'template_name' => 'required|string|max:255',
            'intelligence_aspect' => 'required|string',
            'definition' => 'required|string',
            'job_implication' => 'required|string',
            'total_aspects_score' => 'required|string',
        ]);

        // Simpan data ke ms_form_a3
        $formA3 = MsFormA3::create([
            'template_name' => $request->template_name,
            'status' => 'Inactive',
            'created_by' => auth()->user()->id,
        ]);

        $intelligenceAspects = explode(',', $request->intelligence_aspect);
        $definitions = explode(',', $request->definition);
        $jobImplications = explode(',', $request->job_implication);
        $totalAspectsScores = explode(',', $request->total_aspects_score);

        for ($i = 0; $i < count($intelligenceAspects); $i++) {
            DtlFormA3::create([
                'id_form_a3' => $formA3->id_form_a3,
                'intelligence_aspect' => $intelligenceAspects[$i],
                'definition' => $definitions[$i],
                'job_implication' => $jobImplications[$i],
                'total_aspects_score' => $totalAspectsScores[$i],
            ]);
        }

        // Return response sukses
        return response()->json(['success' => true, 'message' => 'Form A3 berhasil disimpan.']);
    }

    public function updateStatus(Request $request, $id)
    {
        // Validasi status yang dikirim
        $request->validate([
            'status' => 'required|in:Active,Inactive',
        ]);

        // Temukan template berdasarkan ID
        $template = MsFormA3::find($id);
        if (!$template) {
            return response()->json(['error' => 'Template tidak ditemukan.'], 404);
        }

        // Cek apakah user mencoba menonaktifkan satu-satunya template yang aktif
        if ($request->status == 'Inactive') {
            $activeTemplatesCount = MsFormA3::where('status', 'Active')->count();

            // Jika hanya ada satu template yang aktif, cegah perubahan status
            if ($activeTemplatesCount == 1 && $template->status == 'Active') {
                return response()->json(['error' => 'Minimal harus ada satu template yang aktif.'], 400);
            }
        }

        // Jika status yang diminta adalah 'Active', matikan template yang aktif saat ini
        if ($request->status == 'Active') {
            // Matikan template yang sedang aktif
            MsFormA3::where('status', 'Active')
                ->update(['status' => 'Inactive']);
        }

        // Perbarui status template yang dipilih
        $template->status = $request->status;
        $template->save();

        // Kembalikan respons sukses
        return response()->json(['success' => true, 'message' => 'Template status berhasil diperbarui.']);
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
        // Temukan template berdasarkan ID
        $template = MsFormA3::with('details')->find($id);

        if (!$template) {
            return response()->json(['error' => 'Template tidak ditemukan.'], 404);
        }

        $evaluations = [];
        foreach ($template->details as $detail) {
            $evaluations[] = [
                'intelligence_aspect' => $detail->intelligence_aspect,
                'definition' => $detail->definition,
                'job_implication' => $detail->job_implication,
                'total_aspects_score' => $detail->total_aspects_score,
            ];
        }

        return response()->json([
            'result' => [
                'template_name' => $template->template_name,
                'evaluations' => $evaluations
            ]
        ]);
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
        // Validasi data yang dikirim dari form
        $request->validate([
            'template_name' => 'required|string|max:255',
            'intelligence_aspect' => 'required|string',
            'definition' => 'required|string',
            'job_implication' => 'required|string',
            'total_aspects_score' => 'required|string',
        ]);

        // Temukan template berdasarkan ID dan update
        $formA3 = MsFormA3::find($id);
        if (!$formA3) {
            return response()->json(['error' => 'Template tidak ditemukan.'], 404);
        }

        $formA3->update([
            'template_name' => $request->template_name,
            'updated_by' => auth()->user()->id,
        ]);

        DtlFormA3::where('id_form_a3', $id)->delete();

        $intelligenceAspects = explode(',', $request->intelligence_aspect);
        $definitions = explode(',', $request->definition);
        $jobImplications = explode(',', $request->job_implication);
        $totalAspectsScores = explode(',', $request->total_aspects_score);

        for ($i = 0; $i < count($intelligenceAspects); $i++) {
            DtlFormA3::create([
                'id_form_a3' => $formA3->id_form_a3,
                'intelligence_aspect' => $intelligenceAspects[$i],
                'definition' => $definitions[$i],
                'job_implication' => $jobImplications[$i],
                'total_aspects_score' => $totalAspectsScores[$i],
            ]);
        }

        // Return response sukses
        return response()->json(['success' => true, 'message' => 'Form A3 berhasil di-update.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DtlFormA3::where('id_form_a3', $id)->delete();

        MsFormA3::where('id_form_a3', $id)->delete();

        // Mengembalikan respons JSON sukses
        return response()->json(['message' => 'Data berhasil dihapus!']);
    }
}