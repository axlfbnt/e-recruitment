@extends('erecruitment.dashboard.dashboard')

@section('content')
    <style>
        #circle-angle-radial {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }
    </style>
    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-fluid">

                <!-- Header Section -->
                <div class="row">
                    <div class="col-12">
                        <div
                            class="page-title-box justify-content-between d-flex align-items-lg-center flex-lg-row flex-column">
                            <h4 class="page-title">Dashboard</h4>
                            <form class="d-flex mb-xxl-0 mb-2">
                                <div class="input-group">
                                    <input type="text" class="form-control shadow border-0" id="dash-daterange">
                                    <span class="input-group-text bg-primary border-primary text-white">
                                        <i class="ri-calendar-todo-fill fs-13"></i>
                                    </span>
                                </div>
                                <a href="javascript:void(0);" class="btn btn-primary ms-2">
                                    <i class="ri-refresh-line"></i>
                                </a>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row row-cols-1 row-cols-xxl-5 row-cols-lg-3 row-cols-md-2">
                    <div class="col">
                        <div class="card widget-icon-box">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h5 class="text-muted text-uppercase fs-13 mt-0">Total Man Power Request</h5>
                                        <h3 class="my-3" id="total-request">0</h3>
                                        <p class="mb-0 text-muted text-truncate">
                                            <span>Requests</span>
                                        </p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span
                                            class="avatar-title text-bg-success rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="bi bi-emoji-smile"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card widget-icon-box">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h5 class="text-muted text-uppercase fs-13 mt-0">Hold / Cancel</h5>
                                        <h3 class="my-3" id="hold-cancel">0</h3>
                                        <p class="mb-0 text-muted text-truncate">
                                            <span>Man Power</span>
                                        </p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span
                                            class="avatar-title text-bg-warning rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="bi bi-inboxes"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card widget-icon-box">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h5 class="text-muted text-uppercase fs-13 mt-0">On Process</h5>
                                        <h3 class="my-3" id="on-process">0</h3>
                                        <p class="mb-0 text-muted text-truncate">
                                            <span>Man Power</span>
                                        </p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span
                                            class="avatar-title text-bg-dark rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="bi bi-person-bounding-box"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card widget-icon-box">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h5 class="text-muted text-uppercase fs-13 mt-0">On Confirmation</h5>
                                        <h3 class="my-3" id="on-confirmation">0</h3>
                                        <p class="mb-0 text-muted text-truncate">
                                            <span>Man Power</span>
                                        </p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span
                                            class="avatar-title text-bg-info rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="bi bi-person-lines-fill"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card widget-icon-box">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h5 class="text-muted text-uppercase fs-13 mt-0">Man Power Approved</h5>
                                        <h3 class="my-3" id="manpower-approved">0</h3>
                                        <p class="mb-0 text-muted text-truncate">
                                            <span>Man Power</span>
                                        </p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span
                                            class="avatar-title text-bg-primary rounded rounded-3 fs-3 widget-icon-box-avatar">
                                            <i class="bi bi-person-check"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="row row-cols-1 row-cols-md-2">
                    <div class="col-xl-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h4 class="header-title">Man Power Approved by Division</h4>
                                <div dir="ltr">
                                    <div id="basic-bar" class="apex-charts"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h4 class="header-title mb-4">Recruitment Achievement by Division</h4>
                                <div class="text-center" dir="ltr">
                                    <div id="circle-angle-radial" class="apex-charts"
                                        data-colors="#17a497,#4254ba,#fa5c7c,#ffbc00"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h4 class="header-title">Man Power by Source</h4>
                                <div dir="ltr" class="mb-4">
                                    <div id="basic-polar-area" class="apex-charts"
                                        data-colors="#4254ba,#6c757d,#17a497,#fa5c7c,#ffbc00,#39afd1"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h4 class="header-title">Applicant Process Summary</h4>
                                <div dir="ltr" class="mb-4">
                                    <div id="basic-column" class="apex-charts"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-md-2 mt-2">
                    {{-- <div class="col-xl-4 col-lg-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h4 class="header-title">Applicant Process Summary</h4>
                                <div dir="ltr">
                                    <div id="basic-column" class="apex-charts"></div>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <div class="col-xl-6 col-lg-6">
                        <div class="card widget-icon-box">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h5 class="text-muted text-uppercase fs-13 mt-0">Performance Recruitment</h5>
                                        <h3 class="my-3" id="performance-recruitment">0</h3>
                                        <p class="mb-0 text-muted text-truncate">
                                            <span>Percent</span>
                                        </p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span
                                            class="avatar-title text-bg-success rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="bi bi-emoji-smile"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-6">
                        <div class="card widget-icon-box">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h5 class="text-muted text-uppercase fs-13 mt-0">Lead Time Average</h5>
                                        <h3 class="my-3" id="sla-average">0</h3>
                                        <p class="mb-0 text-muted text-truncate">
                                            <span>Days</span>
                                        </p>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span
                                            class="avatar-title text-bg-warning rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                            <i class="bi bi-inboxes"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- container -->

        </div>
        <!-- content -->

        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © Jidox - Coderthemes.com
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
@endsection

@section('script')
    @include('erecruitment.dashboard.script')
@endsection
