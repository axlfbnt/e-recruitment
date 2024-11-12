@extends('erecruitment.dashboard.dashboard')

@section('content')
    </style>
    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box mb-1 mt-2">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-2">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="ri-home-4-line"></i>
                                            Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ route('input-application.index') }}">Input
                                            Application</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Data</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="header-title mb-0">Manage Input Application</h4>
                                    <div>
                                        <button type="button" class="btn btn-success rounded-pill button-add me-2"
                                            data-bs-toggle="modal" data-bs-target="#inputapplication-modal">
                                            <i class="fa fa-plus"></i> Add Row
                                        </button>
                                        <button type="button" class="btn btn-primary rounded-pill button-add"
                                            data-bs-toggle="modal" data-bs-target="#importModal">
                                            Import Excel
                                        </button>
                                    </div>
                                </div>
                                <div class="alert alert-success d-none" id="alert-save-success" role="alert">
                                    <strong>Success - </strong> Your data has been successfully saved. Thank you!
                                </div>
                                <hr>
                                <table id="inputapplication-datatable" class="table table-striped w-100 nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Vacancy Code</th>
                                            <th>Name</th>
                                            <th>Gender</th>
                                            <th>Date of Birth</th>
                                            <th>Phone Number</th>
                                            <th>Email</th>
                                            <th>Domicile</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div> <!-- end card body-->
                        </div> <!-- end card -->
                    </div><!-- end col-->
                </div> <!-- end row-->

            </div> <!-- container -->

        </div> <!-- content -->

        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © E-Recruitment - Patria
                    </div>
                    <div class="col-md-6">
                        <div class="text-md-end footer-links d-none d-md-block">
                            <a href="javascript: void(0);">About</a>
                            <a href="javascript: void(0);">Support</a>
                            <a href="javascript: void(0);">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->

    <!-- Add Input Application modal content -->
    <div id="inputapplication-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="auth-brand text-center mt-2 mb-3 position-relative top-0">
                        <a class="logo-dark">
                            <span class="logo-lg">
                                <img src="{{ asset('images/recruitment.png') }}" alt="dark logo"
                                    style="vertical-align: middle; width: 35px; height: auto;">
                                <span
                                    style="vertical-align: middle; font-size: 15px; font-weight: bold; margin-left: 10px;">
                                    INPUT APPLICATION
                                </span>
                            </span>
                        </a>
                    </div>
                    <form class="needs-validation ps-3 pe-3" novalidate>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="company" class="form-label">Company</label>
                                <select class="form-select" id="company" required>
                                    <option value="" disabled selected>Select company</option>
                                    <!-- Options akan dimuat secara dinamis menggunakan AJAX -->
                                </select>
                                <div class="invalid-feedback">
                                    Please choose a company.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="vacancy" class="form-label">Vacancy</label>
                                <select class="form-control select2" data-toggle="select2" id="vacancy" required>
                                    <option value="" disabled selected>Select vacancy</option>
                                    <!-- Options akan dimuat secara dinamis menggunakan AJAX -->
                                </select>
                                <div class="invalid-feedback">
                                    Please choose a vacancy.
                                </div>
                            </div>
                        </div>
                        <hr>
                        <ul class="nav nav-tabs nav-justified nav-bordered mb-3">
                            <li class="nav-item">
                                <a href="#personal-data" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                                    Personal Data
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#education" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                    Education
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#organization" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                    Organization
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#internship" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                    Internship
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#job-experience" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                    Job Experience
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane show active" id="personal-data">
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input class="form-control" type="text" id="name" required
                                            placeholder="fill name">
                                        <div class="invalid-feedback">
                                            Please fill a name.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input class="form-control" type="email" id="email" required
                                            placeholder="fill email">
                                        <div class="invalid-feedback">
                                            Please fill a email.
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="birth-date" class="form-label">Date of Birth</label>
                                        <input class="form-control" type="date" id="birth-date" required
                                            placeholder="fill date of birth">
                                        <div class="invalid-feedback">
                                            Please fill a date of birth.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select class="form-select" id="gender" required>
                                            <option value="" disabled selected>select gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please choose a gender.
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="domicile" class="form-label">Domicile</label>
                                        <select class="form-control select2" data-toggle="select2" id="domicile"
                                            required>
                                            <option value="" disabled selected>Select domicile</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please choose a domicile.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="phone-number" class="form-label">Phone Number</label>
                                        <input class="form-control" type="number" id="phone-number" required
                                            placeholder="fill phone number">
                                        <div class="invalid-feedback">
                                            Please fill a phone number.
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="years-experience" class="form-label">Year(s)</label>
                                        <input class="form-control" type="number" id="years-experience" min="0"
                                            placeholder="fill years experience">
                                        <div class="invalid-feedback">
                                            Please fill a valid years experience.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="months-experience" class="form-label">Month(s)</label>
                                        <input class="form-control" type="number" id="months-experience" min="0"
                                            placeholder="fill months experience">
                                        <div class="invalid-feedback">
                                            Please fill a valid months experience.
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label for="expected-salary" class="form-label">Expected Salary</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input class="form-control" type="text" id="expected-salary"
                                            placeholder="fill expected salary">
                                        <div class="invalid-feedback">
                                            Please set an expected salary.
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="photo" class="form-label">Upload Photo <span
                                                style="color: red; font-size: 11px;">*make sure the photo size is 4x6 or
                                                2x3</span></label>
                                        <div id="imageContainer"
                                            style="border: 2px dashed #cccccc; padding: 10px; text-align: center; cursor: pointer; width: 148px; height: 220px; position: relative;">
                                            <label for="photo"
                                                style="cursor: pointer; width: 100%; height: 100%; display: flex; justify-content: center; align-items: center; position: absolute; top: 0; left: 0;">
                                                <div id="upload-text" style="text-align: center;">Choose an image</div>
                                                <input type="file" id="photo" accept="image/*"
                                                    style="display:none" onchange="previewImage(event)">
                                            </label>
                                            <img id="imagePreview" src="" alt="Your Image"
                                                style="max-width: 100%; max-height: 100%; object-fit: contain; display: none; position: absolute; top: 0; left: 0; cursor: pointer;"
                                                onclick="document.getElementById('photo').click();" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="cv" class="form-label">Upload CV <span
                                                style="color: red; font-size: 11px;">*file allowed are pdf</span></label>
                                        <input class="form-control" type="file" id="cv"
                                            accept="application/pdf" placeholder="upload cv">
                                        <div class="invalid-feedback">
                                            Please upload cv.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="education">
                                <div class="mb-2">
                                    <label for="degree" class="form-label">Degree</label>
                                    <select class="form-control select2" data-toggle="select2" id="degree">
                                        <option value="" disabled selected>select degree</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please set a degree.
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="institution" class="form-label">Institution</label>
                                        <select class="form-control select2" data-toggle="select2" id="institution">
                                            <option value="" disabled selected>select institution</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please set a institution.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="major" class="form-label">Major</label>
                                        <select class="form-control select2" data-toggle="select2" id="major">
                                            <option value="" disabled selected>select major</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please set a major.
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="start-year" class="form-label">Start Year</label>
                                        <input class="form-control" id="start-year" type="number" min="1900"
                                            max="2100" step="1" placeholder="YYYY">
                                        <div class="invalid-feedback">
                                            Please fill a valid year.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="graduated-year" class="form-label">Graduated Year</label>
                                        <div style="display: flex; align-items: center;">
                                            <input class="form-control" id="graduated-year" type="number"
                                                min="1900" max="2100" step="1" placeholder="YYYY"
                                                style="width: 85%;">
                                            <div style="display: flex; align-items: center; padding-left: 10px;">
                                                <input type="checkbox" id="current-year-checkbox"
                                                    onchange="setGraduatedYear()">
                                                <label for="current-year-checkbox"
                                                    style="margin-left: 5px;">Present</label>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please fill a valid year.
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label for="gpa" class="form-label">GPA/NEM</label>
                                    <div class="input-group">
                                        <input class="form-control" type="number" id="gpa"
                                            placeholder="Fill range GPA" min="0" max="4" step="0.1"
                                            oninput="validateGPA(this)">
                                        <span class="input-group-text">/ 4.0</span>
                                        <div class="invalid-feedback">
                                            Please fill a valid GPA between 0 and 4.0.
                                        </div>
                                    </div>
                                </div>

                                <ul class="pager wizard mb-0 list-inline">
                                    <li class="next list-inline-item float-end">
                                        <button type="button" class="btn btn-primary button-simpan-education"
                                            style="margin-bottom: 10px; margin-top: 10px;">
                                            Add Education
                                        </button>
                                    </li>
                                </ul>

                                <hr style="margin-top: 20px; clear: both;">

                                <table id="education-datatable" class="table table-striped w-100 nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Degree</th>
                                            <th>Institution</th>
                                            <th>Major</th>
                                            <th>Start Year</th>
                                            <th>Graduated Year</th>
                                            <th>GPA/NEM</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data akan ditambahkan di sini -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="organization">
                                <div class="mb-2">
                                    <label for="organization-name" class="form-label">Organization Name</label>
                                    <input class="form-control" type="text" id="organization-name"
                                        placeholder="fill organization name">
                                    <div class="invalid-feedback">
                                        Please set a organization name.
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="scope" class="form-label">Scope</label>
                                        <select class="form-select" id="scope">
                                            <option value="" disabled selected>select scope</option>
                                            <option value="Fakultas">Fakultas</option>
                                            <option value="Internasional">Internasional</option>
                                            <option value="Jurusan">Jurusan</option>
                                            <option value="Kota">Kota</option>
                                            <option value="Nasional">Nasional</option>
                                            <option value="Regional">Regional</option>
                                            <option value="Universitas">Universitas</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please set a scope.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="title" class="form-label">Title</label>
                                        <select class="form-select" id="title">
                                            <option value="" disabled selected>select title</option>
                                            <option value="Anggota">Anggota</option>
                                            <option value="Ketua">Ketua</option>
                                            <option value="Koordinator">Koordinator</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please set a title.
                                        </div>
                                    </div>
                                </div>
                                <ul class="pager wizard mb-0 list-inline">
                                    <li class="next list-inline-item float-end">
                                        <button type="button" class="btn btn-primary button-simpan-organization"
                                            style="margin-bottom: 10px; margin-top: 10px;">
                                            Add Organization
                                        </button>
                                    </li>
                                </ul>

                                <hr style="margin-top: 20px; clear: both;">

                                <table id="organization-datatable" class="table table-striped w-100 nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Organization Name</th>
                                            <th>Scope</th>
                                            <th>Title</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data akan dirender di sini -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="internship">
                                <div class="mb-2">
                                    <label for="internship-company-name" class="form-label">Company Name</label>
                                    <input class="form-control" type="text" id="internship-company-name"
                                        placeholder="fill company name">
                                    <div class="invalid-feedback">
                                        Please set a company name.
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="internship-function" class="form-label">Function</label>
                                        <select class="form-control select2" data-toggle="select2"
                                            id="internship-function">
                                            <option value="" disabled selected>select function</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please set a function.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="internship-industry" class="form-label">Industry</label>
                                        <select class="form-control select2" data-toggle="select2"
                                            id="internship-industry">
                                            <option value="" disabled selected>select industry</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please set a industry.
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="internship-start-date" class="form-label">Start Date</label>
                                        <input class="form-control" type="date" id="internship-start-date"
                                            placeholder="fill start date">
                                        <div class="invalid-feedback">
                                            Please fill a start date.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="internship-end-date" class="form-label">End Date</label>
                                        <div style="display: flex; align-items: center;">
                                            <input class="form-control" type="date" id="internship-end-date"
                                                placeholder="fill end date" style="width: 85%;">
                                            <div style="display: flex; align-items: center; padding-left: 10px;">
                                                <input type="checkbox" id="internship-current-date-checkbox"
                                                    onchange="setEndDateNow()">
                                                <label for="internship-current-date-checkbox"
                                                    style="margin-left: 5px;">Present</label>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please fill a valid year.
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label for="internship-job-description" class="form-label">Job Description</label>
                                    <textarea class="form-control" id="internship-job-description" rows="2"></textarea>
                                </div>

                                <ul class="pager wizard mb-0 list-inline">
                                    <li class="next list-inline-item float-end">
                                        <button type="button" class="btn btn-primary button-simpan-internship"
                                            style="margin-bottom: 10px; margin-top: 10px;">
                                            Add Internship
                                        </button>
                                    </li>
                                </ul>

                                <hr style="margin-top: 20px; clear: both;">

                                <table id="internship-datatable" class="table table-striped w-100 nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Company Name</th>
                                            <th>Function</th>
                                            <th>Industry</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Job Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data akan dirender di sini -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="job-experience">
                                <div class="mb-2">
                                    <label for="job-company-name" class="form-label">Company Name</label>
                                    <input class="form-control" type="text" id="job-company-name"
                                        placeholder="fill company name">
                                    <div class="invalid-feedback">
                                        Please set a company name.
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label for="job-title" class="form-label">Title</label>
                                    <select class="form-select" id="job-title">
                                        <option value="" disabled selected>select job title</option>
                                        <option value="General Manager/ Director / Executive">General Manager/ Director /
                                            Executive</option>
                                        <option value="Internship">Internship</option>
                                        <option value="Middle/ Senior Manager">Middle/ Senior Manager</option>
                                        <option value="Staff/ Officer">Staff/ Officer</option>
                                        <option value="Supervisor/ Coordinator">Supervisor/ Coordinator</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please set a title.
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="job-position" class="form-label">Position</label>
                                        <input class="form-control" type="text" id="job-position"
                                            placeholder="fill position">
                                        <div class="invalid-feedback">
                                            Please set a position.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="job-position-type" class="form-label">Position Type</label>
                                        <select class="form-select" id="job-position-type">
                                            <option value="" disabled selected>select position type</option>
                                            <option value="MT">MT</option>
                                            <option value="Non MT">Non MT</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please set a position type.
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="job-function" class="form-label">Function</label>
                                        <select class="form-control select2" data-toggle="select2" id="job-function">
                                            <option value="" disabled selected>select function</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please set a function.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="job-industry" class="form-label">Industry</label>
                                        <select class="form-control select2" data-toggle="select2" id="job-industry">
                                            <option value="" disabled selected>select industry</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please set a industry.
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="job-start-date" class="form-label">Start Date</label>
                                        <input class="form-control" type="date" id="job-start-date"
                                            placeholder="fill start date">
                                        <div class="invalid-feedback">
                                            Please fill a start date.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="job-end-date" class="form-label">End Date</label>
                                        <div style="display: flex; align-items: center;">
                                            <input class="form-control" type="date" id="job-end-date"
                                                placeholder="fill end date" style="width: 85%;">
                                            <div style="display: flex; align-items: center; padding-left: 10px;">
                                                <input type="checkbox" id="job-current-date-checkbox"
                                                    onchange="setEndDateJobNow()">
                                                <label for="job-current-date-checkbox"
                                                    style="margin-left: 5px;">Present</label>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please fill a valid year.
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label for="job-description-job" class="form-label">Job Description</label>
                                    <textarea class="form-control" id="job-description-job" rows="2"></textarea>
                                </div>

                                <ul class="pager wizard mb-0 list-inline">
                                    <li class="next list-inline-item float-end">
                                        <button type="button" class="btn btn-primary button-simpan-jobExperience"
                                            style="margin-bottom: 10px; margin-top: 10px;">
                                            Add Job
                                            Experience
                                        </button>
                                    </li>
                                </ul>

                                <hr style="margin-top: 20px; clear: both;">

                                <table id="jobexperience-datatable" class="table table-striped w-100 nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Company Name</th>
                                            <th>Title</th>
                                            <th>Position</th>
                                            <th>Position Type</th>
                                            <th>Function</th>
                                            <th>Industry</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Job Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data akan dirender di sini -->
                                    </tbody>
                                </table>
                                <ul class="pager wizard mb-0 list-inline">
                                    <li class="next list-inline-item float-end">
                                        <button type="button" class="btn btn-success button-simpan"
                                            style="margin-bottom: 10px; margin-top: 10px;">
                                            Save All Data
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Edit Input Application modal content -->
    <div id="edit-inputapplication-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="auth-brand text-center mt-2 mb-3 position-relative top-0">
                        <a class="logo-dark">
                            <span class="logo-lg">
                                <img src="{{ asset('images/recruitment.png') }}" alt="dark logo"
                                    style="vertical-align: middle; width: 35px; height: auto;">
                                <span
                                    style="vertical-align: middle; font-size: 15px; font-weight: bold; margin-left: 10px;">
                                    EDIT APPLICATION
                                </span>
                            </span>
                        </a>
                    </div>
                    <form id="edit-inputapplication-form" class="needs-validation ps-3 pe-3" novalidate>
                        <input type="hidden" id="application-id">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="edit-company" class="form-label">Company</label>
                                <select class="form-select" id="edit-company" required>
                                    <!-- Options dynamically loaded via AJAX -->
                                </select>
                                <div class="invalid-feedback">
                                    Please choose a company.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="edit-vacancy" class="form-label">Vacancy</label>
                                <select class="form-select" id="edit-vacancy" required>
                                    <option value="" disabled selected>Select vacancy</option>
                                    <!-- Options dynamically loaded via AJAX -->
                                </select>
                                <div class="invalid-feedback">
                                    Please choose a vacancy.
                                </div>
                            </div>
                        </div>
                        <hr>
                        <ul class="nav nav-tabs nav-justified nav-bordered mb-3">
                            <li class="nav-item">
                                <a href="#edit-personal-data" data-bs-toggle="tab" aria-expanded="true"
                                    class="nav-link active">
                                    Personal Data
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#edit-education" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                    Education
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#edit-organization" data-bs-toggle="tab" aria-expanded="false"
                                    class="nav-link">
                                    Organization
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#edit-internship" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                    Internship
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#edit-job-experience" data-bs-toggle="tab" aria-expanded="false"
                                    class="nav-link">
                                    Job Experience
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane show active" id="edit-personal-data">
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="edit-name" class="form-label">Full Name</label>
                                        <input class="form-control" type="text" id="edit-name" required
                                            placeholder="fill name">
                                        <div class="invalid-feedback">
                                            Please fill a name.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="edit-email" class="form-label">Email</label>
                                        <input class="form-control" type="email" id="edit-email" required
                                            placeholder="fill email">
                                        <div class="invalid-feedback">
                                            Please fill an email.
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="edit-birth-date" class="form-label">Date of Birth</label>
                                        <input class="form-control" type="date" id="edit-birth-date" required>
                                        <div class="invalid-feedback">
                                            Please fill a date of birth.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="edit-gender" class="form-label">Gender</label>
                                        <select class="form-select" id="edit-gender" required>
                                            <option value="" disabled selected>select gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please choose a gender.
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="edit-domicile" class="form-label">Domicile</label>
                                        <select class="form-control select2" id="edit-domicile" required>
                                            <!-- Options dynamically loaded via AJAX -->
                                        </select>
                                        <div class="invalid-feedback">
                                            Please choose a domicile.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="edit-phone-number" class="form-label">Phone Number</label>
                                        <input class="form-control" type="number" id="edit-phone-number" required
                                            placeholder="fill phone number">
                                        <div class="invalid-feedback">
                                            Please fill a phone number.
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="edit-years-experience" class="form-label">Year(s)</label>
                                        <input class="form-control" type="number" id="edit-years-experience"
                                            min="0" placeholder="fill years experience">
                                        <div class="invalid-feedback">
                                            Please fill valid years of experience.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="edit-months-experience" class="form-label">Month(s)</label>
                                        <input class="form-control" type="number" id="edit-months-experience"
                                            min="0" placeholder="fill months experience">
                                        <div class="invalid-feedback">
                                            Please fill valid months of experience.
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label for="edit-expected-salary" class="form-label">Expected Salary</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input class="form-control" type="text" id="edit-expected-salary"
                                            placeholder="fill expected salary">
                                        <div class="invalid-feedback">
                                            Please set an expected salary.
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="edit-photo" class="form-label">Upload Photo <span
                                                style="color: red; font-size: 11px;">*make sure the photo size is 4x6 or
                                                2x3</span></label>
                                        <div id="imageContainerEdit"
                                            style="border: 2px dashed #cccccc; padding: 10px; text-align: center; cursor: pointer; width: 148px; height: 220px; position: relative;">
                                            <label for="edit-photo"
                                                style="cursor: pointer; width: 100%; height: 100%; display: flex; justify-content: center; align-items: center; position: absolute; top: 0; left: 0;">
                                                <div id="upload-text-edit" style="text-align: center;">Choose an image
                                                </div>
                                                <input type="file" id="edit-photo" accept="image/*"
                                                    style="display:none" onchange="previewImageEdit(event)">
                                            </label>
                                            <img id="imagePreviewEdit" src="" alt="Your Image"
                                                style="max-width: 100%; max-height: 100%; object-fit: contain; display: none; position: absolute; top: 0; left: 0; cursor: pointer;"
                                                onclick="document.getElementById('edit-photo').click();" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="edit-cv" class="form-label">Upload CV <span
                                                style="color: red; font-size: 11px;">*file allowed are pdf</span></label>
                                        <input class="form-control" type="file" id="edit-cv"
                                            accept="application/pdf" placeholder="upload cv">
                                        <div class="invalid-feedback">
                                            Please upload a CV.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Education Tab -->
                            <div class="tab-pane" id="edit-education">
                                <!-- Education form fields, similar to Create but with IDs prefixed by "edit-" -->
                                <div class="mb-2">
                                    <label for="edit-degree" class="form-label">Degree</label>
                                    <select class="form-control select2" id="edit-degree">
                                        <option value="" disabled selected>select degree</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please set a degree.
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="edit-institution" class="form-label">Institution</label>
                                        <select class="form-control select2" id="edit-institution">
                                            <option value="" disabled selected>select institution</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please set an institution.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="edit-major" class="form-label">Major</label>
                                        <select class="form-control select2" id="edit-major">
                                            <option value="" disabled selected>select major</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please set a major.
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="edit-start-year" class="form-label">Start Year</label>
                                        <input class="form-control" id="edit-start-year" type="number" min="1900"
                                            max="2100" step="1" placeholder="YYYY">
                                        <div class="invalid-feedback">
                                            Please fill a valid year.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="edit-graduated-year" class="form-label">Graduated Year</label>
                                        <div style="display: flex; align-items: center;">
                                            <input class="form-control" id="edit-graduated-year" type="number"
                                                min="1900" max="2100" step="1" placeholder="YYYY"
                                                style="width: 85%;">
                                            <div style="display: flex; align-items: center; padding-left: 10px;">
                                                <input type="checkbox" id="edit-current-year-checkbox"
                                                    onchange="setEditGraduatedYear()">
                                                <label for="edit-current-year-checkbox"
                                                    style="margin-left: 5px;">Present</label>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please fill a valid year.
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label for="edit-gpa" class="form-label">GPA/NEM</label>
                                    <div class="input-group">
                                        <input class="form-control" type="number" id="edit-gpa"
                                            placeholder="Fill range GPA" min="0" max="4" step="0.1"
                                            oninput="validateEditGPA(this)">
                                        <span class="input-group-text">/ 4.0</span>
                                        <div class="invalid-feedback">
                                            Please fill a valid GPA between 0 and 4.0.
                                        </div>
                                    </div>
                                </div>

                                <ul class="pager wizard mb-0 list-inline">
                                    <li class="next list-inline-item float-end">
                                        <button type="button" class="btn btn-primary button-edit-education"
                                            style="margin-bottom: 10px; margin-top: 10px;">
                                            Add Education
                                        </button>
                                    </li>
                                </ul>

                                <hr style="margin-top: 20px; clear: both;">

                                <table id="edit-education-datatable" class="table table-striped w-100 nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Degree</th>
                                            <th>Institution</th>
                                            <th>Major</th>
                                            <th>Start Year</th>
                                            <th>Graduated Year</th>
                                            <th>GPA/NEM</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be populated here -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Organization Tab -->
                            <div class="tab-pane" id="edit-organization">
                                <div class="mb-2">
                                    <label for="edit-organization-name" class="form-label">Organization Name</label>
                                    <input class="form-control" type="text" id="edit-organization-name"
                                        placeholder="fill organization name">
                                    <div class="invalid-feedback">
                                        Please set an organization name.
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="edit-scope" class="form-label">Scope</label>
                                        <select class="form-select" id="edit-scope">
                                            <option value="" disabled selected>select scope</option>
                                            <option value="Fakultas">Fakultas</option>
                                            <option value="Internasional">Internasional</option>
                                            <option value="Jurusan">Jurusan</option>
                                            <option value="Kota">Kota</option>
                                            <option value="Nasional">Nasional</option>
                                            <option value="Regional">Regional</option>
                                            <option value="Universitas">Universitas</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please set a scope.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="edit-title" class="form-label">Title</label>
                                        <select class="form-select" id="edit-title">
                                            <option value="" disabled selected>select title</option>
                                            <option value="Anggota">Anggota</option>
                                            <option value="Ketua">Ketua</option>
                                            <option value="Koordinator">Koordinator</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please set a title.
                                        </div>
                                    </div>
                                </div>

                                <ul class="pager wizard mb-0 list-inline">
                                    <li class="next list-inline-item float-end">
                                        <button type="button" class="btn btn-primary button-edit-organization"
                                            style="margin-bottom: 10px; margin-top: 10px;">
                                            Add Organization
                                        </button>
                                    </li>
                                </ul>

                                <hr style="margin-top: 20px; clear: both;">

                                <table id="edit-organization-datatable" class="table table-striped w-100 nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Organization Name</th>
                                            <th>Scope</th>
                                            <th>Title</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be populated here -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Internship Tab -->
                            <div class="tab-pane" id="edit-internship">
                                <div class="mb-2">
                                    <label for="edit-internship-company-name" class="form-label">Company Name</label>
                                    <input class="form-control" type="text" id="edit-internship-company-name"
                                        placeholder="fill company name">
                                    <div class="invalid-feedback">
                                        Please set a company name.
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="edit-internship-function" class="form-label">Function</label>
                                        <select class="form-control select2" id="edit-internship-function">
                                            <!-- Options dynamically loaded via AJAX -->
                                        </select>
                                        <div class="invalid-feedback">
                                            Please set a function.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="edit-internship-industry" class="form-label">Industry</label>
                                        <select class="form-control select2" id="edit-internship-industry">
                                            <!-- Options dynamically loaded via AJAX -->
                                        </select>
                                        <div class="invalid-feedback">
                                            Please set an industry.
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="edit-internship-start-date" class="form-label">Start Date</label>
                                        <input class="form-control" type="date" id="edit-internship-start-date"
                                            placeholder="fill start date">
                                        <div class="invalid-feedback">
                                            Please fill a start date.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="edit-internship-end-date" class="form-label">End Date</label>
                                        <div style="display: flex; align-items: center;">
                                            <input class="form-control" type="date" id="edit-internship-end-date"
                                                placeholder="fill end date" style="width: 85%;">
                                            <div style="display: flex; align-items: center; padding-left: 10px;">
                                                <input type="checkbox" id="edit-internship-current-date-checkbox"
                                                    onchange="setEditInternshipEndDateNow()">
                                                <label for="edit-internship-current-date-checkbox"
                                                    style="margin-left: 5px;">Present</label>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please fill a valid end date.
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label for="edit-internship-job-description" class="form-label">Job
                                        Description</label>
                                    <textarea class="form-control" id="edit-internship-job-description" rows="2"></textarea>
                                </div>

                                <ul class="pager wizard mb-0 list-inline">
                                    <li class="next list-inline-item float-end">
                                        <button type="button" class="btn btn-primary button-edit-internship"
                                            style="margin-bottom: 10px; margin-top: 10px;">
                                            Add Internship
                                        </button>
                                    </li>
                                </ul>

                                <hr style="margin-top: 20px; clear: both;">

                                <table id="edit-internship-datatable" class="table table-striped w-100 nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Company Name</th>
                                            <th>Function</th>
                                            <th>Industry</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Job Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be populated here -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Job Experience Tab -->
                            <div class="tab-pane" id="edit-job-experience">
                                <div class="mb-2">
                                    <label for="edit-job-company-name" class="form-label">Company Name</label>
                                    <input class="form-control" type="text" id="edit-job-company-name"
                                        placeholder="fill company name">
                                    <div class="invalid-feedback">
                                        Please set a company name.
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label for="edit-job-title" class="form-label">Title</label>
                                    <select class="form-select" id="edit-job-title">
                                        <option value="" disabled selected>select job title</option>
                                        <option value="General Manager/ Director / Executive">General Manager/ Director /
                                            Executive</option>
                                        <option value="Internship">Internship</option>
                                        <option value="Middle/ Senior Manager">Middle/ Senior Manager</option>
                                        <option value="Staff/ Officer">Staff/ Officer</option>
                                        <option value="Supervisor/ Coordinator">Supervisor/ Coordinator</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please set a job title.
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="edit-job-position" class="form-label">Position</label>
                                        <input class="form-control" type="text" id="edit-job-position"
                                            placeholder="fill position">
                                        <div class="invalid-feedback">
                                            Please set a position.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="edit-job-position-type" class="form-label">Position Type</label>
                                        <select class="form-select" id="edit-job-position-type">
                                            <option value="" disabled selected>select position type</option>
                                            <option value="MT">MT</option>
                                            <option value="Non MT">Non MT</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please set a position type.
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="edit-job-function" class="form-label">Function</label>
                                        <select class="form-control select2" id="edit-job-function">
                                            <!-- Options dynamically loaded via AJAX -->
                                        </select>
                                        <div class="invalid-feedback">
                                            Please set a function.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="edit-job-industry" class="form-label">Industry</label>
                                        <select class="form-control select2" id="edit-job-industry">
                                            <!-- Options dynamically loaded via AJAX -->
                                        </select>
                                        <div class="invalid-feedback">
                                            Please set an industry.
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="edit-job-start-date" class="form-label">Start Date</label>
                                        <input class="form-control" type="date" id="edit-job-start-date"
                                            placeholder="fill start date">
                                        <div class="invalid-feedback">
                                            Please fill a start date.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="edit-job-end-date" class="form-label">End Date</label>
                                        <div style="display: flex; align-items: center;">
                                            <input class="form-control" type="date" id="edit-job-end-date"
                                                placeholder="fill end date" style="width: 85%;">
                                            <div style="display: flex; align-items: center; padding-left: 10px;">
                                                <input type="checkbox" id="edit-job-current-date-checkbox"
                                                    onchange="setEditJobEndDateNow()">
                                                <label for="edit-job-current-date-checkbox"
                                                    style="margin-left: 5px;">Present</label>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please fill a valid end date.
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label for="edit-job-description-job" class="form-label">Job Description</label>
                                    <textarea class="form-control" id="edit-job-description-job" rows="2"></textarea>
                                </div>

                                <ul class="pager wizard mb-0 list-inline">
                                    <li class="next list-inline-item float-end">
                                        <button type="button" class="btn btn-primary button-edit-jobExperience"
                                            style="margin-bottom: 10px; margin-top: 10px;">
                                            Add Job Experience
                                        </button>
                                    </li>
                                </ul>

                                <hr style="margin-top: 20px; clear: both;">

                                <table id="edit-jobexperience-datatable" class="table table-striped w-100 nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Company Name</th>
                                            <th>Title</th>
                                            <th>Position</th>
                                            <th>Position Type</th>
                                            <th>Function</th>
                                            <th>Industry</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Job Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be populated here -->
                                    </tbody>
                                </table>
                            </div>

                            <ul class="pager wizard mb-0 list-inline">
                                <li class="next list-inline-item float-end">
                                    <button type="button" class="btn btn-success button-update"
                                        style="margin-bottom: 20px; margin-top: 10px;">
                                        Update Application
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Modal for Import Excel -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="importForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="excel_file" class="form-label">Upload Excel File</label>
                            <input type="file" class="form-control" id="excel_file" name="excel_file" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteinputapplication-modal" tabindex="-1" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteinputapplication-modal">Delete Confirmation</h5>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <p>Are you sure you want to delete this data?</p>
                </div>
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('erecruitment.input-application.script')
@endsection
