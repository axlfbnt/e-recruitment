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
                                    <li class="breadcrumb-item"><a href="{{ route('flow-recruitment.index') }}">Template
                                            Form A4</a>
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
                                    <h4 class="header-title mb-0">Manage Template Form A4 (Initial Interview)</h4>
                                    <button type="button" class="btn btn-success rounded-pill button-add"
                                        data-bs-toggle="modal" data-bs-target="#addtemplateformA4-modal">
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
                                <div class="alert alert-success d-none" id="alert-status-success" role="alert">
                                    <strong>Success - </strong> Your template has been successfully active. Thank you!
                                </div>
                                <div class="alert alert-danger d-none" id="alert-status-failed" role="alert">
                                    <strong>Failed - </strong> Templates fail inactive, there must be at least one active template!
                                </div>
                                <hr>
                                <table id="templateforma4-datatable" class="table table-striped w-100 nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Template Name</th>
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

    <!-- Add Form A4 modal content -->
    <div id="addtemplateformA4-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-body">
                    <div class="auth-brand text-center mt-2 mb-3 position-relative top-0">
                        <a class="logo-dark">
                            <span class="logo-lg">
                                <img src="{{ asset('images/recruitment.png') }}" alt="dark logo"
                                    style="vertical-align: middle; width: 35px; height: auto;">
                                <span
                                    style="vertical-align: middle; font-size: 15px; font-weight: bold; margin-left: 10px;">
                                    FORM A4 TEMPLATE
                                </span>
                            </span>
                        </a>
                    </div>

                    <form class="needs-validation ps-3 pe-3" novalidate>
                        <div class="mb-2">
                            <label for="template-name" class="form-label">Template Name</label>
                            <input class="form-control" type="text" id="template-name" required
                                placeholder="Enter template name">
                            <div class="invalid-feedback">
                                Please fill in the template name.
                            </div>
                        </div>

                        <div id="evaluation-container">
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <label for="dimension[]" class="form-label">Dimension</label>
                                    <input class="form-control" name="dimension" type="text" required
                                        placeholder="Dimension">
                                    <div class="invalid-feedback">
                                        Please fill in the dimension.
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="key-explanation[]" class="form-label">Key Explanation</label>
                                    <input class="form-control" name="key-explanation" type="text" required
                                        placeholder="Key Explanation">
                                    <div class="invalid-feedback">
                                        Please fill in the key explanation.
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="total-aspects[]" class="form-label">Total Aspects Score</label>
                                    <input class="form-control" name="total-aspects" type="number" required
                                        placeholder="Total Aspects">
                                    <div class="invalid-feedback">
                                        Please fill in the total aspects score.
                                    </div>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-success add-evaluation-btn"><i
                                            class="ri-add-line"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mb-4 mt-3">
                            <button type="button" id="closeModalBtn" class="btn btn-soft-warning me-2"
                                onclick="resetAddFormA4()" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-soft-danger me-2"
                                onclick="resetAddFormA4()">Reset</button>
                            <button class="btn btn-primary button-simpan" type="button">Save Data<i
                                    class="ri-check-line ms-1"></i></button>
                        </div>
                    </form>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Edit Form A4 modal content -->
    <div id="edittemplateformA4-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-body">
                    <div class="auth-brand text-center mt-2 mb-3 position-relative top-0">
                        <a class="logo-dark">
                            <span class="logo-lg">
                                <img src="{{ asset('images/recruitment.png') }}" alt="dark logo"
                                    style="vertical-align: middle; width: 35px; height: auto;">
                                <span
                                    style="vertical-align: middle; font-size: 15px; font-weight: bold; margin-left: 10px;">
                                    EDIT FORM A4 TEMPLATE
                                </span>
                            </span>
                        </a>
                    </div>

                    <form class="needs-validation ps-3 pe-3" novalidate>
                        <div class="mb-2">
                            <label for="edit-template-name" class="form-label">Template Name</label>
                            <input class="form-control" type="text" id="edit-template-name" required
                                placeholder="Enter template name">
                            <div class="invalid-feedback">
                                Please fill in the template name.
                            </div>
                        </div>

                        <div id="edit-evaluation-container">
                            <div class="row mb-2 edit-evaluation-row">
                                <div class="col-md-4">
                                    <label for="dimension" class="form-label">Dimension</label>
                                    <input class="form-control" name="dimension[]" type="text" required
                                        placeholder="Dimension">
                                    <div class="invalid-feedback">
                                        Please fill in the dimension.
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="key-explanation" class="form-label">Key Explanation</label>
                                    <input class="form-control" name="key-explanation[]" type="text" required
                                        placeholder="Key Explanation">
                                    <div class="invalid-feedback">
                                        Please fill in the key explanation.
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="total-aspects" class="form-label">Total Aspects Score</label>
                                    <input class="form-control" name="total-aspects[]" type="number" required
                                        placeholder="Total Aspects">
                                    <div class="invalid-feedback">
                                        Please fill in the total aspects score.
                                    </div>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-success add-evaluation-btn-edit"><i
                                            class="ri-add-line"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mb-4 mt-3">
                            <button type="button" id="closeModalEditBtn" class="btn btn-soft-warning me-2"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-soft-danger me-2"
                                onclick="resetEditFormA4()">Reset</button>
                            <button class="btn btn-primary button-update" type="button">Save Changes<i
                                    class="ri-check-line ms-1"></i></button>
                        </div>
                    </form>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deletetemplateforma4-modal" tabindex="-1" aria-labelledby="deleteModalLabel"
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
    @include('erecruitment.template-forma4.script')
@endsection
