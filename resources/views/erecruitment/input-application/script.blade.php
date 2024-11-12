<script>
    $(document).ready(function() {
        // Setup CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Inisialisasi Datatable
        $('#inputapplication-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('input-application') }}",
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

        $('#edit-inputapplication-modal').on('shown.bs.modal', function() {
            $('#edit-domicile').select2({
                dropdownParent: $('#edit-inputapplication-modal')
            });
            $('#edit-institution').select2({
                dropdownParent: $('#edit-inputapplication-modal')
            });
            $('#edit-major').select2({
                dropdownParent: $('#edit-inputapplication-modal')
            });
            $('#edit-degree').select2({
                dropdownParent: $('#edit-inputapplication-modal')
            });
            $('#edit-internship-function').select2({
                dropdownParent: $('#edit-inputapplication-modal')
            });
            $('#edit-job-function').select2({
                dropdownParent: $('#edit-inputapplication-modal')
            });
            $('#edit-internship-industry').select2({
                dropdownParent: $('#edit-inputapplication-modal')
            });
            $('#edit-job-industry').select2({
                dropdownParent: $('#edit-inputapplication-modal')
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
                $('#edit-company').html(companyOptions);
            }
        });

        $('#company').on('change', function() {
            var companyId = $(this).val();
            $.ajax({
                url: "{{ route('get.vacancy') }}",
                type: 'GET',
                data: {
                    company_id: companyId
                },
                success: function(data) {
                    let vacancyOptions =
                        '<option value="" disabled selected>Select vacancy</option>';
                    $.each(data.vacancies, function(index, vacancy) {
                        vacancyOptions += '<option value="' +
                            vacancy.id_jobvacancy + '">' + vacancy.position +
                            '</option>';
                    });
                    $('#vacancy').html(vacancyOptions);
                }
            });
        });

        $.ajax({
            url: "{{ route('get.domicile') }}",
            type: 'GET',
            success: function(data) {
                let domicileOptions = '<option value="" disabled selected>Select domicile</option>';

                // Loop melalui data dan tambahkan opsi ke dropdown
                $.each(data.domicile, function(index, domicile) {
                    domicileOptions += '<option value="' + domicile.id + '">' + domicile
                        .name + '</option>';
                });

                // Isi dropdown dengan opsi domisili yang diambil
                $('#domicile').html(domicileOptions);
                $('#edit-domicile').html(domicileOptions);
            }
        });

        $.ajax({
            url: "{{ route('get.degree') }}",
            type: 'GET',
            success: function(data) {
                let degreeOptions = '<option value="" disabled selected>Select degree</option>';

                $.each(data.degree, function(index, degree) {
                    degreeOptions += '<option value="' + degree.degree + '">' + degree
                        .degree + '</option>';
                });

                $('#degree').html(degreeOptions);
                $('#edit-degree').html(degreeOptions);
            }
        });

        $.ajax({
            url: "{{ route('get.institution') }}",
            type: 'GET',
            success: function(data) {
                let institutionOptions =
                    '<option value="" disabled selected>Select institution</option>';

                $.each(data.institution, function(index, institution) {
                    institutionOptions += '<option value="' + institution.name + '">' +
                        institution
                        .name + '</option>';
                });

                $('#institution').html(institutionOptions);
                $('#edit-institution').html(institutionOptions);
            }
        });

        $.ajax({
            url: "{{ route('get.major') }}",
            type: 'GET',
            success: function(data) {
                let majorOptions = '<option value="" disabled selected>Select major</option>';

                $.each(data.major, function(index, major) {
                    majorOptions += '<option value="' + major.major + '">' + major
                        .major + '</option>';
                });

                $('#major').html(majorOptions);
                $('#edit-major').html(majorOptions);
            }
        });

        $.ajax({
            url: "{{ route('get.function') }}",
            type: 'GET',
            success: function(data) {
                let functionsOptions =
                    '<option value="" disabled selected>Select functions</option>';

                // Loop melalui data dan tambahkan opsi ke dropdown
                $.each(data.functions, function(index, functions) {
                    functionsOptions += '<option value="' + functions.function+'">' +
                        functions
                        .function+'</option>';
                });

                // Isi dropdown dengan opsi domisili yang diambil
                $('#internship-function').html(functionsOptions);
                $('#job-function').html(functionsOptions);
                $('#edit-internship-function').html(functionsOptions);
                $('#edit-job-function').html(functionsOptions);
            }
        });

        $.ajax({
            url: "{{ route('get.industry') }}",
            type: 'GET',
            success: function(data) {
                let industryOptions =
                    '<option value="" disabled selected>Select industry</option>';

                // Loop melalui data dan tambahkan opsi ke dropdown
                $.each(data.industry, function(index, industry) {
                    industryOptions += '<option value="' + industry.industry + '">' +
                        industry.industry + '</option>';
                });

                // Isi dropdown dengan opsi domisili yang diambil
                $('#internship-industry').html(industryOptions);
                $('#job-industry').html(industryOptions);
                $('#edit-internship-industry').html(industryOptions);
                $('#edit-job-industry').html(industryOptions);
            }
        });

        $('#importForm').on('submit', function(event) {
            $.ajax({
                url: "{{ url('input-application/import') }}",
                type: 'POST',
                data: new FormData(this), // Mengambil data dari form
                processData: false,
                contentType: false,
                success: function(response) {

                },
                error: function(xhr) {

                }
            });
        });
    });

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('imagePreview');
            const uploadText = document.getElementById('upload-text');

            output.src = reader.result;
            output.style.display = 'block'; // Show the image
            uploadText.style.display = 'none'; // Hide the "Choose an image" text
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    // Separator Expected Salary
    const expectedSalaryInput = document.getElementById('expected-salary');
    expectedSalaryInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^0-9]/g, '');

        if (value.length > 0 && value.charAt(0) === '0') {
            value = value.substring(1);
        }

        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        e.target.value = value;
    });

    function setGraduatedYear() {
        var checkbox = document.getElementById('current-year-checkbox');
        var input = document.getElementById('graduated-year');

        if (checkbox.checked) {
            input.value = new Date().getFullYear();
            input.disabled = true;
        } else {
            input.value = '';
            input.disabled = false;
        }
    }

    function setEndDateNow() {
        var checkbox = document.getElementById('internship-current-date-checkbox');
        var input = document.getElementById('internship-end-date');

        if (checkbox.checked) {
            var today = new Date();
            var formattedDate = today.toISOString().slice(0, 10); // Get YYYY-MM-DD format

            input.value = formattedDate;
            input.disabled = true;
        } else {
            input.value = '';
            input.disabled = false;
        }
    }

    function setEndDateJobNow() {
        var checkbox = document.getElementById('job-current-date-checkbox');
        var input = document.getElementById('job-end-date');

        if (checkbox.checked) {
            var today = new Date();
            var formattedDate = today.toISOString().slice(0, 10); // Get YYYY-MM-DD format

            input.value = formattedDate;
            input.disabled = true;
        } else {
            input.value = '';
            input.disabled = false;
        }
    }

    // Validasi inputan GPA
    function validateGPA(input) {
        let value = parseFloat(input.value);

        if (value < 0 || value > 4 || isNaN(value)) {
            input.setCustomValidity("Invalid GPA");
            input.reportValidity();
        } else {
            input.setCustomValidity("");
        }

        if (!isNaN(value) && value >= 0 && value <= 4) {
            input.value = value.toFixed(1);
        }
    }

    let educationList = [];

    document.querySelector('.button-simpan-education').addEventListener('click', function() {
        let degree = document.getElementById("degree").value;
        let institution = document.getElementById("institution").value;
        let major = document.getElementById("major").value;
        let startYear = document.getElementById("start-year").value;
        let graduatedYear = document.getElementById("graduated-year").value;
        let gpa = document.getElementById("gpa").value;

        if (degree && institution && major && startYear && (graduatedYear || document.getElementById(
                "current-year-checkbox").checked) && gpa) {
            educationList.push({
                degree: degree,
                institution: institution,
                major: major,
                startYear: startYear,
                graduatedYear: document.getElementById("current-year-checkbox").checked ? 'Present' :
                    graduatedYear,
                gpa: gpa
            });

            document.getElementById("degree").value = "";
            document.getElementById("institution").value = "";
            document.getElementById("major").value = "";
            document.getElementById("start-year").value = "";
            document.getElementById("graduated-year").value = "";
            document.getElementById("gpa").value = "";
            document.getElementById("current-year-checkbox").checked = false;

            renderTable();
        } else {
            alert('Mohon isi semua field yang diperlukan!');
        }
    });

    function renderTable() {
        let table = document.getElementById("education-datatable").getElementsByTagName('tbody')[0];

        table.innerHTML = "";

        educationList.forEach((education, index) => {
            let row = table.insertRow();
            row.insertCell(0).innerHTML = index + 1; // No
            row.insertCell(1).innerHTML = education.degree; // Degree
            row.insertCell(2).innerHTML = education.institution; // Institution
            row.insertCell(3).innerHTML = education.major; // Major
            row.insertCell(4).innerHTML = education.startYear; // Start Year
            row.insertCell(5).innerHTML = education.graduatedYear; // Graduated Year
            row.insertCell(6).innerHTML = education.gpa; // GPA/NEM
            row.insertCell(7).innerHTML = `
            <button type="button" title="Remove" class="btn btn-danger btn-sm button-delete" onclick="deleteRow(${index})">
                <i class="ri-delete-bin-5-line"></i>
            </button>
        `;
        });
    }

    function deleteRow(index) {
        educationList.splice(index, 1);
        renderTable();
    }

    let organizationList = [];

    document.querySelector('.button-simpan-organization').addEventListener('click', function() {
        let organizationName = document.getElementById("organization-name").value;
        let scope = document.getElementById("scope").value;
        let title = document.getElementById("title").value;

        if (organizationName && scope && title) {
            organizationList.push({
                organizationName: organizationName,
                scope: scope,
                title: title
            });

            document.getElementById("organization-name").value = "";
            document.getElementById("scope").value = "";
            document.getElementById("title").value = "";

            renderOrganizationTable();
        } else {
            alert('Mohon isi semua field yang diperlukan!');
        }
    });

    function renderOrganizationTable() {
        let table = document.getElementById("organization-datatable").getElementsByTagName('tbody')[0];

        table.innerHTML = "";

        organizationList.forEach((organization, index) => {
            let row = table.insertRow();
            row.insertCell(0).innerHTML = index + 1; // No
            row.insertCell(1).innerHTML = organization.organizationName; // Organization Name
            row.insertCell(2).innerHTML = organization.scope; // Scope
            row.insertCell(3).innerHTML = organization.title; // Title
            row.insertCell(4).innerHTML = `
            <button type="button" title="Remove" class="btn btn-danger btn-sm button-delete" onclick="deleteOrganizationRow(${index})">
                <i class="ri-delete-bin-5-line"></i>
            </button>
        `;
        });
    }

    function deleteOrganizationRow(index) {
        organizationList.splice(index, 1);
        renderOrganizationTable();
    }

    let internshipList = [];

    document.querySelector('.button-simpan-internship').addEventListener('click', function() {
        let companyName = document.getElementById("internship-company-name").value;
        let functionRole = document.getElementById("internship-function").value;
        let industry = document.getElementById("internship-industry").value;
        let startDate = document.getElementById("internship-start-date").value;
        let endDate = document.getElementById("internship-end-date").value;
        let jobDescription = document.getElementById("internship-job-description").value;

        if (companyName && functionRole && industry && startDate && (endDate || document.getElementById(
                "internship-current-date-checkbox").checked)) {
            internshipList.push({
                companyName: companyName,
                functionRole: functionRole,
                industry: industry,
                startDate: startDate,
                endDate: document.getElementById("internship-current-date-checkbox").checked ?
                    'Present' : endDate,
                jobDescription: jobDescription
            });

            // Clear form inputs
            document.getElementById("internship-company-name").value = "";
            document.getElementById("internship-function").value = "";
            document.getElementById("internship-industry").value = "";
            document.getElementById("internship-start-date").value = "";
            document.getElementById("internship-end-date").value = "";
            document.getElementById("internship-job-description").value = "";
            document.getElementById("internship-current-date-checkbox").checked = false;

            // Render to table
            renderInternshipTable();
        } else {
            alert('Mohon isi semua field yang diperlukan!');
        }
    });

    function renderInternshipTable() {
        let table = document.getElementById("internship-datatable").getElementsByTagName('tbody')[0];
        table.innerHTML = "";

        internshipList.forEach((internship, index) => {
            let row = table.insertRow();
            row.insertCell(0).innerHTML = index + 1; // No
            row.insertCell(1).innerHTML = internship.companyName; // Company Name
            row.insertCell(2).innerHTML = internship.functionRole; // Function
            row.insertCell(3).innerHTML = internship.industry; // Industry
            row.insertCell(4).innerHTML = internship.startDate; // Start Date
            row.insertCell(5).innerHTML = internship.endDate; // End Date
            row.insertCell(6).innerHTML = internship.jobDescription || "-"; // Job Description
            row.insertCell(7).innerHTML = `
            <button type="button" title="Remove" class="btn btn-danger btn-sm button-delete" onclick="deleteInternshipRow(${index})">
                <i class="ri-delete-bin-5-line"></i>
            </button>
        `;
        });
    }

    function deleteInternshipRow(index) {
        internshipList.splice(index, 1); // Hapus data dari list
        renderInternshipTable(); // Render ulang tabel
    }

    let jobExperienceList = [];

    document.querySelector('.button-simpan-jobExperience').addEventListener('click', function() {
        let companyName = document.getElementById("job-company-name").value;
        let title = document.getElementById("job-title").value;
        let position = document.getElementById("job-position").value;
        let positionType = document.getElementById("job-position-type").value;
        let functionRole = document.getElementById("job-function").value;
        let industry = document.getElementById("job-industry").value;
        let startDate = document.getElementById("job-start-date").value;
        let endDate = document.getElementById("job-end-date").value;
        let jobDescription = document.getElementById("job-description-job").value;

        if (companyName && title && position && positionType && functionRole && industry && startDate && (
                endDate || document.getElementById("job-current-date-checkbox").checked)) {
            jobExperienceList.push({
                companyName: companyName,
                title: title,
                position: position,
                positionType: positionType,
                functionRole: functionRole,
                industry: industry,
                startDate: startDate,
                endDate: document.getElementById("job-current-date-checkbox").checked ? 'Present' :
                    endDate,
                jobDescription: jobDescription
            });

            // Clear form inputs
            document.getElementById("job-company-name").value = "";
            document.getElementById("job-title").value = "";
            document.getElementById("job-position").value = "";
            document.getElementById("job-position-type").value = "";
            document.getElementById("job-function").value = "";
            document.getElementById("job-industry").value = "";
            document.getElementById("job-start-date").value = "";
            document.getElementById("job-end-date").value = "";
            document.getElementById("job-description-job").value = "";
            document.getElementById("job-current-date-checkbox").checked = false;

            // Render to table
            renderJobExperienceTable();
        } else {
            alert('Mohon isi semua field yang diperlukan!');
        }
    });

    function renderJobExperienceTable() {
        let table = document.getElementById("jobexperience-datatable").getElementsByTagName('tbody')[0];
        table.innerHTML = "";

        jobExperienceList.forEach((jobExperience, index) => {
            let row = table.insertRow();
            row.insertCell(0).innerHTML = index + 1; // No
            row.insertCell(1).innerHTML = jobExperience.companyName; // Company Name
            row.insertCell(2).innerHTML = jobExperience.title; // Title
            row.insertCell(3).innerHTML = jobExperience.position; // Position
            row.insertCell(4).innerHTML = jobExperience.positionType; // Position Type
            row.insertCell(5).innerHTML = jobExperience.functionRole; // Function
            row.insertCell(6).innerHTML = jobExperience.industry; // Industry
            row.insertCell(7).innerHTML = jobExperience.startDate; // Start Date
            row.insertCell(8).innerHTML = jobExperience.endDate; // End Date
            row.insertCell(9).innerHTML = jobExperience.jobDescription || "-"; // Job Description
            row.insertCell(10).innerHTML = `
            <button type="button" title="Remove" class="btn btn-danger btn-sm button-delete" onclick="deleteJobExperienceRow(${index})">
                <i class="ri-delete-bin-5-line"></i>
            </button>
        `;
        });
    }

    function deleteJobExperienceRow(index) {
        jobExperienceList.splice(index, 1); // Hapus data dari list
        renderJobExperienceTable(); // Render ulang tabel
    }

    // Preview Image for Edit Modal
    function previewImageEdit(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('imagePreviewEdit');
            const uploadText = document.getElementById('upload-text-edit');

            output.src = reader.result;
            output.style.display = 'block'; // Show the image
            uploadText.style.display = 'none'; // Hide the "Choose an image" text
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    // Separator for Expected Salary in Edit Modal
    const expectedSalaryInputEdit = document.getElementById('edit-expected-salary');
    expectedSalaryInputEdit.addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^0-9]/g, '');

        if (value.length > 0 && value.charAt(0) === '0') {
            value = value.substring(1);
        }

        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        e.target.value = value;
    });

    function setEditGraduatedYear() {
        var checkbox = document.getElementById('edit-current-year-checkbox');
        var input = document.getElementById('edit-graduated-year');

        if (checkbox.checked) {
            input.value = new Date().getFullYear();
            input.disabled = true;
        } else {
            input.value = '';
            input.disabled = false;
        }
    }

    function setEditInternshipEndDateNow() {
        var checkbox = document.getElementById('edit-internship-current-date-checkbox');
        var input = document.getElementById('edit-internship-end-date');

        if (checkbox.checked) {
            var today = new Date();
            var formattedDate = today.toISOString().slice(0, 10); // Get YYYY-MM-DD format

            input.value = formattedDate;
            input.disabled = true;
        } else {
            input.value = '';
            input.disabled = false;
        }
    }

    function setEditJobEndDateNow() {
        var checkbox = document.getElementById('edit-job-current-date-checkbox');
        var input = document.getElementById('edit-job-end-date');

        if (checkbox.checked) {
            var today = new Date();
            var formattedDate = today.toISOString().slice(0, 10); // Get YYYY-MM-DD format

            input.value = formattedDate;
            input.disabled = true;
        } else {
            input.value = '';
            input.disabled = false;
        }
    }

    // Validate GPA for Edit Modal
    function validateEditGPA(input) {
        let value = parseFloat(input.value);

        if (value < 0 || value > 4 || isNaN(value)) {
            input.setCustomValidity("Invalid GPA");
            input.reportValidity();
        } else {
            input.setCustomValidity("");
        }

        if (!isNaN(value) && value >= 0 && value <= 4) {
            input.value = value.toFixed(1);
        }
    }

    // Universal delete handler for all dynamic buttons
    $(document).on('click', '.button-delete', function() {
        const index = $(this).data('index');
        const type = $(this).data('type');

        switch (type) {
            case 'education':
                editDeleteEducationRow(index);
                break;
            case 'organization':
                editDeleteOrganizationRow(index);
                break;
            case 'internship':
                editDeleteInternshipRow(index);
                break;
            case 'jobExperience':
                editDeleteJobExperienceRow(index);
                break;
        }
    });

    let editEducationList = [];

    document.querySelector('.button-edit-education').addEventListener('click', function() {
        let degree = document.getElementById("edit-degree").value;
        let institution = document.getElementById("edit-institution").value;
        let major = document.getElementById("edit-major").value;
        let startYear = document.getElementById("edit-start-year").value;
        let graduatedYear = document.getElementById("edit-graduated-year").value;
        let gpa = document.getElementById("edit-gpa").value;

        if (degree && institution && major && startYear && (graduatedYear || document.getElementById(
                "edit-current-year-checkbox").checked) && gpa) {
            editEducationList.push({
                degree: degree,
                institution: institution,
                major: major,
                start_year: startYear,
                graduated_year: document.getElementById("edit-current-year-checkbox").checked ?
                    'Present' : graduatedYear,
                gpa: gpa
            });

            // Reset form setelah menambahkan data
            document.getElementById("edit-degree").value = "";
            document.getElementById("edit-institution").value = "";
            document.getElementById("edit-major").value = "";
            document.getElementById("edit-start-year").value = "";
            document.getElementById("edit-graduated-year").value = "";
            document.getElementById("edit-gpa").value = "";
            document.getElementById("edit-current-year-checkbox").checked = false;

            editRenderEducationTable();
        } else {
            alert('Mohon isi semua field yang diperlukan!');
        }
    });

    function editRenderEducationTable() {
        let table = document.getElementById("edit-education-datatable").getElementsByTagName('tbody')[0];
        table.innerHTML = "";

        editEducationList.forEach((education, index) => {
            let row = table.insertRow();
            row.insertCell(0).innerHTML = index + 1;
            row.insertCell(1).innerHTML = education.degree;
            row.insertCell(2).innerHTML = education.institution;
            row.insertCell(3).innerHTML = education.major;
            row.insertCell(4).innerHTML = education.start_year;
            row.insertCell(5).innerHTML = education.graduated_year;
            row.insertCell(6).innerHTML = education.gpa;
            row.insertCell(7).innerHTML = `
        <button type="button" class="btn btn-danger btn-sm button-delete" onclick="editDeleteEducationRow(${index})">
            <i class="ri-delete-bin-5-line"></i>
        </button>`;
        });
    }

    function editDeleteEducationRow(index) {
        editEducationList.splice(index, 1);
        editRenderEducationTable();
    }

    let editOrganizationList = [];

    document.querySelector('.button-edit-organization').addEventListener('click', function() {
        let organizationName = document.getElementById("edit-organization-name").value;
        let scope = document.getElementById("edit-scope").value;
        let title = document.getElementById("edit-title").value;

        if (organizationName && scope && title) {
            editOrganizationList.push({
                organization_name: organizationName,
                scope: scope,
                title: title
            });

            // Reset form setelah menambahkan data
            document.getElementById("edit-organization-name").value = "";
            document.getElementById("edit-scope").value = "";
            document.getElementById("edit-title").value = "";

            editRenderOrganizationTable();
        } else {
            alert('Mohon isi semua field yang diperlukan!');
        }
    });

    function editRenderOrganizationTable() {
        let table = document.getElementById("edit-organization-datatable").getElementsByTagName('tbody')[0];
        table.innerHTML = "";

        editOrganizationList.forEach((organization, index) => {
            let row = table.insertRow();
            row.insertCell(0).innerHTML = index + 1;
            row.insertCell(1).innerHTML = organization.organization_name;
            row.insertCell(2).innerHTML = organization.scope;
            row.insertCell(3).innerHTML = organization.title;
            row.insertCell(4).innerHTML = `
        <button type="button" class="btn btn-danger btn-sm button-delete" onclick="editDeleteOrganizationRow(${index})">
            <i class="ri-delete-bin-5-line"></i>
        </button>`;
        });
    }

    function editDeleteOrganizationRow(index) {
        editOrganizationList.splice(index, 1);
        editRenderOrganizationTable();
    }

    let editInternshipList = [];

    document.querySelector('.button-edit-internship').addEventListener('click', function() {
        let companyName = document.getElementById("edit-internship-company-name").value;
        let functionRole = document.getElementById("edit-internship-function").value;
        let industry = document.getElementById("edit-internship-industry").value;
        let startDate = document.getElementById("edit-internship-start-date").value;
        let endDate = document.getElementById("edit-internship-end-date").value;
        let jobDescription = document.getElementById("edit-internship-job-description").value;

        if (companyName && functionRole && industry && startDate && (endDate || document.getElementById(
                "edit-internship-current-date-checkbox").checked)) {
            editInternshipList.push({
                company_name: companyName,
                function: functionRole,
                industry: industry,
                start_date: startDate,
                end_date: document.getElementById("edit-internship-current-date-checkbox").checked ?
                    'Present' : endDate,
                job_description: jobDescription
            });

            // Reset form setelah menambahkan data
            document.getElementById("edit-internship-company-name").value = "";
            document.getElementById("edit-internship-function").value = "";
            document.getElementById("edit-internship-industry").value = "";
            document.getElementById("edit-internship-start-date").value = "";
            document.getElementById("edit-internship-end-date").value = "";
            document.getElementById("edit-internship-job-description").value = "";
            document.getElementById("edit-internship-current-date-checkbox").checked = false;

            editRenderInternshipTable();
        } else {
            alert('Mohon isi semua field yang diperlukan!');
        }
    });

    function editRenderInternshipTable() {
        let table = document.getElementById("edit-internship-datatable").getElementsByTagName('tbody')[0];
        table.innerHTML = "";

        editInternshipList.forEach((internship, index) => {
            let row = table.insertRow();
            row.insertCell(0).innerHTML = index + 1;
            row.insertCell(1).innerHTML = internship.company_name;
            row.insertCell(2).innerHTML = internship.function;
            row.insertCell(3).innerHTML = internship.industry;
            row.insertCell(4).innerHTML = internship.start_date;
            row.insertCell(5).innerHTML = internship.end_date;
            row.insertCell(6).innerHTML = internship.job_description || "-";
            row.insertCell(7).innerHTML = `
        <button type="button" class="btn btn-danger btn-sm button-delete" onclick="editDeleteInternshipRow(${index})">
            <i class="ri-delete-bin-5-line"></i>
        </button>`;
        });
    }

    function editDeleteInternshipRow(index) {
        editInternshipList.splice(index, 1);
        editRenderInternshipTable();
    }

    let editJobExperienceList = [];

    document.querySelector('.button-edit-jobExperience').addEventListener('click', function() {
        let companyName = document.getElementById("edit-job-company-name").value;
        let title = document.getElementById("edit-job-title").value;
        let position = document.getElementById("edit-job-position").value;
        let positionType = document.getElementById("edit-job-position-type").value;
        let functionRole = document.getElementById("edit-job-function").value;
        let industry = document.getElementById("edit-job-industry").value;
        let startDate = document.getElementById("edit-job-start-date").value;
        let endDate = document.getElementById("edit-job-end-date").value;
        let jobDescription = document.getElementById("edit-job-description-job").value;

        if (companyName && title && position && positionType && functionRole && industry && startDate && (
                endDate || document.getElementById("edit-job-current-date-checkbox").checked)) {
            editJobExperienceList.push({
                company_name: companyName,
                title: title,
                position: position,
                position_type: positionType,
                function: functionRole,
                industry: industry,
                start_date: startDate,
                end_date: document.getElementById("edit-job-current-date-checkbox").checked ?
                    'Present' : endDate,
                job_description: jobDescription
            });

            // Reset form setelah menambahkan data
            document.getElementById("edit-job-company-name").value = "";
            document.getElementById("edit-job-title").value = "";
            document.getElementById("edit-job-position").value = "";
            document.getElementById("edit-job-position-type").value = "";
            document.getElementById("edit-job-function").value = "";
            document.getElementById("edit-job-industry").value = "";
            document.getElementById("edit-job-start-date").value = "";
            document.getElementById("edit-job-end-date").value = "";
            document.getElementById("edit-job-description-job").value = "";
            document.getElementById("edit-job-current-date-checkbox").checked = false;

            editRenderJobExperienceTable();
        } else {
            alert('Mohon isi semua field yang diperlukan!');
        }
    });

    function editRenderJobExperienceTable() {
        let table = document.getElementById("edit-jobexperience-datatable").getElementsByTagName('tbody')[0];
        table.innerHTML = "";

        editJobExperienceList.forEach((jobExperience, index) => {
            let row = table.insertRow();
            row.insertCell(0).innerHTML = index + 1;
            row.insertCell(1).innerHTML = jobExperience.company_name;
            row.insertCell(2).innerHTML = jobExperience.title;
            row.insertCell(3).innerHTML = jobExperience.position;
            row.insertCell(4).innerHTML = jobExperience.position_type;
            row.insertCell(5).innerHTML = jobExperience.function;
            row.insertCell(6).innerHTML = jobExperience.industry;
            row.insertCell(7).innerHTML = jobExperience.start_date;
            row.insertCell(8).innerHTML = jobExperience.end_date;
            row.insertCell(9).innerHTML = jobExperience.job_description || "-";
            row.insertCell(10).innerHTML = `
        <button type="button" class="btn btn-danger btn-sm button-delete" onclick="editDeleteJobExperienceRow(${index})">
            <i class="ri-delete-bin-5-line"></i>
        </button>`;
        });
    }

    function editDeleteJobExperienceRow(index) {
        editJobExperienceList.splice(index, 1);
        editRenderJobExperienceTable();
    }

    // Implementasi serupa untuk job experience...

    // Collect data dari Form Input Application
    // Bagian Education, Organization, Internship, dan Job Experience diambil dari tabel
    function collectDataAddInputApplication() {
        var formData = new FormData();

        // Mengumpulkan data dari Personal Data
        formData.append('company', $('#company').val());
        formData.append('vacancy', $('#vacancy').val());
        formData.append('full_name', $('#name').val());
        formData.append('email', $('#email').val());
        formData.append('birth_date', $('#birth-date').val());
        formData.append('gender', $('#gender').val());
        formData.append('domicile', $('#domicile').val());
        formData.append('phone_number', $('#phone-number').val());
        formData.append('years_experience', $('#years-experience').val());
        formData.append('months_experience', $('#months-experience').val());
        formData.append('expected_salary', $('#expected-salary').val());

        // Mengumpulkan file (photo dan CV)
        var photo = $('#photo')[0].files[0];
        if (photo) {
            formData.append('photo', photo);
        }

        var cv = $('#cv')[0].files[0];
        if (cv) {
            formData.append('cv', cv);
        }

        // Mengumpulkan data dari Education
        let educationData = [];
        educationList.forEach(function(education) {
            educationData.push(education);
        });
        formData.append('education', JSON.stringify(educationData));

        // Mengumpulkan data dari Organization
        let organizationData = [];
        organizationList.forEach(function(organization) {
            organizationData.push(organization);
        });
        formData.append('organization', JSON.stringify(organizationData));

        // Mengumpulkan data dari Internship
        let internshipData = [];
        internshipList.forEach(function(internship) {
            internshipData.push(internship);
        });
        formData.append('internship', JSON.stringify(internshipData));

        // Mengumpulkan data dari Job Experience
        let jobExperienceData = [];
        jobExperienceList.forEach(function(jobExperience) {
            jobExperienceData.push(jobExperience);
        });
        formData.append('job_experience', JSON.stringify(jobExperienceData));

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

    // Fungsi proses Simpan data Input Application
    function simpan() {
        var formData = collectDataAddInputApplication();
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: "{{ url('input-application') }}",
            type: 'POST',
            data: formData,
            processData: false,
            cache: false,
            contentType: false,
            success: function(response) {
                // Menutup modal setelah sukses
                resetInputApplicationModal();
                $('#inputapplication-modal').modal('hide');

                // Menampilkan alert
                $('#alert-save-success').removeClass('d-none');

                // Refresh datatable
                $('#inputapplication-datatable').DataTable().ajax.reload();

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
        var id = $(this).data('id'); // Mengambil ID dari tombol edit
        isEditing = true; // Menandai bahwa proses sedang dalam mode edit

        $.ajax({
            url: "{{ url('input-application') }}" + "/" + id + '/edit',
            type: 'GET',
            success: function(response) {
                if (response) {
                    // Pengisian data personal
                    $('#application-id').val(response.application.id);
                    $('#edit-company').val(response.application.company).trigger('change');

                    // Panggil fungsi untuk mengambil vacancy berdasarkan company
                    loadVacancyData(response.application.company, response.application.vacancy);

                    $('#edit-name').val(response.application.full_name);
                    $('#edit-email').val(response.application.email);
                    $('#edit-birth-date').val(response.application.birth_date);
                    $('#edit-gender').val(response.application.gender).trigger('change');
                    $('#edit-domicile').val(response.application.domicile).trigger('change');
                    $('#edit-phone-number').val(response.application.phone_number);
                    $('#edit-years-experience').val(response.application.years_experience);
                    $('#edit-months-experience').val(response.application.months_experience);
                    $('#edit-expected-salary').val(response.application.expected_salary);

                    // Isi preview foto
                    if (response.application.photo_path) {
                        $('#imagePreviewEdit').attr('src', response.application.photo_path).show();
                        $('#upload-text-edit').hide();
                    }

                    // Simulasikan klik "Add" untuk setiap data yang diambil dari server
                    simulateEducationData(response.educations);
                    simulateOrganizationData(response.organizations);
                    simulateInternshipData(response.internships);
                    simulateJobExperienceData(response.jobExperiences);

                    isEditing = false; // Setelah semua selesai, kembali ke mode normal
                } else {
                    alert('Data not found');
                    isEditing = false;
                }
            },
            error: function(xhr) {
                alert('Error loading data');
                isEditing = false;
            }
        });
    });

    // Fungsi untuk memuat vacancy berdasarkan company dan memilih vacancy yang sudah tersimpan
    function loadVacancyData(companyId, selectedVacancyId) {
        $.ajax({
            url: "{{ route('get.vacancy') }}",
            type: 'GET',
            data: {
                company_id: companyId
            },
            success: function(data) {
                let vacancyOptions = '<option value="" disabled selected>Select vacancy</option>';
                $.each(data.vacancies, function(index, vacancy) {
                    vacancyOptions += '<option value="' + vacancy.id_jobvacancy + '">' + vacancy
                        .position + '</option>';
                });
                $('#edit-vacancy').html(vacancyOptions);

                $('#edit-vacancy').val(selectedVacancyId).trigger('change');
            }
        });
    }

    // Event handler untuk memuat vacancy ketika company diubah manual
    $('#edit-company').on('change', function() {
        if (!isEditing) { // Jika tidak sedang dalam mode edit
            var companyId = $(this).val();
            loadVacancyData(companyId, null);
        }
    });

    function simulateEducationData(educations) {
        educations.forEach(function(education) {
            $('#edit-degree').val(education.degree);
            $('#edit-institution').val(education.institution);
            $('#edit-major').val(education.major);
            $('#edit-start-year').val(education.start_year);
            $('#edit-graduated-year').val(education.graduated_year);
            $('#edit-gpa').val(education.gpa);

            // Klik tombol "Add Education" seolah-olah data dimasukkan manual
            $('.button-edit-education').click();
        });
    }

    // Simulasi untuk Organization
    function simulateOrganizationData(organizations) {
        organizations.forEach(function(organization) {
            $('#edit-organization-name').val(organization.organization_name);
            $('#edit-scope').val(organization.scope);
            $('#edit-title').val(organization.title);

            // Klik tombol "Add Organization" seolah-olah data dimasukkan manual
            $('.button-edit-organization').click();
        });
    }

    // Simulasi untuk Internship
    function simulateInternshipData(internships) {
        internships.forEach(function(internship) {
            $('#edit-internship-company-name').val(internship.company_name);
            $('#edit-internship-function').val(internship.function_role);
            $('#edit-internship-industry').val(internship.industry);
            $('#edit-internship-start-date').val(internship.start_date);

            if (internship.end_date === "Present") {
                $('#edit-internship-current-date-checkbox').prop('checked', true); // Centang checkbox
                $('#edit-internship-end-date').val(''); // Kosongkan nilai end-date jika present
            } else {
                $('#edit-internship-current-date-checkbox').prop('checked',
                    false); // Uncheck jika tidak present
                $('#edit-internship-end-date').val(internship.end_date);
            }

            $('#edit-internship-job-description').val(internship.job_description);

            // Klik tombol "Add Internship" seolah-olah data dimasukkan manual
            $('.button-edit-internship').click();
        });
    }

    // Simulasi untuk Job Experience
    function simulateJobExperienceData(jobExperiences) {
        jobExperiences.forEach(function(jobExperience) {
            $('#edit-job-company-name').val(jobExperience.company_name);
            $('#edit-job-title').val(jobExperience.title);
            $('#edit-job-position').val(jobExperience.position);
            $('#edit-job-position-type').val(jobExperience.position_type);
            $('#edit-job-function').val(jobExperience.function_role);
            $('#edit-job-industry').val(jobExperience.industry);
            $('#edit-job-start-date').val(jobExperience.start_date);

            if (jobExperience.end_date === "Present") {
                $('#edit-job-current-date-checkbox').prop('checked', true); // Centang checkbox
                $('#edit-job-end-date').val(''); // Kosongkan nilai end-date jika present
            } else {
                $('#edit-job-current-date-checkbox').prop('checked', false); // Uncheck jika tidak present
                $('#edit-job-end-date').val(jobExperience.end_date);
            }

            $('#edit-job-description-job').val(jobExperience.job_description);

            // Klik tombol "Add Job Experience" seolah-olah data dimasukkan manual
            $('.button-edit-jobExperience').click();
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
            url: "{{ url('input-application') }}" + "/" + id,
            type: 'DELETE',
            data: {
                "_token": $('meta[name="csrf-token"]').attr(
                    'content')
            },
            success: function(response) {
                console.log(response.message);
                $('#deleteinputapplication-modal').modal('hide');
                // $('#alert-delete-success').removeClass('d-none');
                $('#inputapplication-datatable').DataTable().ajax.reload();

                setTimeout(function() {
                    // $('#alert-delete-success').addClass('d-none');
                }, 5000);
            },
            error: function(xhr) {
                // Tampilkan pesan error di konsol
                console.error('Gagal menghapus data!');
            }
        });
    });

    function resetInputApplicationModal() {
        // Reset select fields to default
        document.getElementById('company').selectedIndex = 0;
        document.getElementById('vacancy').selectedIndex = 0;
        document.getElementById('gender').selectedIndex = 0;
        document.getElementById('domicile').selectedIndex = 0;
        document.getElementById('degree').selectedIndex = 0;
        document.getElementById('institution').selectedIndex = 0;
        document.getElementById('major').selectedIndex = 0;

        // Reset input fields
        document.getElementById('name').value = '';
        document.getElementById('email').value = '';
        document.getElementById('birth-date').value = '';
        document.getElementById('phone-number').value = '';
        document.getElementById('years-experience').value = '';
        document.getElementById('months-experience').value = '';
        document.getElementById('expected-salary').value = '';
        document.getElementById('start-year').value = '';
        document.getElementById('graduated-year').value = '';
        document.getElementById('gpa').value = '';

        // Reset file input fields (photo & cv)
        document.getElementById('photo').value = '';
        document.getElementById('cv').value = '';

        // Reset Job Experience form fields (specific to the screenshot)
        document.getElementById('job-company-name').value = '';
        document.getElementById('job-title').selectedIndex = 0;
        document.getElementById('job-position').value = '';
        document.getElementById('job-position-type').selectedIndex = 0;
        document.getElementById('job-function').selectedIndex = 0;
        document.getElementById('job-industry').selectedIndex = 0;
        document.getElementById('job-start-date').value = '';
        document.getElementById('job-end-date').value = '';
        document.getElementById('job-description-job').value = '';

        // Reset image preview (if any)
        const imagePreview = document.getElementById('imagePreview');
        const uploadText = document.getElementById('upload-text');
        imagePreview.src = '';
        imagePreview.style.display = 'none'; // Hide the image
        uploadText.style.display = 'block'; // Show "Choose an image" text

        // Remove validation classes
        var form = $('#inputapplication-modal form');
        form.removeClass('was-validated'); // Remove Bootstrap validation classes

        // Remove invalid-feedback and validation styles
        form.find('.form-select, .form-control').each(function() {
            $(this).removeClass('is-invalid'); // Remove invalid class
            $(this).removeClass('is-valid'); // Remove valid class (if any)
        });

        // If you are using Select2, reset those as well
        $('#company, #vacancy, #gender, #domicile, #degree, #institution, #major, #job-title, #job-position-type, #job-function, #job-industry')
            .val(null).trigger('change');
    }

    // Event listener untuk reset modal ketika modal ditutup
    $('#edit-inputapplication-modal').on('hidden.bs.modal', function() {
        resetEditInputApplicationModal();
    });

    function resetEditInputApplicationModal() {
        // Reset select fields to default (for selects without Select2)
        document.getElementById('edit-company').selectedIndex = 0;
        document.getElementById('edit-vacancy').selectedIndex = 0;
        document.getElementById('edit-gender').selectedIndex = 0;
        document.getElementById('edit-domicile').selectedIndex = 0;
        document.getElementById('edit-job-title').selectedIndex = 0;
        document.getElementById('edit-job-position-type').selectedIndex = 0;
        document.getElementById('edit-scope').selectedIndex = 0;
        document.getElementById('edit-title').selectedIndex = 0;

        // Reset select fields with Select2
        $('#edit-degree, #edit-institution, #edit-major, #edit-job-function, #edit-job-industry, #edit-internship-function, #edit-internship-industry')
            .val(null).trigger('change');

        // Reset input fields
        $('#edit-name').val('');
        $('#edit-email').val('');
        $('#edit-birth-date').val('');
        $('#edit-phone-number').val('');
        $('#edit-years-experience').val('');
        $('#edit-months-experience').val('');
        $('#edit-expected-salary').val('');
        $('#edit-start-year').val('');
        $('#edit-graduated-year').val('');
        $('#edit-gpa').val('');

        // Reset checkboxes
        $('#edit-current-year-checkbox').prop('checked', false);
        $('#edit-internship-current-date-checkbox').prop('checked', false);
        $('#edit-job-current-date-checkbox').prop('checked', false);

        // Reset file input fields (photo & cv)
        $('#edit-photo').val('');
        $('#edit-cv').val('');

        // Reset image preview (if any)
        $('#imagePreviewEdit').attr('src', '').hide();
        $('#upload-text-edit').show();

        // Reset Education Tab inputs
        $('#edit-degree').val(null).trigger('change');
        $('#edit-institution').val(null).trigger('change');
        $('#edit-major').val(null).trigger('change');
        $('#edit-start-year').val('');
        $('#edit-graduated-year').val('');
        $('#edit-current-year-checkbox').prop('checked', false);
        $('#edit-gpa').val('');

        // Reset Organization Tab inputs
        $('#edit-organization-name').val('');
        $('#edit-scope').prop('selectedIndex', 0);
        $('#edit-title').prop('selectedIndex', 0);

        // Reset Internship Tab inputs
        $('#edit-internship-company-name').val('');
        $('#edit-internship-function').val(null).trigger('change');
        $('#edit-internship-industry').val(null).trigger('change');
        $('#edit-internship-start-date').val('');
        $('#edit-internship-end-date').val('');
        $('#edit-internship-current-date-checkbox').prop('checked', false);
        $('#edit-internship-job-description').val('');

        // Reset Job Experience Tab inputs
        $('#edit-job-company-name').val('');
        $('#edit-job-title').prop('selectedIndex', 0);
        $('#edit-job-position').val('');
        $('#edit-job-position-type').prop('selectedIndex', 0);
        $('#edit-job-function').val(null).trigger('change');
        $('#edit-job-industry').val(null).trigger('change');
        $('#edit-job-start-date').val('');
        $('#edit-job-end-date').val('');
        $('#edit-job-current-date-checkbox').prop('checked', false);
        $('#edit-job-description-job').val('');

        // Remove validation classes
        var form = $('#edit-inputapplication-modal form');
        form.removeClass('was-validated');

        // Remove validation feedback from all form controls
        form.find('.form-select, .form-control, .select2-selection').each(function() {
            $(this).removeClass('is-invalid is-valid');
        });

        // Reset all the tables (Education, Organization, Internship, Job Experience)
        resetEducationTable();
        resetOrganizationTable();
        resetInternshipTable();
        resetJobExperienceTable();
    }

    // Reset Education Table
    function resetEducationTable() {
        $('#edit-education-datatable tbody').empty(); // Clear the table body
        editEducationList = []; // Clear the array holding the data
    }

    // Reset Organization Table
    function resetOrganizationTable() {
        $('#edit-organization-datatable tbody').empty(); // Clear the table body
        editOrganizationList = []; // Clear the array holding the data
    }

    // Reset Internship Table
    function resetInternshipTable() {
        $('#edit-internship-datatable tbody').empty(); // Clear the table body
        editInternshipList = []; // Clear the array holding the data
    }

    // Reset Job Experience Table
    function resetJobExperienceTable() {
        $('#edit-jobexperience-datatable tbody').empty(); // Clear the table body
        editJobExperienceList = []; // Clear the array holding the data
    }
</script>
