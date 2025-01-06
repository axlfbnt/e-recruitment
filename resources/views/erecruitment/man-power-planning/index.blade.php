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

        .label-onprocess {
            display: inline-block;
            background-color: #ffc107;
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
                                    <li class="breadcrumb-item"><a href="{{ route('mpp.index') }}">Man Power Planning</a>
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
                                    <h4 class="header-title mb-0">Manage Man Power Planning</h4>
                                    <button type="button" class="btn btn-success rounded-pill button-add"
                                        data-bs-toggle="modal" data-bs-target="#addmpp-modal">
                                        <i class="fa fa-plus"></i> Create Data
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
                                <div class="alert alert-danger d-none" id="alert-no-applications-yet" role="alert">
                                    <strong>Failed - </strong> Sorry, No application yet !
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-md-2">
                                        <label for="filter-company">Filter Company:</label>
                                        <select class="form-select" id="filter-company" required>
                                            <option value="All" selected>All</option>
                                            <!-- Options akan dimuat secara dinamis menggunakan AJAX -->
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="filter-department">Filter Department:</label>
                                        <select class="form-select" id="filter-department" required>
                                            <option value="All" selected>All</option>
                                            <!-- Options akan dimuat secara dinamis menggunakan AJAX -->
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="filter-a1-status">Filter A1 Status:</label>
                                        <select id="filter-a1-status" class="form-control">
                                            <option value="">All</option>
                                            <option value="Not Yet">Not Yet</option>
                                            <option value="Approved by HC">Approved by HC</option>
                                        </select>
                                    </div>
                                </div>
                                <table id="mpp-datatable" class="table table-striped w-100 nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Company</th>
                                            <th>Division</th>
                                            <th>Position</th>
                                            <th>Position Status</th>
                                            <th>Source of Submission</th>
                                            <th>Total Man Power</th>
                                            <th>Last Education</th>
                                            <th>Due Date</th>
                                            <th>Form A1 Status</th>
                                            <th>Man Power Status</th>
                                            <th>Action</th>
                                            <th>Created At</th>
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

    <!-- Add Man Power Planning modal content -->
    <div id="addmpp-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="auth-brand text-center mt-2 mb-3 position-relative top-0">
                        <a class="logo-dark">
                            <span class="logo-lg">
                                <img src="{{ asset('images/recruitment.png') }}" alt="dark logo"
                                    style="vertical-align: middle; width: 35px; height: auto;">
                                <span
                                    style="vertical-align: middle; font-size: 15px; font-weight: bold; margin-left: 10px;">
                                    MAN POWER PLANNING
                                </span>
                            </span>
                        </a>
                    </div>

                    <form class="needs-validation ps-3 pe-3" novalidate>
                        <div class="mb-2">
                            <label for="company" class="form-label">Company</label>
                            <select class="form-select" id="company" required>
                                <option value="" disabled selected>Select company</option>
                                <!-- Options akan dimuat secara dinamis menggunakan AJAX -->
                            </select>
                            <div class="invalid-feedback">
                                Please choose a company.
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="department" class="form-label">Department</label>
                            <select class="form-control select2" data-toggle="select2" id="department" required>
                                <option value="" disabled selected>Select department</option>
                                <!-- Options akan dimuat secara dinamis menggunakan AJAX -->
                            </select>
                            <div class="invalid-feedback">
                                Please choose a department.
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="division" class="form-label">Division</label>
                            <input class="form-control" type="text" id="division" required disabled
                                placeholder="fill division">
                            <div class="invalid-feedback">
                                Please fill a division.
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="position" class="form-label">Position</label>
                                <select class="form-control select2" data-toggle="select2" id="position" required>
                                    <option value="" disabled selected>Select position</option>
                                    <!-- Options akan dimuat secara dinamis menggunakan AJAX -->
                                </select>
                                <div class="invalid-feedback">
                                    Please choose a position.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="position-status" class="form-label">Position Status</label>
                                <select class="form-select" id="position-status" required>
                                    <option value="" disabled selected>select position status</option>
                                    <option value="1">Replacement</option>
                                    <option value="2">New</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please choose a position status.
                                </div>
                            </div>
                        </div>

                        <div class="mb-2" id="new-position-container" style="display: none;">
                            <label for="new-position" class="form-label">New Position</label>
                            <input class="form-control" type="text" id="new-position" required
                                placeholder="fill new position">
                            <div class="invalid-feedback">
                                Please fill a new position.
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="source-submission" class="form-label">Source of Submission</label>
                                <select class="form-select" id="source-submission" required>
                                    <option value="" disabled selected>select source</option>
                                    <option value="1">Organik</option>
                                    <option value="2">Outsource</option>
                                    <option value="3">Pelatihan Kerja</option>
                                    <option value="4">OS PKWT</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please choose a source.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="job-position" class="form-label">Job Position</label>
                                <select class="form-select" id="job-position" required>
                                    <option value="" disabled selected>select job position</option>
                                    <option value="Head Office">Head Office</option>
                                    <option value="Plant">Plant</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please choose a job position.
                                </div>
                            </div>
                        </div>

                        <div class="mb-2" id="vendor-container" style="display: none;">
                            <label for="vendor" class="form-label">Vendor</label>
                            <select class="form-select" id="vendor" required>
                                <option value="" disabled selected>select vendor</option>
                            </select>
                            <div class="invalid-feedback">
                                Please choose a vendor.
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="total-man-power" class="form-label">Total Man Power</label>
                                <input class="form-control" type="number" id="total-man-power" required
                                    placeholder="fill total man power">
                                <div class="invalid-feedback">
                                    Please fill a total man power.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="last-education" class="form-label">Last Education</label>
                                <select class="form-select" id="last-education" required>
                                    <option value="" disabled selected>select last education</option>
                                    <option value="1">SMA/SMK/Sederajat</option>
                                    <option value="2">Diploma 3</option>
                                    <option value="3">Sarjana</option>
                                    <option value="4">Magister</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please choose a last education.
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="remarks" rows="2"></textarea>
                        </div>

                        <div class="mb-2">
                            <label for="due-date" class="form-label">Due Date</label>
                            <input class="form-control" type="date" id="due-date" required
                                placeholder="fill due date">
                            <div class="invalid-feedback">
                                Please set a due date.
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mb-4 mt-3">
                            <button type="button" id="closeModalBtn" class="btn btn-soft-warning me-2"
                                onclick="resetAddManPowerPlanningModal()" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-soft-danger me-2"
                                onclick="resetAddManPowerPlanningModal()">Reset</button>
                            <button class="btn btn-primary button-simpan" type="button">Save Data<i
                                    class="ri-check-line ms-1"></i></button>
                        </div>
                    </form>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Edit Man Power Planning modal content -->
    <div id="editmpp-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    EDIT MAN POWER PLANNING
                                </span>
                            </span>
                        </a>
                    </div>

                    <form class="needs-validation ps-3 pe-3" novalidate>
                        <div class="mb-2">
                            <label for="edit-company" class="form-label">Company</label>
                            <input class="form-control" type="text" id="edit-company" required disabled
                                placeholder="Select company">
                            <div class="invalid-feedback">
                                Please choose a company.
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="edit-department" class="form-label">Department</label>
                            <input class="form-control" type="text" id="edit-department" required disabled
                                placeholder="Select department">
                            <div class="invalid-feedback">
                                Please choose a department.
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="edit-division" class="form-label">Division</label>
                            <input class="form-control" type="text" id="edit-division" required disabled
                                placeholder="Select division">
                            <div class="invalid-feedback">
                                Please select a division.
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="edit-position" class="form-label">Position</label>
                                <input class="form-control" type="text" id="edit-position" required disabled
                                    placeholder="Select position">
                                <div class="invalid-feedback">
                                    Please choose a position.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="edit-position-status" class="form-label">Position Status</label>
                                <input class="form-control" type="text" id="edit-position-status" required disabled
                                    placeholder="Select position status">
                                <div class="invalid-feedback">
                                    Please choose a position status.
                                </div>
                            </div>
                        </div>

                        <div class="mb-2" id="edit-new-position-container" style="display: none;">
                            <label for="edit-new-position" class="form-label">New Position</label>
                            <input class="form-control" type="text" id="edit-new-position" required
                                placeholder="Fill new position">
                            <div class="invalid-feedback">
                                Please fill a new position.
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="edit-source-submission" class="form-label">Source of Submission</label>
                                <select class="form-select" id="edit-source-submission" required>
                                    <option value="" disabled selected>Select source</option>
                                    <option value="1">Organik</option>
                                    <option value="2">Outsource</option>
                                    <option value="3">Pelatihan Kerja</option>
                                    <option value="4">OS PKWT</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please choose a source.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="edit-job-position" class="form-label">Job Position</label>
                                <select class="form-select" id="edit-job-position" required>
                                    <option value="" disabled selected>Select job position</option>
                                    <option value="Head Office">Head Office</option>
                                    <option value="Plant">Plant</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please choose a job position.
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="edit-total-man-power" class="form-label">Total Man Power</label>
                                <input class="form-control" type="number" id="edit-total-man-power" required
                                    placeholder="Fill total man power">
                                <div class="invalid-feedback">
                                    Please fill a total man power.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="edit-last-education" class="form-label">Last Education</label>
                                <select class="form-select" id="edit-last-education" required>
                                    <option value="" disabled selected>Select last education</option>
                                    <option value="1">SMA/SMK/Sederajat</option>
                                    <option value="2">Diploma 3</option>
                                    <option value="3">Sarjana</option>
                                    <option value="4">Magister</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please choose a last education.
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="edit-remarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="edit-remarks" rows="2"></textarea>
                        </div>

                        <div class="mb-2">
                            <label for="edit-due-date" class="form-label">Due Date</label>
                            <input class="form-control" type="date" id="edit-due-date" required
                                placeholder="Fill due date">
                            <div class="invalid-feedback">
                                Please set a due date.
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mb-4 mt-3">
                            <button type="button" id="closeEditModalBtn" class="btn btn-soft-warning me-2"
                                data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-primary button-update" type="submit">Update Data</button>
                        </div>
                    </form>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Detail Man Power Planning modal content -->
    <div id="detailmpp-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    DETAIL MAN POWER PLANNING
                                </span>
                            </span>
                        </a>
                    </div>

                    <form class="needs-validation ps-3 pe-3" novalidate>
                        <div id="detail-container"></div>

                        <div class="d-flex justify-content-end mb-4 mt-3">
                            <button type="button" id="closeModalBtn" class="btn btn-soft-warning me-2"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deletempp-modal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="deletempp-modal">Delete Confirmation</h5>
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
    @include('erecruitment.man-power-planning.script')
@endsection
