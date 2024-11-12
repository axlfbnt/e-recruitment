@extends('erecruitment.dashboard.dashboard')

@section('content')
    <style>
        .label-open {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 0.2em 0.6em;
            font-size: 12px;
            border-radius: 0.25rem;
        }

        .label-close {
            display: inline-block;
            background-color: #dc3545;
            color: white;
            padding: 0.2em 0.6em;
            font-size: 12px;
            border-radius: 0.25rem;
        }
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
                                    <li class="breadcrumb-item"><a href="{{ route('forma1.index') }}">Employee
                                            Submission</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Form A1</li>
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
                                    <h4 class="header-title mb-0">Manage Employee Submission (Form A1)</h4>
                                    <button type="button" class="btn btn-success rounded-pill button-add"
                                        data-bs-toggle="modal" data-bs-target="#addforma1-modal">
                                        <i class="fa fa-plus"></i> Add Row
                                    </button>
                                </div>
                                <div class="alert alert-success d-none" id="alert-save-success" role="alert">
                                    <strong>Success - </strong> Your data has been successfully saved. Thank you!
                                </div>
                                <div class="alert alert-success d-none" id="alert-approve-success" role="alert">
                                    <strong>Success - </strong> Your A1 has been successfully approve. Thank you!
                                </div>
                                <div class="alert alert-success d-none" id="alert-update-success" role="alert">
                                    <strong>Success - </strong> Your data has been successfully updated. Thank you!
                                </div>
                                <div class="alert alert-success d-none" id="alert-delete-success" role="alert">
                                    <strong>Success - </strong> Your data has been successfully deleted. Thank you!
                                </div>
                                <hr>
                                <table id="forma1-datatable" class="table table-striped w-100 nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Position</th>
                                            <th>Department</th>
                                            <th>Last Education</th>
                                            <th>Number of Requests</th>
                                            <th>Date</th>
                                            <th>SLA</th>
                                            <th>Status</th>
                                            <th>Rejection Statement</th>
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

    <!-- Add Employee Submission Form A1 modal content -->
    <div id="addforma1-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    EMPLOYEE SUBMISSION (FORM A1)
                                </span>
                            </span>
                        </a>
                    </div>

                    <form class="needs-validation ps-3 pe-3" novalidate>
                        <div id="progressbarwizard">
                            <ul class="nav nav-pills nav-justified form-wizard-header mb-3">
                                <li class="nav-item">
                                    <a href="#information" data-bs-toggle="tab" data-toggle="tab"
                                        class="nav-link rounded-0 py-1 active">
                                        <i class="ri-information-line fw-normal fs-18 align-middle me-1"></i>
                                        <span class="d-none d-sm-inline">INFORMATION</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#required-position" data-bs-toggle="tab" data-toggle="tab"
                                        class="nav-link rounded-0 py-1">
                                        <i class="ri-briefcase-line fw-normal fs-18 align-middle me-1"></i>
                                        <span class="d-none d-sm-inline">REQUIRED POSITION</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#position-requirements" data-bs-toggle="tab" data-toggle="tab"
                                        class="nav-link rounded-0 py-1">
                                        <i class="ri-file-text-line fw-normal fs-18 align-middle me-1"></i>
                                        <span class="d-none d-sm-inline">POSITION REQUIREMENTS</span>
                                    </a>
                                </li>
                            </ul>

                            <!-- Progress bar -->
                            <div id="bar" class="progress mb-3" style="height: 7px;">
                                <div class="bar progress-bar progress-bar-striped progress-bar-animated bg-success"></div>
                            </div>

                            <div class="tab-content b-0 mb-0">
                                <!-- INFORMATION -->
                                <div class="tab-pane active" id="information">
                                    <div class="mb-2">
                                        <label for="no-form" class="form-label">No Form</label>
                                        <input class="form-control" type="text" id="no-form"
                                            value="{{ $formattedId }}" required disabled placeholder="Fill no form">
                                        <div class="invalid-feedback">
                                            Please fill form number.
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <label for="department" class="form-label">Department</label>
                                            <input class="form-control" type="text" id="department" required disabled
                                                value="{{ Auth::user()->department }}" placeholder="Fill department">
                                            <div class="invalid-feedback">
                                                Please fill department.
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="division" class="form-label">Division</label>
                                            <input class="form-control" type="text" id="division" required disabled
                                                value="{{ Auth::user()->division }}" placeholder="Fill division">
                                            <div class="invalid-feedback">
                                                Please fill division.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label for="due-date" class="form-label">Due Date</label>
                                        <input class="form-control" type="date" id="due-date" required
                                            placeholder="fill due date">
                                        <div class="invalid-feedback">
                                            Please set a due date.
                                        </div>
                                    </div>

                                    <ul class="pager wizard mb-0 list-inline">
                                        <li class="next list-inline-item float-end">
                                            <button type="button" class="btn btn-primary"
                                                style="margin-bottom: 10px; margin-top: 10px;">Next
                                                <i class="ri-arrow-right-line ms-1"></i></button>
                                        </li>
                                    </ul>
                                </div>

                                <!-- REQUIRED POSITION -->
                                <div class="tab-pane" id="required-position">
                                    <div class="mb-2">
                                        <label for="position" class="form-label">Position</label>
                                        <select class="form-control select2" data-toggle="select2" id="position"
                                            name="position" required>
                                            <option value="" disabled selected>Select position</option>
                                            <!-- Options akan dimuat secara dinamis menggunakan AJAX -->
                                        </select>
                                        <div class="invalid-feedback">
                                            Please choose a position.
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label for="direct-supervisor" class="form-label">Direct Supervisor</label>
                                        <input class="form-control" type="text" id="direct-supervisor" required
                                            disabled placeholder="Fill direct supervisor">
                                        <div class="invalid-feedback">
                                            Please choose a direct supervisor.
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <label for="position-status" class="form-label">Position Status</label>
                                            <input class="form-control" type="text" id="position-status" required
                                                disabled placeholder="Fill position status">
                                            <div class="invalid-feedback">
                                                Please fill position status.
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="job-position" class="form-label">Job Position</label>
                                            <input class="form-control" type="text" id="job-position" required
                                                disabled placeholder="Fill job position">
                                            <div class="invalid-feedback">
                                                Please fill job position.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label for="join-date" class="form-label">Join Date</label>
                                        <input class="form-control" type="date" id="join-date" required disabled
                                            placeholder="fill join date">
                                        <div class="invalid-feedback">
                                            Please set a join date.
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <label for="number-requests" class="form-label">Number of Requests</label>
                                            <input class="form-control" type="number" id="number-requests" required
                                                placeholder="Fill number of requests" min="" max="">
                                            <div class="invalid-feedback">
                                                Please fill number of requests.
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="source-submission" class="form-label">Source of Fulfillment Man
                                                Power</label>
                                            <input class="form-control" type="text" id="source-submission" required
                                                disabled placeholder="Fill source fulfillment man power">
                                            <div class="invalid-feedback">
                                                Please fill source fulfillment man power.
                                            </div>
                                        </div>
                                    </div>

                                    <ul class="pager wizard mb-0 list-inline">
                                        <li class="previous list-inline-item">
                                            <button type="button" class="btn btn-light"
                                                style="margin-bottom: 10px; margin-top: 10px;"><i
                                                    class="ri-arrow-left-line me-1"></i> Back</button>
                                        </li>
                                        <li class="next list-inline-item float-end">
                                            <button type="button" class="btn btn-primary"
                                                style="margin-bottom: 10px; margin-top: 10px;">Next <i
                                                    class="ri-arrow-right-line ms-1"></i></button>
                                        </li>
                                    </ul>
                                </div>

                                <!-- POSITION REQUIREMENTS -->
                                <div class="tab-pane" id="position-requirements">
                                    <div class="mb-2">
                                        <label for="job-desc" class="form-label">Job Desc</label>
                                        <div id="job-desc" style="height: 200px;"></div>
                                        <div class="invalid-feedback">
                                            Please fill a job desk.
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label for="last-education" class="form-label">Last Education</label>
                                        <input class="form-control" type="text" id="last-education" required disabled
                                            placeholder="Fill last education">
                                        <div class="invalid-feedback">
                                            Please fill last education.
                                        </div>
                                    </div>

                                    <div id="major-container">
                                        <div class="row mb-2">
                                            <div class="col-md-8">
                                                <label for="major[]" class="form-label">Major</label>
                                                <input type="text" class="form-control" id="major" name="major[]"
                                                    required placeholder="Fill major">
                                                <div class="invalid-feedback">
                                                    Please fill a major.
                                                </div>
                                            </div>
                                            <div class="col-md-4 d-flex align-items-end">
                                                <button type="button" class="btn btn-success add-major-btn"><i
                                                        class="ri-add-line"></i></button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <h6 class="fs-15 mt-3">Gender</h6>
                                        <div class="mt-2">
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" class="form-check-input" id="man">
                                                <label class="form-check-label" for="man">Man</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" class="form-check-input" id="woman">
                                                <label class="form-check-label" for="woman">Woman</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <h6 class="fs-15 mt-3">Marital Status</h6>
                                        <div class="mt-2">
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" class="form-check-input" id="marry">
                                                <label class="form-check-label" for="marry">Marry</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" class="form-check-input" id="single">
                                                <label class="form-check-label" for="single">Single</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label for="personality-traits" class="form-label">Personality Traits</label>
                                        <div id="personality-traits" style="height: 200px;"></div>
                                        <div class="invalid-feedback">
                                            Please fill personality traits.
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label for="required-skills" class="form-label">Required Skills</label>
                                        <div id="required-skills" style="height: 200px;"></div>
                                        <div class="invalid-feedback">
                                            Please fill required skills.
                                        </div>
                                    </div>

                                    <ul class="pager wizard mb-0 list-inline">
                                        <li class="previous list-inline-item">
                                            <button type="button" class="btn btn-light"
                                                style="margin-bottom: 10px; margin-top: 10px;"><i
                                                    class="ri-arrow-left-line me-1"></i> Back</button>
                                        </li>
                                        <li class="next list-inline-item float-end">
                                            <button type="button" id="closeModalBtn" class="btn btn-soft-warning me-2"
                                                onclick="resetAddFormA1Modal()" data-bs-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-primary button-simpan"
                                                style="margin-bottom: 10px; margin-top: 10px;">Save Data<i
                                                    class="ri-check-line ms-1"></i></button>
                                        </li>
                                    </ul>
                                </div>
                            </div> <!-- tab-content -->
                        </div> <!-- end #progressbarwizard-->
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Edit Employee Submission Form A1 Modal -->
    <div id="editforma1-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    EDIT EMPLOYEE SUBMISSION (FORM A1)
                                </span>
                            </span>
                        </a>
                    </div>

                    <form class="needs-validation ps-3 pe-3" novalidate>
                        <input type="hidden" id="edit-id-form-a1">

                        <div class="mb-2">
                            <label for="edit-no-form" class="form-label">No Form</label>
                            <input class="form-control" type="text" id="edit-no-form" required disabled>
                            <div class="invalid-feedback">
                                Please fill form number.
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="edit-department" class="form-label">Department</label>
                                <input class="form-control" type="text" id="edit-department" required disabled>
                                <div class="invalid-feedback">
                                    Please fill department.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="edit-division" class="form-label">Division</label>
                                <input class="form-control" type="text" id="edit-division" required disabled>
                                <div class="invalid-feedback">
                                    Please fill division.
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="edit-due-date" class="form-label">Due Date</label>
                            <input class="form-control" type="date" id="edit-due-date" required>
                            <div class="invalid-feedback">
                                Please set a due date.
                            </div>
                        </div>

                        <div class="alert alert-info text-center fw-bold py-1 mb-0" role="alert">
                            <i class="ri-briefcase-line me-1"></i> REQUIRED POSITION
                        </div>
                        <hr>

                        <div class="mb-2">
                            <label for="edit-position" class="form-label">Position</label>
                            <input class="form-control" type="text" id="edit-position" required disabled>
                            <div class="invalid-feedback">
                                Please choose a position.
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="edit-direct-supervisor" class="form-label">Direct Supervisor</label>
                            <input class="form-control" type="text" id="edit-direct-supervisor" required disabled>
                            <div class="invalid-feedback">
                                Please choose a direct supervisor.
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="edit-position-status" class="form-label">Position Status</label>
                                <input class="form-control" type="text" id="edit-position-status" required disabled>
                                <div class="invalid-feedback">
                                    Please fill position status.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="edit-job-position" class="form-label">Job Position</label>
                                <input class="form-control" type="text" id="edit-job-position" required disabled>
                                <div class="invalid-feedback">
                                    Please fill job position.
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="edit-join-date" class="form-label">Join Date</label>
                            <input class="form-control" type="date" id="edit-join-date" required disabled>
                            <div class="invalid-feedback">
                                Please set a join date.
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="edit-number-requests" class="form-label">Number of Requests</label>
                                <input class="form-control" type="number" id="edit-number-requests" required>
                                <div class="invalid-feedback">
                                    Please fill number of requests.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="edit-source-submission" class="form-label">Source of Fulfillment Man
                                    Power</label>
                                <input class="form-control" type="text" id="edit-source-submission" required disabled>
                                <div class="invalid-feedback">
                                    Please fill source fulfillment man power.
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info text-center fw-bold py-1 mb-0" role="alert">
                            <i class="ri-file-text-line me-1"></i> POSITION REQUIREMENTS
                        </div>
                        <hr>

                        <div class="mb-2">
                            <label for="edit-job-desc" class="form-label">Job Desc</label>
                            <div id="edit-job-desc" style="height: 200px;">
                            </div>
                            <div class="invalid-feedback">
                                Please fill a job desk.
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="edit-last-education" class="form-label">Last Education</label>
                            <input class="form-control" type="text" id="edit-last-education" required disabled>
                            <div class="invalid-feedback">
                                Please fill last education.
                            </div>
                        </div>

                        <div id="edit-major-container">
                            <div class="row mb-2">
                                <div class="col-md-8">
                                    <label for="edit-major[]" class="form-label">Major</label>
                                    <input type="text" class="form-control" id="edit-major" name="edit-major[]"
                                        required>
                                    <div class="invalid-feedback">
                                        Please fill a major.
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="button" class="btn btn-success add-major-btn"><i
                                            class="ri-add-line"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <h6 class="fs-15 mt-3">Gender</h6>
                            <div class="mt-2">
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" id="edit-man">
                                    <label class="form-check-label" for="edit-man">Man</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" id="edit-woman">
                                    <label class="form-check-label" for="edit-woman">Woman</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <h6 class="fs-15 mt-3">Marital Status</h6>
                            <div class="mt-2">
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" id="edit-marry">
                                    <label class="form-check-label" for="edit-marry">Marry</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" id="edit-single">
                                    <label class="form-check-label" for="edit-single">Single</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="edit-personality-traits" class="form-label">Personality Traits</label>
                            <div id="edit-personality-traits" style="height: 200px;">
                            </div>
                            <div class="invalid-feedback">
                                Please fill a personality traits.
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="edit-required-skills" class="form-label">Required Skills</label>
                            <div id="edit-required-skills" style="height: 200px;">
                            </div>
                            <div class="invalid-feedback">
                                Please fill required skills.
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mb-4 mt-3">
                            <button type="button" id="closeEditModalBtn" class="btn btn-soft-warning me-2"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary button-update">Save Changes</button>
                        </div>
                    </form>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Detail Employee Submission Form A1 Modal -->
    <div id="detailforma1-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    DETAIL EMPLOYEE SUBMISSION (FORM A1)
                                </span>
                            </span>
                        </a>
                    </div>

                    <form class="needs-validation ps-3 pe-3" novalidate>
                        <input type="hidden" id="detail-id-form-a1">

                        <div class="mb-2">
                            <label for="detail-no-form" class="form-label">No Form</label>
                            <input class="form-control" type="text" id="detail-no-form" required disabled>
                            <div class="invalid-feedback">
                                Please fill form number.
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="detail-department" class="form-label">Department</label>
                                <input class="form-control" type="text" id="detail-department" required disabled>
                                <div class="invalid-feedback">
                                    Please fill department.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="detail-division" class="form-label">Division</label>
                                <input class="form-control" type="text" id="detail-division" required disabled>
                                <div class="invalid-feedback">
                                    Please fill division.
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="detail-due-date" class="form-label">Due Date</label>
                            <input class="form-control" type="date" id="detail-due-date" required disabled>
                            <div class="invalid-feedback">
                                Please set a due date.
                            </div>
                        </div>

                        <div class="alert alert-info text-center fw-bold py-1 mb-0" role="alert">
                            <i class="ri-briefcase-line me-1"></i> REQUIRED POSITION
                        </div>
                        <hr>

                        <div class="mb-2">
                            <label for="detail-position" class="form-label">Position</label>
                            <input class="form-control" type="text" id="detail-position" required disabled>
                            <div class="invalid-feedback">
                                Please choose a position.
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="detail-direct-supervisor" class="form-label">Direct Supervisor</label>
                            <input class="form-control" type="text" id="detail-direct-supervisor" required disabled>
                            <div class="invalid-feedback">
                                Please choose a direct supervisor.
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="detail-position-status" class="form-label">Position Status</label>
                                <input class="form-control" type="text" id="detail-position-status" required disabled>
                                <div class="invalid-feedback">
                                    Please fill position status.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="detail-job-position" class="form-label">Job Position</label>
                                <input class="form-control" type="text" id="detail-job-position" required disabled>
                                <div class="invalid-feedback">
                                    Please fill job position.
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="detail-join-date" class="form-label">Join Date</label>
                            <input class="form-control" type="date" id="detail-join-date" required disabled>
                            <div class="invalid-feedback">
                                Please set a join date.
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="detail-number-requests" class="form-label">Number of Requests</label>
                                <input class="form-control" type="number" id="detail-number-requests" required disabled>
                                <div class="invalid-feedback">
                                    Please fill number of requests.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="detail-source-submission" class="form-label">Source of Fulfillment Man
                                    Power</label>
                                <input class="form-control" type="text" id="detail-source-submission" required
                                    disabled>
                                <div class="invalid-feedback">
                                    Please fill source fulfillment man power.
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info text-center fw-bold py-1 mb-0" role="alert">
                            <i class="ri-file-text-line me-1"></i> POSITION REQUIREMENTS
                        </div>
                        <hr>

                        <div class="mb-2">
                            <label for="detail-job-desc" class="form-label">Job Desc</label>
                            <div id="detail-job-desc" style="height: 200px;">
                            </div>
                            <div class="invalid-feedback">
                                Please fill a job desk.
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="detail-last-education" class="form-label">Last Education</label>
                            <input class="form-control" type="text" id="detail-last-education" required disabled>
                            <div class="invalid-feedback">
                                Please fill last education.
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="detail-major" class="form-label">Major</label>
                            <input class="form-control" type="text" id="detail-major" required disabled>
                            <div class="invalid-feedback">
                                Please choose a major.
                            </div>
                        </div>

                        <div class="mb-2">
                            <h6 class="fs-15 mt-3">Gender</h6>
                            <div class="mt-2">
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" id="detail-man">
                                    <label class="form-check-label" for="detail-man">Man</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" id="detail-woman">
                                    <label class="form-check-label" for="detail-woman">Woman</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <h6 class="fs-15 mt-3">Marital Status</h6>
                            <div class="mt-2">
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" id="detail-marry">
                                    <label class="form-check-label" for="detail-marry">Marry</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" id="detail-single">
                                    <label class="form-check-label" for="detail-single">Single</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="detail-personality-traits" class="form-label">Personality Traits</label>
                            <div id="detail-personality-traits" style="height: 200px;">
                            </div>
                            <div class="invalid-feedback">
                                Please fill a personality traits.
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="detail-required-skills" class="form-label">Required Skills</label>
                            <div id="detail-required-skills" style="height: 200px;">
                            </div>
                            <div class="invalid-feedback">
                                Please fill required skills.
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mb-4 mt-3">
                            <button type="button" id="closeDetailModalBtn" class="btn btn-soft-warning me-2"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="button" id="button-approve" class="btn btn-success">Approve Form A1</button>
                        </div>
                    </form>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteforma1-modal" tabindex="-1" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteforma1-modal">Delete Confirmation</h5>
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
    @include('erecruitment.employee-submission-forma1.script')
@endsection
