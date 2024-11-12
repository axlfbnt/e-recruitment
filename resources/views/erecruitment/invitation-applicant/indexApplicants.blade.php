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
                                    <li class="breadcrumb-item"><a href="{{ route('invitation-applicant.index') }}">Invitation Applicant</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Applicants Invited List</li>
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
                                    <h4 class="header-title mb-0">Invitation Applicant</h4>
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
                                <table id="invitationApplicants-datatable" class="table table-striped w-100 nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Previously Applied Position</th>
                                            <th>Name</th>
                                            <th>Gender</th>
                                            <th>Date of Birth</th>
                                            <th>Phone Number</th>
                                            <th>Email</th>
                                            <th>Domicile</th>
                                            <th>Invitation Status</th>
                                            <th>Created At</th>
                                            {{-- <th>Action</th> --}}
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
@endsection

@section('script')
    @include('erecruitment.invitation-applicant.scriptApplicants')
@endsection
