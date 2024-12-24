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
                    d.company = $('#filter-company').val(); // Kirim filter company
                    d.department = $('#filter-department').val(); // Kirim filter department
                    d.position_status = $('#filter-position-status')
                        .val(); // Kirim filter position status
                }
            },
            columns: [{
                    data: 'action',
                    orderable: false,
                    searchable: false
                },
                {
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

                        function generateAcronym(companyName) {
                            const words = companyName.split(' ');
                            const acronym = words
                                .filter(word => word.length >
                                    2)
                                .map(word => word[0]
                                    .toUpperCase())
                                .join('');
                            return acronym;
                        }

                        return generateAcronym(data); // Kembalikan hanya singkatan
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
                    data: 'is_mpp',
                    render: function(data) {
                        return data ? data : 'N/A';
                    }
                },
                {
                    data: 'position_status',
                    render: function(data) {
                        const positionStatus = {
                            1: 'Replacement',
                            2: 'New'
                        };
                        return `<div data-search="${data}">${positionStatus[data] || 'N/A'}</div>`;
                    }
                },
                {
                    data: 'source_submission',
                    render: function(data) {
                        const sourceSubmission = {
                            1: 'Organik',
                            2: 'Outsource',
                            3: 'Pelatihan Kerja',
                            4: 'OS PKWT'
                        };
                        return `<div data-search="${data}">${sourceSubmission[data] || 'N/A'}</div>`;
                    }
                },
                {
                    data: 'total_man_power',
                    render: function(data) {
                        return data ? data : 'N/A';
                    }
                },
                {
                    data: 'last_education',
                    render: function(data) {
                        const lastEducation = {
                            1: 'SMA/SMK/Sederajat',
                            2: 'Diploma 3',
                            3: 'Sarjana 1',
                            4: 'Sarjana 2'
                        };
                        return `<div data-search="${data}">${lastEducation[data] || 'N/A'}</div>`;
                    }
                },
                {
                    data: 'entry_od_date',
                    render: function(data) {
                        if (data) {
                            const date = new Date(data);
                            return date.toLocaleDateString('id-ID');
                        }
                        return '';
                    }
                },
                {
                    data: 'approved_date',
                    render: function(data) {
                        if (data) {
                            const date = new Date(data);
                            return date.toLocaleDateString('id-ID');
                        }
                        return '';
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
                    data: 'closed_date',
                    render: function(data) {
                        if (data) {
                            const date = new Date(data);
                            return date.toLocaleDateString('id-ID');
                        }
                        return '';
                    }
                },
                {
                    data: 'sla',
                    render: function(data) {
                        return data ? data : '0';
                    }
                },
                {
                    data: 'progress_recruitment',
                    render: function(data) {
                        var labelClass = data === 'Open' ? 'label-open' : (data ===
                            'Sourcing' || data === 'Psikotes' || data === 'Interview HC' ||
                            data === 'Interview User' || data === 'Interview BOD' ||
                            data === 'MCU' || data === 'Offering Letter' ?
                            'label-onprocess' : (data ===
                                'Closed' || data === 'Cancel' ? 'label-close' :
                                ''));
                        return '<span class="' + labelClass + '">' + data +
                            '</span>';
                    }
                },
                {
                    data: 'psikotes',
                    render: function(data) {
                        return data ? data : '0';
                    }
                },
                {
                    data: 'interview_hc',
                    render: function(data) {
                        return data ? data : '0';
                    }
                },
                {
                    data: 'interview_user',
                    render: function(data) {
                        return data ? data : '0';
                    }
                },
                {
                    data: 'interview_bod',
                    render: function(data) {
                        return data ? data : '0';
                    }
                },
                {
                    data: 'mcu',
                    render: function(data) {
                        return data ? data : '0';
                    }
                },
                {
                    data: 'offering_letter',
                    render: function(data) {
                        return data ? data : '0';
                    }
                },
                {
                    data: 'closed',
                    render: function(data) {
                        return data ? data : '0';
                    }
                },
                {
                    data: 'a1_status',
                    render: function(data) {
                        var labelClass = data === 'Created by HC' || data ===
                            'Approved by Dept Head' || data ===
                            'Approved by Div Head' || data === 'Approved by HC' ?
                            'label-open' :
                            (data === 'Not Yet' ? 'label-close' : '');
                        return '<span class="' + labelClass + '">' + data +
                            '</span>';
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
                }, {
                    data: 'created_at',
                    name: 'created_at',
                    visible: false,
                    render: function(data) {
                        return data ? data : 'N/A';
                    }
                },
            ],
            order: [
                ['26', 'desc']
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

        // Event listener untuk filter position status
        $('#filter-position-status').on('change', function() {
            table.draw(); // Memanggil ulang DataTables
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
                $('#edit-progress-recruitment').val(response.result.progress_recruitment);
                $('#edit-psikotes').val(response.result.psikotes);
                $('#edit-interview-hc').val(response.result.interview_hc);
                $('#edit-interview-user').val(response.result.interview_user);
                $('#edit-interview-bod').val(response.result.interview_bod);
                $('#edit-mcu').val(response.result.mcu);
                $('#edit-offering-letter').val(response.result.offering_letter);
                $('#edit-closed').val(response.result.closed);

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
        formData.append('progress_recruitment', $('#edit-progress-recruitment').val());
        formData.append('psikotes', $('#edit-psikotes').val());
        formData.append('interview_hc', $('#edit-interview-hc').val());
        formData.append('interview_user', $('#edit-interview-user').val());
        formData.append('interview_bod', $('#edit-interview-bod').val());
        formData.append('mcu', $('#edit-mcu').val());
        formData.append('offering_letter', $('#edit-offering-letter').val());
        formData.append('closed', $('#edit-closed').val());

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
