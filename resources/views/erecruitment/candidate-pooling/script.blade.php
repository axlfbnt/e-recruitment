<script>
    $(document).ready(function() {
        // Setup CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Inisialisasi Datatable
        $('#candidatepooling-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('candidate-pooling') }}",
                type: 'GET'
            },
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'vacancy'
                },
                {
                    data: 'full_name'
                },
                {
                    data: 'gender',
                },
                {
                    data: 'birth_date',
                },
                {
                    data: 'phone_number',
                },
                {
                    data: 'email',
                },
                {
                    data: 'domicile',
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
                ['8', 'desc']
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

        // Load Data Company
        $.ajax({
            url: "{{ url('get-companies') }}",
            type: 'GET',
            success: function(companies) {
                let companyOptions = '<option value="" disabled selected>Select company</option>';
                $.each(companies, function(index, company) {
                    companyOptions += '<option value="' + company.company_name + '">' +
                        company.company_name + '</option>';
                });
                $('#company').html(companyOptions);
                $('#edit-company').html(companyOptions);
            }
        });

        $('#company').on('change', function() {
            var companyId = $(this).val();
            $.ajax({
                url: "{{ url('get-vacancy') }}",
                type: 'GET',
                data: {
                    company_id: companyId
                },
                success: function(data) {
                    let vacancyOptions =
                        '<option value="" disabled selected>Select vacancy</option>';
                    $.each(data.vacancies, function(index, vacancy) {
                        vacancyOptions += '<option value="' + vacancy
                            .id_jobvacancy + '">' + vacancy.position + '</option>';
                    });
                    $('#vacancy').html(vacancyOptions);
                }
            });
        });

        $('#vacancy').on('change', function() {
            var vacancyId = $(this).val();
            $('#vacancy-code').val(vacancyId);
        });
    });

    function collectDataInviteCandidate() {
        var formData = new FormData();

        formData.append('company', $('#company').val());
        formData.append('vacancy', $('#vacancy').val());
        formData.append('vacancy_code', $('#vacancy-code').val());
        formData.append('recruitment_stage', $('#edit-recruitment-stage').val());

        formData.append('_method', 'PUT');

        return formData;
    }

    $(document).on('click', '.button-invite', function() {
        var id = $(this).data('id');
        console.log(id);

        $('.button-invited').data('id', id);
    });

    $(document).on('click', '.button-invited', function() {
        var id = $(this).data('id');
        var formData = collectDataInviteCandidate();

        $.ajax({
            url: "{{ url('candidate-pooling') }}" + "/" + id,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#candidatepooling-datatable').DataTable().ajax.reload();
                $('#candidatepooling-modal').modal('hide');
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseText);
            }
        });
    });
</script>
