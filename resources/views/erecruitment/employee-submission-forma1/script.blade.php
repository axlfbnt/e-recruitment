<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Inisialisasi Datatable
        var table = $('#forma1-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('employee-submission-forma1') }}",
                type: 'GET',
                data: function(d) {
                    d.department = $('#filter-department')
                        .val(); // Kirim filter department
                    d.status = $('#filter-status').val(); // Kirim filter status
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'position_name',
                    name: 'position_name',
                    render: function(data) {
                        return data ? data : 'N/A';
                    }
                },
                {
                    data: 'department',
                    name: 'department',
                    render: function(data) {
                        return data ? data : 'N/A';
                    }
                },
                {
                    data: 'last_education',
                    name: 'last_education',
                    render: function(data) {
                        return data ? data : 'N/A';
                    }
                },
                {
                    data: 'number_requests',
                    name: 'number_requests',
                    render: function(data) {
                        return data ? data : 'N/A';
                    }
                },
                {
                    data: 'due_date',
                    name: 'due_date',
                    render: function(data) {
                        return data ? new Date(data).toLocaleDateString('id-ID') :
                            'N/A';
                    }
                },
                {
                    data: 'sla',
                    name: 'sla',
                    render: function(data) {
                        return data ? data + ' hari' : '0 hari';
                    }
                },
                {
                    data: 'a1_status',
                    name: 'a1_status',
                    render: function(data) {
                        var labelClass = '';
                        if (data === 'Created by HC' || data ===
                            'Approved by Dept Head' || data ===
                            'Approved by Div Head' || data ===
                            'Approved by Human Capital') {
                            labelClass = 'label-open';
                        } else if (data === 'Not Yet') {
                            labelClass = 'label-close';
                        }
                        return '<span class="' + labelClass + '">' + data +
                            '</span>';
                    }
                },
                {
                    data: 'rejection_statement',
                    name: 'rejection_statement',
                    render: function(data) {
                        return data ? data : 'N/A';
                    }
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    visible: false,
                    render: function(data) {
                        return data ? data : 'N/A';
                    }
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [9, 'desc']
            ],
            keys: true,
            scrollY: 550,
            scrollX: true,
            scrollCollapse: true,
            pagingType: "full_numbers",
            autoWidth: false,
            columnDefs: [{
                targets: "_all",
                render: function(data) {
                    return '<div style="white-space: normal;">' + (data ? data :
                        '') + '</div>';
                }
            }],
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
            }
        });

        // Event listener untuk filter department
        $('#filter-department').on('change', function() {
            table.draw(); // Memanggil ulang DataTables
        });

        // Event listener untuk filter status
        $('#filter-status').on('change', function() {
            table.draw(); // Memanggil ulang DataTables
        });

        // Inisialisasi inputan with MPP show dan without MPP hide, Memunculkan Position with MPP
        $('#position-withMPP-container').show();
        // Menghilangkan Position without MPP
        $('#position-withoutMPP-container').hide();
        // Memunculkan Position Detail with MPP
        $('#detailPosition-withMPP-container').show();
        // Menghilangkan Position Detail without MPP
        $('#detailPosition-withoutMPP-container').hide();
        // Memunculkan Last Education with MPP
        $('#last-education-withMPP-container').show();
        // Menghilangkan Last Education without MPP
        $('#last-education-withoutMPP-container').hide();
        // Menambahkan required pada Position with MPP dan detail-nya
        $('#position').prop('required', true);
        $('#position-status').prop('required', true);
        $('#source-submission').prop('required', true);
        $('#job-position').prop('required', true);
        $('#number-requests').prop('required', true);
        $('#join-date').prop('required', true);
        $('#last-education').prop('required', true);
        // Menghilangkan required pada Position without MPP dan detail-nya
        $('#position-withoutMPP').prop('required', false);
        $('#new-position').prop('required', false);
        $('#position-status-withoutMPP').prop('required', false);
        $('#source-submission-withoutMPP').prop('required', false);
        $('#vendor').prop('required', false);
        $('#job-position-withoutMPP').prop('required', false);
        $('#number-requests-withoutMPP').prop('required', false);
        $('#join-date-withoutMPP').prop('required', false);
        $('#last-education-withoutMPP').prop('required', false);

        $('#addforma1-modal').on('shown.bs.modal', function() {
            $('#position').select2({
                dropdownParent: $('#addforma1-modal')
            });
            $('#position-withoutMPP').select2({
                dropdownParent: $('#addforma1-modal')
            });
        });

        $.ajax({
            url: "{{ url('get-positions-forFormA1') }}",
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Kosongkan elemen select
                $('#position').empty();
                $('#position').append(
                    '<option value="" disabled selected>Select position</option>');

                // Loop melalui data yang diterima dan tambahkan ke elemen select
                $.each(data, function(key, value) {
                    $('#position').append('<option value="' + value.id_mpp + '">' + value
                        .position + '</option>');
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching positions:', error);
            }
        });

        $.ajax({
            url: "{{ url('get-positions') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                company_name: '{{ Auth::user()->company_name }}',
                department: $('#department').val(),
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

                $('#position-withoutMPP').html(
                    positionOptions);
            }
        });

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

    function setFormA1WithoutMPP() {
        var checkbox = document.getElementById('forma1-withoutmpp-checkbox');

        if (checkbox.checked) {
            // Menghilangkan Position with MPP
            $('#position-withMPP-container').hide();
            // Memunculkan Position without MPP
            $('#position-withoutMPP-container').show();
            // Menghilangkan Position Detail with MPP
            $('#detailPosition-withMPP-container').hide();
            // Memunculkan Position Detail without MPP
            $('#detailPosition-withoutMPP-container').show();
            // Menghilangkan Last Education with MPP
            $('#last-education-withMPP-container').hide();
            // Memunculkan Last Education without MPP
            $('#last-education-withoutMPP-container').show();
            // Menghilangkan required pada Position with MPP dan detail-nya
            $('#position').prop('required', false);
            $('#position-status').prop('required', false);
            $('#source-submission').prop('required', false);
            $('#job-position').prop('required', false);
            $('#number-requests').prop('required', false);
            $('#join-date').prop('required', false);
            $('#last-education').prop('required', false);
            // Menambahkan required pada Position without MPP dan detail-nya
            $('#position-withoutMPP').prop('required', true);
            $('#position-status-withoutMPP').prop('required', true);
            $('#source-submission-withoutMPP').prop('required', true);
            $('#job-position-withoutMPP').prop('required', true);
            $('#number-requests-withoutMPP').prop('required', true);
            $('#join-date-withoutMPP').prop('required', true);
            $('#last-education-withoutMPP').prop('required', true);
            resetAddFormA1ForCheckbox();
        } else {
            // Memunculkan Position with MPP
            $('#position-withMPP-container').show();
            // Menghilangkan Position without MPP
            $('#position-withoutMPP-container').hide();
            // Memunculkan Position Detail with MPP
            $('#detailPosition-withMPP-container').show();
            // Menghilangkan Position Detail without MPP
            $('#detailPosition-withoutMPP-container').hide();
            // Memunculkan Last Education with MPP
            $('#last-education-withMPP-container').show();
            // Menghilangkan Last Education without MPP
            $('#last-education-withoutMPP-container').hide();
            // Menambahkan required pada Position with MPP dan detail-nya
            $('#position').prop('required', true);
            $('#position-status').prop('required', true);
            $('#source-submission').prop('required', true);
            $('#job-position').prop('required', true);
            $('#number-requests').prop('required', true);
            $('#join-date').prop('required', true);
            $('#last-education').prop('required', true);
            // Menghilangkan required pada Position without MPP dan detail-nya
            $('#position-withoutMPP').prop('required', false);
            $('#position-status-withoutMPP').prop('required', false);
            $('#source-submission-withoutMPP').prop('required', false);
            $('#job-position-withoutMPP').prop('required', false);
            $('#number-requests-withoutMPP').prop('required', false);
            $('#join-date-withoutMPP').prop('required', false);
            $('#last-education-withoutMPP').prop('required', false);
            $('#new-position').prop('required', false);
            resetAddFormA1ForCheckbox();
        }
    }

    // Memunculkan input untuk new position ketika Position without MPP selected is Others
    $('#position-withoutMPP').on('change', function() {
        var selectedValue = $(this).val();

        if (selectedValue === 'Others') {
            $('#new-position-container').show();
            $('#new-position').prop('required', true);
        } else {
            $('#new-position-container').hide();
            $('#new-position').prop('required', false);
        }
    });

    $('#position').on('change', function() {
        var positionId = $(this).val();

        $('#number-requests').attr('min', '');
        $('#number-requests').attr('max', '');
        $('#number-requests').val('');

        $.ajax({
            url: "{{ url('get-position-details') }}",
            type: 'GET',
            data: {
                id: positionId
            },
            dataType: 'json',
            success: function(response) {
                $('#position-status').val(response.position_status);
                $('#job-position').val(response.job_position);
                $('#join-date').val(response.due_date);

                var remainingQuota = response.total_man_power;
                $('#number-requests').attr('min', 1);
                $('#number-requests').attr('max', remainingQuota);

                $('#number-requests').val(remainingQuota);
                $('#source-submission').val(response.source_submission);
                quillJobDesk.root.innerHTML = response.job_desc || '';
                $('#last-education').val(response.last_education);
                quillPersonalityTraits.root.innerHTML = response.personality_traits || '';
                quillRequiredSkills.root.innerHTML = response.required_skills || '';
            },
            error: function(xhr, status, error) {
                console.error('Error fetching position details:', error);
            }
        });
    });

    // Memunculkan dropdownlist untuk vendor list ketika source-submission selected is Outsource atau OS PKWT
    $('#source-submission-withoutMPP').on('change', function() {
        var selectedValue = $(this).val();

        if (selectedValue === 'Outsource' || selectedValue === 'OS PKWT') {
            $('#vendor-container').show();
            $('#vendor').prop('required', true); // Menjadikan field required
        } else {
            $('#vendor-container').hide();
            $('#vendor').prop('required', false); // Menghilangkan required jika tidak dipilih
        }
    });

    // Fungsi untuk menambah input major
    $(document).on('click', '.add-major-btn', function() {
        var newInputRow = `
            <div class="row mb-2 major-row">
                <div class="col-md-8">
                    <input type="text" class="form-control" name="major[]" required placeholder="Fill major">
                    <div class="invalid-feedback">
                        Please fill a major.
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-major-btn"><i class="ri-delete-bin-5-line"></i></button>
                </div>
            </div>`;
        $('#major-container').append(newInputRow);
    });

    // Fungsi untuk menghapus input input major
    $(document).on('click', '.remove-major-btn', function() {
        $(this).closest('.major-row').remove();
    });

    // Collect data dari form Add Form A1
    function collectDataAddFormA1() {
        var formData = new FormData();

        // Ambil nilai dari input teks biasa
        formData.append('no_form', $('#no-form').val());
        formData.append('due_date', $('#due-date').val());
        formData.append('join_date', $('#join-date').val());
        formData.append('number_requests', $('#number-requests').val());

        var is_withmpp = $('#forma1-withoutmpp-checkbox').is(':checked') ? 'Without MPP' : 'With MPP';
        formData.append('is_withmpp', is_withmpp);

        // Ambil nilai dari dropdowns
        formData.append('position', $('#position').val());
        formData.append('direct_supervisor', $('#direct-supervisor').val());
        formData.append('position_status', $('#position-status').val());
        formData.append('job_position', $('#job-position').val());
        formData.append('source_submission', $('#source-submission').val());
        formData.append('last_education', $('#last-education').val());

        // Ambil nilai untuk without MPP
        formData.append('position_withoutMPP', $('#position-withoutMPP').val());
        formData.append('position_status_withoutMPP', $('#position-status-withoutMPP').val());
        formData.append('source_submission_withoutMPP', $('#source-submission-withoutMPP').val());
        formData.append('job_position_withoutMPP', $('#job-position-withoutMPP').val());
        formData.append('number_requests_withoutMPP', $('#number-requests-withoutMPP').val());
        formData.append('last_education_withoutMPP', $('#last-education-withoutMPP').val());
        formData.append('join_date_withoutMPP', $('#join-date-withoutMPP').val());
        formData.append('vendor', $('#vendor').val());
        formData.append('new_position', $('#new-position').val());

        // Ambil nilai dari Quill editor
        formData.append('job_desc', quillJobDesk.root.innerHTML); // Mengambil isi dari editor job_desc
        formData.append('personality_traits', quillPersonalityTraits.root
            .innerHTML); // Mengambil isi dari editor personality_traits
        formData.append('required_skills', quillRequiredSkills.root
            .innerHTML); // Mengambil isi dari editor required_skills

        // Ambil nilai dari checkboxes untuk gender dan marital status
        var gender = [];
        if ($('#man').is(':checked')) {
            gender.push('Man');
        }
        if ($('#woman').is(':checked')) {
            gender.push('Woman');
        }
        formData.append('gender', gender.join(','));

        var maritalStatus = [];
        if ($('#marry').is(':checked')) {
            maritalStatus.push('Marry');
        }
        if ($('#single').is(':checked')) {
            maritalStatus.push('Single');
        }
        formData.append('marital_status', maritalStatus.join(','));

        // Collect and combine all Majors into a single string
        var majors = [];
        $('#major-container input[name="major[]"]').each(function() {
            majors.push($(this).val());
        });
        formData.append('majors', majors.join(','));

        return formData;
    }

    // Proses Button Simpan
    $('.button-simpan').on('click', function(event) {
        // Cek apakah form valid
        var form = $(this).closest('form')[0];

        if (form.checkValidity() === false) {
            event.stopPropagation(); // Mencegah pengiriman form jika tidak valid
        } else {
            simpan();
        }

        form.classList.add('was-validated'); // Kelas untuk menampilkan validasi Bootstrap
    });

    // Fungsi proses Simpan data Form A1
    function simpan() {
        var formData = collectDataAddFormA1();
        formData.append('_token', '{{ csrf_token() }}');
        console.log(formData);

        $.ajax({
            url: "{{ url('employee-submission-forma1') }}",
            type: 'POST',
            data: formData,
            processData: false,
            cache: false,
            contentType: false,
            success: function(response) {
                // Menutup modal setelah sukses
                $('#closeModalBtn').click();

                // Reset form setelah data berhasil disimpan
                $('#addforma1-modal').find('input, select, textarea').val('');
                $('#addforma1-modal').find('select').prop('selectedIndex', 0);
                $('#addforma1-modal').find('.needs-validation').removeClass('was-validated');
                $('#addforma1-modal').find('#major-container').empty(); // Kosongkan container untuk major
                $('#addforma1-modal').find('.ql-editor').html(''); // Kosongkan editor Quill
                $('#addforma1-modal').find('input[type=checkbox]').prop('checked',
                    false); // Reset semua checkbox

                // Menampilkan alert
                $('#alert-save-success').removeClass('d-none');

                // Refresh datatable
                $('#forma1-datatable').DataTable().ajax.reload();

                setTimeout(function() {
                    $('#alert-save-success').addClass('d-none');
                    location.reload();
                }, 5000);
            },
            error: function(xhr) {
                alert('Terjadi kesalahan: ' + xhr.responseText);
            }
        });
    }

    // Proses button Edit
    $(document).on('click', '.button-edit', function() {
        var id = $(this).data('id');
        console.log(id);

        $.ajax({
            url: "{{ url('employee-submission-forma1') }}" + "/" + id + '/edit',
            type: 'GET',
            success: function(response) {

                // Pengisian data input form
                $('#edit-no-form').val(response.result.id_form_a1);
                $('#edit-department').val(response.result.department);
                $('#edit-division').val(response.result.division);
                $('#edit-due-date').val(response.result.due_date);
                $('#edit-position').val(response.result.position_name);
                $('#edit-direct-supervisor').val(response.result.supervisor_name);
                $('#edit-position-status').val(response.result.position_status);
                $('#edit-job-position').val(response.result.job_position);
                $('#edit-join-date').val(response.result.join_date);
                $('#edit-number-requests').val(response.result.number_requests);
                $('#edit-source-submission').val(response.result.source_submission);
                $('#edit-last-education').val(response.result.last_education);
                $('#edit-remarks').val(response.result.remarks);

                // Isi Quill editor dengan data dari response
                quillJobDeskEdit.root.innerHTML = response.result.job_desc || '';
                quillPersonalityTraitsEdit.root.innerHTML = response.result.personality_traits ||
                    '';
                quillRequiredSkillsEdit.root.innerHTML = response.result.required_skills || '';

                $('#edit-major-container').find('.row.mb-2').not(':first').remove();

                if (response.result.major) {
                    var majorsArray = response.result.major.split(',');
                    $('#edit-major-container').find('input[name="edit-major[]"]').val(majorsArray[0]
                        .trim());

                    for (var i = 1; i < majorsArray.length; i++) {
                        var majorInput = `
                    <div class="row mb-2">
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="edit-major[]" value="${majorsArray[i].trim()}" required>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-major-btn"><i class="ri-delete-bin-line"></i></button>
                        </div>
                    </div>`;
                        $('#edit-major-container').append(majorInput);
                    }
                }

                // Isi checkbox gender
                var genders = response.result.gender.split(',');
                $('#edit-man').prop('checked', genders.includes('Man'));
                $('#edit-woman').prop('checked', genders.includes('Woman'));

                // Isi checkbox marital status
                var maritalStatuses = response.result.marital_status.split(',');
                $('#edit-marry').prop('checked', maritalStatuses.includes('Marry'));
                $('#edit-single').prop('checked', maritalStatuses.includes('Single'));

                $('.button-update').data('id', id);
            }
        });
    });

    function collectDataEditFormA1() {
        var formData = new FormData();

        formData.append('id_form_a1', $('#edit-id-form-a1').val());
        formData.append('no_form', $('#edit-no-form').val());
        formData.append('due_date', $('#edit-due-date').val());
        formData.append('join_date', $('#edit-join-date').val());
        formData.append('number_requests', $('#edit-number-requests').val());

        formData.append('position', $('#edit-position').val());
        formData.append('direct_supervisor', $('#edit-direct-supervisor').val());

        formData.append('department', $('#edit-department').val());
        formData.append('division', $('#edit-division').val());
        formData.append('position_status', $('#edit-position-status').val());
        formData.append('job_position', $('#edit-job-position').val());
        formData.append('source_submission', $('#edit-source-submission').val());
        formData.append('last_education', $('#edit-last-education').val());

        // Ambil nilai dari Quill editor
        formData.append('job_desc', quillJobDeskEdit.root.innerHTML); // Mengambil isi dari editor job_desc
        formData.append('personality_traits', quillPersonalityTraitsEdit.root
            .innerHTML); // Mengambil isi dari editor personality_traits
        formData.append('required_skills', quillRequiredSkillsEdit.root
            .innerHTML); // Mengambil isi dari editor required_skills

        // Ambil nilai dari checkboxes untuk gender
        var gender = [];
        if ($('#edit-man').is(':checked')) {
            gender.push('Man');
        }
        if ($('#edit-woman').is(':checked')) {
            gender.push('Woman');
        }
        formData.append('gender', gender.join(','));

        // Ambil nilai dari checkboxes untuk marital status
        var maritalStatus = [];
        if ($('#edit-marry').is(':checked')) {
            maritalStatus.push('Marry');
        }
        if ($('#edit-single').is(':checked')) {
            maritalStatus.push('Single');
        }
        formData.append('marital_status', maritalStatus.join(','));

        // Mengumpulkan nilai Major dari input yang diulang
        var majors = [];
        $('#edit-major-container input[name="edit-major[]"]').each(function() {
            majors.push($(this).val());
        });
        formData.append('majors', majors.join(','));

        formData.append('_method', 'PUT');

        return formData;
    }

    // Proses Button Update
    $('.button-update').on('click', function(event) {
        event.preventDefault();

        var id = $(this).data('id');
        update(id);
    });

    // Fungsi proses Update data Employee Submission Form A1
    function update(id) {
        var formData = collectDataEditFormA1();
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: "{{ url('employee-submission-forma1') }}" + "/" + id,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            success: function(response) {
                $('#editforma1-modal').modal('hide');
                $('#alert-update-success').removeClass('d-none');
                $('#forma1-datatable').DataTable().ajax.reload();

                setTimeout(function() {
                    $('#alert-update-success').addClass('d-none');
                    location.reload();
                }, 5000);
            },
            error: function(xhr) {
                alert('Terjadi kesalahan: ' + xhr.responseText);
            }
        });
    }

    $(document).on('click', '.button-detail', function() {
        var id = $(this).data('id');
        console.log(id);

        // CSRF Token
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: "{{ url('employee-submission-forma1') }}" + "/" + id + '/detail',
            type: 'GET',
            success: function(response) {
                console.log(response);

                // Pengisian data input form
                $('#detail-no-form').val(response.result.id_form_a1);
                $('#detail-department').val(response.result.department);
                $('#detail-division').val(response.result.division);
                $('#detail-due-date').val(response.result.due_date);
                $('#detail-position').val(response.result.position_name);
                $('#detail-direct-supervisor').val(response.result.supervisor_name);
                $('#detail-position-status').val(response.result.position_status);
                $('#detail-job-position').val(response.result.job_position);
                $('#detail-join-date').val(response.result.join_date);
                $('#detail-number-requests').val(response.result.number_requests);
                $('#detail-source-submission').val(response.result.source_submission);
                $('#detail-last-education').val(response.result.last_education);
                $('#detail-remarks').val(response.result.remarks);

                // Isi Quill editor dengan data dari response
                quillJobDeskDetail.root.innerHTML = response.result.job_desc || '';
                quillPersonalityTraitsDetail.root.innerHTML = response.result.personality_traits ||
                    '';
                quillRequiredSkillsDetail.root.innerHTML = response.result.required_skills || '';

                var major = response.result.major;
                var formattedMajor = major.split(',').join(', ');
                $('#detail-major').val(formattedMajor);

                // Isi checkbox gender
                var genders = response.result.gender.split(',');
                $('#detail-man').prop('checked', genders.includes('Man'));
                $('#detail-woman').prop('checked', genders.includes('Woman'));

                // Isi checkbox marital status
                var maritalStatuses = response.result.marital_status.split(',');
                $('#detail-marry').prop('checked', maritalStatuses.includes('Marry'));
                $('#detail-single').prop('checked', maritalStatuses.includes('Single'));
            }
        });

        $('#button-approve').off('click').on('click', function() {
            var position = $('#detail-position').val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ route('forma1.approvalFormA1', '') }}/" + id,
                type: 'PUT',
                data: {
                    _token: csrfToken,
                    position: position
                },
                success: function(response) {
                    // Menampilkan alert
                    $('#alert-approve-success').removeClass('d-none');

                    $('#forma1-datatable').DataTable().ajax.reload();

                    setTimeout(function() {
                        $('#alert-approve-success').addClass('d-none');
                    }, 5000);
                }
            });
            // Hide the modal after the action
            $('#detailforma1-modal').modal('hide');
        });
    });

    // Proses Button Delete
    $(document).on('click', '.button-delete', function() {
        var id = $(this).data('id');
        $('#confirmDelete').data('id', id);
    });

    // Fungsi proses Delete data Man Power Planning
    $('#confirmDelete').on('click', function() {
        var id = $(this).data('id');

        $.ajax({
            url: "{{ url('employee-submission-forma1') }}" + "/" + id,
            type: 'DELETE',
            data: {
                "_token": $('meta[name="csrf-token"]').attr(
                    'content')
            },
            success: function(response) {
                console.log(response.message);
                $('#deleteforma1-modal').modal('hide');
                $('#alert-delete-success').removeClass('d-none');
                $('#forma1-datatable').DataTable().ajax.reload();

                setTimeout(function() {
                    $('#alert-delete-success').addClass('d-none');
                    location.reload();
                }, 5000);
            },
            error: function(xhr) {
                // Tampilkan pesan error di konsol
                console.error('Gagal menghapus data!');
            }
        });
    });

    // Handler untuk tombol hapus major
    $(document).on('click', '.remove-major-btn', function() {
        $(this).closest('.row').remove();
    });

    // Handler untuk tombol tambah major
    $(document).on('click', '.add-major-btn', function() {
        var majorInput = `
    <div class="row mb-2">
        <div class="col-md-8">
            <input type="text" class="form-control" name="edit-major[]" required>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="button" class="btn btn-danger remove-major-btn"><i class="ri-delete-bin-line"></i></button>
        </div>
    </div>`;
        $('#edit-major-container').append(majorInput);
    });

    // Reset Modal Add Form A1
    function resetAddFormA1Modal() {
        // Reset input teks biasa
        $('#no-form').val("{{ $formattedId }}");
        $('#due-date').val('');
        $('#join-date').val('');
        $('#number-requests').val('');

        $('#position').val('').change();
        $('#position-status').val('');
        $('#job-position').val('');
        $('#source-submission').val('');
        $('#last-education').val('');

        // Reset Quill editors
        quillJobDesk.setContents([]);
        quillPersonalityTraits.setContents([]);
        quillRequiredSkills.setContents([]);

        // Reset checkboxes
        $('#man').prop('checked', false);
        $('#woman').prop('checked', false);
        $('#marry').prop('checked', false);
        $('#single').prop('checked', false);
        $('#forma1-withoutmpp-checkbox').prop('checked', false);

        // Reset dynamic major fields
        $('#major-container .row').slice(1).remove();
        $('#major-container .row:first-child input').val('');

        document.querySelectorAll('.nav-link').forEach(function(tab) {
            tab.classList.remove('active');
        });
        document.querySelectorAll('.tab-pane').forEach(function(content) {
            content.classList.remove('active', 'show');
        });

        document.querySelector('a[href="#information"]').classList.add('active');
        document.getElementById('information').classList.add('active', 'show');
        document.querySelector('.bar').style.width = '33%';
    }

    // Reset Modal Add Form A1
    function resetAddFormA1ForCheckbox() {
        // Reset input teks biasa
        $('#no-form').val("{{ $formattedId }}");
        $('#join-date').val('');
        $('#join-date').val('');
        $('#number-requests').val('');

        // Reset dropdowns
        $('#position').val('').change();
        $('#position-status').val('');
        $('#job-position').val('');
        $('#source-submission').val('');
        $('#last-education').val('');

        // Reset Quill editors
        quillJobDesk.setContents([]);
        quillPersonalityTraits.setContents([]);
        quillRequiredSkills.setContents([]);

        // Reset checkboxes
        $('#man').prop('checked', false);
        $('#woman').prop('checked', false);
        $('#marry').prop('checked', false);
        $('#single').prop('checked', false);

        // Reset dynamic major fields
        $('#major-container .row').slice(1).remove();
        $('#major-container .row:first-child input').val('');
    }

    var quillJobDesk = new Quill("#job-desc", {
        theme: "snow",
        modules: {
            toolbar: [
                [{
                    font: []
                }, {
                    size: []
                }],
                ["bold", "italic", "underline", "strike"],
                [{
                    color: []
                }, {
                    background: []
                }],
                [{
                    script: "super"
                }, {
                    script: "sub"
                }],
                [{
                    header: [!1, 1, 2, 3, 4, 5, 6]
                }, "blockquote", "code-block"],
                [{
                    list: "ordered"
                }, {
                    list: "bullet"
                }, {
                    indent: "-1"
                }, {
                    indent: "+1"
                }],
                ["direction", {
                    align: []
                }],
                ["link", "image", "video"],
                ["clean"],
            ],
        },
    });

    var quillPersonalityTraits = new Quill("#personality-traits", {
        theme: "snow",
        modules: {
            toolbar: [
                [{
                    font: []
                }, {
                    size: []
                }],
                ["bold", "italic", "underline", "strike"],
                [{
                    color: []
                }, {
                    background: []
                }],
                [{
                    script: "super"
                }, {
                    script: "sub"
                }],
                [{
                    header: [!1, 1, 2, 3, 4, 5, 6]
                }, "blockquote", "code-block"],
                [{
                    list: "ordered"
                }, {
                    list: "bullet"
                }, {
                    indent: "-1"
                }, {
                    indent: "+1"
                }],
                ["direction", {
                    align: []
                }],
                ["link", "image", "video"],
                ["clean"],
            ],
        },
    });

    var quillRequiredSkills = new Quill("#required-skills", {
        theme: "snow",
        modules: {
            toolbar: [
                [{
                    font: []
                }, {
                    size: []
                }],
                ["bold", "italic", "underline", "strike"],
                [{
                    color: []
                }, {
                    background: []
                }],
                [{
                    script: "super"
                }, {
                    script: "sub"
                }],
                [{
                    header: [!1, 1, 2, 3, 4, 5, 6]
                }, "blockquote", "code-block"],
                [{
                    list: "ordered"
                }, {
                    list: "bullet"
                }, {
                    indent: "-1"
                }, {
                    indent: "+1"
                }],
                ["direction", {
                    align: []
                }],
                ["link", "image", "video"],
                ["clean"],
            ],
        },
    });

    var quillJobDeskEdit = new Quill("#edit-job-desc", {
        theme: "snow",
        modules: {
            toolbar: [
                [{
                    font: []
                }, {
                    size: []
                }],
                ["bold", "italic", "underline", "strike"],
                [{
                    color: []
                }, {
                    background: []
                }],
                [{
                    script: "super"
                }, {
                    script: "sub"
                }],
                [{
                    header: [!1, 1, 2, 3, 4, 5, 6]
                }, "blockquote", "code-block"],
                [{
                    list: "ordered"
                }, {
                    list: "bullet"
                }, {
                    indent: "-1"
                }, {
                    indent: "+1"
                }],
                ["direction", {
                    align: []
                }],
                ["link", "image", "video"],
                ["clean"],
            ],
        },
    });

    var quillPersonalityTraitsEdit = new Quill("#edit-personality-traits", {
        theme: "snow",
        modules: {
            toolbar: [
                [{
                    font: []
                }, {
                    size: []
                }],
                ["bold", "italic", "underline", "strike"],
                [{
                    color: []
                }, {
                    background: []
                }],
                [{
                    script: "super"
                }, {
                    script: "sub"
                }],
                [{
                    header: [!1, 1, 2, 3, 4, 5, 6]
                }, "blockquote", "code-block"],
                [{
                    list: "ordered"
                }, {
                    list: "bullet"
                }, {
                    indent: "-1"
                }, {
                    indent: "+1"
                }],
                ["direction", {
                    align: []
                }],
                ["link", "image", "video"],
                ["clean"],
            ],
        },
    });

    var quillRequiredSkillsEdit = new Quill("#edit-required-skills", {
        theme: "snow",
        modules: {
            toolbar: [
                [{
                    font: []
                }, {
                    size: []
                }],
                ["bold", "italic", "underline", "strike"],
                [{
                    color: []
                }, {
                    background: []
                }],
                [{
                    script: "super"
                }, {
                    script: "sub"
                }],
                [{
                    header: [!1, 1, 2, 3, 4, 5, 6]
                }, "blockquote", "code-block"],
                [{
                    list: "ordered"
                }, {
                    list: "bullet"
                }, {
                    indent: "-1"
                }, {
                    indent: "+1"
                }],
                ["direction", {
                    align: []
                }],
                ["link", "image", "video"],
                ["clean"],
            ],
        },
    });

    var quillJobDeskDetail = new Quill("#detail-job-desc", {
        theme: "snow",
        readOnly: true, // Menambahkan properti readOnly
        modules: {
            toolbar: [
                [{
                    font: []
                }, {
                    size: []
                }],
                ["bold", "italic", "underline", "strike"],
                [{
                    color: []
                }, {
                    background: []
                }],
                [{
                    script: "super"
                }, {
                    script: "sub"
                }],
                [{
                    header: [!1, 1, 2, 3, 4, 5, 6]
                }, "blockquote", "code-block"],
                [{
                    list: "ordered"
                }, {
                    list: "bullet"
                }, {
                    indent: "-1"
                }, {
                    indent: "+1"
                }],
                ["direction", {
                    align: []
                }],
                ["link", "image", "video"],
                ["clean"],
            ],
        },
    });

    var quillPersonalityTraitsDetail = new Quill("#detail-personality-traits", {
        theme: "snow",
        readOnly: true, // Menambahkan properti readOnly
        modules: {
            toolbar: [
                [{
                    font: []
                }, {
                    size: []
                }],
                ["bold", "italic", "underline", "strike"],
                [{
                    color: []
                }, {
                    background: []
                }],
                [{
                    script: "super"
                }, {
                    script: "sub"
                }],
                [{
                    header: [!1, 1, 2, 3, 4, 5, 6]
                }, "blockquote", "code-block"],
                [{
                    list: "ordered"
                }, {
                    list: "bullet"
                }, {
                    indent: "-1"
                }, {
                    indent: "+1"
                }],
                ["direction", {
                    align: []
                }],
                ["link", "image", "video"],
                ["clean"],
            ],
        },
    });

    var quillRequiredSkillsDetail = new Quill("#detail-required-skills", {
        theme: "snow",
        readOnly: true, // Menambahkan properti readOnly
        modules: {
            toolbar: [
                [{
                    font: []
                }, {
                    size: []
                }],
                ["bold", "italic", "underline", "strike"],
                [{
                    color: []
                }, {
                    background: []
                }],
                [{
                    script: "super"
                }, {
                    script: "sub"
                }],
                [{
                    header: [!1, 1, 2, 3, 4, 5, 6]
                }, "blockquote", "code-block"],
                [{
                    list: "ordered"
                }, {
                    list: "bullet"
                }, {
                    indent: "-1"
                }, {
                    indent: "+1"
                }],
                ["direction", {
                    align: []
                }],
                ["link", "image", "video"],
                ["clean"],
            ],
        },
    });
</script>
