<script>
    $(document).ready(function() {
        // Setup CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Inisialisasi Datatable
        $('#templateforma4-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('template-forma4') }}",
                type: 'GET'
            },
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'template_name'
                },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        var labelClass = '';
                        if (data === 'Active') {
                            labelClass = 'label-open';
                        } else if (data === 'Inactive') { // Perbaikan di sini
                            labelClass = 'label-close';
                        }
                        return '<span class="' + labelClass + '">' + data + '</span>';
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
                ['3', 'desc']
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
    });

    // Fungsi untuk menambah input evaluation row
    $(document).on('click', '.add-evaluation-btn', function() {
        var newInputRow = `
        <div class="row mb-2">
            <div class="col-md-4">
                <input class="form-control" name="dimension" type="text" required
                    placeholder="Dimension">
                <div class="invalid-feedback">
                    Please fill in the dimension.
                </div>
            </div>
            <div class="col-md-4">
                <input class="form-control" name="key-explanation" type="text" required
                    placeholder="Key Explanation">
                <div class="invalid-feedback">
                    Please fill in the key explanation.
                </div>
            </div>
            <div class="col-md-3">
                <input class="form-control" name="total-aspects" type="number" required
                    placeholder="Total Aspects">
                <div class="invalid-feedback">
                    Please fill in the total aspects score.
                </div>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-evaluation-btn"><i class="ri-delete-bin-5-line"></i></button>
            </div>
        </div>`;
        $('#evaluation-container').append(newInputRow);
    });

    // Fungsi untuk menghapus baris input evaluation
    $(document).on('click', '.remove-evaluation-btn', function() {
        $(this).closest('.evaluation-row').remove();
    });

    // Fungsi untuk mengumpulkan data dari form Add Form A4
    function collectDataFormA4() {
        var formData = new FormData();

        formData.append('template_name', $('#template-name').val());
        var dimension = [];
        var key_explanation = [];
        var total_aspects_score = [];

        $('#evaluation-container .row').each(function() {
            dimension.push($(this).find('input[name="dimension"]').val());
            key_explanation.push($(this).find('input[name="key-explanation"]').val());
            total_aspects_score.push($(this).find('input[name="total-aspects"]').val());
        });

        // Tambahkan data ke FormData
        formData.append('dimension', dimension.join(','));
        formData.append('key_explanation', key_explanation.join(','));
        formData.append('total_aspects_score', total_aspects_score.join(','));

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

    // Fungsi proses Simpan data Form A4
    function simpan() {
        var formData = collectDataFormA4();
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: "{{ url('template-forma4') }}",
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
                $('#templateforma4-datatable').DataTable().ajax.reload();

                setTimeout(function() {
                    $('#alert-save-success').addClass('d-none');
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

        $.ajax({
            url: "{{ url('template-forma4') }}" + "/" + id + '/edit',
            type: 'GET',
            success: function(response) {
                // Isi field template name
                $('#edit-template-name').val(response.result.template_name);

                // Isi data untuk baris pertama (yang ada tombol Add)
                if (response.result.evaluations && response.result.evaluations.length > 0) {
                    var firstEvaluation = response.result.evaluations[0];

                    // Pastikan baris pertama memiliki class .edit-evaluation-row
                    var firstRow = $('#edit-evaluation-container').find('.edit-evaluation-row')
                        .first();

                    // Set nilai di baris pertama
                    firstRow.find('input[name="dimension[]"]').val(firstEvaluation.dimension);
                    firstRow.find('input[name="key-explanation[]"]').val(firstEvaluation
                        .key_explanation);
                    firstRow.find('input[name="total-aspects[]"]').val(firstEvaluation
                        .total_aspects_score);

                    // Hapus data evaluasi pertama dari array karena sudah diisi
                    response.result.evaluations.shift();
                }

                // Hapus semua baris lain yang ada di dalam container
                $('#edit-evaluation-container').find('.edit-evaluation-row').not(':first').remove();

                // Tambahkan baris dinamis untuk data evaluasi lainnya
                if (response.result.evaluations) {
                    var evaluationsArray = response.result.evaluations;

                    // Loop untuk data evaluasi yang tersisa
                    for (var i = 0; i < evaluationsArray.length; i++) {
                        var evaluation = evaluationsArray[i];

                        var evaluationRow = `
                    <div class="row mb-2 edit-evaluation-row">
                        <div class="col-md-4">
                            <input class="form-control" name="dimension[]" type="text" required placeholder="Dimension" value="${evaluation.dimension}">
                            <div class="invalid-feedback">
                                Please fill in the dimension.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" name="key-explanation[]" type="text" required placeholder="Key Explanation" value="${evaluation.key_explanation}">
                            <div class="invalid-feedback">
                                Please fill in the key explanation.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <input class="form-control" name="total-aspects[]" type="number" required placeholder="Total Aspects" value="${evaluation.total_aspects_score}">
                            <div class="invalid-feedback">
                                Please fill in the total aspects score.
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-evaluation-btn"><i class="ri-delete-bin-line"></i></button>
                        </div>
                    </div>`;

                        $('#edit-evaluation-container').append(evaluationRow);
                    }
                }

                // Set data id di tombol update
                $('.button-update').data('id', id);
            },
            error: function(xhr) {
                console.log('Terjadi kesalahan saat mengambil data:', xhr.responseText);
                alert('Terjadi kesalahan saat mengambil data: ' + xhr.responseText);
            }
        });
    });

    // Fungsi untuk mengumpulkan data dari Form Edit A4
    function collectDataFormA4Edit() {
        var formData = new FormData();

        // Ambil nilai dari template name
        formData.append('template_name', $('#edit-template-name').val());

        var dimension = [];
        var key_explanation = [];
        var total_aspects_score = [];

        // Ambil semua row termasuk yang pertama dengan class .edit-evaluation-row
        $('#edit-evaluation-container .edit-evaluation-row').each(function() {
            dimension.push($(this).find('input[name="dimension[]"]').val());
            key_explanation.push($(this).find('input[name="key-explanation[]"]').val());
            total_aspects_score.push($(this).find('input[name="total-aspects[]"]').val());
        });

        // Tambahkan data array ke formData
        formData.append('dimension', dimension.join(','));
        formData.append('key_explanation', key_explanation.join(','));
        formData.append('total_aspects_score', total_aspects_score.join(','));

        // Tambahkan metode PUT untuk update
        formData.append('_method', 'PUT');

        return formData;
    }

    // Proses Button Update
    $('.button-update').on('click', function(event) {
        event.preventDefault();

        var id = $(this).data('id');
        update(id);
    });

    // Fungsi proses Update data Employee Submission Form A4
    function update(id) {
        var formData = collectDataFormA4Edit();
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: "{{ url('template-forma4') }}" + "/" + id,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            success: function(response) {
                $('#closeModalEditBtn').click();

                $('#alert-update-success').removeClass('d-none');

                $('#templateforma4-datatable').DataTable().ajax.reload();

                setTimeout(function() {
                    $('#alert-update-success').addClass('d-none');
                }, 5000);
            },
            error: function(xhr) {
                alert('Terjadi kesalahan: ' + xhr.responseText);
            }
        });
    }

    // Fungsi untuk menambah baris evaluasi di modal edit
    $(document).on('click', '.add-evaluation-btn-edit', function() {
        var newInputRow = `
            <div class="row mb-2 edit-evaluation-row">
                <div class="col-md-4">
                    <input class="form-control" name="dimension[]" type="text" required placeholder="Dimension">
                    <div class="invalid-feedback">
                        Please fill in the dimension.
                    </div>
                </div>
                <div class="col-md-4">
                    <input class="form-control" name="key-explanation[]" type="text" required placeholder="Key Explanation">
                    <div class="invalid-feedback">
                        Please fill in the key explanation.
                    </div>
                </div>
                <div class="col-md-3">
                    <input class="form-control" name="total-aspects[]" type="number" required placeholder="Total Aspects">
                    <div class="invalid-feedback">
                        Please fill in the total aspects score.
                    </div>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-evaluation-btn"><i class="ri-delete-bin-line"></i></button>
                </div>
            </div>`;
        $('#edit-evaluation-container').append(newInputRow);
    });

    // Fungsi untuk menghapus baris evaluasi
    $(document).on('click', '.remove-evaluation-btn', function() {
        $(this).closest('.row.mb-2').remove();
    });

    // Update status Template Form A4
    $(document).on('click', '.btn-toggle-status', function() {
        var id = $(this).data('id');
        var currentStatus = $(this).data('status');
        var newStatus = currentStatus === 'Active' ? 'Inactive' : 'Active';

        $.ajax({
            url: "{{ url('template-forma4/update-status') }}" + "/" + id,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: newStatus
            },
            success: function(response) {
                if (response.success) {
                    if (newStatus === 'Active') {
                        $(this).removeClass('btn-success').addClass('btn-success').html(
                            '<i class="ri-toggle-fill"></i>');
                        $('#alert-status-success').removeClass('d-none');
                        setTimeout(function() {
                            $('#alert-status-success').addClass('d-none');
                        }, 5000);
                    }
                    $(this).data('status', newStatus);
                    $('#templateforma4-datatable').DataTable().ajax.reload();
                }
            }.bind(this),
            error: function(xhr) {
                $('#alert-status-failed').removeClass('d-none');
                setTimeout(function() {
                    $('#alert-status-failed').addClass('d-none');
                }, 5000);
            }
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
            url: "{{ url('template-forma4') }}" + "/" + id,
            type: 'DELETE',
            data: {
                "_token": $('meta[name="csrf-token"]').attr(
                    'content')
            },
            success: function(response) {
                console.log(response.message);
                $('#deletetemplateforma4-modal').modal('hide');
                $('#alert-delete-success').removeClass('d-none');
                $('#templateforma4-datatable').DataTable().ajax.reload();

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

    // Reset Modal Add Flow Recruitment
    function resetAddFormA4() {
        $('#template-name').val('');

        $('#evaluation-container .row').slice(1).remove();
        $('#evaluation-container .row:first-child input').val('');

        $('#addtemplateformA4-modal form').removeClass('was-validated');
        $('#addtemplateformA4-modal .form-control').removeClass('is-valid is-invalid');
        $('#addtemplateformA4-modal .invalid-feedback').hide();
    }
</script>
