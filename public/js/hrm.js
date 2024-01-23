$(document).ready(function () {
    $("#month_year").datepicker({
        autoclose: true,
        format: "mm/yyyy",
        minViewMode: "months",
    });

    if ($("#payroll_assign_table").length) {
        $("#payroll_assign_table").DataTable({
            dom: 'T<"clear"><"button">lfrtip',
            bFilter: false,
            bLengthChange: false,
            paging: false

        });
    }

    __calculateTotalGrossAmount();

    $(document).on(
        "change",
        "input.allowance-amount,input.allowance-check",
        function () {
            var total = 0;
            var table = $(this).closest("table");
            table.find("tbody tr").each(function () {
                if ($(this).find("input.allowance-check").is(":checked")) {
                    var allowance = __read_number(
                        $(this).find("input.allowance-amount")
                    )
                        ? __read_number($(this).find("input.allowance-amount"))
                        : 0;
                    var subtotal = allowance;
                    total = total + subtotal;
                    $(this).find(".deduction-divider").val(0);
                }
            });
            table
                .find("span.allowance_final_total")
                .text(__currency_trans_from_en(total, true));
            __write_number($("input#allowance_final_total"), total);
            __calculateTotalGrossAmount();
        }
    );
    $(document).on("change", ".deduction-amount,.deduction-check", function () {
        var total = 0;
        var table = $(this).closest(".deduction-table");
        table.find("tbody tr").each(function () {
            if ($(this).find(".deduction-check").is(":checked")) {
                var deduction = __read_number($(this).find(".deduction-amount"))
                    ? __read_number($(this).find(".deduction-amount"))
                    : 0;
                var subtotal = deduction;
                total = total + subtotal;

                $(this).find(".deduction-divider").val(0);
            }
        });
        table
            .find("span.deduction_final_total")
            .text(__currency_trans_from_en(total, true));
        __write_number($("#deduction_final_total"), total);
        __calculateTotalGrossAmount();
    });
    $(document).on(
        "change",
        ".deduction-divider,.deduction-check",
        function () {
            var total = 0;
            var table = $(this).closest(".deduction-table");
            table.find("tbody tr").each(function () {
                if ($(this).find(".deduction-check").is(":checked")) {
                    var transaction_final_total = __read_number(
                        $("#transaction_final_total")
                    );
                    var transaction_default_allowance = __read_number(
                        $("#transaction_default_allowance")
                    );
                    var transaction_default_deduction = __read_number(
                        $("#transaction_default_deduction")
                    );
                    if (transaction_final_total > 0) {
                        var day_salary = transaction_final_total / 30;
                        var divider = $(this).find(".deduction-divider").val();

                        __write_number(
                            $(this).find(".deduction-amount"),
                            day_salary * divider
                        );
                    }
                    var deduction = __read_number(
                        $(this).find(".deduction-amount")
                    )
                        ? __read_number($(this).find(".deduction-amount"))
                        : 0;
                    var subtotal = deduction;
                    total = total + subtotal +(transaction_default_allowance-transaction_default_deduction);
                }
            });
            table
                .find("span.deduction_final_total")
                .text(__currency_trans_from_en(total, true));
            __write_number($("#deduction_final_total"), total);
            __calculateTotalGrossAmount();
        }
    );
    $(document).on(
        "change",
        ".allowance-divider,.allowance-check",
        function () {
            var total = 0;
            var table = $(this).closest(".allowance-table");
            table.find("tbody tr").each(function () {
                if ($(this).find(".allowance-check").is(":checked")) {
                    var transaction_final_total = __read_number(
                        $("#transaction_final_total")
                    );
                    if (transaction_final_total > 0) {
                        var day_salary = transaction_final_total / 30;
                        var divider = $(this).find(".allowance-divider").val();

                        __write_number(
                            $(this).find(".allowance-amount"),
                            day_salary * divider
                        );
                    }
                    var allowance = __read_number(
                        $(this).find(".allowance-amount")
                    )
                        ? __read_number($(this).find(".allowance-amount"))
                        : 0;
                    var subtotal = allowance;
                    total = total + subtotal;
                }
            });
            table
                .find("span.allowance_final_total")
                .text(__currency_trans_from_en(total, true));
            __write_number($("#allowance_final_total"), total);
            __calculateTotalGrossAmount();
        }
    );

    //employees_table
    var employees_table = $("#employees_table").DataTable({
        processing: true,
        serverSide: true,
        processing: true
        , serverSide: true,
        scrollY:        "75vh",
        scrollX:        true,
        scrollCollapse: false,
           "ajax": {
        "url": "/hrm-employee",
        "data": function ( d ) {
                if ($("#employees_list_filter_date_range").val()) {
                    var start = $("#employees_list_filter_date_range")
                        .data("daterangepicker")
                        .startDate.format("YYYY-MM-DD");
                    var end = $("#employees_list_filter_date_range")
                        .data("daterangepicker")
                        .endDate.format("YYYY-MM-DD");
                    d.start_date = start;
                    d.end_date = end;
                }

                if ($("#list_filter_campus_id").length) {
                    d.campus_id = $("#list_filter_campus_id").val();
                }
                if ($("#employees_list_filter").length) {
                    d.status = $("#employees_list_filter").val();
                }
                if ($("#employees_list_filter_employeeID").length) {
                    d.employeeID = $("#employees_list_filter_employeeID").val();
                }
                d = __datatable_ajax_callback(d);
            },
        },

        columns: [
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
            },

            {
                data: "employee_name",
                name: "employee_name",
            },
            {
                data: "father_name",
                name: "father_name",
            },
            {
                data: "status",
                name: "status",
                orderable: false,
                searchable: false,
            },
            {
                data: "employeeID",
                name: "employeeID",
            },
            {
                data: "joining_date",
                name: "joining_date",
            },
            {
                data: "campus_name",
                name: "campus_name",
                orderable: false,
                searchable: false,
            },
        ],
    });
    $(document).on("click", ".update_status", function (e) {
        e.preventDefault();
        $("#update_employee_status_form")
            .find("#status")
            .val($(this).data("status"));
        $("#update_employee_status_form")
            .find("#employee_id")
            .val($(this).data("employee_id"));
        $("#update_employee_status_modal").modal("show");
    });
    $(document).on("submit", "#update_employee_status_form", function (e) {
        e.preventDefault();
        var form = $(this);

        var data = form.serialize();

        $.ajax({
            method: "POST",
            url: $(this).attr("action"),
            dataType: "json",
            data: data,
            beforeSend: function (xhr) {
                __disable_submit_button(form.find('button[type="submit"]'));
            },
            success: function (result) {
                if (result.success == true) {
                    $("#update_employee_status_modal").modal("hide");
                    toastr.success(result.msg);
                    employees_table.ajax.reload();
                    $("#update_employee_status_form")
                        .find('button[type="submit"]')
                        .attr("disabled", false);
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });
    $(document).on("click", ".employee_resign", function (e) {
        e.preventDefault();
        $("#employee_resign_form")
            .find("#employee_name")
            .text($(this).data("employee-name"));
        $("#employee_resign_form")
            .find("#employee_id")
            .val($(this).data("employee_id"));
        $("#employee_resign_modal").modal("show");
    });

    $(document).on("submit", "#employee_resign_form", function (e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
            method: "POST",
            url: $(this).attr("action"),
            data: new FormData(this),
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,

            beforeSend: function (xhr) {
                __disable_submit_button(form.find('button[type="submit"]'));
            },
            success: function (result) {
                if (result.success == true) {
                    $("#employee_resign_modal").modal("hide");
                    toastr.success(result.msg);
                    employees_table.ajax.reload();
                    $("#employee_resign_form")
                        .find('button[type="submit"]')
                        .attr("disabled", false);
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });

    $(document).on(
        "change",
        "#list_filter_campus_id,#employees_list_filter",
        function () {
            employees_table.ajax.reload();
        }
    );
    $(document).on(
        "keyup",
        "#employees_list_filter_admission_no,#employees_list_filter_employeeID",
        function () {
            employees_table.ajax.reload();
        }
    );



    ///payroll_transaction_table
    
    $(document).on('click', '.delete_payment', function(e) {
        swal({
            title: LANG.sure
            , text: LANG.confirm_delete_payment
            , icon: 'warning'
            , buttons: true
            , dangerMode: true
        , }).then(willDelete => {
            if (willDelete) {
                $.ajax({
                    url: $(this).data('href')
                    , method: 'delete'
                    , dataType: 'json'
                    , success: function(result) {
                        if (result.success === true) {
                            $('div.payment_modal').modal('hide');
                            $('div.edit_payment_modal').modal('hide');
                            toastr.success(result.msg);
                            payroll_transaction_table.ajax.reload();

                        } else {
                            toastr.error(result.msg);
                        }
                    }
                , });
            }
        });
    });

    $(document).on('click', '.edit_payment', function(e) {
        e.preventDefault();
        var container = $('.edit_payment_modal');

        $.ajax({
            url: $(this).data('href')
            , dataType: 'html'
            , success: function(result) {
                container.html(result).modal('show');
                __currency_convert_recursively(container);
                $('#datetimepicker').datetimepicker({
                    format: moment_date_format + ' ' + moment_time_format
                    , ignoreReadonly: true
                , });
                $('div.payment_modal').modal('hide');

                container.find('form#transaction_payment_add_form').validate();
            }
        , });
    });
    $(document).on('click', '.add_payment_modal', function(e) {
        e.preventDefault();
        var container = $('.payment_modal');

        $.ajax({
            url: $(this).attr('href')
            , dataType: 'json'
            , success: function(result) {
                if (result.status == 'due') {
                    container.html(result.view).modal('show');
                    __currency_convert_recursively(container);
                    $('#datetimepicker').datetimepicker({
                        format: moment_date_format + ' ' + moment_time_format
                        , ignoreReadonly: true
                    , });
                    container.find('form#transaction_payment_add_form').validate();
                    $('.payment_modal')
                        .find('input[type="checkbox"].input-icheck')
                        .each(function() {
                            $(this).iCheck({
                                checkboxClass: 'icheckbox_square-blue'
                                , radioClass: 'iradio_square-blue'
                            , });
                        });
                } else {
                    toastr.error(result.msg);
                }
            }
        , });
    });
    $(document).on("submit", "form#transaction_payment_add_form", function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = new FormData(this);

        $.ajax({
            method: "POST"
            , url: $(this).attr("action")
            , dataType: "json"
            , data: formData
            , cache: false
            , contentType: false
            , processData: false
            , beforeSend: function(xhr) {
                __disable_submit_button(form.find('button[type="submit"]'));
            }
            , success: function(result) {
                if (result.success == true) {
                    $("div.payment_modal").modal("hide");
                    toastr.success(result.msg);
                    payroll_transaction_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            }
        , });
    });
    //payroll_transaction_table
    var payroll_transaction_table = $("#payroll_transaction_table").DataTable({
        processing: true
        , serverSide: true
        , scrollY: "75vh"
        , scrollX: true
        , scrollCollapse: false
        , "ajax": {
            "url": "/hrm-payroll"
            , "data": function(d) {

                if ($('#campus_id').length) {
                    d.campus_id = $('#campus_id').val();
                }
                if ($('#payment_status').length) {
                    d.payment_status = $('#payment_status').val();
                }
                if ($("#list_filter_date_range").val()) {
                    var start = $("#list_filter_date_range")
                        .data("daterangepicker")
                        .startDate.format("YYYY-MM-DD");
                    var end = $("#list_filter_date_range")
                        .data("daterangepicker")
                        .endDate.format("YYYY-MM-DD");
                    d.start_date = start;
                    d.end_date = end;
                }

                d = __datatable_ajax_callback(d);
            }
        },


        columns: [{
                data: "action"
                , name: "action"
                , orderable: false
                , "searchable": false
            }

            , {
                data: "month"
                , name: "month"
                  , orderable: false
                , "searchable": false
            }, {
                data: "transaction_date"
                , name: "transaction_date"
               
            }, {
                data: "ref_no"
                , name: "ref_no"
            }, {
                data: "campus_name"
                , name: "campus_name"
                , orderable: false
                , "searchable": false
            }
            , {
                data: "employee_name"
                , name: "employee_name",

            }, {
                data: "payment_status"
                , name: "payment_status"
                , orderable: false
                , "searchable": false
            }, {
                data: "final_total"
                , name: "final_total"
                , orderable: false
                , "searchable": false
            }, {
                data: "total_paid"
                , name: "total_paid"
                , orderable: false
                , "searchable": false
            }, {
                data: "total_remaining"
                , name: "total_remaining"
                , orderable: false
                , "searchable": false
            }, {
                data: "father_name"
                , name: "father_name",

            }, {
                data: "status"
                , name: "status"
                , orderable: false
                , "searchable": false
            }, {
                data: "employeeID"
                , name: "employeeID",

            }
        , ],

    });

    //Delete Sale
    $(document).on('click', '.delete-hrm_transaction', function(e) {
        e.preventDefault();
        swal({
            title: LANG.sure
            , icon: 'warning'
            , buttons: true
            , dangerMode: true
        , }).then(willDelete => {
            if (willDelete) {
                var href = $(this).attr('href');
                var is_suspended = $(this).hasClass('is_suspended');
                $.ajax({
                    method: 'DELETE'
                    , url: href
                    , dataType: 'json'
                    , success: function(result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            payroll_transaction_table.ajax.reload();

                        } else {
                            toastr.error(result.msg);
                        }
                    }
                , });
            }
        });
    });
    $(document).on('change', '#campus_id,#payment_status,#list_filter_date_range', function() {
        payroll_transaction_table.ajax.reload();
    });

});
