<script>
    $(document).ready(function() {
        // Setup CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Inisialisasi Datatable
        $('#flowrecruitment-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('flow-recruitment') }}",
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
                    data: 'recruitment_stage',
                    render: function(data, type, row) {
                        if (data) {
                            var stages = data.split(',');
                            var numberedStages = stages.map(function(stage, index) {
                                return (index + 1) + ". " + stage;
                            });
                            return numberedStages.join('<br>');
                        }
                        return data;
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

    // Fungsi untuk menambah input recruitment stage
    $(document).on('click', '.add-recruitment-stage-btn', function() {
        var newInputRow = `
            <div class="row mb-2 recruitment-stage-row">
                <div class="col-md-8">
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
                    <button type="button" class="btn btn-danger remove-recruitment-stage-btn"><i class="ri-delete-bin-5-line"></i></button>
                </div>
            </div>`;
        $('#recruitment-stage-container').append(newInputRow);
    });

    // Fungsi untuk menghapus input input recruitment stage
    $(document).on('click', '.remove-recruitment-stage-btn', function() {
        $(this).closest('.recruitment-stage-row').remove();
    });

    // Collect data dari form Add Flow Recruitment
    function collectDataFlowRecruitment() {
        var formData = new FormData();

        // Ambil nilai dari input teks biasa
        formData.append('template_name', $('#template-name').val());

        // Collect and combine all recruitment stage into a single string
        var recruitment_stage = [];
        $('#recruitment-stage-container select[id="recruitment-stage"]').each(function() {
            recruitment_stage.push($(this).val());
        });
        formData.append('recruitment_stage', recruitment_stage.join(','));

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

    // Fungsi proses Simpan data Flow Recruitment
    function simpan() {
        var formData = collectDataFlowRecruitment();
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: "{{ url('flow-recruitment') }}",
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
                $('#flowrecruitment-datatable').DataTable().ajax.reload();

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
        console.log(id);

        $.ajax({
            url: "{{ url('flow-recruitment') }}" + "/" + id + '/edit',
            type: 'GET',
            success: function(response) {

                // Fill in the template name
                $('#edit-template-name').val(response.result.template_name);

                // Remove all recruitment stage rows except the first one
                $('#edit-recruitment-stage-container').find('.row.mb-2').not(':first').remove();

                if (response.result.recruitment_stage) {
                    var stagesArray = response.result.recruitment_stage.split(',');

                    // Set the first recruitment stage
                    $('#edit-recruitment-stage-container').find('select').first().val(stagesArray[0]
                        .trim());

                    // Add additional recruitment stages
                    for (var i = 1; i < stagesArray.length; i++) {
                        var stageSelect = `
                    <div class="row mb-2 edit-recruitment-stage-row">
                        <div class="col-md-8">
                            <select class="form-select" name="edit-recruitment-stage[]" required>
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
                            <button type="button" class="btn btn-danger remove-recruitment-stage-btn"><i class="ri-delete-bin-line"></i></button>
                        </div>
                    </div>`;

                        $('#edit-recruitment-stage-container').append(stageSelect);

                        // Set the value of the newly added select field
                        $('#edit-recruitment-stage-container').find('select').last().val(
                            stagesArray[i].trim());
                    }
                }
                $('.button-update').data('id', id);
            }
        });
    });

    function collectDataEditFlowRecruitment() {
        var formData = new FormData();

        formData.append('template_name', $('#edit-template-name').val());

        var recruitment_stage = [];
        $('#edit-recruitment-stage-container select').each(function() {
            recruitment_stage.push($(this).val());
        });

        formData.append('recruitment_stage', recruitment_stage.join(','));

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
        var formData = collectDataEditFlowRecruitment();
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: "{{ url('flow-recruitment') }}" + "/" + id,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            success: function(response) {
                // Menutup modal setelah sukses
                $('#closeEditModalBtn').click();

                // Menampilkan alert
                $('#alert-update-success').removeClass('d-none');

                // Refresh datatable
                $('#flowrecruitment-datatable').DataTable().ajax.reload();

                setTimeout(function() {
                    $('#alert-update-success').addClass('d-none');
                }, 5000);
            },
            error: function(xhr) {
                alert('Terjadi kesalahan: ' + xhr.responseText);
            }
        });
    }

    // Function to add a new recruitment stage input
    $(document).on('click', '.add-recruitment-stage-btn', function() {
        var newInputRow = `
        <div class="row mb-2 edit-recruitment-stage-row">
            <div class="col-md-8">
                <select class="form-select" name="edit-recruitment-stage[]" required>
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
                <button type="button" class="btn btn-danger remove-recruitment-stage-btn"><i class="ri-delete-bin-line"></i></button>
            </div>
        </div>`;
        $('#edit-recruitment-stage-container').append(newInputRow);
    });

    // Function to remove recruitment stage row
    $(document).on('click', '.remove-recruitment-stage-btn', function() {
        $(this).closest('.row.mb-2').remove();
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
            url: "{{ url('flow-recruitment') }}" + "/" + id,
            type: 'DELETE',
            data: {
                "_token": $('meta[name="csrf-token"]').attr(
                    'content')
            },
            success: function(response) {
                console.log(response.message);
                $('#deleteflowrecruitment-modal').modal('hide');
                $('#alert-delete-success').removeClass('d-none');
                $('#flowrecruitment-datatable').DataTable().ajax.reload();

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
    function resetAddFlowRecruitment() {
        $('#template-name').val('');

        $('#recruitment-stage-container .row').slice(1).remove();
        $('#recruitment-stage-container .row:first-child input').val('');

        $('#addflowrecruitment-modal').find('.needs-validation').removeClass('was-validated');
    }
</script>
