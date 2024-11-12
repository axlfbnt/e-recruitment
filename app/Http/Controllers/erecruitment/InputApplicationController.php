<?php

namespace App\Http\Controllers\erecruitment;

use App\Http\Controllers\Controller;
use App\Models\erecruitment\table\DtlEducation;
use App\Models\erecruitment\table\DtlHistory;
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
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;

class InputApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $data = TrxInputApplication::query()
                ->join('ms_domicile', 'trx_inputapplication.domicile', '=', 'ms_domicile.id')
                ->select('trx_inputapplication.*', 'ms_domicile.name as domicile_name');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('domicile', function ($data) {
                    return $data->domicile_name;
                })
                ->addColumn('action', function ($data) {
                    return view('erecruitment.input-application.action')->with('data', $data);
                })
                ->filterColumn('domicile', function ($query, $keyword) {
                    $query->whereRaw("LOWER(ms_domicile.name) LIKE ?", ["%" . strtolower($keyword) . "%"]);
                })
                ->make(true);
        }

        return view('erecruitment.input-application.index');
    }

    public function getVacancy(Request $request)
    {
        $companyId = $request->input('company_id');

        $vacancies = MsJobVacancy::join('ms_manpowerplanning', 'ms_jobvacancy.position', '=', 'ms_manpowerplanning.id_mpp')
            ->where('ms_manpowerplanning.company', $companyId)
            ->select('ms_manpowerplanning.position', 'ms_jobvacancy.id_jobvacancy')
            ->get();

        // Return as JSON
        return response()->json(['vacancies' => $vacancies]);
    }

    public function getDomicile(Request $request)
    {
        $domiciles = MsDomicile::all();

        return response()->json(['domicile' => $domiciles]);
    }

    public function getDegree(Request $request)
    {
        $degrees = MsDegree::all();

        return response()->json(['degree' => $degrees]);
    }

    public function getInstitution(Request $request)
    {
        $institutions = MsInstitution::all();

        return response()->json(['institution' => $institutions]);
    }

    public function getMajor(Request $request)
    {
        $majors = MsMajor::all();

        return response()->json(['major' => $majors]);
    }

    public function getFunction(Request $request)
    {
        $functions = MsFunction::all();

        return response()->json(['functions' => $functions]);
    }

    public function getIndustry(Request $request)
    {
        $industry = MsIndustry::all();

        return response()->json(['industry' => $industry]);
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
        // Validasi input untuk data utama (Personal Data)
        $request->validate([
            'company' => 'required',
            'vacancy' => 'required',
            'full_name' => 'required|string',
            'email' => 'required|email',
            'birth_date' => 'required|date',
            'gender' => 'required',
            'domicile' => 'required|string',
            'phone_number' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cv' => 'nullable|mimes:pdf|max:2048'
        ]);

        // Menginisialisasi variabel untuk menyimpan path file
        $photoPath = null;
        $cvPath = null;

        // Menyimpan file photo jika ada
        if ($request->file('photo')) {
            $photo = $request->file('photo');
            // Penamaan file sesuai format yang diinginkan
            $photoName = 'ERecPhoto' . '-' . date('Ymdhis') . '.' . $photo->getClientOriginalName();
            // Simpan file ke folder 'photo' dalam storage
            $storedPhotoPath = $photo->storeAs('photo', $photoName, 'public');
            // Simpan path dengan awalan '/storage/'
            $photoPath = '/storage/' . $storedPhotoPath;
        }

        // Menyimpan file CV jika ada
        if ($request->file('cv')) {
            $cv = $request->file('cv');
            // Penamaan file sesuai format yang diinginkan
            $cvName = 'ERecCV' . '-' . date('Ymdhis') . '.' . $cv->getClientOriginalName();
            // Simpan file ke folder 'cv' dalam storage
            $storedCvPath = $cv->storeAs('cv', $cvName, 'public');
            // Simpan path dengan awalan '/storage/'
            $cvPath = '/storage/' . $storedCvPath;
        }

        // Menyimpan data utama ke trx_inputapplication
        $inputApplication = TrxInputApplication::create([
            'company' => $request->input('company'),
            'vacancy' => $request->input('vacancy'),
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'birth_date' => $request->input('birth_date'),
            'gender' => $request->input('gender'),
            'domicile' => $request->input('domicile'),
            'phone_number' => $request->input('phone_number'),
            'phone_number' => $request->input('phone_number'),
            'years_experience' => $request->input('years_experience'),
            'month_experience' => $request->input('month_experience'),
            'expected_salary' => $request->input('expected_salary'),
            'photo_path' => $photoPath,
            'cv_path' => $cvPath,
            'applicant_status' => 'In Process',
            'current_stage' => 'Administrative Selection',
            'administrative_status' => 'Under Review',
            'created_by' => Auth::user()->id
        ]);

        // Dapatkan ID input application yang baru saja dibuat
        $inputApplicationId = $inputApplication->id;

        DtlHistory::create([
            'inputapplication_id' => $inputApplicationId,
            'company' => $request->input('company'),
            'vacancy' => $request->input('vacancy'),
            'last_stage' => 'Administrative Selection',
            'status' => 'Under Review',
            'applied_date' => Carbon::now(),
        ]);

        // Decode JSON strings
        $educationData = json_decode($request->input('education'), true);
        $organizationData = json_decode($request->input('organization'), true);
        $internshipData = json_decode($request->input('internship'), true);
        $jobExperienceData = json_decode($request->input('job_experience'), true);

        // Cek dan simpan detail education jika ada
        if ($educationData && is_array($educationData)) {
            foreach ($educationData as $education) {
                DtlEducation::create([
                    'inputapplication_id' => $inputApplicationId,
                    'degree' => $education['degree'] ?? null,
                    'institution' => $education['institution'] ?? null,
                    'major' => $education['major'] ?? null,
                    'start_year' => $education['startYear'] ?? null,
                    'graduated_year' => $education['graduatedYear'] ?? null,
                    'gpa' => $education['gpa'] ?? null,
                    'created_by' => Auth::user()->id
                ]);
            }
        }

        // Cek dan simpan detail organization jika ada
        if ($organizationData && is_array($organizationData)) {
            foreach ($organizationData as $organization) {
                DtlOrganization::create([
                    'inputapplication_id' => $inputApplicationId,
                    'organization_name' => $organization['organizationName'] ?? null,
                    'scope' => $organization['scope'] ?? null,
                    'title' => $organization['title'] ?? null,
                    'created_by' => Auth::user()->id
                ]);
            }
        }

        // Cek dan simpan detail internship jika ada
        if ($internshipData && is_array($internshipData)) {
            foreach ($internshipData as $internship) {
                DtlInternship::create([
                    'inputapplication_id' => $inputApplicationId,
                    'company_name' => $internship['companyName'] ?? null,
                    'function_role' => $internship['functionRole'] ?? null,
                    'industry' => $internship['industry'] ?? null,
                    'start_date' => $internship['startDate'] ?? null,
                    'end_date' => $internship['endDate'] ?? null,
                    'job_description' => $internship['jobDescription'] ?? null,
                    'created_by' => Auth::user()->id
                ]);
            }
        }

        // Cek dan simpan detail job experience jika ada
        if ($jobExperienceData && is_array($jobExperienceData)) {
            foreach ($jobExperienceData as $jobExperience) {
                DtlJobExperience::create([
                    'inputapplication_id' => $inputApplicationId,
                    'company_name' => $jobExperience['companyName'] ?? null,
                    'title' => $jobExperience['title'] ?? null,
                    'position' => $jobExperience['position'] ?? null,
                    'position_type' => $jobExperience['positionType'] ?? null,
                    'function_role' => $jobExperience['functionRole'] ?? null,
                    'industry' => $jobExperience['industry'] ?? null,
                    'start_date' => $jobExperience['startDate'] ?? null,
                    'end_date' => $jobExperience['endDate'] ?? null,
                    'job_description' => $jobExperience['jobDescription'] ?? null,
                    'created_by' => Auth::user()->id
                ]);
            }
        }

        return response()->json(['success' => "Successfully saved application data"]);
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
        // Mengambil data dari TrxInputApplication
        $application = TrxInputApplication::find($id);

        // Mengambil data terkait dari masing-masing model tanpa relasi
        $educations = DtlEducation::where('inputapplication_id', $id)->get();
        $organizations = DtlOrganization::where('inputapplication_id', $id)->get();
        $internships = DtlInternship::where('inputapplication_id', $id)->get();
        $jobExperiences = DtlJobExperience::where('inputapplication_id', $id)->get();

        // Kirimkan semua data ini ke view atau ke response JSON untuk diisi di form edit
        return response()->json([
            'application' => $application,
            'educations' => $educations,
            'organizations' => $organizations,
            'internships' => $internships,
            'jobExperiences' => $jobExperiences,
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
        DtlEducation::where('inputapplication_id', $id)->delete();
        DtlOrganization::where('inputapplication_id', $id)->delete();
        DtlInternship::where('inputapplication_id', $id)->delete();
        DtlJobExperience::where('inputapplication_id', $id)->delete();

        TrxInputApplication::where('id', $id)->delete();

        // Mengembalikan respons JSON sukses
        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
        ]);
        $file = $request->file('excel_file');
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $dataArray = $worksheet->toArray(null, true, true, true);

        foreach ($worksheet->getRowIterator(4) as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $dataArray = [];
            foreach ($cellIterator as $cell) {
                $dataArray[] = $cell->getValue();
            }

            $birthDateValue = $dataArray[2];
            $birthDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($birthDateValue)->format('Y-m-d');

            $domicileName = $dataArray[5];
            $domicileId = MsDomicile::where('name', $domicileName)->value('id');

            $inputApplication = TrxInputApplication::create([
                'vacancy' => $dataArray[107],
                'company' => $dataArray[106],
                'full_name' => $dataArray[0],
                'email' => $dataArray[4],
                'birth_date' => $birthDate,
                'gender' => $dataArray[1],
                'domicile' => $domicileId,
                'phone_number' => $dataArray[3],
                'years_experience' => $dataArray[7],
                'months_experience' => $dataArray[8],
                'expected_salary' => $dataArray[9],
                'photo_path' => '',
                'cv_path' => '',
                'created_by' => Auth::user()->id,
            ]);

            // Simpan data pendidikan
            for ($i = 1; $i <= 3; $i++) { // Untuk Degree1, Degree2, Degree3
                $offset = ($i - 1) * 9; // Setiap degree memiliki 9 kolom

                // Ambil nilai dari Excel dengan indeks yang sesuai
                $degree = isset($dataArray[10 + $offset]) ? trim($dataArray[10 + $offset]) : null; // K
                $institution = isset($dataArray[11 + $offset]) ? trim($dataArray[11 + $offset]) : null; // L
                $institutionOther = isset($dataArray[12 + $offset]) ? trim($dataArray[12 + $offset]) : null; // M
                $major = isset($dataArray[13 + $offset]) ? trim($dataArray[13 + $offset]) : null; // N
                $majorOther = isset($dataArray[14 + $offset]) ? trim($dataArray[14 + $offset]) : null; // O
                $startYear = isset($dataArray[15 + $offset]) ? trim($dataArray[15 + $offset]) : null; // P
                $graduatedYear = isset($dataArray[16 + $offset]) ? trim($dataArray[16 + $offset]) : null; // Q
                $gpa = isset($dataArray[17 + $offset]) ? trim($dataArray[17 + $offset]) : null; // R
                $outOfGpa = isset($dataArray[18 + $offset]) ? trim($dataArray[18 + $offset]) : null; // S

                // Hanya menyimpan jika degree tidak kosong
                if (!empty($degree)) {
                    DtlEducation::create([
                        'inputapplication_id' => $inputApplication->id,
                        'degree' => $degree,
                        'institution' => $institution,
                        'institutionOther' => $institutionOther,
                        'major' => $major,
                        'majorOther' => $majorOther,
                        'start_year' => $startYear,
                        'graduated_year' => $graduatedYear,
                        'gpa' => $gpa,
                        'created_by' => Auth::user()->id,
                    ]);
                }
            }

            // Simpan data organisasi
            for ($i = 1; $i <= 3; $i++) { // Untuk OrgName1, OrgName2, OrgName3
                $offset = ($i - 1) * 3; // Setiap organisasi memiliki 3 kolom

                // Ambil nilai dari Excel dengan indeks yang sesuai
                $organizationName = isset($dataArray[37 + $offset]) ? trim($dataArray[37 + $offset]) : null; // AL
                $scope = isset($dataArray[38 + $offset]) ? trim($dataArray[38 + $offset]) : null; // AM
                $title = isset($dataArray[39 + $offset]) ? trim($dataArray[39 + $offset]) : null; // AN

                // Hanya menyimpan jika nama organisasi tidak kosong
                if (!empty($organizationName)) {
                    DtlOrganization::create([
                        'inputapplication_id' => $inputApplication->id,
                        'organization_name' => $organizationName,
                        'scope' => $scope,
                        'title' => $title,
                        'created_by' => Auth::user()->id,
                    ]);
                }
            }

            // Simpan data pengalaman kerja
            for ($i = 1; $i <= 3; $i++) { // Untuk CompanyName1, CompanyName2, CompanyName3
                $offset = ($i - 1) * 10; // Setiap pengalaman kerja memiliki 10 kolom

                // Ambil nilai dari Excel dengan indeks yang sesuai
                $companyName = isset($dataArray[40 + $offset]) ? trim($dataArray[40 + $offset]) : null; // AU
                $jobTitle = isset($dataArray[41 + $offset]) ? trim($dataArray[41 + $offset]) : null; // AV
                $industry = isset($dataArray[42 + $offset]) ? trim($dataArray[42 + $offset]) : null; // AW
                $industryOther = isset($dataArray[43 + $offset]) ? trim($dataArray[43 + $offset]) : null; // AX
                $position = isset($dataArray[44 + $offset]) ? trim($dataArray[44 + $offset]) : null; // AY
                $positionType = isset($dataArray[45 + $offset]) ? trim($dataArray[45 + $offset]) : null; // AZ
                $firstFunction = isset($dataArray[46 + $offset]) ? trim($dataArray[46 + $offset]) : null; // BA
                $secondFunction = isset($dataArray[47 + $offset]) ? trim($dataArray[47 + $offset]) : null; // BB
                $thirdFunction = isset($dataArray[48 + $offset]) ? trim($dataArray[48 + $offset]) : null; // BC
                $startDate = isset($dataArray[49 + $offset]) ? trim($dataArray[49 + $offset]) : null; // BD
                $endDate = isset($dataArray[50 + $offset]) ? trim($dataArray[50 + $offset]) : null; // BE

                // Hanya menyimpan jika nama perusahaan tidak kosong
                if (!empty($companyName)) {
                    DtlJobExperience::create([
                        'inputapplication_id' => $inputApplication->id,
                        'company_name' => $companyName,
                        'title' => $jobTitle,
                        'industry' => $industry,
                        'industry_other' => $industryOther,
                        'position' => $position,
                        'position_type' => $positionType,
                        'first_function' => $firstFunction,
                        'second_function' => $secondFunction,
                        'third_function' => $thirdFunction,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'created_by' => Auth::user()->id,
                    ]);
                }
            }

            // Simpan data magang
            for ($i = 1; $i <= 3; $i++) { // Untuk InstitutionName1, InstitutionName2, InstitutionName3
                $offset = ($i - 1) * 5; // Setiap magang memiliki 5 kolom

                // Ambil nilai dari Excel dengan indeks yang sesuai
                $institutionName = isset($dataArray[51 + $offset]) ? trim($dataArray[51 + $offset]) : null; // CF
                $internFunction = isset($dataArray[52 + $offset]) ? trim($dataArray[52 + $offset]) : null; // CG
                $internIndustry = isset($dataArray[53 + $offset]) ? trim($dataArray[53 + $offset]) : null; // CH
                $startDateIntern = isset($dataArray[54 + $offset]) ? trim($dataArray[54 + $offset]) : null; // CI
                $endDateIntern = isset($dataArray[55 + $offset]) ? trim($dataArray[55 + $offset]) : null; // CJ
                $jobDesc = isset($dataArray[56 + $offset]) ? trim($dataArray[56 + $offset]) : null; // CK

                // Hanya menyimpan jika nama institusi tidak kosong
                if (!empty($institutionName)) {
                    DtlInternship::create([
                        'inputapplication_id' => $inputApplication->id,
                        'company_name' => $institutionName,
                        'function_role' => $internFunction,
                        'industry' => $internIndustry,
                        'start_date' => $startDateIntern,
                        'end_date' => $endDateIntern,
                        'job_description' => $jobDesc,
                        'created_by' => Auth::user()->id,
                    ]);
                }
            }
        }
        return redirect()->route('input-application.index')->with('success', 'Data imported successfully');
    }
}