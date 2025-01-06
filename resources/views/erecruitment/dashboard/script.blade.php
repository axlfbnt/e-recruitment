<script>
    $(document).ready(function() {
        $.ajax({
            url: '/get-manpower-summary',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.length > 0) {
                    const summary = data[0];

                    $('#total-request').text(summary.total_manpower_request || 0);
                    $('#hold-cancel').text(summary.hold_cancel || 0);
                    $('#on-process').text(summary.on_process || 0);
                    $('#on-confirmation').text(summary.on_confirmation || 0);
                    $('#manpower-approved').text(summary.total_manpower_approved || 0);
                } else {
                    console.error("No data received");
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching manpower summary:', error);
            }
        });

        $.ajax({
            url: '/get-manpower-approved-division',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const categories = [];
                const data = [];

                response.forEach(item => {
                    let divisionName = item.division_name;

                    divisionName = divisionName.replace(/Corporate/g, 'Corp.');
                    divisionName = divisionName.replace(/Division/g, 'Div.');

                    categories.push(divisionName);
                    data.push(item.total_man_power);
                });

                const options = {
                    chart: {
                        type: 'bar',
                        height: 350
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true
                        }
                    },
                    series: [{
                        name: 'Total Manpower',
                        data: data
                    }],
                    xaxis: {
                        categories: categories,
                        title: {
                            text: 'Total Manpower'
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Division'
                        }
                    },
                    colors: ['#39afd1'],
                    title: {
                        align: 'center'
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function(val) {
                            return val.toString();
                        }
                    },
                    tooltip: {
                        enabled: true,
                        y: {
                            formatter: function(val) {
                                return val + ' Manpower';
                            }
                        },
                        x: {
                            formatter: function(val, opts) {
                                return categories[opts.dataPointIndex];
                            }
                        }
                    }
                };

                const chart = new ApexCharts(document.querySelector("#basic-bar"), options);
                chart.render();
            },
            error: function(xhr, status, error) {
                console.error('Error fetching manpower approved by division:', error);
            }
        });

        $.ajax({
            url: '/get-applicant-process-summary',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.length > 0) {
                    const data = response[0];

                    const categories = [
                        'Psychological Test',
                        'Interview HC',
                        'Interview User',
                        'Interview BOD',
                        'Offering Letter',
                        'Medical Check-Up',
                        'Closed'
                    ];

                    const seriesData = [
                        parseInt(data.psikotes_count || 0, 10),
                        parseInt(data.interview_hc_count || 0, 10),
                        parseInt(data.interview_user_count || 0, 10),
                        parseInt(data.interview_bod_count || 0, 10),
                        parseInt(data.offering_letter_count || 0, 10),
                        parseInt(data.mcu_count || 0, 10),
                        parseInt(data.closed_count || 0, 10)
                    ];

                    const options = {
                        chart: {
                            type: 'bar',
                            height: 350
                        },
                        series: [{
                            name: 'Applicants',
                            data: seriesData
                        }],
                        xaxis: {
                            categories: categories,
                            title: {
                                text: 'Recruitment Stages'
                            }
                        },
                        yaxis: {
                            title: {
                                text: 'Number of Applicants'
                            }
                        },
                        colors: ['#4254ba'],
                        title: {
                            align: 'center'
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: function(val) {
                                return val.toString();
                            }
                        }
                    };

                    const chart = new ApexCharts(document.querySelector("#basic-column"), options);
                    chart.render();
                } else {
                    console.error("No data found in the response");
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching applicant process summary:', error);
            }
        });

        $.ajax({
            url: '/get-achievement-division',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.length > 0) {
                    function abbreviateDivisionName(name) {
                        return name
                            .replace(/Corporate/gi, 'Corp.')
                            .replace(/Division/gi, 'Div.')
                            .replace(/Engineering/gi, 'Eng.')
                            .replace(/Research and Development/gi,
                                'RnD')
                            .replace(/Production Plant (\d+)/gi,
                                'Prod. Plant $1')
                            .replace(/Human Capital/gi, 'HC')
                            .replace(/Strategy/gi, 'St.')
                            .replace(/Digitalization/gi,
                                'Dg.')
                            .replace(/Information System/gi,
                                'IS.')
                            .replace(/Supply Chain Management/gi,
                                'SCM')
                            .replace(/SDIS/gi, 'SDIS')
                            .replace(/FAP/gi, 'FAP')
                            .trim();
                    }

                    const seriesData = response.map(item => {
                        const fulfilled = parseInt(item.total_fulfilled || 0);
                        const request = parseInt(item.total_requests || 0);
                        const progress = request > 0 ? (fulfilled / request) * 100 : 0;
                        return progress;
                    });
                    const labels = response.map(item => abbreviateDivisionName(item.division_name));

                    const options = {
                        chart: {
                            height: 350,
                            type: 'radialBar'
                        },
                        series: seriesData,
                        labels: labels,
                        plotOptions: {
                            radialBar: {
                                hollow: {
                                    size: '50%'
                                },
                                dataLabels: {
                                    name: {
                                        offsetY: -10
                                    },
                                    value: {
                                        offsetY: 5,
                                        formatter: function(val) {
                                            return `${Math.round(val)}%`;
                                        }
                                    },
                                }
                            }
                        },
                        colors: ['#17a497', '#fa5c7c', '#4254ba',
                            '#ffbc00'
                        ],
                        legend: {
                            show: true,
                            position: 'right',
                            formatter: function(seriesName, opts) {
                                const index = opts.seriesIndex;
                                const fulfilled = parseInt(response[index]
                                    .total_fulfilled || 0);
                                const request = parseInt(response[index].total_requests ||
                                    0);
                                return `${seriesName} : ${fulfilled}/${request}`;
                            }
                        },
                        title: {
                            align: 'center'
                        }
                    };

                    const chart = new ApexCharts(document.querySelector("#circle-angle-radial"),
                        options);
                    chart.render();
                } else {
                    console.error('No data available in the response');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching achievement data:', error);
            }
        });

        $.ajax({
            url: '/get-manpower-source',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.length > 0) {
                    // Extract data for the chart
                    const categories = response.map(item => item.source_type); // Source types
                    const seriesData = response.map(item => parseInt(item.total_manpower ||
                    0)); // Total manpower

                    // Chart options
                    const options = {
                        chart: {
                            height: 350,
                            type: 'polarArea'
                        },
                        series: seriesData,
                        labels: categories,
                        colors: ['#4254ba', '#17a497', '#fa5c7c', '#ffbc00',
                            '#39afd1'
                        ],
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            text: 'Man Power by Source',
                            align: 'center'
                        },
                        fill: {
                            opacity: 0.85
                        },
                        stroke: {
                            width: 1,
                            colors: ['#fff']
                        },
                        responsive: [{
                            breakpoint: 480,
                            options: {
                                chart: {
                                    height: 320
                                },
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }]
                    };

                    // Render the chart
                    const chart = new ApexCharts(document.querySelector("#basic-polar-area"),
                        options);
                    chart.render();
                } else {
                    console.error('No data available for the chart');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching manpower source data:', error);
            }
        });
    });
</script>
