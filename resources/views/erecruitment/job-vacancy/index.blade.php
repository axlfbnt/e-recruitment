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
                                    <li class="breadcrumb-item"><a href="{{ route('forma1.index') }}">Job Vacancy</a>
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
                                    <h4 class="header-title mb-0">Manage Job Vacancy</h4>
                                    <button type="button" class="btn btn-success rounded-pill button-add"
                                        data-bs-toggle="modal" data-bs-target="#addjobvacancy-modal">
                                        <i class="fa fa-plus"></i> Add Row
                                    </button>
                                </div>
                                <div class="alert alert-success d-none" id="alert-save-success" role="alert">
                                    <strong>Success - </strong> Your data has been successfully saved. Thank you!
                                </div>
                                <div class="alert alert-success d-none" id="alert-update-success" role="alert">
                                    <strong>Success - </strong> Your data has been successfully updated. Thank you!
                                </div>
                                <div class="alert alert-success d-none" id="alert-delete-success" role="alert">
                                    <strong>Success - </strong> Your data has been successfully deleted. Thank you!
                                </div>
                                <hr>
                                <table id="jobvacancy-datatable" class="table table-striped w-100 nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Vacancy Code</th>
                                            <th>Position</th>
                                            <th>Department</th>
                                            <th>Last Education</th>
                                            <th>Major</th>
                                            <th>IPK</th>
                                            <th>Registration Deadline</th>
                                            <th>Vacancy Status</th>
                                            <th>Status</th>
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
    <div id="addjobvacancy-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    JOB VACANCY
                                </span>
                            </span>
                        </a>
                    </div>

                    <form class="needs-validation ps-3 pe-3" novalidate>
                        <div id="progressbarwizard">
                            <ul class="nav nav-pills nav-justified form-wizard-header mb-3">
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
                                <!-- REQUIRED POSITION -->
                                <div class="tab-pane" id="required-position">
                                    <div class="mb-2">
                                        <label for="position" class="form-label">Position</label>
                                        <select class="form-select" id="position" name="position" required>
                                            <option value="" disabled selected>Select position</option>
                                            <!-- Options akan dimuat secara dinamis menggunakan AJAX -->
                                        </select>
                                        <div class="invalid-feedback">
                                            Please choose a position.
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

                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <label for="join-date" class="form-label">Join Date</label>
                                            <input class="form-control" type="date" id="join-date" required disabled
                                                placeholder="fill join date">
                                            <div class="invalid-feedback">
                                                Please set a join date.
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="number-requests" class="form-label">Number of Requests</label>
                                            <input class="form-control" type="number" id="number-requests" required
                                                placeholder="Fill number of requests">
                                            <div class="invalid-feedback">
                                                Please fill number of requests.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <label for="last-education" class="form-label">Last Education</label>
                                            <input class="form-control" type="text" id="last-education" required
                                                disabled placeholder="Fill last education">
                                            <div class="invalid-feedback">
                                                Please fill last education.
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

                                    <div class="mb-2">
                                        <label for="job-desc" class="form-label">Job Desk</label>
                                        <div id="job-desc" style="height: 200px;"></div>
                                        <div class="invalid-feedback">
                                            Please fill a job desk.
                                        </div>
                                    </div>

                                    <ul class="pager wizard mb-0 list-inline">
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
                                        <label for="required-skills" class="form-label">Required Skills</label>
                                        <div id="required-skills" style="height: 200px;"></div>
                                        <div class="invalid-feedback">
                                            Please fill required skills.
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label for="range-ipk" class="form-label">Range IPK</label>
                                        <div class="input-group">
                                            <input class="form-control" type="number" id="range-ipk" required
                                                placeholder="Fill range IPK" min="0" max="4"
                                                step="0.1" oninput="validateIPK(this)">
                                            <span class="input-group-text">/ 4.0</span>
                                            <div class="invalid-feedback">
                                                Please fill a valid IPK between 0 and 4.0.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <label for="open-date" class="form-label">Open Date</label>
                                            <input class="form-control" type="date" id="open-date" required
                                                placeholder="fill open date">
                                            <div class="invalid-feedback">
                                                Please set a open date.
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="close-date" class="form-label">Close Date</label>
                                            <input class="form-control" type="date" id="close-date" required
                                                placeholder="fill close date">
                                            <div class="invalid-feedback">
                                                Please set a close date.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label for="flow-recruitment" class="form-label">Flow Recruitment</label>
                                        <select class="form-select" id="flow-recruitment" name="flow-recruitment"
                                            required>
                                            <option value="" disabled selected>Select flow recruitment</option>
                                            <!-- Options akan dimuat secara dinamis menggunakan AJAX -->
                                        </select>
                                        <div class="invalid-feedback">
                                            Please choose a flow recruitment.
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <h6 class="fs-15 mt-3">Vacancy Status</h6>
                                        <div class="mt-2">
                                            <div class="form-check form-check-inline">
                                                <input type="radio" id="public" name="vacancy_status"
                                                    class="form-check-input" value="Public" required>
                                                <label class="form-check-label" for="public">Public</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" id="private" name="vacancy_status"
                                                    class="form-check-input" value="Private" required>
                                                <label class="form-check-label" for="private">Private</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-2" id="range-datepicker-container" style="display: none;">
                                        <label for="range-datepicker" class="form-label">Range Publication Vacancy</label>
                                        <input type="text" id="range-datepicker" name="range_publication_add"
                                            class="form-control" required placeholder="yyyy/mm/dd to yyyy/mm/dd">
                                        <div class="invalid-feedback">
                                            Please choose a range publication.
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
                                                onclick="resetJobVacancyModal()" data-bs-dismiss="modal">Cancel</button>
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

    <!-- Edit Job Vacancy Modal -->
    <div id="editjobvacancy-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    EDIT JOB VACANCY
                                </span>
                            </span>
                        </a>
                    </div>

                    <!-- Form for editing job vacancy -->
                    <form class="needs-validation ps-3 pe-3" novalidate>
                        <!-- REQUIRED POSITION Section -->
                        <div class="mb-2">
                            <label for="edit-position" class="form-label">Position</label>
                            <input class="form-control" type="text" id="edit-position" required disabled
                                placeholder="Fill position">
                            <div class="invalid-feedback">
                                Please choose a position.
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="edit-position-status" class="form-label">Position Status</label>
                                <input class="form-control" type="text" id="edit-position-status" required disabled
                                    placeholder="Fill position status">
                                <div class="invalid-feedback">
                                    Please fill position status.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="edit-job-position" class="form-label">Job Position</label>
                                <input class="form-control" type="text" id="edit-job-position" required disabled
                                    placeholder="Fill job position">
                                <div class="invalid-feedback">
                                    Please fill job position.
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="edit-join-date" class="form-label">Join Date</label>
                                <input class="form-control" type="date" id="edit-join-date" required disabled
                                    placeholder="Fill join date">
                                <div class="invalid-feedback">
                                    Please set a join date.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="edit-number-requests" class="form-label">Number of Requests</label>
                                <input class="form-control" type="number" id="edit-number-requests" required
                                    placeholder="Fill number of requests">
                                <div class="invalid-feedback">
                                    Please fill number of requests.
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="edit-last-education" class="form-label">Last Education</label>
                                <input class="form-control" type="text" id="edit-last-education" required disabled
                                    placeholder="Fill last education">
                                <div class="invalid-feedback">
                                    Please fill last education.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="edit-source-submission" class="form-label">Source of Fulfillment Man
                                    Power</label>
                                <input class="form-control" type="text" id="edit-source-submission" required disabled
                                    placeholder="Fill source fulfillment man power">
                                <div class="invalid-feedback">
                                    Please fill source fulfillment man power.
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="edit-job-desc" class="form-label">Job Description</label>
                            <div id="edit-job-desc" style="height: 200px;"></div>
                            <div class="invalid-feedback">
                                Please fill a job description.
                            </div>
                        </div>

                        <!-- POSITION REQUIREMENTS Section -->
                        <div class="mb-2">
                            <label for="edit-required-skills" class="form-label">Required Skills</label>
                            <div id="edit-required-skills" style="height: 200px;"></div>
                            <div class="invalid-feedback">
                                Please fill required skills.
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="edit-range-ipk" class="form-label">Range IPK</label>
                            <div class="input-group">
                                <input class="form-control" type="number" id="edit-range-ipk" required
                                    placeholder="Fill range IPK" min="0" max="4" step="0.1"
                                    oninput="validateIPK(this)">
                                <span class="input-group-text">/ 4.0</span>
                                <div class="invalid-feedback">
                                    Please fill a valid IPK between 0 and 4.0.
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="edit-open-date" class="form-label">Open Date</label>
                                <input class="form-control" type="date" id="edit-open-date" required
                                    placeholder="Fill open date">
                                <div class="invalid-feedback">
                                    Please set an open date.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="edit-close-date" class="form-label">Close Date</label>
                                <input class="form-control" type="date" id="edit-close-date" required
                                    placeholder="Fill close date">
                                <div class="invalid-feedback">
                                    Please set a close date.
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="edit-flow-recruitment" class="form-label">Flow Recruitment</label>
                            <select class="form-select" id="edit-flow-recruitment" name="flow-recruitment">
                                <option value="" disabled selected>Select flow recruitment</option>
                                <!-- Options will be loaded dynamically using AJAX -->
                            </select>
                            <div class="invalid-feedback">
                                Please choose a flow recruitment.
                            </div>
                        </div>

                        <div class="mb-2">
                            <h6 class="fs-15 mt-3">Vacancy Status</h6>
                            <div class="mt-2">
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="edit-public" name="edit_vacancy_status"
                                        class="form-check-input" value="Public" required>
                                    <label class="form-check-label" for="edit-public">Public</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="edit-private" name="edit_vacancy_status"
                                        class="form-check-input" value="Private" required>
                                    <label class="form-check-label" for="edit-private">Private</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-2" id="edit-range-datepicker-container" style="display: none;">
                            <label for="edit-range-datepicker" class="form-label">Range Publication Vacancy</label>
                            <input type="text" id="range-datepicker" name="range_publication_edit"
                                class="form-control" required placeholder="yyyy/mm/dd to yyyy/mm/dd">
                            <div class="invalid-feedback">
                                Please choose a range publication.
                            </div>
                        </div>

                        <!-- Action buttons -->
                        <div class="d-flex justify-content-end mb-4 mt-3">
                            <button type="button" id="closeModalBtn" class="btn btn-soft-warning me-2"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary button-update">Save Changes<i
                                    class="ri-check-line ms-1"></i></button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deletejobvacancy-modal" tabindex="-1" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="deletejobvacancy-modal">Delete Confirmation</h5>
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
    @include('erecruitment.job-vacancy.script')
@endsection
