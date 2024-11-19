<script>
    $(document).ready(function() {
        // Setup CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // ID lowongan yang dikirim dari controller
        var idJobVacancy = "{{ $id_jobvacancy }}";

        // Inisialisasi DataTable
        $('#invitationApplicants-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "/invitation-applicant/" + idJobVacancy + "/applicants-data",
                type: 'GET'
            },
            columns: [
                {
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
                    data: 'invite_status',
                    render: function(data, type, row) {
                        var labelClass = '';
                        if (data === 'Confirmed') {
                            labelClass = 'label-open';
                        } else if (data === 'Rejected' || data === 'Not yet confirmed') {
                            labelClass = 'label-close';
                        }
                        return '<span class="' + labelClass + '">' + data +
                            '</span>';
                    }
                },
                {
                    data: 'application_date',
                    visible: false
                },
                // {
                //     data: 'action',
                //     orderable: false,
                //     searchable: false
                // }
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
            columnDefs: [
                {
                    targets: "_all",
                    render: function(data, type, row) {
                        return '<div style="white-space: normal;">' + data + '</div>';
                    }
                }
            ],
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
            }
        });
    });
</script>
