<script>
    $(document).ready(function() {
        // Setup CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Inisialisasi Datatable
        var table = $('#mpp-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('man-power-planning') }}",
                type: 'GET',
                data: function(d) {
                    d.company = $('#filter-company').val();
                    d.department = $('#filter-department').val();
                    d.a1_status = $('#filter-a1-status')
                        .val();
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'company',
                    render: function(data) {
                        if (!data) {
                            return 'N/A';
                        }
                        return data.replace(/\bCorporate\b/g, 'Corp.');
                    }
                },
                {
                    data: 'division',
                    render: function(data) {
                        if (!data) {
                            return 'N/A';
                        }
                        return data.replace(/\bCorporate\b/g, 'Corp.');
                    }
                },
                {
                    data: 'position',
                    render: function(data) {
                        return data ? data : 'N/A';
                    }
                },
                {
                    data: 'position_status',
                    render: function(data) {
                        const positionStatusMap = {
                            1: 'Replacement',
                            2: 'New'
                        };
                        return positionStatusMap[data] ||
                            'N/A';
                    }
                },
                {
                    data: 'source_submission',
                    render: function(data) {
                        const sourceSubmissionMap = {
                            1: 'Organik',
                            2: 'Outsource',
                            3: 'Pelatihan Kerja',
                            4: 'OS PKWT'
                        };
                        return sourceSubmissionMap[data] ||
                            'N/A';
                    }
                },
                {
                    data: 'total_man_power',
                    render: function(data) {
                        return data ? data :
                            'N/A';
                    }
                },
                {
                    data: 'last_education',
                    render: function(data) {
                        const educationMap = {
                            1: 'SMA/SMK/Sederajat',
                            2: 'Diploma 3',
                            3: 'Sarjana 1',
                            4: 'Sarjana 2'
                        };
                        return educationMap[data] ||
                            'N/A';
                    }
                },
                {
                    data: 'due_date',
                    render: function(data) {
                        if (data) {
                            const date = new Date(data);
                            return date.toLocaleDateString('id-ID');
                        }
                        return '';
                    }
                },
                {
                    data: 'a1_status',
                    render: function(data) {
                        if (!data)
                            return '<span>N/A</span>';

                        var labelClass = data === 'Created by HC' || data ===
                            'Approved by Dept Head' ||
                            data === 'Approved by Div Head' || data === 'Approved by HC' ?
                            'label-open' :
                            (data === 'Not Yet' ? 'label-close' : '');

                        return '<span class="' + labelClass + '">' + data + '</span>';
                    }
                },
                {
                    data: 'man_power_status',
                    render: function(data) {
                        var labelClass = data === 'Open' ? 'label-open' : (data ===
                            'On Process' ? 'label-onprocess' : (data ===
                                'Closed' || data === 'Need Approval' ? 'label-close' :
                                ''));
                        return '<span class="' + labelClass + '">' + data +
                            '</span>';
                    }
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'created_at',
                    visible: false
                },
            ],
            order: [
                ['12', 'desc']
            ],
            keys: true,
            scrollY: true,
            scrollX: true,
            scrollCollapse: true,
            pagingType: "full_numbers",
            autoWidth: false,
            columnDefs: [{
                targets: "_all",
                render: function(data) {
                    return '<div style="white-space: normal;">' + data +
                        '</div>';
                }
            }],
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
            }
        });

        // Event listener untuk filter company
        $('#filter-company').on('change', function() {
            table.draw(); // Memanggil ulang DataTables
        });
        $('#filter-company').select2();
        $.ajax({
            url: "{{ url('get-companies') }}",
            type: 'GET',
            success: function(companies) {
                let companyOptions = '<option value="All" selected>All</option>';
                $.each(companies, function(index, company) {
                    companyOptions += '<option value="' + company.company_name + '">' +
                        company
                        .company_name + '</option>';
                });
                $('#filter-company').html(companyOptions);
            }
        });

        // Event listener untuk filter department
        $('#filter-department').on('change', function() {
            table.draw(); // Memanggil ulang DataTables
        });
        $('#filter-department').select2();
        $.ajax({
            url: "{{ url('get-filter-departments') }}",
            type: 'GET',
            success: function(filter_department) {
                let filterDepartmentOptions = '<option value="" selected>All</option>';
                $.each(filter_department, function(index, department) {
                    filterDepartmentOptions += '<option value="' + department.department +
                        '">' +
                        department
                        .department + '</option>';
                });
                $('#filter-department').html(filterDepartmentOptions);
            }
        });
        $('#filter-company').on('change', function() {
            var company_name = $(this).val();
            $.ajax({
                url: "{{ url('get-departments') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    company_name: company_name
                },
                success: function(data) {
                    let departmentOptions =
                        '<option value="" selected>All</option>';
                    $.each(data.departments, function(index, department) {
                        departmentOptions += '<option value="' +
                            department + '">' + department +
                            '</option>';
                    });
                    table.draw(); // Memanggil ulang DataTables
                    $('#filter-department').html(departmentOptions);
                }
            });
        });

        // Event listener untuk filter a1 status
        $('#filter-a1-status').on('change', function() {
            table.draw();
        });

        $('#addmpp-modal').on('shown.bs.modal', function() {
            $('#department').select2({
                dropdownParent: $('#addmpp-modal')
            });
            $('#position').select2({
                dropdownParent: $('#addmpp-modal')
            });
        });

        // Load Data Company
        $.ajax({
            url: "{{ url('get-companies') }}",
            type: 'GET',
            success: function(companies) {
                let companyOptions = '<option value="" disabled selected>Select company</option>';
                $.each(companies, function(index, company) {
                    companyOptions += '<option value="' + company.company_name + '">' +
                        company
                        .company_name + '</option>';
                });
                $('#company').html(companyOptions);
            }
        });

        // Load Data Department by Company Selected
        $('#company').on('change', function() {
            var company_name = $(this).val();
            $.ajax({
                url: "{{ url('get-departments') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    company_name: company_name
                },
                success: function(data) {
                    let departmentOptions =
                        '<option value="" disabled selected>Select department</option>';
                    $.each(data.departments, function(index, department) {
                        departmentOptions += '<option value="' +
                            department + '">' + department +
                            '</option>';
                    });
                    $('#department').html(departmentOptions);
                    $('#filter-department').html(departmentOptions);
                }
            });
        });

        // Load Data Division by Company and Department Selected
        $('#department').on('change', function() {
            var department = $(this).val();
            var company_name = $('#company').val();

            $.ajax({
                url: "{{ url('get-division') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    company_name: company_name,
                    department: department
                },
                success: function(data) {
                    $('#division').val(data
                        .division);

                    if ($('#division')
                        .val()
                    ) {
                        $.ajax({
                            url: "{{ url('get-positions') }}",
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                company_name: company_name,
                                department: department,
                                division: $('#division').val()
                            },
                            success: function(data) {
                                let positionOptions =
                                    '<option value="" disabled selected>Select position</option>';

                                if (data.positions.length > 0) {
                                    $.each(data.positions, function(
                                        index, position) {
                                        positionOptions +=
                                            '<option value="' +
                                            position +
                                            '">' +
                                            position +
                                            '</option>';
                                    });
                                } else {
                                    positionOptions +=
                                        '<option value="Others">Others</option>';
                                }

                                // Always add the 'Others' option at the end
                                positionOptions +=
                                    '<option value="Others">Others</option>';

                                $('#position').html(
                                    positionOptions);
                            }
                        });
                    } else {
                        console.error('Division value is null or undefined');
                    }
                }
            });
        });

        // Memunculkan input untuk new position ketika Position selected is Others
        $('#position').on('change', function() {
            var selectedValue = $(this).val();

            if (selectedValue === 'Others') {
                $('#new-position-container').show();
                $('#new-position').prop('required', true); // Menjadikan field required
            } else {
                $('#new-position-container').hide();
                $('#new-position').prop('required', false); // Menghilangkan required jika tidak dipilih
            }
        });

        // Load Data Vendor
        $.ajax({
            url: "{{ route('get.vendor') }}",
            type: 'GET',
            success: function(data) {
                let vendorOptions = '<option value="" disabled selected>Select vendor</option>';

                $.each(data.vendor, function(index, vendor) {
                    vendorOptions += '<option value="' + vendor.id + '">' + vendor
                        .vendor + '</option>';
                });
                $('#vendor').html(vendorOptions);
            }
        });
    });

    // Memunculkan dropdownlist untuk vendor list ketika source-submission selected is Outsource atau OS PKWT
    $('#source-submission').on('change', function() {
        var selectedValue = $(this).val();

        if (selectedValue === '2' || selectedValue === '4') {
            $('#vendor-container').show();
            $('#vendor').prop('required', true); // Menjadikan field required
        } else {
            $('#vendor-container').hide();
            $('#vendor').prop('required', false); // Menghilangkan required jika tidak dipilih
        }
    });

    // Collect data dari form Add MPP
    function collectDataAddMPP() {
        var formData = new FormData();

        formData.append('company', $('#company').val());
        formData.append('department', $('#department').val());
        formData.append('division', $('#division').val());
        formData.append('position', $('#position').val());
        formData.append('position_status', $('#position-status').val());

        // Cek jika posisi "Others" dipilih, tambahkan new position
        if ($('#position').val() === 'Others') {
            formData.append('new_position', $('#new-position').val());
        }

        formData.append('source_submission', $('#source-submission').val());
        formData.append('vendor', $('#vendor').val());
        formData.append('job_position', $('#job-position').val());
        formData.append('total_man_power', $('#total-man-power').val());
        formData.append('last_education', $('#last-education').val());
        formData.append('remarks', $('#remarks').val());
        formData.append('due_date', $('#due-date').val());

        return formData;
    }

    // Proses Button Simpan
    $('.button-simpan').on('click', function(event) {
        // Cek apakah form valid
        var form = $(this).closest('form')[0];

        if (form.checkValidity() === false) {
            event.stopPropagation(); // Mencegah pengiriman form jika tidak valid
        } else {
            simpan(); // Panggil fungsi simpan jika form valid
        }

        form.classList.add('was-validated'); // Kelas untuk menampilkan validasi Bootstrap
    });

    // Fungsi proses Simpan data Man Power Planning
    function simpan() {
        var formData = collectDataAddMPP();
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: "{{ url('man-power-planning') }}",
            type: 'POST',
            data: formData,
            processData: false,
            cache: false,
            contentType: false,
            success: function(response) {
                // Menutup modal setelah sukses
                $('#closeModalBtn').click();

                // Menampilkan alert
                $('#alert-save-success').removeClass('d-none');

                // Refresh datatable
                $('#mpp-datatable').DataTable().ajax.reload();

                // Reset form setelah data berhasil disimpan
                $('#addmpp-modal').find('input, select, textarea').val('');
                $('#addmpp-modal').find('select').prop('selectedIndex', 0);
                $('#addmpp-modal').find('.needs-validation').removeClass('was-validated');
                $('#new-position-container').hide().find('input').val(
                    ''); // Reset elemen spesifik seperti New Position

                setTimeout(function() {
                    $('#alert-save-success').addClass('d-none');
                }, 5000);
            },
            error: function(xhr) {
                alert('Terjadi kesalahan: ' + xhr.responseText);
            }
        });
    }

    // Proses Button Detail
    $(document).on('click', '.button-detail', function() {
        var id = $(this).data('id');
        console.log(id);

        // CSRF Token
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: `/man-power-planning/${id}/detail`,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                console.log(response);
                $('#detailmpp-modal').modal('show');

                if (response.data && response.data.length > 0) {
                    var data = response.data;

                    $('#detail-container').empty();

                    data.forEach(function(item) {
                        var detailHTML = `
                        <div class="alert alert-info text-center fw-bold py-1 mb-0" role="alert">
                            <i class="ri-file-text-line me-1"></i> FORM A1 | PROGRESS RECRUITMENT
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">No Form</label>
                                <input class="form-control" type="text" value="${item.id_form_a1}" disabled>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Due Date</label>
                                <input class="form-control" type="text" value="${item.due_date}" disabled>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Lead Time</label>
                                <div class="input-group">
                                    <input class="form-control" type="number" value="${item.sla}" disabled>
                                    <span class="input-group-text">Day</span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">A1 Status</label>
                                <input class="form-control" type="text" value="${item.a1_status}" disabled>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Number Request</label>
                                <input class="form-control" type="number" value="${item.number_requests}" disabled>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Progress Recruitment</label>
                                <select id="progress_recruitment-${item.id_form_a1}" class="form-control field-edit-${item.id_form_a1}" disabled>
                                    <option value="Open" ${item.progress_recruitment === 'Open' ? 'selected' : ''}>Open</option>
                                    <option value="Sourcing" ${item.progress_recruitment === 'Sourcing' ? 'selected' : ''}>Sourcing</option>
                                    <option value="Psikotes" ${item.progress_recruitment === 'Psikotes' ? 'selected' : ''}>Psikotes</option>
                                    <option value="Interview HC" ${item.progress_recruitment === 'Interview HC' ? 'selected' : ''}>Interview HC</option>
                                    <option value="Interview User" ${item.progress_recruitment === 'Interview User' ? 'selected' : ''}>Interview User</option>
                                    <option value="Interview BOD" ${item.progress_recruitment === 'Interview BOD' ? 'selected' : ''}>Interview BOD</option>
                                    <option value="MCU" ${item.progress_recruitment === 'MCU' ? 'selected' : ''}>MCU</option>
                                    <option value="Offering Letter" ${item.progress_recruitment === 'Offering Letter' ? 'selected' : ''}>Offering Letter</option>
                                    <option value="Closed" ${item.progress_recruitment === 'Closed' ? 'selected' : ''}>Closed</option>
                                    <option value="Cancel" ${item.progress_recruitment === 'Cancel' ? 'selected' : ''}>Cancel</option>
                                    <option value="Waiting Approval" ${item.progress_recruitment === 'Waiting Approval' ? 'selected' : ''}>Waiting Approval</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Psikotes</label>
                                <input id="psikotes-${item.id_form_a1}" class="form-control field-edit-${item.id_form_a1}" type="number" value="${item.psikotes}" disabled>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Interview HC</label>
                                <input id="interview_hc-${item.id_form_a1}" class="form-control field-edit-${item.id_form_a1}" type="number" value="${item.interview_hc}" disabled>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Interview User</label>
                                <input id="interview_user-${item.id_form_a1}" class="form-control field-edit-${item.id_form_a1}" type="number" value="${item.interview_user}" disabled>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Interview BOD</label>
                                <input id="interview_bod-${item.id_form_a1}" class="form-control field-edit-${item.id_form_a1}" type="number" value="${item.interview_bod}" disabled>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Medical Check UP</label>
                                <input id="mcu-${item.id_form_a1}" class="form-control field-edit-${item.id_form_a1}" type="number" value="${item.mcu}" disabled>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Offering Letter</label>
                                <input id="offering_letter-${item.id_form_a1}" class="form-control field-edit-${item.id_form_a1}" type="number" value="${item.offering_letter}" disabled>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Closed</label>
                                <input id="closed-${item.id_form_a1}" class="form-control field-edit-${item.id_form_a1}" type="number" value="${item.closed}" disabled>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mb-4 mt-3">
                            <button type="button" id="button-update-${item.id_form_a1}" value="${item.id_form_a1}" class="btn btn-warning me-2">
                                Update
                            </button>
                        </div>
                    `;
                        $('#detail-container').append(detailHTML);
                    });

                    $(document).on('click', `[id^=button-update-]`, function() {
                        var formId = $(this).val();
                        console.log("Update clicked for ID:", formId);

                        $(`.field-edit-${formId}`).prop('disabled', false);

                        $(this).replaceWith(`
                        <button type="button" id="button-save-${formId}" value="${formId}" class="btn btn-primary me-2">
                            Update Data
                        </button>
                    `);
                    });

                    $(document).on('click', `[id^=button-save-]`, function() {
                        var formId = $(this).val(); // Use `val()` to get the form ID
                        var csrfToken = $('meta[name="csrf-token"]').attr('content');

                        // Collect updated values
                        var updatedData = {
                            progress_recruitment: $(`#progress_recruitment-${formId}`)
                                .val(),
                            psikotes: $(`#psikotes-${formId}`).val(),
                            interview_hc: $(`#interview_hc-${formId}`).val(),
                            interview_user: $(`#interview_user-${formId}`).val(),
                            interview_bod: $(`#interview_bod-${formId}`).val(),
                            mcu: $(`#mcu-${formId}`).val(),
                            offering_letter: $(`#offering_letter-${formId}`).val(),
                            closed: $(`#closed-${formId}`).val(),
                            _token: csrfToken,
                            _method: 'PUT'
                        };

                        $.ajax({
                            url: `/man-power-planning/${formId}/detailUpdate`,
                            type: 'PUT',
                            data: updatedData,
                            success: function(response) {
                                alert('Data updated successfully!');
                                location
                            .reload(); // Reload the page after successful update
                            },
                            error: function(xhr, status, error) {
                                console.error('Error updating data:', xhr
                                    .responseText);
                                alert(
                                    'Failed to update data. Please try again.');
                            }
                        });
                    });
                } else {
                    alert('No data found for this ID');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error: ', status, error);

                $('#noapplication-modal').modal('show');
                $('#noapplication-container').empty();

                var noapplicationHTML = `
                <div class="alert alert-danger text-center fw-bold py-1 mb-0" role="alert">
                    <i class="ri-file-text-line me-1"></i> No Application Found
                </div>
            `;
                $('#noapplication-container').append(noapplicationHTML);
            }
        });
    });

    // Proses Button Edit
    $(document).on('click', '.button-edit', function() {
        var id = $(this).data('id');

        $.ajax({
            url: "{{ url('man-power-planning') }}" + "/" + id + '/edit',
            type: 'GET',
            success: function(response) {
                var positionStatusMapping = {
                    1: "Replacement",
                    2: "New"
                };

                $('#edit-company').val(response.result.company);
                $('#edit-department').val(response.result.department);
                $('#edit-division').val(response.result.division);
                $('#edit-position').val(response.result.position);
                $('#edit-position-status').val(positionStatusMapping[response.result
                    .position_status]);
                $('#edit-new-position').val(response.result.new_position);
                $('#edit-source-submission').val(response.result.source_submission);
                $('#edit-job-position').val(response.result.job_position);
                $('#edit-total-man-power').val(response.result.total_man_power);
                $('#edit-last-education').val(response.result.last_education);
                $('#edit-remarks').val(response.result.remarks);
                $('#edit-due-date').val(response.result.due_date);

                $('.button-update').data('id', id);
            }
        });
    });

    // Collect data dari form Edit MPP
    function collectDataEditMPP() {
        var formData = new FormData();

        formData.append('company', $('#edit-company').val());
        formData.append('department', $('#edit-department').val());
        formData.append('division', $('#edit-division').val());
        formData.append('position', $('#edit-position').val());

        var positionStatusText = $('#edit-position-status').val();
        var positionStatusInteger = positionStatusText === "Replacement" ? 1 : (positionStatusText === "New" ? 2 :
            null);
        formData.append('position_status', positionStatusInteger);

        formData.append('source_submission', $('#edit-source-submission').val());
        formData.append('job_position', $('#edit-job-position').val());
        formData.append('total_man_power', $('#edit-total-man-power').val());
        formData.append('last_education', $('#edit-last-education').val());
        formData.append('remarks', $('#edit-remarks').val());
        formData.append('due_date', $('#edit-due-date').val());

        formData.append('_method', 'PUT');

        return formData;
    }

    // Proses Button Update
    $('.button-update').on('click', function(event) {
        event.preventDefault();

        var id = $(this).data('id');
        update(id);
    });

    // Fungsi proses Update data Man Power Planning
    function update(id) {
        var formData = collectDataEditMPP();
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: "{{ url('man-power-planning') }}" + "/" + id,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            success: function(response) {
                $('#editmpp-modal').modal('hide');
                $('#alert-update-success').removeClass('d-none');
                $('#mpp-datatable').DataTable().ajax.reload();

                setTimeout(function() {
                    $('#alert-update-success').addClass('d-none');
                }, 5000);
            },
            error: function(xhr) {
                alert('Terjadi kesalahan: ' + xhr.responseText);
            }
        });
    }

    // Proses Button Delete
    $(document).on('click', '.button-delete', function() {
        var id = $(this).data('id');
        $('#confirmDelete').data('id', id);
    });

    // Fungsi proses Delete data Man Power Planning
    $('#confirmDelete').on('click', function() {
        var id = $(this).data('id');

        $.ajax({
            url: "{{ url('man-power-planning') }}" + "/" + id,
            type: 'DELETE',
            data: {
                "_token": $('meta[name="csrf-token"]').attr(
                    'content')
            },
            success: function(response) {
                console.log(response.message);
                $('#deletempp-modal').modal('hide');
                $('#alert-delete-success').removeClass('d-none');
                $('#mpp-datatable').DataTable().ajax.reload();

                setTimeout(function() {
                    $('#alert-delete-success').addClass('d-none');
                }, 5000);
            },
            error: function(xhr) {
                // Tampilkan pesan error di konsol
                console.error('Gagal menghapus data!');
            }
        });
    });

    // Reset Modal Add Man Power Planning
    function resetAddManPowerPlanningModal() {
        document.getElementById('company').selectedIndex = 0;
        document.getElementById('department').selectedIndex = 0;
        document.getElementById('position').selectedIndex = 0;
        document.getElementById('position-status').selectedIndex = 0;
        document.getElementById('source-submission').selectedIndex = 0;
        document.getElementById('job-position').selectedIndex = 0;
        document.getElementById('last-education').selectedIndex = 0;

        // Reset input fields
        document.getElementById('division').value = '';
        document.getElementById('total-man-power').value = '';
        document.getElementById('due-date').value = '';

        // Reset textarea
        document.getElementById('remarks').value = '';

        // Remove invalid-feedback class (if exists)
        document.querySelectorAll('.form-select, .form-control').forEach(function(element) {
            element.classList.remove('is-invalid');
        });
    }
</script>
