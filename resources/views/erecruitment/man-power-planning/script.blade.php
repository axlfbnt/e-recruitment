<script>
    $(document).ready(function() {
        // Setup CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Inisialisasi Datatable
        $('#mpp-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('man-power-planning') }}",
                type: 'GET'
            },
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'company'
                },
                {
                    data: 'division'
                },
                {
                    data: 'position'
                },
                {
                    data: 'position_status',
                    render: function(data, type, row) {
                        // Mapping untuk position_status
                        const positionStatus = {
                            1: 'Replacement',
                            2: 'New'
                        };
                        return `<div data-search="${data}">${positionStatus[data] || 'N/A'}</div>`;
                    }
                },
                {
                    data: 'source_submission',
                    render: function(data, type, row) {
                        // Mapping untuk source_submission
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
                    data: 'total_man_power'
                },
                {
                    data: 'last_education',
                    render: function(data, type, row) {
                        // Mapping untuk last_education
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
                    data: 'remarks'
                },
                {
                    data: 'created_at',
                    render: function(data, type, row) {
                        if (data) {
                            const date = new Date(data);
                            const day = String(date.getDate()).padStart(2, '0');
                            const month = String(date.getMonth() + 1).padStart(2, '0');
                            const year = date.getFullYear();
                            return `${day}-${month}-${year}`;
                        }
                        return '';
                    }
                },
                {
                    data: 'due_date',
                    render: function(data, type, row) {
                        // Pastikan data tidak kosong
                        if (data) {
                            var date = new Date(data);
                            // Format tanggal ke DD/MM/YYYY
                            var day = String(date.getDate()).padStart(2, '0');
                            var month = String(date.getMonth() + 1).padStart(2, '0');
                            var year = date.getFullYear();
                            return day + '/' + month + '/' + year;
                        }
                        return data; // Jika data kosong, kembalikan nilai aslinya
                    }
                },
                {
                    data: 'a1_status',
                    render: function(data, type, row) {
                        var labelClass = '';
                        if (data === 'Created by HC' || data === 'Approved by Dept Head' ||
                            data === 'Approved by Div Head' || data === 'Approved by HC') {
                            labelClass = 'label-open';
                        } else if (data === 'Not Yet') {
                            labelClass = 'label-close';
                        }
                        return '<span class="' + labelClass + '">' + data +
                            '</span>';
                    }
                },
                {
                    data: 'man_power_status',
                    render: function(data, type, row) {
                        var labelClass = '';
                        if (data === 'Open') {
                            labelClass = 'label-open';
                        } else if (data === 'On Process') {
                            labelClass = 'label-onprocess';
                        } else if (data === 'Close' || data === 'Closed') {
                            labelClass = 'label-close';
                        }
                        return '<span class="' + labelClass + '">' + data +
                            '</span>';
                    }
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                ['9', 'desc']
            ],
            keys: true,
            scrollY: 550,
            scrollX: true,
            scrollCollapse: true,
            pagingType: "full_numbers",
            autoWidth: false,
            columnDefs: [{
                targets: "_all",
                render: function(data, type, row) {
                    return '<div style="white-space: normal;">' + data + '</div>';
                }
            }],
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
            }
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
                    ) { // Load Data Position by Company, Department, and Division Selected
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
