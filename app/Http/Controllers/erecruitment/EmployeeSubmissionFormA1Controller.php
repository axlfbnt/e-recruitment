<?php

namespace App\Http\Controllers\erecruitment;

use App\Http\Controllers\Controller;
use App\Models\erecruitment\table\MsFormA1;
use App\Models\erecruitment\table\MsJobDesc;
use App\Models\erecruitment\table\MsManPowerPlanning;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeSubmissionFormA1Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $data = MsFormA1::select('ms_form_a1.*', 'ms_manpowerplanning.position as position_name')
                ->join('ms_manpowerplanning', 'ms_form_a1.position', '=', 'ms_manpowerplanning.id_mpp');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('erecruitment.employee-submission-forma1.action', compact('data'))->render();
                })
                ->filterColumn('position_name', function ($query, $keyword) {
                    $query->whereRaw("LOWER(ms_manpowerplanning.position) like ?", ["%{$keyword}%"]);
                })
                ->rawColumns(['action']) // Add this if there are HTML elements in the action column
                ->make(true);
        }

        // Menyediakan ID yang diformat untuk tampilan form baru
        $division = Auth::user()->division;
        $department = Auth::user()->department;
        $formattedId = $this->generateFormattedId($division, $department);

        return view('erecruitment.employee-submission-forma1.index', compact('formattedId'));
    }

    public function generateFormattedId($division, $department)
    {
        // Ambil tahun saat ini
        $year = date('Y');

        // Buat singkatan dari divisi dan departemen
        $divisionAbbreviation = $this->getAbbreviation($division);
        $departmentAbbreviation = $this->getAbbreviation($department);

        // Pola untuk pencarian ID terakhir berdasarkan divisi dan departemen tertentu di tahun ini
        $pattern = 'FRMA1-' . $year . '-' . $divisionAbbreviation . '-' . $departmentAbbreviation . '-%';

        // Cari ID terakhir di database dengan format yang mirip
        $lastEntry = MsFormA1::where('id_form_a1', 'like', $pattern)
            ->orderBy('id_form_a1', 'desc')
            ->first();

        if ($lastEntry) {
            // Ambil nomor urut dari ID terakhir dan tingkatkan satu
            $lastNumber = (int) substr($lastEntry->id_form_a1, -5);
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            // Jika tidak ada ID yang ditemukan, mulai dari 00001
            $newNumber = '00001';
        }

        // Gabungkan semua komponen untuk membuat ID yang diformat
        $formattedId = 'FRMA1-' . $year . '-' . $divisionAbbreviation . '-' . $departmentAbbreviation . '-' . $newNumber;

        return $formattedId;
    }

    private function getAbbreviation($name)
    {
        // Menghapus semua karakter non-alfabet
        $name = preg_replace('/[^a-zA-Z\s]/', '', $name);

        // Ambil karakter pertama dari setiap kata
        $words = explode(' ', $name);
        $abbreviation = '';
        foreach ($words as $word) {
            // Pastikan kata tidak kosong sebelum mengambil karakter pertama
            if (!empty($word)) {
                $abbreviation .= strtoupper($word[0]);
            }
        }
        return $abbreviation;
    }

    public function getPositions_forFormA1()
    {
        $userDepartment = Auth::user()->department;
        $userDivision = Auth::user()->division;

        $positions = MsManPowerPlanning::select('id_mpp', 'position', 'total_man_power')
            ->where('department', 'LIKE', '%' . $userDepartment . '%')
            ->where('division', 'LIKE', '%' . $userDivision . '%')
            ->get();

        $positionsWithQuota = $positions->filter(function ($position) {
            $existingRequests = MsFormA1::where('position', $position->id_mpp)
                ->sum('number_requests');

            return ($position->total_man_power - $existingRequests) > 0;
        });

        return response()->json($positionsWithQuota->values());
    }

    public function getPositionDetails(Request $request)
    {
        $positionId = $request->get('id');
        $userDepartment = Auth::user()->department;

        // Ambil data dari ms_manpowerplanning berdasarkan id_mpp
        $mpp = MsManPowerPlanning::where('id_mpp', $positionId)->first();

        if ($mpp) {
            // Hitung total number_requests yang sudah ada di ms_form_a1 untuk posisi ini
            $existingRequests = MsFormA1::where('position', $positionId)
                ->sum('number_requests');

            // Sisa kuota yang tersedia
            $remainingQuota = $mpp->total_man_power - $existingRequests;

            // Ambil deskripsi pekerjaan
            $jobdesc = MsJobDesc::select('job_desc')
                ->where('department', 'LIKE', '%' . $userDepartment . '%')
                ->where('position', $mpp->position)
                ->first();

            $required_skills = MsFormA1::select('required_skills')
                ->where('position', $positionId)
                ->first();

            // Mapping untuk position_status
            $positionStatusMapping = [
                1 => 'Replacement',
                2 => 'New'
            ];

            // Mapping untuk source_submission
            $sourceSubmissionMapping = [
                1 => 'Organik',
                2 => 'Outsource',
                3 => 'Pelatihan Kerja',
                4 => 'OS PKWT'
            ];

            // Mapping untuk last_education
            $lastEducationMapping = [
                1 => 'SMA/SMK/Sederajat',
                2 => 'Diploma 3',
                3 => 'Sarjana 1',
                4 => 'Sarjana 2'
            ];

            // Direct supervisor Auth user Dept Head
            $direct_supervisor = Auth::user()->name;

            $response = [
                'position_status' => $positionStatusMapping[$mpp->position_status] ?? 'N/A',
                'job_position' => $mpp->job_position,
                'due_date' => $mpp->due_date,
                'total_man_power' => $remainingQuota, // Menampilkan sisa kuota
                'source_submission' => $sourceSubmissionMapping[$mpp->source_submission] ?? 'N/A',
                'job_desc' => $jobdesc ? $jobdesc->job_desc : null,
                'last_education' => $lastEducationMapping[$mpp->last_education] ?? 'N/A',
                'direct_supervisor' => $direct_supervisor,
                'required_skills' => $required_skills ? $required_skills->required_skills : null,
            ];

            return response()->json($response);
        }

        return response()->json(['error' => 'Position not found'], 404);
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
            'no_form' => 'required|string',
            'due_date' => 'required|date',
            'join_date' => 'required|date',
            'number_requests' => 'required|integer',
            'position' => 'required|string',
            'direct_supervisor' => 'required|string',
            'position_status' => 'required|string',
            'job_position' => 'required|string',
            'source_submission' => 'required|string',
            'last_education' => 'required|string',
            'job_desc' => 'required|string',
            'personality_traits' => 'required|string',
            'required_skills' => 'required|string',
            'gender' => 'required|string',
            'marital_status' => 'required|string',
            'majors' => 'required|string',
        ]);

        // Ambil data manpower planning berdasarkan id_mpp
        $manpower = MsManPowerPlanning::where('id_mpp', $request->input('position'))->first();
        if (!$manpower) {
            return response()->json(['error' => 'Manpower Planning not found'], 404);
        }
        $position = $manpower->position; // Ambil position dari manpower planning

        // Cek apakah job_desc sudah ada di MsJobDesc
        $jobDesc = MsJobDesc::where('position', $position)
            ->where('department', Auth::user()->department)
            ->first();

        $maxId = MsJobDesc::withTrashed()->max('id_jobdesc');
        $nextId = $maxId ? $maxId + 1 : 1;

        if ($jobDesc) {
            // Jika job_desc ada, cek apakah job_desc dari request berbeda dengan yang ada di database
            if ($jobDesc->job_desc !== $request->input('job_desc')) {
                // Jika berbeda, lakukan update
                $jobDesc->update([
                    'job_desc' => $request->input('job_desc'),
                    'updated_by' => Auth::user()->id,
                ]);
            }
        } else {
            MsJobDesc::create([
                'id_jobdesc' => $nextId,
                'company' => Auth::user()->company_name,
                'department' => Auth::user()->department,
                'division' => Auth::user()->division,
                'position' => $position, // Gunakan position dari manpower planning
                'job_desc' => $request->input('job_desc'),
                'created_by' => Auth::user()->id,
            ]);
        }

        // Simpan data ke MsFormA1
        MsFormA1::create([
            'id_form_a1' => $request->input('no_form'),
            'department' => Auth::user()->department,
            'division' => Auth::user()->division,
            'due_date' => $request->input('due_date'),
            'position' => $request->input('position'),
            'direct_supervisor' => Auth::user()->id,
            'position_status' => $request->input('position_status'),
            'job_position' => $request->input('job_position'),
            'join_date' => $request->input('join_date'),
            'number_requests' => $request->input('number_requests'),
            'source_submission' => $request->input('source_submission'),
            'job_desc' => $request->input('job_desc'),
            'last_education' => $request->input('last_education'),
            'major' => $request->input('majors'),
            'gender' => $request->input('gender'),
            'marital_status' => $request->input('marital_status'),
            'personality_traits' => $request->input('personality_traits'),
            'required_skills' => $request->input('required_skills'),
            'sla' => null,
            'a1_status' => 'Not Yet',
            'rejection_statement' => null,
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

    public function detail($id)
    {
        // Mengambil data menggunakan where dan first()
        $data = MsFormA1::where('id_form_a1', $id)->first();

        if (!$data) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        // Ambil nama direct_supervisor dari tabel User, jika ada
        if ($data->direct_supervisor) {
            $supervisor = User::where('id', $data->direct_supervisor)
                ->select('name')
                ->first();
            $data->supervisor_name = $supervisor ? $supervisor->name : null;
        }

        // Ambil nama position dari tabel MsManPowerPlanning, jika ada
        if ($data->position) {
            $position = MsManPowerPlanning::where('id_mpp', $data->position)
                ->select('position')
                ->first();
            $data->position_name = $position ? $position->position : null;
        }

        return response()->json(['result' => $data]);
    }

    public function approvalFormA1(Request $request, $id)
    {
        $userEmail = Auth::user()->email;

        if ($userEmail == '1612075') {
            $data = [
                'a1_status' => 'Approved by Dept Head'
            ];
        } elseif ($userEmail == '1801002') {
            $data = [
                'a1_status' => 'Approved by Div Head'
            ];
        } elseif ($userEmail == 'firdariskap@gmail.com') {
            $data = [
                'a1_status' => 'Approved by Human Capital'
            ];

            $formA1 = MsFormA1::where('id_form_a1', $id)->first();

            if ($formA1) {
                $id_mpp = $formA1->position;

                MsManPowerPlanning::where('id_mpp', $id_mpp)->update([
                    'man_power_status' => 'On Process',
                    'a1_status' => 'Approved by HC'
                ]);
            }
        } else {
            return response()->json(['error' => "User email does not have approval rights"], 403);
        }

        MsFormA1::where('id_form_a1', $id)->update($data);

        return response()->json(['success' => "Successfully updated data"]);
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
        $data = MsFormA1::where('id_form_a1', $id)->first();

        if (!$data) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        // Ambil nama direct_supervisor dari tabel User, jika ada
        if ($data->direct_supervisor) {
            $supervisor = User::where('id', $data->direct_supervisor)
                ->select('name')
                ->first();
            $data->supervisor_name = $supervisor ? $supervisor->name : null;
        }

        // Ambil nama position dari tabel MsManPowerPlanning, jika ada
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
        // Validasi input yang diterima
        $request->validate([
            'no_form' => 'required|string',
            'due_date' => 'required|date',
            'join_date' => 'required|date',
            'number_requests' => 'required|integer',
            'position' => 'required|string',
            'direct_supervisor' => 'required|string',
            'position_status' => 'required|string',
            'job_position' => 'required|string',
            'source_submission' => 'required|string',
            'last_education' => 'required|string',
            'job_desc' => 'required|string',
            'personality_traits' => 'required|string',
            'required_skills' => 'required|string',
            'gender' => 'required|string',
            'marital_status' => 'required|string',
            'majors' => 'required|string',
        ]);

        // Cari data MsFormA1 berdasarkan ID menggunakan where dan first
        $formA1 = MsFormA1::where('id_form_a1', $id)->first();
        if (!$formA1) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        // Cari ID dari Position (id_mpp) berdasarkan nama posisi
        $positionData = MsManPowerPlanning::where('position', $request->input('position'))->first();
        if (!$positionData) {
            return response()->json(['error' => 'Position not found'], 404);
        }

        // Cari ID dari Direct Supervisor (user_id) berdasarkan nama
        $supervisorData = User::where('name', $request->input('direct_supervisor'))->first();
        if (!$supervisorData) {
            return response()->json(['error' => 'Direct Supervisor not found'], 404);
        }

        $jobDesc = MsJobDesc::where('position', $request->input('position'))
            ->where('department', Auth::user()->department)
            ->first();

        $maxId = MsJobDesc::withTrashed()->max('id_jobdesc');
        $nextId = $maxId ? $maxId + 1 : 1;

        if ($jobDesc) {
            // Jika job_desc ada, cek apakah job_desc dari request berbeda dengan yang ada di database
            if ($jobDesc->job_desc !== $request->input('job_desc')) {
                // Jika berbeda, lakukan update
                $jobDesc->update([
                    'job_desc' => $request->input('job_desc'),
                    'updated_by' => Auth::user()->id,
                ]);
            }
        } else {
            MsJobDesc::create([
                'id_jobdesc' => $nextId,
                'company' => Auth::user()->company_name,
                'department' => Auth::user()->department,
                'division' => Auth::user()->division,
                'position' => $request->input('position'),
                'job_desc' => $request->input('job_desc'),
                'created_by' => Auth::user()->id,
            ]);
        }

        MsFormA1::where('id_form_a1', $id)->update([
            'id_form_a1' => $request->input('no_form'),
            'department' => Auth::user()->department,
            'division' => Auth::user()->division,
            'due_date' => $request->input('due_date'),
            'position' => $positionData->id_mpp,
            'direct_supervisor' => $supervisorData->id,
            'position_status' => $request->input('position_status'),
            'job_position' => $request->input('job_position'),
            'join_date' => $request->input('join_date'),
            'number_requests' => $request->input('number_requests'),
            'source_submission' => $request->input('source_submission'),
            'job_desc' => $request->input('job_desc'),
            'last_education' => $request->input('last_education'),
            'major' => $request->input('majors'),
            'gender' => $request->input('gender'),
            'marital_status' => $request->input('marital_status'),
            'personality_traits' => $request->input('personality_traits'),
            'required_skills' => $request->input('required_skills'),
            'updated_by' => Auth::user()->id,
        ]);

        return response()->json(['success' => "Successfully updated data"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MsFormA1::where('id_form_a1', $id)->delete();

        // Mengembalikan respons JSON sukses
        return response()->json(['message' => 'Data berhasil dihapus!']);
    }
}