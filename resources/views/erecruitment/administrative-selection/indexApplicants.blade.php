@extends('erecruitment.dashboard.dashboard')

@section('content')
    <style>
        .vr {
            border-left: 1px solid #ccc;
            height: 35px;
        }

        .applicant-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            /* Dua kolom yang sama besar */
            gap: 20px;
            /* Spasi antar kolom */
            align-items: stretch;
            /* Pastikan semua elemen memiliki tinggi yang sama */
        }
    </style>
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box mb-1 mt-2">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-2">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="ri-home-4-line"></i>
                                            Dashboard</a></li>
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('administrative-selection.index') }}">Administrative
                                            Selection</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Applicants List</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <!-- Card untuk Select All dan Button Action -->
                <div class="row mb-0">
                    <div class="col-12">
                        <div class="card p-3">
                            <div class="d-flex justify-content-start align-items-center">
                                <div class="me-3">
                                    <input type="checkbox" id="select-all-applicants" class="form-check-input me-2"
                                        {{ $applicants->isEmpty() ? 'disabled' : '' }}>
                                    <label for="select-all-applicants">Select All</label>
                                </div>
                                <div class="vr mx-2"></div>
                                <div>
                                    <button type="button" class="btn btn-success ms-2 button-pass"
                                        {{ $applicants->isEmpty() ? 'disabled' : '' }}>
                                        <i class="ri-check-fill me-1"></i> Pass
                                    </button>
                                    <button type="button" class="btn btn-danger ms-2 button-reject"
                                        {{ $applicants->isEmpty() ? 'disabled' : '' }}>
                                        <i class="ri-close-fill me-1"></i> Reject
                                    </button>
                                    <button type="button" class="btn btn-soft-info ms-2 button-candidate-pooling"
                                        {{ $applicants->isEmpty() ? 'disabled' : '' }}>
                                        <i class="ri-inbox-archive-line me-1"></i> Candidate Pooling
                                    </button>
                                    <button type="button" class="btn btn-soft-primary ms-2 button-invite-vacancy"
                                        data-bs-toggle="modal" data-bs-target="#invitevacancy-modal"
                                        {{ $applicants->isEmpty() ? 'disabled' : '' }}>
                                        <i class="ri-send-plane-fill me-1"></i> Invite to Another Vacancy
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Data Pelamar -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                @if ($applicants->isEmpty())
                                    <!-- Jika tidak ada pelamar -->
                                    <div class="text-center p-5">
                                        <h4>No Applicant Here</h4>
                                        <p class="text-muted">There are no applicants for this vacancy at the moment.</p>
                                    </div>
                                @else
                                    <!-- Jika ada pelamar -->
                                    @foreach ($applicants as $applicant)
                                        <div class="card shadow-sm p-3 mb-3 bg-white rounded">
                                            <div class="d-flex justify-content-between">
                                                <div class="d-flex">
                                                    <div class="me-3">
                                                        <!-- Checkbox untuk memilih pelamar -->
                                                        <input type="checkbox" class="form-check-input select-applicant"
                                                            value="{{ $applicant->id }}" />
                                                    </div>
                                                    <div class="me-3">
                                                        <!-- Gambar pelamar -->
                                                        <img src="{{ $applicant->photo_path ? asset('' . $applicant->photo_path) : asset('images/default-avatar.jpg') }}"
                                                            class="rounded"
                                                            style="width: 80px; height: 120px; object-fit: cover;"
                                                            alt="Applicant Photo">
                                                    </div>
                                                </div>

                                                <div class="flex-grow-1">
                                                    <!-- Informasi dasar pelamar -->
                                                    <h5 class="mb-1">{{ $applicant->full_name ?? 'Not Available' }}<span
                                                            class="text-muted"> | </span>
                                                        @if ($applicant->cv_path)
                                                            <a href="/administrative-selection/{{ $applicant->id }}/applicant-cv"
                                                                target="_blank" class="small">View Applicant CV</a>
                                                        @else
                                                            <span class="small text-muted">View Applicant CV</span>
                                                        @endif
                                                    </h5>
                                                    <p class="mb-1 text-muted">
                                                        <i class="ri-user-star-line"></i> Profesional
                                                        ({{ $applicant->years_experience ?? '0' }} years -
                                                        {{ $applicant->months_experience ?? '0' }} months)
                                                        <br>
                                                        <i class="ri-map-pin-line"></i>
                                                        {{ $applicant->domicile_name ?? 'Not Available' }}<br>
                                                        <i class="ri-account-pin-box-line"></i>
                                                        {{ $applicant->age ?? 'Not Available' }} y.o
                                                    </p>
                                                    <p class="mb-1 text-muted"><strong>Applied date:</strong>
                                                        {{ $applicant->created_at ? $applicant->created_at->format('d/m/Y') : 'Not Available' }}
                                                        |
                                                        <strong>Status:</strong>
                                                        {{ $applicant->administrative_status ?? 'Not Available' }}
                                                    </p>
                                                </div>

                                                <div class="applicant-details-grid ms-5 flex-grow-1">
                                                    <!-- Bagian pengalaman kerja pelamar -->
                                                    <div>
                                                        <h6 class="mb-1"><strong>Job Experience</strong></h6>
                                                        @if (isset($applicant->jobExperience) && $applicant->jobExperience->isNotEmpty())
                                                            @foreach ($applicant->jobExperience as $experience)
                                                                <p class="mb-1">
                                                                    <i class="ri-briefcase-line"></i>
                                                                    <strong>{{ $experience->position ?? 'Not Available' }}</strong><br>
                                                                    {{ $experience->title ?? 'Not Available' }}
                                                                    ({{ $experience->start_date ? \Carbon\Carbon::parse($experience->start_date)->format('d/m/Y') : 'N/A' }}
                                                                    -
                                                                    {{ $experience->end_date !== 'Present' ? \Carbon\Carbon::parse($experience->end_date)->format('d/m/Y') : 'Present' }})
                                                                    <br>
                                                                    {{ $experience->company_name ?? 'Not Available' }}<br><br>
                                                                </p>
                                                            @endforeach
                                                        @else
                                                            <p>No Work Experience</p>
                                                        @endif
                                                    </div>

                                                    <!-- Bagian pendidikan pelamar -->
                                                    <div>
                                                        <h6 class="mb-1"><strong>Education</strong></h6>
                                                        @if (isset($applicant->education) && $applicant->education->isNotEmpty())
                                                            @foreach ($applicant->education as $education)
                                                                <p class="mb-1">
                                                                    <i class="ri-graduation-cap-line"></i>
                                                                    <strong>{{ $education->major ?? 'Not Available' }}</strong><br>
                                                                    {{ $education->degree ?? 'Not Available' }} - GPA:
                                                                    {{ $education->gpa ?? 'N/A' }}<br>
                                                                    {{ $education->institution ?? 'Not Available' }}<br><br>
                                                                </p>
                                                            @endforeach
                                                        @else
                                                            <p>No Education Data</p>
                                                        @endif
                                                    </div>

                                                    <div class="dropdown position-absolute" style="top: 15px; right: 15px;">
                                                        <button class="btn btn-link text-muted p-0" type="button"
                                                            id="dropdownMenuButton{{ $applicant->id }}"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ri-more-2-fill" style="font-size: 1.5rem;"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end"
                                                            aria-labelledby="dropdownMenuButton{{ $applicant->id }}">
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    id="detailsMenu{{ $applicant->id }}"
                                                                    data-applicant-id="{{ $applicant->id }}">
                                                                    <i class="ri-contacts-line"></i> View Detail
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <div class="dropdown-divider"></div>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    id="addToPoolMenu{{ $applicant->id }}"
                                                                    data-applicant-id="{{ $applicant->id }}">
                                                                    <i class="ri-inbox-archive-line"></i> Add to Candidate
                                                                    Pool
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    id="inviteMenu{{ $applicant->id }}"
                                                                    data-applicant-id="{{ $applicant->id }}">
                                                                    <i class="ri-send-plane-fill me-1"></i>Invite to
                                                                    Another Vacancy
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <div class="dropdown-divider"></div>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item text-success"
                                                                    id="passMenu{{ $applicant->id }}"
                                                                    data-applicant-id="{{ $applicant->id }}">
                                                                    <i class="ri-check-fill me-1"></i>Pass Candidate
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item text-danger"
                                                                    id="failMenu{{ $applicant->id }}"
                                                                    data-applicant-id="{{ $applicant->id }}">
                                                                    <i class="ri-close-fill me-1"></i>Reject Candidate
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Kontrol Pagination -->
                                    <div class="d-flex justify-content-center">
                                        {{ $applicants->links('pagination::custom-pagination') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invite Another Vacancy modal content -->
    <div id="invitevacancy-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    INVITE TO ANOTHER VACANCY
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
                            <label for="invite-stage" class="form-label">Invite Stage</label>
                            <select class="form-select" id="invite-stage" required>
                                <option value="Administrative Selection">Administrative Selection</option>
                                <option value="Psychological Test">Psychological Test</option>
                                <option value="Initial Interview">Initial Interview</option>
                                <option value="Follow-up Interview">Follow-up Interview</option>
                                <option value="Final Interview">Final Interview</option>
                                <option value="Medical Check Up">Medical Check Up</option>
                            </select>
                            <div class="invalid-feedback">
                                Please choose an invite stage.
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mb-4 mt-3">
                            <button type="button" id="closeModalBtn" class="btn btn-soft-warning me-2"
                                onclick="resetInviteVacancyModal()" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-primary button-invite" type="button">Invite <i
                                    class=" ri-send-plane-fill"></i></button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- History Detail modal content -->
    <div id="historydetail-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    HISTORY DETAILS
                                </span>
                            </span>
                        </a>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="historydetail-datatable" class="table table-striped w-100 nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Company</th>
                                            <th>Position</th>
                                            <th>Last Stage</th>
                                            <th>Status</th>
                                            <th>Applied Date</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('script')
    @include('erecruitment.administrative-selection.scriptApplicants')
@endsection
