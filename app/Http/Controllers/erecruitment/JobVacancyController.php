<?php

namespace App\Http\Controllers\erecruitment;

use App\Http\Controllers\Controller;
use App\Models\erecruitment\table\MsFlowRecruitment;
use App\Models\erecruitment\table\MsJobVacancy;
use App\Models\erecruitment\table\MsManPowerPlanning;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobVacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $data = MsJobVacancy::select('ms_jobvacancy.*', 'ms_manpowerplanning.position as position_name', 'ms_manpowerplanning.department as department', 'ms_form_a1.major as major')
                ->leftJoin('ms_manpowerplanning', 'ms_jobvacancy.position', '=', 'ms_manpowerplanning.id_mpp')
                ->leftJoin('ms_form_a1', 'ms_jobvacancy.position', '=', 'ms_form_a1.position');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('erecruitment.job-vacancy.action')->with('data', $data);
                })
                ->editColumn('major', function ($data) {
                    return str_replace(',', ', ', $data->major);
                })
                ->filterColumn('position_name', function ($query, $keyword) {
                    $query->whereRaw("LOWER(ms_manpowerplanning.position) like ?", ["%{$keyword}%"]);
                })
                ->filterColumn('department', function ($query, $keyword) {
                    $query->whereRaw("LOWER(ms_manpowerplanning.department) like ?", ["%{$keyword}%"]);
                })
                ->filterColumn('major', function ($query, $keyword) {
                    $query->whereRaw("LOWER(ms_form_a1.major) like ?", ["%{$keyword}%"]);
                })
                ->make(true);
        }
        return view('erecruitment.job-vacancy.index');
    }

    public function getPositions_forVacancy()
    {
        $positions = MsManPowerPlanning::select('id_mpp', 'position')->get();

        return response()->json($positions);
    }

    public function getFlowRecruitment()
    {
        $flowRecruitment = MsFlowRecruitment::select('id_flowrecruitment', 'template_name')->get();

        return response()->json($flowRecruitment);
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

    // Fungsi untuk membuat singkatan perusahaan (menghapus "PT" dan mengambil huruf pertama dari setiap kata)
    private function getCompanyAbbreviation($companyName)
    {
        // Menghapus "PT" jika ada di awal nama perusahaan
        $companyName = preg_replace('/^PT\s+/i', '', $companyName);

        // Mengambil huruf pertama dari setiap kata dan mengonversi ke huruf besar
        return strtoupper($this->getAbbreviation($companyName));
    }

    // Fungsi untuk membuat singkatan umum (ambil huruf pertama dari setiap kata)
    private function getAbbreviation($name)
    {
        $name = preg_replace('/[^a-zA-Z\s]/', '', $name); // Hapus karakter non-alfabet
        $words = explode(' ', $name); // Pecah nama berdasarkan spasi
        $abbreviation = '';

        foreach ($words as $word) {
            if (!empty($word)) {
                $abbreviation .= strtoupper($word[0]); // Ambil huruf pertama dari setiap kata dan konversi ke uppercase
            }
        }

        return $abbreviation;
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
            'position' => 'required',
            'position_status' => 'required',
            'job_position' => 'required|string',
            'join_date' => 'required',
            'number_requests' => 'required',
            'last_education' => 'required',
            'source_submission' => 'required',
            'job_desc' => 'required',
            'required_skills' => 'required',
            'range_ipk' => 'required',
            'open_date' => 'required',
            'close_date' => 'required'
        ]);

        // Ambil data perusahaan, divisi, dan departemen dari pengguna yang sedang login (Auth::user)
        $user = Auth::user();
        $companyName = $user->company_name;
        $division = $user->division;
        $department = $user->department;

        // Buat singkatan untuk company, divisi, dan departemen
        $companyAbbreviation = $this->getCompanyAbbreviation($companyName);
        $divisionAbbreviation = $this->getAbbreviation($division);
        $departmentAbbreviation = $this->getAbbreviation($department);

        // Tanggal create vacancy (format Ymd)
        $creationDate = date('Ymd');

        // Pola untuk pencarian ID terakhir berdasarkan company, divisi, departemen, dan tanggal
        $pattern = 'VAC-' . $companyAbbreviation . '-' . $divisionAbbreviation . '-' . $departmentAbbreviation . '-' . $creationDate . '-%';

        $lastEntry = MsJobVacancy::withTrashed()->where('id_jobvacancy', 'like', $pattern)
            ->orderBy('id_jobvacancy', 'desc')
            ->first();

        if ($lastEntry) {
            // Ambil nomor urut dari ID terakhir dan tingkatkan satu
            $lastNumber = (int) substr($lastEntry->id_jobvacancy, -5);
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            // Jika tidak ada ID yang ditemukan, mulai dari 00001
            $newNumber = '00001';
        }

        // Format unique ID sesuai dengan format yang diinginkan
        $uniqueCode = 'VAC-' . $companyAbbreviation . '-' . $divisionAbbreviation . '-' . $departmentAbbreviation . '-' . $creationDate . '-' . $newNumber;

        // Simpan data ke database termasuk unique code, vacancy_status, dan created_by
        MsJobVacancy::create([
            'id_jobvacancy' => $uniqueCode,
            'position' => $request->input('position'),
            'position_status' => $request->input('position_status'),
            'job_position' => $request->input('job_position'),
            'join_date' => $request->input('join_date'),
            'number_requests' => $request->input('number_requests'),
            'last_education' => $request->input('last_education'),
            'source_submission' => $request->input('source_submission'),
            'job_desc' => $request->input('job_desc'),
            'required_skills' => $request->input('required_skills'),
            'range_ipk' => $request->input('range_ipk'),
            'open_date' => $request->input('open_date'),
            'close_date' => $request->input('close_date'),
            'open_publication_date' => $request->input('open_publication_date'),
            'close_publication_date' => $request->input('close_publication_date'),
            'flow_recruitment' => $request->input('flow_recruitment'),
            'created_by' => $user->id
        ]);

        // Mengembalikan response sukses
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
        $data = MsJobVacancy::where('id_jobvacancy', $id)->first();

        if ($data->position) {
            $position = MsManPowerPlanning::where('id_mpp', $data->position)
                ->select('position')
                ->first();
            $data->position_name = $position ? $position->position : null;
        }

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
            'position' => 'required|string',
            'position_status' => 'required|string',
            'job_position' => 'required|string',
            'join_date' => 'required|date',
            'number_requests' => 'required|integer',
            'last_education' => 'required|string',
            'source_submission' => 'required|string',
            'job_desc' => 'required|string',
            'required_skills' => 'required|string',
            'range_ipk' => 'required|numeric|between:0,4',
            'open_date' => 'required|date',
            'close_date' => 'required|date',
            'flow_recruitment' => 'required|string'
        ]);

        // Cari ID dari Position (id_mpp) berdasarkan nama posisi
        $positionData = MsManPowerPlanning::where('position', $request->input('position'))->first();
        if (!$positionData) {
            return response()->json(['error' => 'Position not found'], 404);
        }

        $vacancyStatus = $request->input('vacancy_status');
        $openPublicationDate = $request->input('open_publication_date');
        $closePublicationDate = $request->input('close_publication_date');

        if ($vacancyStatus === 'Private') {
            $openPublicationDate = null;
            $closePublicationDate = null;
        }

        $updated = MsJobVacancy::where('id_jobvacancy', $id)->update([
            'position' => $positionData->id_mpp,
            'position_status' => $request->input('position_status'),
            'job_position' => $request->input('job_position'),
            'join_date' => $request->input('join_date'),
            'number_requests' => $request->input('number_requests'),
            'last_education' => $request->input('last_education'),
            'source_submission' => $request->input('source_submission'),
            'job_desc' => $request->input('job_desc'),
            'required_skills' => $request->input('required_skills'),
            'range_ipk' => $request->input('range_ipk'),
            'open_date' => $request->input('open_date'),
            'close_date' => $request->input('close_date'),
            'open_publication_date' => $openPublicationDate,
            'close_publication_date' => $closePublicationDate,
            'flow_recruitment' => $request->input('flow_recruitment'),
            'vacancy_status' => $vacancyStatus
        ]);

        // Check if the update was successful
        if ($updated) {
            return response()->json(['success' => true, 'message' => 'Job vacancy updated successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to update job vacancy.']);
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
        MsJobVacancy::where('id_jobvacancy', $id)->delete();

        // Mengembalikan respons JSON sukses
        return response()->json(['message' => 'Data berhasil dihapus!']);
    }
}