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
                                    <li class="breadcrumb-item"><a href="{{ route('flow-recruitment.index') }}">Flow
                                            Recruitment</a>
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
                                    <h4 class="header-title mb-0">Manage Flow Recruitment</h4>
                                    <button type="button" class="btn btn-success rounded-pill button-add"
                                        data-bs-toggle="modal" data-bs-target="#addflowrecruitment-modal">
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
                                <table id="flowrecruitment-datatable" class="table table-striped w-100 nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Template Name</th>
                                            <th>Recruitment Stage</th>
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

    <!-- Add Flow Recruitment modal content -->
    <div id="addflowrecruitment-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    FLOW RECRUITMENT
                                </span>
                            </span>
                        </a>
                    </div>

                    <form class="needs-validation ps-3 pe-3" novalidate>
                        <div class="mb-2">
                            <label for="template-name" class="form-label">Template Name</label>
                            <input class="form-control" type="text" id="template-name" required
                                placeholder="Fill template name">
                            <div class="invalid-feedback">
                                Please fill a template name.
                            </div>
                        </div>

                        <div id="recruitment-stage-container">
                            <div class="row mb-2">
                                <div class="col-md-8">
                                    <label for="recruitment-stage[]" class="form-label">Recruitment Stage</label>
                                    <select class="form-select" id="recruitment-stage" required>
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
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="button" class="btn btn-success add-recruitment-stage-btn"><i
                                            class="ri-add-line"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mb-4 mt-3">
                            <button type="button" id="closeModalBtn" class="btn btn-soft-warning me-2"
                                onclick="resetAddFlowRecruitment()" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-soft-danger me-2"
                                onclick="resetAddFlowRecruitment()">Reset</button>
                            <button class="btn btn-primary button-simpan" type="button">Save Data<i
                                    class="ri-check-line ms-1"></i></button>
                        </div>
                    </form>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Edit Flow Recruitment modal content -->
    <div id="editflowrecruitment-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    FLOW RECRUITMENT
                                </span>
                            </span>
                        </a>
                    </div>

                    <form class="needs-validation ps-3 pe-3" novalidate>
                        <div class="mb-2">
                            <label for="edit-template-name" class="form-label">Template Name</label>
                            <input class="form-control" type="text" id="edit-template-name" required
                                placeholder="Edit template name" value="">
                            <div class="invalid-feedback">
                                Please fill a template name.
                            </div>
                        </div>

                        <div id="edit-recruitment-stage-container">
                            <div class="row mb-2">
                                <div class="col-md-8">
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
                                <div class="col-md-4 d-flex align-items-end">
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button type="button" class="btn btn-success add-recruitment-stage-btn"><i
                                                class="ri-add-line"></i></button>
                                    </div>
                                </div>
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
    <div class="modal fade" id="deleteflowrecruitment-modal" tabindex="-1" aria-labelledby="deleteModalLabel"
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
    @include('erecruitment.flow-recruitment.script')
@endsection
