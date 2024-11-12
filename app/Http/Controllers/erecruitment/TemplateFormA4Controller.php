<?php

namespace App\Http\Controllers\erecruitment;

use App\Http\Controllers\Controller;
use App\Models\erecruitment\table\DtlFormA4;
use App\Models\erecruitment\table\MsFormA4;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemplateFormA4Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $data = MsFormA4::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('erecruitment.template-forma4.action')->with('data', $data);
                })
                ->make(true);
        }
        return view('erecruitment.template-forma4.index');
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
            'dimension' => 'required|string',
            'key_explanation' => 'required|string',
            'total_aspects_score' => 'required|string',
        ]);

        // Simpan data ke ms_form_a4
        $formA4 = MsFormA4::create([
            'template_name' => $request->template_name,
            'status' => 'Inactive',
            'created_by' => auth()->user()->id,
        ]);

        $dimension = explode(',', $request->dimension);
        $key_explanation = explode(',', $request->key_explanation);
        $totalAspectsScores = explode(',', $request->total_aspects_score);

        for ($i = 0; $i < count($dimension); $i++) {
            DtlFormA4::create([
                'id_form_a4' => $formA4->id_form_a4,
                'dimension' => $dimension[$i],
                'key_explanation' => $key_explanation[$i],
                'total_aspects_score' => $totalAspectsScores[$i],
            ]);
        }

        // Return response sukses
        return response()->json(['success' => true, 'message' => 'Form A4 berhasil disimpan.']);
    }

    public function updateStatus(Request $request, $id)
    {
        // Validasi status yang dikirim
        $request->validate([
            'status' => 'required|in:Active,Inactive',
        ]);

        // Temukan template berdasarkan ID
        $template = MsFormA4::find($id);
        if (!$template) {
            return response()->json(['error' => 'Template tidak ditemukan.'], 404);
        }

        // Cek apakah user mencoba menonaktifkan satu-satunya template yang aktif
        if ($request->status == 'Inactive') {
            $activeTemplatesCount = MsFormA4::where('status', 'Active')->count();

            // Jika hanya ada satu template yang aktif, cegah perubahan status
            if ($activeTemplatesCount == 1 && $template->status == 'Active') {
                return response()->json(['error' => 'Minimal harus ada satu template yang aktif.'], 400);
            }
        }

        // Jika status yang diminta adalah 'Active', matikan template yang aktif saat ini
        if ($request->status == 'Active') {
            // Matikan template yang sedang aktif
            MsFormA4::where('status', 'Active')
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
        $template = MsFormA4::with('details')->find($id);

        if (!$template) {
            return response()->json(['error' => 'Template tidak ditemukan.'], 404);
        }

        $evaluations = [];
        foreach ($template->details as $detail) {
            $evaluations[] = [
                'dimension' => $detail->dimension,
                'key_explanation' => $detail->key_explanation,
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
        $request->validate([
            'template_name' => 'required|string|max:255',
            'dimension' => 'required|string',
            'key_explanation' => 'required|string',
            'total_aspects_score' => 'required|string',
        ]);

        $formA4 = MsFormA4::find($id);
        if (!$formA4) {
            return response()->json(['error' => 'Template tidak ditemukan.'], 404);
        }

        $formA4->update([
            'template_name' => $request->template_name,
            'updated_by' => auth()->user()->id,
        ]);

        // Hapus data lama dari DtlFormA4
        DtlFormA4::where('id_form_a4', $id)->delete();

        $dimensions = explode(',', $request->dimension);
        $keyExplanations = explode(',', $request->key_explanation);
        $totalAspectsScores = explode(',', $request->total_aspects_score);

        // Masukkan data baru ke DtlFormA4
        for ($i = 0; $i < count($dimensions); $i++) {
            DtlFormA4::create([
                'id_form_a4' => $formA4->id_form_a4,
                'dimension' => $dimensions[$i],
                'key_explanation' => $keyExplanations[$i],
                'total_aspects_score' => $totalAspectsScores[$i],
            ]);
        }

        // Return response sukses
        return response()->json(['success' => true, 'message' => 'Form A4 berhasil di-update.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DtlFormA4::where('id_form_a4', $id)->delete();

        MsFormA4::where('id_form_a4', $id)->delete();

        // Mengembalikan respons JSON sukses
        return response()->json(['message' => 'Data berhasil dihapus!']);
    }
}