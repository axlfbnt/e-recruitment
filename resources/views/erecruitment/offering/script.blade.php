<script>
    $(document).ready(function() {
        // Setup CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Inisialisasi Datatable
        $('#offering-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('offering') }}",
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
                    data: 'total_applicants',
                    render: function(data, type, row) {
                        return data == 0 ? 'No applicant' : data; 
                    }
                },
                {
                    data: 'vacancy_status',
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
    });
</script>
