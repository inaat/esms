$(document).ready(function () {
    if ($("#dashboard_date_filter").length == 1) {
        dateRangeSettings.startDate = moment();
        dateRangeSettings.endDate = moment();
        $("#dashboard_date_filter").daterangepicker(
            dateRangeSettings,
            function (start, end) {
                $("#dashboard_date_filter span").html(
                    start.format(moment_date_format) +
                        " ~ " +
                        end.format(moment_date_format)
                );
                update_statistics(
                    start.format("YYYY-MM-DD"),
                    end.format("YYYY-MM-DD")
                );
            }
        );

        update_statistics(
            moment().format("YYYY-MM-DD"),
            moment().format("YYYY-MM-DD")
        );
    }
    $("#campus,dashboard_date_filter").change(function (e) {
        var start = $("#dashboard_date_filter")
            .data("daterangepicker")
            .startDate.format("YYYY-MM-DD");

        var end = $("#dashboard_date_filter")
            .data("daterangepicker")
            .endDate.format("YYYY-MM-DD");

        update_statistics(start, end);
    });
});

function update_statistics(start, end) {
    var campus_id = "";
    if ($("#campus").length > 0) {
        campus_id = $("#campus").val();
    }
    var data = { start: start, end: end, campus_id: campus_id };
   
    var loader =
        '<i class="widgets-icons  bg-light-success text-success fas fa-sync fa-spin fa-fw margin-bottom"></i>';
    $(".loader").html(loader);

    $.ajax({
        method: "get",
        url: "/home/get-totals",
        dataType: "json",
        data: data,
        success: function (data) {
            
            $(".active_students").html(
                data.active_students
            );
            $(".inactive_students").html(
                data.inactive_students
            );
            $(".pass_out_students").html(
                data.pass_out_students
            );
            $(".struck_up_students").html(
                data.struck_up_students
            );
            $(".took_slc_students").html(
                data.took_slc_students
            );
            $(".active_employees").html(
                data.active_employees
            );
            $(".resign_employees").html(
                data.resign_employees
            );
            $(".total_due_amount").html(
                __currency_trans_from_en(data.total_dues_this_month, true)
            );
            $(".total_paid_amount").html(
                __currency_trans_from_en(data.total_paid_amount, true)
            );
            $(".total_fee_balance_amount").html(
                __currency_trans_from_en(data.total_dues_this_month-data.total_paid_amount, true)
            );
            $(".total_progress_collection_during_month").html(
                __currency_trans_from_en(data.total_progress_collection_during_month, true)
            );
            $(".total_profit_amount").html(
                __currency_trans_from_en(data.total_progress_collection_during_month-data.total_hrm_paid_amount, true)
            );
            $(".total_transport_dues_this_month").html(
                __currency_trans_from_en(data.total_transport_dues_this_month, true)
            );
            $(".total_transport_paid_amount").html(
                __currency_trans_from_en(data.total_transport_paid_amount, true)
            );
            $(".total_transport_progressive_amount").html(
                __currency_trans_from_en(data.total_transport_progressive_amount, true)
            );
            $(".total_transport_balance").html(
                __currency_trans_from_en(data.total_transport_dues_this_month-data.total_transport_progressive_amount, true)
            );
            $(".total_hrm_paid_amount").html(
                __currency_trans_from_en(data.total_hrm_paid_amount, true)
            );
            $(".total_expenses").html(
                __currency_trans_from_en(data.total_expense, true)
            );
            $(".total_employee_present").html(
               data.total_employee_attendance 
            );
            $(".total_student_present").html(
               data.total_student_attendance 

            );
            var labels_fee = [];
            stringArr=data.labels;
            stringArr.forEach(function(element) {
                labels_fee.push(element);
            });
            var chartData_fee = [];
            floatArr=data.all_sell_values;
            floatArr.forEach(function(element) {
                //labels.push(element);
                chartData_fee.push(element);

            });
            // var labels_expense = [];
            // expenseArr=data.labels_expenses;
            // expenseArr.forEach(function(element) {
            //     labels_expense.push(element);
            // });
            var chartData_expense = [];
            expenseArr=data.all_expenses_values;
            expenseArr.forEach(function(element) {
                //labels.push(element);
                chartData_expense.push(element);

            });
            //hrm
            var chartData_hrm = [];
            hrmArr=data.all_hrm_values;
            hrmArr.forEach(function(element) {
                //labels.push(element);
                chartData_hrm.push(element);

            });
            barChart(labels_fee, chartData_fee,chartData_expense,chartData_hrm);
            pieChart(
                data.students_gender.male_perc,
                data.students_gender.female_perc,
                data.students_gender.other_perc
            );
        },
    });
}

function pieChart(data1, data2,data3) {
    
    Highcharts.chart("pieChart", {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: "pie",
            styledMode: true,
        },
        credits: {
            enabled: false,
        },
        exporting: {
            buttons: {
                contextButton: {
                    enabled: true,
                },
            },
        },
        title: {
            text: "Students Gender",
        },
        tooltip: {
            pointFormat: "{series.name}: <b>{point.percentage:.1f}%</b>",
        },
        accessibility: {
            point: {
                valueSuffix: "%",
            },
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: "pointer",
                dataLabels: {
                    enabled: true,
                    format: "<b>{point.name}</b>: {point.percentage:.1f} %",
                },
            },
        },
        series: [
            {
                name: "Students",
                colorByPoint: true,
                data: [
                    {
                        name: "Male Students",
                        y: parseFloat(data1),
                        sliced: true,
                        selected: true,
                    },
                    {
                        name: "Female Students",
                        y: parseFloat(data2),
                    },
                    {
                        name: "Others Students",
                        y: parseFloat(data3),
                    },
                ],
            },
        ],
    });
}
function barChart(labels_fee ,chartData_fee,chartData_expense,chartData_hrm) {
    Highcharts.chart('chart5', {
        chart: {
            type: 'line',
            styledMode: true

        },
        credits: {
            enabled: false,
        },
        exporting: {
            buttons: {
                contextButton: {
                    enabled: true,
                },
            },
        },
        title: {
            text: 'Fees Collection & Expenses , Hrm Last 30 days'
        },
        
        legend:{
            align:'right',
            verticalAlign:top,
            floating:true,
            layout:'vertical'
        },
        xAxis: {
            categories: labels_fee,
            crosshair: true
        },
        
        yAxis: {
			title: {
				text: 'Total Amount In (PKR)'
			},
			labels: {
				formatter: function () {
					return this.value / 1000 + 'k';
				}
			}
		},
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: Rs&nbsp {series.data.value} </td>' +
                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
       

    series: [{
        name: 'Fee Paid Amount',
        data: chartData_fee

    }, {
        name: 'Expenses Paid Amount',
        data: chartData_expense

    },{
        name :'Hrm Paid Amount',
        data:chartData_hrm
    }

]
    });
}
