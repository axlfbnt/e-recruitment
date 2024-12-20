<script>
    $(document).ready(function() {
        // Setup CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Inisialisasi Datatable
        $('#jobdesc-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('job-description') }}",
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
                    data: 'department'
                },
                {
                    data: 'position'
                },
                {
                    data: 'job_desc',
                    render: function(data, type, row) {
                        var doc = new DOMParser().parseFromString(data, 'text/html');
                        return doc.body.textContent || "";
                    }
                },
                {
                    data: 'updated_by_name',
                    render: function(data, type, row) {
                        return data ? data :
                            'N/A';
                    }
                },
                {
                    data: 'created_at',
                    visible: false
                },
                {
                    data: 'updated_at',
                    render: function(data) {
                        if (!data) return ''; 

                        let date = new Date(data);

                        if (isNaN(date.getTime())) return '';

                        let day = date.getUTCDate().toString().padStart(2, '0');
                        let month = date.toLocaleString('en-US', {
                            month: 'short'
                        });
                        let year = date.getUTCFullYear();
                        let hours = date.getUTCHours().toString().padStart(2, '0');
                        let minutes = date.getUTCMinutes().toString().padStart(2, '0');
                        let seconds = date.getUTCSeconds().toString().padStart(2, '0');

                        return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
                    }
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                ['6', 'desc']
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

        $('#addjobdesc-modal').on('shown.bs.modal', function() {
            $('#department').select2({
                dropdownParent: $('#addjobdesc-modal')
            });
            $('#position').select2({
                dropdownParent: $('#addjobdesc-modal')
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

    // Collect data dari form Add Job Desc
    function collectDataAddJobDesc() {
        var formData = new FormData();

        formData.append('company', $('#company').val());
        formData.append('department', $('#department').val());
        formData.append('division', $('#division').val());
        formData.append('position', $('#position').val());

        // Cek jika posisi "Others" dipilih, tambahkan new position
        if ($('#position').val() === 'Others') {
            formData.append('new_position', $('#new-position').val());
        }

        formData.append('job_desc', quillJobDesk.root.innerHTML); // Mengambil isi dari editor job_desc

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

    // Fungsi proses Simpan data Job Description
    function simpan() {
        var formData = collectDataAddJobDesc();
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: "{{ url('job-description') }}",
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
                $('#jobdesc-datatable').DataTable().ajax.reload();

                setTimeout(function() {
                    $('#alert-save-success').addClass('d-none');
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
            url: "{{ url('job-description') }}" + "/" + id + '/edit',
            type: 'GET',
            success: function(response) {
                $('#edit-company').val(response.result.company);
                $('#edit-department').val(response.result.department);
                $('#edit-division').val(response.result.division);
                $('#edit-position').val(response.result.position);

                quillJobDeskEdit.root.innerHTML = response.result.job_desc || '';

                $('.button-update').data('id', id);
            },
            error: function(xhr) {
                console.log('An error occurred:', xhr.responseText);
                // Optionally show an error message to the user
            }
        });
    });

    function collectDataEditJobDesc() {
        var formData = new FormData();

        // Append form fields to FormData
        formData.append('company', $('#edit-company').val());
        formData.append('department', $('#edit-department').val());
        formData.append('division', $('#edit-division').val());
        formData.append('position', $('#edit-position').val());

        formData.append('job_desc', quillJobDeskEdit.root.innerHTML);
        formData.append('_method', 'PUT');

        return formData;
    }

    $('.button-update').on('click', function(event) {
        event.preventDefault();

        var id = $(this).data('id');
        update(id);
    });

    function update(id) {
        var formData = collectDataEditJobDesc();
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: "{{ url('job-description') }}" + "/" + id,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            success: function(response) {
                $('#editjobdesc-modal').modal('hide');
                $('#alert-update-success').removeClass('d-none');
                $('#jobdesc-datatable').DataTable().ajax.reload();

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
            url: "{{ url('job-description') }}" + "/" + id,
            type: 'DELETE',
            data: {
                "_token": $('meta[name="csrf-token"]').attr(
                    'content')
            },
            success: function(response) {
                console.log(response.message);
                $('#deletejobdesc-modal').modal('hide');
                $('#alert-delete-success').removeClass('d-none');
                $('#jobdesc-datatable').DataTable().ajax.reload();

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

    function resetJobDescModal() {
        $('#addjobdesc-modal').find('input, select, textarea').val('');
        $('#addjobdesc-modal').find('select').prop('selectedIndex', 0);
        $('#addjobdesc-modal').find('.needs-validation').removeClass('was-validated');
        $('#addjobdesc-modal').find('.ql-editor').html(''); // Kosongkan editor Quill
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
</script>
