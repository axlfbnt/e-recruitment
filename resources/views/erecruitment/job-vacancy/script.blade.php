<script>
    $(document).ready(function() {
        // Setup CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Inisialisasi Datatable
        $('#jobvacancy-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('job-vacancy') }}",
                type: 'GET'
            },
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'id_jobvacancy'
                },
                {
                    data: 'position_name'
                },
                {
                    data: 'department'
                },
                {
                    data: 'last_education'
                },
                {
                    data: 'major'
                },
                {
                    data: 'range_ipk'
                },
                {
                    data: 'close_date'
                },
                {
                    data: 'vacancy_status',
                    render: function(data, type, row) {
                        var labelClass = '';
                        if (data === 'Public') {
                            labelClass = 'label-open';
                        } else if (data === 'Private') {
                            labelClass = 'label-close';
                        }
                        return '<span class="' + labelClass + '">' + data +
                            '</span>';
                    }
                },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        var labelClass = '';
                        if (data === 'Open') {
                            labelClass = 'label-open';
                        } else if (data === 'Close' || data === 'Closed') {
                            labelClass = 'label-close';
                        }
                        return '<span class="' + labelClass + '">' + data +
                            '</span>';
                    }
                },
                {
                    data: 'created_at',
                    visible: false
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                ['10', 'desc']
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

        $('#addjobvacancy-modal').on('shown.bs.modal', function() {
            $('#position').select2({
                dropdownParent: $('#addjobvacancy-modal')
            });
        });

        $.ajax({
            url: "{{ url('get-positions-mpp') }}",
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Kosongkan elemen select
                $('#position').empty();
                $('#position').append(
                    '<option value="" disabled selected>Select position</option>');

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
            url: "{{ url('get-flowrecruitment') }}",
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Kosongkan elemen select
                $('#flow-recruitment').empty();
                $('#flow-recruitment').append(
                    '<option value="" disabled selected>Select flow recruitment</option>');

                // Loop melalui data yang diterima dan tambahkan ke elemen select
                $.each(data, function(key, value) {
                    $('#flow-recruitment').append('<option value="' + value
                        .id_flowrecruitment + '">' + value
                        .template_name + '</option>');
                });

                $('#edit-flow-recruitment').empty();
                $('#edit-flow-recruitment').append(
                    '<option value="" disabled selected>Select flow recruitment</option>');

                // Loop melalui data yang diterima dan tambahkan ke elemen select
                $.each(data, function(key, value) {
                    $('#edit-flow-recruitment').append('<option value="' + value
                        .id_flowrecruitment + '">' + value
                        .template_name + '</option>');
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching flow recruitment:', error);
            }
        });

        $('input[name="vacancy_status"]').on('change', function() {
            var selectedValue = $(this).val();

            if (selectedValue === 'Public') {
                $('#range-datepicker-container').show();
                $('#range-datepicker').prop('required', true); // Menjadikan field required
            } else {
                $('#range-datepicker-container').hide();
                $('#range-datepicker').prop('required',
                    false); // Menghilangkan required jika tidak dipilih
            }
        });

        $('input[name="edit_vacancy_status"]').on('change', function() {
            var selectedValue = $(this).val();
            if (selectedValue === 'Public') {
                $('#edit-range-datepicker-container').show();
                $('#edit-range-datepicker').prop('required', true); // Set as required
            } else {
                $('#edit-range-datepicker-container').hide();
                $('#edit-range-datepicker').prop('required', false); // Remove required
            }
        });
    });

    $('#position').on('change', function() {
        var positionId = $(this).val();

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
                $('#number-requests').val(response.total_man_power);
                $('#source-submission').val(response.source_submission);
                quillJobDesk.root.innerHTML = response.job_desc || '';
                quillRequiredSkills.root.innerHTML = response.required_skills || '';
                $('#last-education').val(response.last_education);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching position details:', error);
            }
        });
    });

    // Validasi inputan IPK
    function validateIPK(input) {
        let value = parseFloat(input.value);

        if (value < 0 || value > 4 || isNaN(value)) {
            input.setCustomValidity("Invalid IPK");
            input.reportValidity();
        } else {
            input.setCustomValidity("");
        }

        if (!isNaN(value) && value >= 0 && value <= 4) {
            input.value = value.toFixed(1);
        }
    }

    // Collect data dari form Add Job Vacancy
    function collectDataAddJobVacancy() {
        var formData = new FormData();

        // Mengumpulkan data dari setiap inputan form
        formData.append('position', $('#position').val());
        formData.append('position_status', $('#position-status').val());
        formData.append('job_position', $('#job-position').val());
        formData.append('join_date', $('#join-date').val());
        formData.append('number_requests', $('#number-requests').val());
        formData.append('last_education', $('#last-education').val());
        formData.append('source_submission', $('#source-submission').val());
        formData.append('job_desc', quillJobDesk.root.innerHTML);
        formData.append('required_skills', quillRequiredSkills.root.innerHTML);
        formData.append('range_ipk', $('#range-ipk').val());
        formData.append('open_date', $('#open-date').val());
        formData.append('close_date', $('#close-date').val());
        formData.append('flow_recruitment', $('#flow-recruitment').val());

        // Mendapatkan nilai range date picker
        var rangePublication = $('input[name="range_publication_add"]').val();
        if (rangePublication) {
            var dates = rangePublication.split(" to ");
            formData.append('open_publication_date', dates[0]);
            formData.append('close_publication_date', dates[1]);
        }

        return formData;
    }

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

    // Fungsi proses Simpan data Job Vacancy
    function simpan() {
        var formData = collectDataAddJobVacancy();
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: "{{ url('job-vacancy') }}",
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
                $('#jobvacancy-datatable').DataTable().ajax.reload();

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

    $(document).on('click', '.button-edit', function() {
        var id = $(this).data('id');
        console.log(id);

        $.ajax({
            url: "{{ url('job-vacancy') }}" + "/" + id +
                '/edit',
            type: 'GET',
            success: function(response) {
                $('#edit-position').val(response.result.position_name);
                $('#edit-position-status').val(response.result.position_status);
                $('#edit-job-position').val(response.result.job_position);
                $('#edit-join-date').val(response.result.join_date);
                $('#edit-number-requests').val(response.result.number_requests);
                $('#edit-last-education').val(response.result.last_education);
                $('#edit-source-submission').val(response.result.source_submission);

                quillJobDeskEdit.root.innerHTML = response.result.job_desc || '';
                quillRequiredSkillsEdit.root.innerHTML = response.result.required_skills || '';

                $('#edit-range-ipk').val(response.result.range_ipk);
                $('#edit-open-date').val(response.result.open_date);
                $('#edit-close-date').val(response.result.close_date);
                $('#edit-flow-recruitment').val(response.result.flow_recruitment);

                $('input[name="edit_vacancy_status"][value="' + response.result.vacancy_status +
                    '"]').prop('checked', true);

                if (response.result.vacancy_status === 'Public' ||
                    (response.result.open_publication_date && response.result
                        .close_publication_date)) {
                    $('input[name="range_publication_edit"]').closest(
                        '#edit-range-datepicker-container').show();
                    $('input[name="range_publication_edit"]').val(
                        response.result.open_publication_date + ' to ' + response.result
                        .close_publication_date
                    );

                } else {
                    $('input[name="range_publication_edit"]').closest(
                        '#edit-range-datepicker-container').hide();
                    $('input[name="range_publication_edit"]').val('');
                }

                $('.button-update').data('id', id);
            },
            error: function(xhr) {
                console.log('An error occurred:', xhr.responseText);
                // Optionally show an error message to the user
                alert('Failed to load job vacancy data. Please try again.');
            }
        });
    });

    function collectDataEditJobVacancy() {
        var formData = new FormData();

        formData.append('position', $('#edit-position').val());
        formData.append('position_status', $('#edit-position-status').val());
        formData.append('job_position', $('#edit-job-position').val());
        formData.append('join_date', $('#edit-join-date').val());
        formData.append('number_requests', $('#edit-number-requests').val());
        formData.append('last_education', $('#edit-last-education').val());
        formData.append('source_submission', $('#edit-source-submission').val());

        formData.append('job_desc', quillJobDeskEdit.root.innerHTML);
        formData.append('required_skills', quillRequiredSkillsEdit.root.innerHTML);

        formData.append('range_ipk', $('#edit-range-ipk').val());
        formData.append('open_date', $('#edit-open-date').val());
        formData.append('close_date', $('#edit-close-date').val());
        formData.append('flow_recruitment', $('#edit-flow-recruitment').val());

        var vacancyStatus = $('input[name="edit_vacancy_status"]:checked').val();
        formData.append('vacancy_status', vacancyStatus);

        var rangePublication = $('input[name="range_publication_edit"]').val();
        if (rangePublication) {
            var dates = rangePublication.split(" to ");
            formData.append('open_publication_date', dates[0]);
            formData.append('close_publication_date', dates[1]);
        }

        formData.append('_method', 'PUT');

        return formData;
    }

    $('.button-update').on('click', function(event) {
        event.preventDefault();

        var id = $(this).data('id');
        update(id);
    });

    function update(id) {
        var formData = collectDataEditJobVacancy();
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: "{{ url('job-vacancy') }}" + "/" + id,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            success: function(response) {
                $('#editjobvacancy-modal').modal('hide');
                $('#alert-update-success').removeClass('d-none');
                $('#jobvacancy-datatable').DataTable().ajax.reload();

                setTimeout(function() {
                    $('#alert-update-success').addClass(
                        'd-none');
                }, 5000);
            },
            error: function(xhr) {
                alert('An error occurred: ' + xhr.responseText);
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
            url: "{{ url('job-vacancy') }}" + "/" + id,
            type: 'DELETE',
            data: {
                "_token": $('meta[name="csrf-token"]').attr(
                    'content')
            },
            success: function(response) {
                console.log(response.message);
                $('#deletejobvacancy-modal').modal('hide');
                $('#alert-delete-success').removeClass('d-none');
                $('#jobvacancy-datatable').DataTable().ajax.reload();

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

    function resetJobVacancyModal() {
        // Reset semua input field ke keadaan default
        $('#addjobvacancy-modal').find('input, select, textarea').val('');
        $('#addjobvacancy-modal').find('select').prop('selectedIndex', 0);
        $('#addjobvacancy-modal').find('.needs-validation').removeClass('was-validated');
        $('#addjobvacancy-modal').find('.ql-editor').html(''); // Kosongkan editor Quill
        $('#addjobvacancy-modal').find('input[type="radio"]').prop('checked', false);

        document.querySelectorAll('.nav-link').forEach(function(tab) {
            tab.classList.remove('active');
        });
        document.querySelectorAll('.tab-pane').forEach(function(content) {
            content.classList.remove('active', 'show');
        });

        document.querySelector('a[href="#required-position"]').classList.add('active');
        document.getElementById('required-position').classList.add('active', 'show');
        document.querySelector('.bar').style.width = '50%';
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
</script>
