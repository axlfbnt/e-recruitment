<script>
    $(document).ready(function() {
        // Load Data Company untuk modal invite vacancy
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
            }
        });

        // Load Vacancy Options Berdasarkan Company yang Dipilih
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

        // Set Vacancy Code di Modal ketika Vacancy dipilih
        $('#vacancy').on('change', function() {
            var vacancyId = $(this).val();
            $('#vacancy-code').val(vacancyId);
        });

        document.querySelector('.button-invite-vacancy').addEventListener('click', function() {
            
            var modal = new bootstrap.Modal(document.getElementById('invitevacancy-modal'));
            modal.show();
        });

        // Checkbox pelamar
        $('.select-applicant').on('change', function() {
            const applicantId = $(this).val();
            const isChecked = $(this).is(':checked');
            console.log(isChecked ? 'Checked applicant ID:' + applicantId : 'Unchecked applicant ID:' +
                applicantId);
        });

        // Select All Checkbox
        $('#select-all-applicants').on('click', function() {
            const isChecked = $(this).prop('checked');
            $('.select-applicant').prop('checked', isChecked).trigger('change');
        });

        // Mendapatkan ID vacancy dari Blade
        let vacancyId = '{{ $jobVacancyId }}';

        // Handle aksi "Pass", "Reject", "Candidate Pooling"
        $('.button-pass').on('click', function() {
            handleBulkAction(vacancyId, 'Medical Check Up');
        });
        $('.button-candidate-pooling').on('click', function() {
            handleBulkAction(vacancyId, 'Candidate Pooling');
        });

        // Handle "Invite" dari modal
        $('.button-invite').on('click', function(event) {
            let form = $(this).closest('form')[0];
            if (form.checkValidity() === false) {
                event.stopPropagation();
            } else {
                let applicantId = $('#invitevacancy-modal').data('applicant-id'); // ID dari dropdown
                let selectedApplicants = getSelectedApplicants(); // ID dari checkbox
                if (applicantId && !selectedApplicants.includes(applicantId)) {
                    selectedApplicants.push(applicantId); // Tambahkan ID dari dropdown jika belum ada
                }

                let inviteVacancy = $('#vacancy').val();
                let inviteStage = $('#invite-stage').val();

                if (selectedApplicants.length > 0) {
                    console.log('Selected Applicants:', selectedApplicants); // Debugging log
                    $.ajax({
                        url: "{{ url('/offering/approvalAdministrative') }}" + "/" +
                            vacancyId,
                        type: 'PUT',
                        data: {
                            _token: '{{ csrf_token() }}',
                            status: 'Invited',
                            applicants: selectedApplicants, 
                            vacancy_id: vacancyId,
                            invite_vacancy: inviteVacancy,
                            invite_stage: inviteStage
                        },
                        success: function(response) {
                            location.reload();
                        },
                        error: function(xhr) {
                            alert('Error: ' + xhr.responseText);
                        }
                    });
                } else {
                    alert('Please select at least one applicant.');
                }
            }
            form.classList.add('was-validated');
        });

        // Fungsi untuk meng-handle aksi secara bulk
        function handleBulkAction(vacancyId, status, applicantIds = null) {
            let selectedApplicants = applicantIds || getSelectedApplicants();
            if (selectedApplicants.length > 0) {
                $.ajax({
                    url: "{{ url('/offering/approvalAdministrative') }}" + "/" + vacancyId,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: status,
                        applicants: selectedApplicants,
                        vacancy_id: vacancyId
                    },
                    success: function(response) {
                        alert(response.success);
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseText);
                    }
                });
            } else {
                alert('Please select at least one applicant.');
            }
        }

        // Fungsi untuk mengambil ID pelamar yang dipilih
        function getSelectedApplicants() {
            let selectedApplicants = [];
            $('.select-applicant:checked').each(function() {                                                                                                                                                                      
                selectedApplicants.push($(this).val()); // Ambil nilai (ID) dari checkbox yang dipilih
            });
            return selectedApplicants; // Mengembalikan array ID pelamar yang dipilih
        }

        // Fungsi Reset Modal Invite
        function resetInviteVacancyModal() {
            $('#invitevacancy-modal').find('input, textarea').val('');
            $('#invitevacancy-modal').find('select').each(function() {
                if (this.id === 'invite-stage') {
                    $(this).val($(this).find('option:first').val());
                } else {
                    $(this).val(null).trigger('change');
                }
            });
            $('#invitevacancy-modal').find('input, select, textarea').removeClass('is-valid is-invalid');
            $('#invitevacancy-modal').find('.needs-validation').removeClass('was-validated');
        }

        // Fungsi untuk memuat DataTable di modal
        function loadHistoryTable(applicantId) {
            if ($.fn.DataTable.isDataTable('#historydetail-datatable')) {
                $('#historydetail-datatable').DataTable().destroy();
            }

            $('#historydetail-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/administrative-selection/" + applicantId + "/history",
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
                        data: 'vacancy'
                    },
                    {
                        data: 'last_stage'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'application_date'
                    },
                    {
                        data: 'created_at',
                        visible: false
                    }
                ],
                order: [
                    ['6', 'desc']
                ],
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
        }

        // Event listener untuk membuka modal dan memuat data History
        $('body').on('click', '[id^=detailsMenu]', function() {
            var applicantId = $(this).attr('id').replace('detailsMenu', '');
            loadHistoryTable(applicantId);
            $('#historydetail-modal').modal('show');
        });

        // Event listener untuk dropdown menu lainnya
        $('body').on('click', '[id^=addToPoolMenu]', function() {
            var applicantId = $(this).attr('id').replace('addToPoolMenu', '');
            console.log('Add to Candidate Pool clicked for applicant ID:', applicantId);
            handleBulkAction(vacancyId, 'Candidate Pooling', [applicantId]);
        });

        $('body').on('click', '[id^=inviteMenu]', function() {
            var applicantId = $(this).data('applicant-id');
            $('#invitevacancy-modal').modal('show');
            $('#invitevacancy-modal').data('applicant-id', applicantId);
        });

        $('body').on('click', '[id^=passMenu]', function() {
            var applicantId = $(this).attr('id').replace('passMenu', '');
            console.log('Pass clicked for applicant ID:', applicantId);
            handleBulkAction(vacancyId, 'Offering', [applicantId]);
        });

        $('body').on('click', '[id^=failMenu]', function() {
            var applicantId = $(this).attr('id').replace('failMenu', '');
            console.log('Fail clicked for applicant ID:', applicantId);
            handleBulkAction(vacancyId, 'Failed Interview Test', [applicantId]);
        });
    });

    $('#importModal form').on('submit', function(event) {
        event.preventDefault(); // Mencegah form untuk disubmit secara default

        let formData = new FormData(this); // Ambil data form, termasuk file yang diunggah

        $.ajax({
            url: "{{ route('offering.import') }}", // Rute untuk mengimpor file
            type: 'POST',
            data: formData,
            processData: false, // Jangan proses data form
            contentType: false, // Jangan tentukan content type, karena file diupload
            success: function(response) {
                // Jika sukses, tampilkan pesan sukses dan tutup modal
                $('#importMessage').html(
                    '<div class="alert alert-success">Import successful!</div>');
                $('#importModal').modal('hide');
                location.reload();
            },
            error: function(xhr, status, error) {
                // Jika ada error, tampilkan pesan error
                $('#importMessage').html(
                    '<div class="alert alert-danger">An error occurred. Please try again.</div>'
                );
            }
        });
    });
</script>
