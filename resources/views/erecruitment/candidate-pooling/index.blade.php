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
                                    <li class="breadcrumb-item"><a href="{{ route('candidate-pooling.index') }}">Candidate
                                            Pooling</a>
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
                                    <h4 class="header-title mb-0">Manage Candidate Pooling</h4>
                                </div>
                                <div class="alert alert-success d-none" id="alert-save-success" role="alert">
                                    <strong>Success - </strong> Your data has been successfully saved. Thank you!
                                </div>
                                <hr>
                                <table id="candidatepooling-datatable" class="table table-striped w-100 nowrap">
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

    <!-- Add Candidate Pooling modal content -->
    <div id="candidatepooling-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    INVITE TO JOIN RECRUITMENT PROCESS
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

                        <div class="mb-2">
                            <label for="vacancy-code" class="form-label">Vacancy Code</label>
                            <input class="form-control" type="text" id="vacancy-code" required readonly>
                            <div class="invalid-feedback">
                                Please fill vacancy code.
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="edit-recruitment-stage" class="form-label">Recruitment Stage</label>
                            <select class="form-select" id="edit-recruitment-stage" required>
                                <option value="Administrative Selection">Administrative Selection</option>
                                <option value="Psychological Test">Psychological Test</option>
                                <option value="Initial Interview">Initial Interview</option>
                                <option value="Follow-up Interview">Follow-up Interview</option>
                                <option value="Final Interview">Final Interview</option>
                                <option value="Medical Check Up">Medical Check Up</option>
                            </select>
                            <div class="invalid-feedback">
                                Please choose a recruitment stage.
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mb-4 mt-3">
                            <button type="button" id="closeModalBtn" class="btn btn-soft-warning me-2"
                                data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-primary button-invited" type="button">Invite <i
                                    class=" ri-send-plane-fill"></i></button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('script')
    @include('erecruitment.candidate-pooling.script')
@endsection
