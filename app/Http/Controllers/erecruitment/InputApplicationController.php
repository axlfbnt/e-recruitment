<?php

namespace App\Http\Controllers\erecruitment;

use App\Http\Controllers\Controller;
use App\Models\erecruitment\table\DtlApplicantVacancy;
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
            $data = VwInputApplicantion::get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('domicile', function ($data) {
                    return $data->domicile_name;
                })
                ->addColumn('vacancy', function ($data) {
                    return $data->vacancy;
                })
                ->addColumn('action', function ($data) {
                    return view('erecruitment.input-application.action')->with('data', $data);
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

        // Menghasilkan applicant_id dengan format APP-birth_date-panjang_string_nama
        $domicileId = $request->input('domicile');
        $birthDate = date('ymd', strtotime($request->input('birth_date')));
        $nameLength = strlen($request->input('full_name')); // Menghitung panjang string dari full_name
        $applicantId = $nameLength . $birthDate . $domicileId; // Format: lengthOfName-YYYYMMDD-domicileId

        // Cek apakah pelamar sudah pernah mendaftar berdasarkan kombinasi full_name, email, birth_date
        $existingApplicant = TrxInputApplication::where('full_name', $request->input('full_name'))
            ->where('email', $request->input('email'))
            ->where('birth_date', $request->input('birth_date'))
            ->first();

        if ($existingApplicant) {
            // Jika applicant sudah ada, hanya simpan data vacancy baru
            DtlApplicantVacancy::create([
                'applicant_id' => $applicantId, 
                'company' => $request->input('company'),
                'vacancy' => $request->input('vacancy'),
                'application_date' => Carbon::now(),
                'expected_salary' => $request->input('expected_salary'),
                'last_stage' => 'Administrative Selection',
                'status' => 'In Process',
                'administrative_status' => 'Under Review'
            ]);

            return response()->json(['success' => "Successfully saved new vacancy data for existing applicant"]);
        }

        // Jika applicant_id belum ada, lanjutkan menyimpan data utama dan detailnya
        // Menginisialisasi variabel untuk menyimpan path file
        $photoPath = null;
        $cvPath = null;

        // Menyimpan file photo jika ada
        if ($request->file('photo')) {
            $photo = $request->file('photo');
            $photoName = 'ERecPhoto-' . date('Ymdhis') . '.' . $photo->getClientOriginalExtension();
            $storedPhotoPath = $photo->storeAs('photo', $photoName, 'public');
            $photoPath = '/storage/' . $storedPhotoPath;
        }

        // Menyimpan file CV jika ada
        if ($request->file('cv')) {
            $cv = $request->file('cv');
            $cvName = 'ERecCV-' . date('Ymdhis') . '.' . $cv->getClientOriginalExtension();
            $storedCvPath = $cv->storeAs('cv', $cvName, 'public');
            $cvPath = '/storage/' . $storedCvPath;
        }

        // Menyimpan data utama ke trx_inputapplication
        $inputApplication = TrxInputApplication::create([
            'applicant_id' => $applicantId, // Menyimpan applicant_id yang dihasilkan
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'birth_date' => $request->input('birth_date'),
            'gender' => $request->input('gender'),
            'domicile' => $request->input('domicile'),
            'phone_number' => $request->input('phone_number'),
            'years_experience' => $request->input('years_experience'),
            'months_experience' => $request->input('months_experience'),
            'photo_path' => $photoPath,
            'cv_path' => $cvPath,
            'created_by' => Auth::user()->id
        ]);

        // Cek jika applicant_id berhasil dibuat
        if ($applicantId) {
            // Menyimpan data detail aplikasi di DtlApplicantVacancy
            DtlApplicantVacancy::create([
                'applicant_id' => $applicantId,
                'company' => $request->input('company'),
                'vacancy' => $request->input('vacancy'),
                'application_date' => Carbon::now(),
                'expected_salary' => $request->input('expected_salary'),
                'last_stage' => 'Administrative Selection',
                'status' => 'Under Review'
            ]);

            // Decode JSON strings untuk detail pendidikan, organisasi, magang, dan pengalaman kerja
            $educationData = json_decode($request->input('education'), true);
            $organizationData = json_decode($request->input('organization'), true);
            $internshipData = json_decode($request->input('internship'), true);
            $jobExperienceData = json_decode($request->input('job_experience'), true);

            // Menyimpan data pendidikan (DtlEducation)
            if ($educationData && is_array($educationData)) {
                foreach ($educationData as $education) {
                    DtlEducation::create([
                        'applicant_id' => $applicantId,
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

            // Menyimpan data organisasi (DtlOrganization)
            if ($organizationData && is_array($organizationData)) {
                foreach ($organizationData as $organization) {
                    DtlOrganization::create([
                        'applicant_id' => $applicantId,
                        'organization_name' => $organization['organizationName'] ?? null,
                        'scope' => $organization['scope'] ?? null,
                        'title' => $organization['title'] ?? null,
                        'created_by' => Auth::user()->id
                    ]);
                }
            }

            // Menyimpan data magang (DtlInternship)
            if ($internshipData && is_array($internshipData)) {
                foreach ($internshipData as $internship) {
                    DtlInternship::create([
                        'applicant_id' => $applicantId,
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

            // Menyimpan data pengalaman kerja (DtlJobExperience)
            if ($jobExperienceData && is_array($jobExperienceData)) {
                foreach ($jobExperienceData as $jobExperience) {
                    DtlJobExperience::create([
                        'applicant_id' => $applicantId,
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

            return response()->json(['success' => "Successfully saved application and all details"]);
        } else {
            return response()->json(['error' => 'Failed to generate applicant_id'], 500);
        }
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
        $application = TrxInputApplication::find($id);

        $vacancies = DtlApplicantVacancy::where('applicant_id', $id)->get();

        $educations = DtlEducation::where('applicant_id', $id)->get();
        $organizations = DtlOrganization::where('applicant_id', $id)->get();
        $internships = DtlInternship::where('applicant_id', $id)->get();
        $jobExperiences = DtlJobExperience::where('applicant_id', $id)->get();

        return response()->json([
            'application' => $application,
            'vacancies' => $vacancies,
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

        // Iterasi untuk baris data pelamar, dimulai dari baris ke-4
        foreach ($worksheet->getRowIterator(4) as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $dataArray = [];
            foreach ($cellIterator as $cell) {
                $dataArray[] = $cell->getValue();
            }

            // Mengambil dan memformat birth_date dari excel
            $birthDateValue = $dataArray[2];
            $birthDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($birthDateValue)->format('Ymd');

            // Membuat applicant_id berdasarkan format APP-birthDate-lengthOfName
            $applicantId = 'APP-' . $birthDate . strlen($dataArray[0]); // Format: APP-YYYYMMDD-lengthOfName

            // Menyimpan domicileId berdasarkan nama dari tabel MsDomicile
            $domicileName = $dataArray[5];
            $domicileId = MsDomicile::where('name', $domicileName)->value('id');

            // Menyimpan data pelamar utama (TrxInputApplication)
            $inputApplication = TrxInputApplication::create([
                'applicant_id' => $applicantId, // Menggunakan applicant_id yang sudah dibuat
                'full_name' => $dataArray[0],
                'email' => $dataArray[4],
                'birth_date' => $birthDate,
                'gender' => $dataArray[1],
                'domicile' => $domicileId,
                'phone_number' => $dataArray[3],
                'years_experience' => $dataArray[7],
                'months_experience' => $dataArray[8],
                'photo_path' => '', // File photo tidak disertakan dalam import
                'cv_path' => '', // File CV tidak disertakan dalam import
                'created_by' => Auth::user()->id,
            ]);

            // Menyimpan data detail aplikasi di DtlApplicantVacancy menggunakan applicant_id
            DtlApplicantVacancy::create([
                'applicant_id' => $applicantId, // Menggunakan applicant_id yang sudah dibuat
                'company' => $dataArray[106],
                'vacancy' => $dataArray[107],
                'application_date' => Carbon::now(),
                'expected_salary' => $dataArray[9],
                'last_stage' => 'Administrative Selection',
                'status' => 'Under Review'
            ]);

            // **Penyimpanan Data Pendidikan (Degree1, Degree2, Degree3)**
            for ($i = 1; $i <= 3; $i++) { // Untuk Degree1, Degree2, Degree3
                $offset = ($i - 1) * 9; // Setiap degree memiliki 9 kolom

                // Ambil nilai dari Excel dengan indeks yang sesuai
                $degree = isset($dataArray[10 + $offset]) ? trim($dataArray[10 + $offset]) : null; // K (Degree)
                $institution = isset($dataArray[11 + $offset]) ? trim($dataArray[11 + $offset]) : null; // L (Institution)
                $institutionOther = isset($dataArray[12 + $offset]) ? trim($dataArray[12 + $offset]) : null; // M (Other Institution)
                $major = isset($dataArray[13 + $offset]) ? trim($dataArray[13 + $offset]) : null; // N (Major)
                $majorOther = isset($dataArray[14 + $offset]) ? trim($dataArray[14 + $offset]) : null; // O (Other Major)
                $startYear = isset($dataArray[15 + $offset]) ? trim($dataArray[15 + $offset]) : null; // P (Start Year)
                $graduatedYear = isset($dataArray[16 + $offset]) ? trim($dataArray[16 + $offset]) : null; // Q (Graduated Year)
                $gpa = isset($dataArray[17 + $offset]) ? trim($dataArray[17 + $offset]) : null; // R (GPA)
                $outOfGpa = isset($dataArray[18 + $offset]) ? trim($dataArray[18 + $offset]) : null; // S (Out of GPA)

                // Hanya menyimpan jika degree tidak kosong
                if (!empty($degree)) {
                    DtlEducation::create([
                        'applicant_id' => $applicantId, // Menggunakan applicant_id yang sudah ada
                        'degree' => $degree,
                        'institution' => $institution,
                        'institutionOther' => $institutionOther,
                        'major' => $major,
                        'majorOther' => $majorOther,
                        'start_year' => $startYear,
                        'graduated_year' => $graduatedYear,
                        'gpa' => $gpa,
                        'created_by' => Auth::user()->id
                    ]);
                }
            }

            // **Penyimpanan Data Organisasi (OrgName1, OrgName2, OrgName3)**
            for ($i = 1; $i <= 3; $i++) {
                $offset = ($i - 1) * 3;

                // Ambil nilai dari Excel dengan indeks yang sesuai
                $organizationName = isset($dataArray[37 + $offset]) ? trim($dataArray[37 + $offset]) : null; // AL (Organization Name)
                $scope = isset($dataArray[38 + $offset]) ? trim($dataArray[38 + $offset]) : null; // AM (Scope)
                $title = isset($dataArray[39 + $offset]) ? trim($dataArray[39 + $offset]) : null; // AN (Title)

                // Hanya menyimpan jika nama organisasi tidak kosong
                if (!empty($organizationName)) {
                    DtlOrganization::create([
                        'applicant_id' => $applicantId, // Menggunakan applicant_id yang sudah ada
                        'organization_name' => $organizationName,
                        'scope' => $scope,
                        'title' => $title,
                        'created_by' => Auth::user()->id
                    ]);
                }
            }

            // **Penyimpanan Data Pengalaman Kerja (CompanyName1, CompanyName2, CompanyName3)**
            for ($i = 1; $i <= 3; $i++) {
                $offset = ($i - 1) * 10;

                // Ambil nilai dari Excel dengan indeks yang sesuai
                $companyName = isset($dataArray[40 + $offset]) ? trim($dataArray[40 + $offset]) : null; // AU (Company Name)
                $jobTitle = isset($dataArray[41 + $offset]) ? trim($dataArray[41 + $offset]) : null; // AV (Job Title)
                $industry = isset($dataArray[42 + $offset]) ? trim($dataArray[42 + $offset]) : null; // AW (Industry)
                $industryOther = isset($dataArray[43 + $offset]) ? trim($dataArray[43 + $offset]) : null; // AX (Industry Other)
                $position = isset($dataArray[44 + $offset]) ? trim($dataArray[44 + $offset]) : null; // AY (Position)
                $positionType = isset($dataArray[45 + $offset]) ? trim($dataArray[45 + $offset]) : null; // AZ (Position Type)
                $firstFunction = isset($dataArray[46 + $offset]) ? trim($dataArray[46 + $offset]) : null; // BA (First Function)
                $secondFunction = isset($dataArray[47 + $offset]) ? trim($dataArray[47 + $offset]) : null; // BB (Second Function)
                $thirdFunction = isset($dataArray[48 + $offset]) ? trim($dataArray[48 + $offset]) : null; // BC (Third Function)
                $startDate = isset($dataArray[49 + $offset]) ? trim($dataArray[49 + $offset]) : null; // BD (Start Date)
                $endDate = isset($dataArray[50 + $offset]) ? trim($dataArray[50 + $offset]) : null; // BE (End Date)

                // Hanya menyimpan jika nama perusahaan tidak kosong
                if (!empty($companyName)) {
                    DtlJobExperience::create([
                        'applicant_id' => $applicantId, // Menggunakan applicant_id yang sudah ada
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
                        'created_by' => Auth::user()->id
                    ]);
                }
            }

            // **Penyimpanan Data Magang (InstitutionName1, InstitutionName2, InstitutionName3)**
            for ($i = 1; $i <= 3; $i++) {
                $offset = ($i - 1) * 5;

                // Ambil nilai dari Excel dengan indeks yang sesuai
                $institutionName = isset($dataArray[51 + $offset]) ? trim($dataArray[51 + $offset]) : null; // CF (Institution Name)
                $internFunction = isset($dataArray[52 + $offset]) ? trim($dataArray[52 + $offset]) : null; // CG (Intern Function)
                $internIndustry = isset($dataArray[53 + $offset]) ? trim($dataArray[53 + $offset]) : null; // CH (Intern Industry)
                $startDateIntern = isset($dataArray[54 + $offset]) ? trim($dataArray[54 + $offset]) : null; // CI (Intern Start Date)
                $endDateIntern = isset($dataArray[55 + $offset]) ? trim($dataArray[55 + $offset]) : null; // CJ (Intern End Date)
                $jobDesc = isset($dataArray[56 + $offset]) ? trim($dataArray[56 + $offset]) : null; // CK (Intern Job Description)

                // Hanya menyimpan jika nama institusi tidak kosong
                if (!empty($institutionName)) {
                    DtlInternship::create([
                        'applicant_id' => $applicantId, // Menggunakan applicant_id yang sudah ada
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