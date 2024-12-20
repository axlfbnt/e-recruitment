@extends('erecruitment.dashboard.dashboard')

@section('content')
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
                                    <li class="breadcrumb-item"><a href="{{ route('job-description.index') }}">Job
                                            Description</a>
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
                                    <h4 class="header-title mb-0">Manage Job Description</h4>
                                    <button type="button" class="btn btn-success rounded-pill button-add"
                                        data-bs-toggle="modal" data-bs-target="#addjobdesc-modal">
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
                                <hr>
                                <table id="jobdesc-datatable" class="table table-striped w-100 nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Company</th>
                                            <th>Department</th>
                                            <th>Position</th>
                                            <th>Job Description</th>
                                            <th>Updated By</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
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

    <!-- Add Man Power Planning modal content -->
    <div id="addjobdesc-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    JOB DESCRIPTION
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

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="department" class="form-label">Department</label>
                                <select class="form-control select2" data-toggle="select2" id="department" required>
                                    <option value="" disabled selected>Select department</option>
                                    <!-- Options akan dimuat secara dinamis menggunakan AJAX -->
                                </select>
                                <div class="invalid-feedback">
                                    Please choose a department.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="division" class="form-label">Division</label>
                                <input class="form-control" type="text" id="division" required readonly
                                    placeholder="fill division">
                                <div class="invalid-feedback">
                                    Please fill a division.
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="position" class="form-label">Position</label>
                            <select class="form-control select2" data-toggle="select2" id="position" required>
                                <option value="" disabled selected>Select position</option>
                                <!-- Options akan dimuat secara dinamis menggunakan AJAX -->
                            </select>
                            <div class="invalid-feedback">
                                Please choose a position.
                            </div>
                        </div>

                        <div class="mb-2" id="new-position-container" style="display: none;">
                            <label for="new-position" class="form-label">New Position</label>
                            <input class="form-control" type="text" id="new-position" required
                                placeholder="Fill new position">
                            <div class="invalid-feedback">
                                Please fill a new position.
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="job-desc" class="form-label">Job Description</label>
                            <div id="job-desc" style="height: 200px;">
                            </div>
                            <div class="invalid-feedback">
                                Please fill a job description.
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mb-4 mt-3">
                            <button type="button" id="closeModalBtn" class="btn btn-soft-warning me-2"
                                onclick="resetJobDescModal()" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-soft-danger me-2"
                                onclick="resetJobDescModal()">Reset</button>
                            <button class="btn btn-primary button-simpan" type="button">Save Data<i
                                    class="ri-check-line ms-1"></i></button>
                        </div>
                    </form>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- Edit Job Description Modal -->
    <div id="editjobdesc-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    EDIT JOB DESCRIPTION
                                </span>
                            </span>
                        </a>
                    </div>

                    <form class="needs-validation ps-3 pe-3" novalidate>
                        <div class="mb-2">
                            <label for="edit-company" class="form-label">Company</label>
                            <input class="form-control" type="text" id="edit-company" required disabled
                                placeholder="Fill company">
                            <div class="invalid-feedback">
                                Please choose a company.
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="edit-department" class="form-label">Department</label>
                                <input class="form-control" type="text" id="edit-department" required disabled
                                    placeholder="Fill department">
                                <div class="invalid-feedback">
                                    Please choose a department.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="edit-division" class="form-label">Division</label>
                                <input class="form-control" type="text" id="edit-division" required disabled
                                    placeholder="Fill division">
                                <div class="invalid-feedback">
                                    Please fill a division.
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="edit-position" class="form-label">Position</label>
                            <input class="form-control" type="text" id="edit-position" required disabled
                                placeholder="Fill position">
                            <div class="invalid-feedback">
                                Please choose a position.
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="edit-job-desc" class="form-label">Job Description</label>
                            <div id="edit-job-desc" style="height: 200px;">
                                <!-- Populate job description content dynamically -->
                            </div>
                            <div class="invalid-feedback">
                                Please fill a job description.
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
    
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deletejobdesc-modal" tabindex="-1" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="deletejobdesc-modal">Delete Confirmation</h5>
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
    @include('erecruitment.job-description.script')
@endsection
