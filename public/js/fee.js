$(document).ready(function() {

    //students_table
    var students_table = $("#students_table").DataTable({
        processing: true,
        serverSide: true,

        dom: 'T<"clear"><"button">lfrtip',
        bFilter: false,
        bLengthChange: false,
        scrollY: "20vh",
        scrollX: true,
        scrollCollapse: false,
        paging: false,
        "ajax": {
            "url": "/fee-collection/create",
            "data": function(d) {

                if ($('#students_list_filter_campus_id').length) {
                    d.campus_id = $('#students_list_filter_campus_id').val();
                }
                if ($('#students_list_filter_status').length) {
                    d.status = $('#students_list_filter_status').val();
                }
                if ($('#students_list_filter_class_id').length) {
                    d.class_id = $('#students_list_filter_class_id').val();
                }
                if ($('#students_list_filter_class_section_id').length) {
                    d.class_section_id = $('#students_list_filter_class_section_id').val();
                }
                if ($('#students_list_filter_father_name').length) {
                    d.father_name = $('#students_list_filter_father_name').val();
                }
                if ($('#students_list_filter_student_name').length) {
                    d.student_name = $('#students_list_filter_student_name').val();
                }
                if ($('#students_list_filter_roll_no').length) {
                    d.roll_no = $('#students_list_filter_roll_no').val();
                }
                d = __datatable_ajax_callback(d);
            }
        }

        ,
        columns: [{
            data: "roll_no",
            name: "roll_no"
        }, {
            data: "student_name",
            name: "student_name"
        }, {
            data: "father_name",
            name: "father_name"
        }, {
            data: "current_class",
            name: "current_class"
        }, {
            data: "current_section",
            name: "current_section"
        }, {
            data: 'student_tuition_fee',
            name: 'student_tuition_fee'
        }, {
            data: 'student_transport_fee',
            name: 'student_transport_fee'
        }, ],
        fnDrawCallback: function(oSettings) {
            __currency_convert_recursively($('#students_table'));
        },

    });
    $(document).on('change', '#students_list_filter_campus_id,#students_list_filter_class_id,#students_list_filter_class_section_id,#students_list_filter_status', function() {
        students_table.ajax.reload();
    });
    $(document).on('change', '#month_id,#session_id', function() {
        if ($.trim($('#current-class').val()) != '') {
            $('.fee-heads-details').remove();


            $('table.ajax_get tbody').find("tr:first").trigger("click");
        }
    });
    $(document).on('keyup', '#students_list_filter_roll_no', function() {
        $('#students_list_filter_father_name').val('');
        $('#students_list_filter_student_name').val('');
        $('#current-class').val('');
    });
    $(document).on('keyup', '#students_list_filter_father_name,#students_list_filter_student_name,#students_list_filter_roll_no', function() {
        if ($.trim($('#current-class').val()) == '') {
            students_table.ajax.reload();
            var $focused = $(':focus');
        }

    })

    $(document).on('click', 'table.ajax_get tbody tr', function(e) {

        if (!$(e.target).is('td.selectable_td input[type=checkbox]') &&
            !$(e.target).is('td.selectable_td') &&
            !$(e.target).is('td.clickable_td') &&
            !$(e.target).is('a') &&
            !$(e.target).is('button') &&
            !$(e.target).hasClass('label') &&
            !$(e.target).is('li') &&
            $(this).data('href') &&
            !$(e.target).is('i')
        ) {

            var month_id = $('#month_id').val();
            var session_id = $('#session_id').val();
            $.ajax({
                url: $(this).data('href'),
                data: {
                    month_id: month_id,
                    session_id: session_id
                },
                dataType: 'json',
                success: function(result) {
                    if (result.success) {
                        $('.fee-heads-details').remove();

                        $('#students_list_filter_roll_no').val(result.data.student_details.roll_no);
                        $('#students_list_filter_student_name').val(result.data.student_details.student_name);
                        $('#students_list_filter_father_name').val(result.data.student_details.father_name);
                        $('#current-class').val(result.data.student_details.current_class + ' ' + result.data.student_details.current_class_section);
                        __write_number($('#tuition-fee'), result.data.student_details.student_tuition_fee);
                        __write_number($('#transport-fee'), result.data.student_details.student_transport_fee);
                        if (result.data.fee_transaction != null) {
                            $('#voucher_no').val(result.data.fee_transaction.voucher_no);
                        }
                        $(".student_image").attr("src", base_path + '/uploads/student_image/' + result.data.student_details.student_image);

                        $('.fee-heads')
                            .append(result.html_content);
                        $('#datetimepicker').datetimepicker({
                            format: moment_date_format + ' ' + moment_time_format,

                        });
                        $('#other_datetimepicker').datetimepicker({
                            format: moment_date_format + ' ' + moment_time_format,

                        });
                        $('input.amount').focus();
                    } else {
                        toastr.error(result.msg);
                        $('input#search_student')
                            .focus()
                            .select();
                    }
                },
            });
        }
    });
    $('body').on('keydown', 'input, select ,.select2-input', function(e) {
        if (e.key === "Enter") {
            verify = $('table.ajax_get tbody').find("tr:first").find("td:eq(1)").text();
            if ($.trim($('#current-class').val()) != '') {
                var self = $(this),
                    form = self.parents('form:eq(0)'),
                    focusable, next;
                focusable = form.find('.tabkey').filter(':visible');
                next = focusable.eq(focusable.index(this) + 1);
                if (next.length) {
                    next.focus();
                } else {
                    form.submit();
                }
            } else {
                if (verify === '') {

                } else {
                    $('.fee-heads-details').remove();
                    $('table.ajax_get tbody').find("tr:first").trigger("click");
                }
            }

            return false;
        }

    });

    $(document).on('click', '.delete_payment', function(e) {
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_payment,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                $.ajax({
                    url: $(this).data('href'),
                    method: 'delete',
                    dataType: 'json',
                    success: function(result) {
                        if (result.success === true) {
                            $("div.payment_modal").modal("hide");
                            $("div.edit_payment_modal").modal("hide");
                            toastr.success(result.msg);
                            fee_transaction_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                        // if (result.success === true) {
                        //     toastr.success(result.msg);
                        //     $('div.payment_modal').modal('hide');
                        //     $('div.edit_payment_modal').modal('hide');
                            
                        //     $('.fee-heads-details').remove();
                        //     $('table.ajax_get tbody').find("tr:first").trigger("click");
                        //     $(".student_image").attr("src", base_path + '/uploads/student_image/default.png');


                        // } else {
                        //     toastr.error(result.msg);
                        // }
                    },
                });
            }
        });
    });
    $(document).on('click', '.edit_payment', function(e) {
        alert(55);
        e.preventDefault();
        var container = $('.edit_payment_modal');

        $.ajax({
            url: $(this).data('href'),
            dataType: 'html',
            success: function(result) {
                container.html(result).modal('show');
                __currency_convert_recursively(container);
                $('#datetimepicker').datetimepicker({
                    format: moment_date_format + ' ' + moment_time_format,
                    ignoreReadonly: true,
                });
                $('div.payment_modal').modal('hide');

                container.find('form#transaction_payment_add_form').validate();
            },
        });
    });
    $(document).on('change', 'input.head-amount,input.fee-head-check', function() {
        var total = 0;
        var table = $(this).closest('table');
        var transaction_discount_amount = __number_uf($('input.transaction_discount_amount').val()) ? __number_uf($('input.transaction_discount_amount').val()) : 0;

        table.find('tbody tr').each(function() {
            if ($(this).find('input.fee-head-check').is(':checked')) {
                var denomination = __number_uf($(this).find('input.head-amount').val()) ? __number_uf($(this)
                    .find('input.head-amount').val()) : 0;

                var subtotal = denomination;
                total = total + subtotal;
            }
        });

        table.find('span.final_total').text(__currency_trans_from_en(total - transaction_discount_amount, true));
        $('input#final_total').val(total - transaction_discount_amount);
        $('input.before_discount_total').val(total);

    });




    $(document).on('submit', 'form#student_transaction_due_form', function(e) {
        e.preventDefault();
        var form = $(this);
        var data = form.serialize();
        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            beforeSend: function(xhr) {
                __disable_submit_button(form.find('button[type="submit"]'));
            },
            success: function(result) {
                if (result.success == true) {
                    toastr.success(result.msg);
                    $('#student_due_form')
                        .find('button[type="submit"]')
                        .attr('disabled', false);
                    $('.fee-heads-details').remove();
                    form.trigger("reset");
                    $(".student_image").attr("src", base_path + '/uploads/student_image/default.png');

                    //$("#month_id").trigger( "change" );
                    $('select').each(function() {
                        $(this).trigger("change");
                    });
                    $('#students_list_filter_roll_no').focus();

                } else {
                    toastr.error(result.msg);
                    __enable_submit_button(form.find('button[type="submit"]'));
                }
            },
        });
    });



    ////all transaction
    $(document).on('click', '.delete_payment', function(e) {
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_payment,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                $.ajax({
                    url: $(this).data('href'),
                    method: 'delete',
                    dataType: 'json',
                    success: function(result) {
                        if (result.success === true) {
                            $('div.payment_modal').modal('hide');
                            $('div.edit_payment_modal').modal('hide');
                            toastr.success(result.msg);
                            fee_transaction_table.ajax.reload();

                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    $(document).on('click', '.edit_payment', function(e) {
        e.preventDefault();
        var container = $('.edit_payment_modal');

        $.ajax({
            url: $(this).data('href'),
            dataType: 'html',
            success: function(result) {
                container.html(result).modal('show');
                __currency_convert_recursively(container);
                $('#datetimepicker').datetimepicker({
                    format: moment_date_format + ' ' + moment_time_format,
                    ignoreReadonly: true,
                });
                $('div.payment_modal').modal('hide');

                container.find('form#transaction_payment_add_form').validate();
            },
        });
    });
    $(document).on('click', '.add_payment_modal', function(e) {
        e.preventDefault();
        var container = $('.payment_modal');

        $.ajax({
            url: $(this).attr('href'),
            dataType: 'json',
            success: function(result) {
                if (result.status == 'due') {
                    container.html(result.view).modal('show');
                    __currency_convert_recursively(container);
                    $('#datetimepicker').datetimepicker({
                        format: moment_date_format + ' ' + moment_time_format,
                        ignoreReadonly: true,
                    });
                    container.find('form#transaction_payment_add_form').validate();
                    $('.payment_modal')
                        .find('input[type="checkbox"].input-icheck')
                        .each(function() {
                            $(this).iCheck({
                                checkboxClass: 'icheckbox_square-blue',
                                radioClass: 'iradio_square-blue',
                            });
                        });
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });
    //fee_transaction_table
    var fee_transaction_table = $("#fee_transaction_table").DataTable({
        processing: true,
        serverSide: true,
        "ajax": {
            "url": "/fee-allocation",
            "data": function(d) {

                if ($('#campus_id').length) {
                    d.campus_id = $('#campus_id').val();
                }
                if ($('#payment_status').length) {
                    d.payment_status = $('#payment_status').val();
                }
                // if ($('#session_id').length) {
                //     d.session_id = $('#session_id').val();
                // }
                if ($('#transaction_type').length) {
                    d.transaction_type = $('#transaction_type').val();
                }
                var start = "";
                var end = "";
                if ($("#list_filter_date_range").val()) {
                    start = $("input#list_filter_date_range")
                        .data("daterangepicker")
                        .startDate.format("YYYY-MM-DD");
                    end = $("input#list_filter_date_range")
                        .data("daterangepicker")
                        .endDate.format("YYYY-MM-DD");
                }
                d.start_date = start;
                d.end_date = end;

            }
        },

        columns: [{
                data: "action",
                name: "action",
                orderable: false,
                "searchable": false
            }
            ,
            //  {
            //     data: "session_info",
            //     name: "session_info",
            //     orderable: false,
            //     "searchable": false
            // },

            {
                data: "month",
                name: "month",
                orderable: false,
                "searchable": false
            },
            {
                data: "transaction_date",
                name: "transaction_date",
                orderable: false,
                "searchable": false
            },
            {
                data: "transaction_class",
                name: "transaction_class",
                orderable: false,
                "searchable": false
            }, {
                data: "voucher_no",
                name: "voucher_no"
            },
            {
                data: "roll_no",
                name: "roll_no"

            },
            {
                data: "student_name",
                name: "student_name",

            },
            {
                data: "father_name",
                name: "father_name",

            },
            {
                data: "campus_name",
                name: "campus_name",
                orderable: false,
                "searchable": false
            },
            {
                data: "current_class",
                name: "current_class",
                orderable: false,
                "searchable": false
            },
            {
                data: "payment_status",
                name: "payment_status",
                orderable: false,
                "searchable": false
            }, {
                data: "before_discount_total",
                name: "before_discount_total",
                orderable: false,
                "searchable": false
            }, {
                data: "discount_amount",
                name: "discount_amount",
                orderable: false,
                "searchable": false
            }, {
                data: "final_total",
                name: "final_total",
                orderable: false,
                "searchable": false
            }, {
                data: "total_paid",
                name: "total_paid",
                orderable: false,
                "searchable": false
            }, {
                data: "total_remaining",
                name: "total_remaining",
                orderable: false,
                "searchable": false
            }, 
            // {
            //     data: "status",
            //     name: "status",
            //     orderable: false,
            //     "searchable": false
            // },
        ],
        fnDrawCallback: function(oSettings) {
            __currency_convert_recursively($("#fee_transaction_table"));
        },
        footerCallback: function(row, data, start, end, display) {
            var total_final_total = 0;
            var total_paid = 0;
            var total_remaining = 0;
            var before_discount_total= 0;
            var discount_amount= 0;
            
            for (var r in data) {
                total_final_total += $(
                        data[r].final_total
                    ).data("orig-value") ?
                    parseFloat(
                        $(data[r].final_total).data("orig-value")
                    ) :
                    0;
                total_paid += $(
                        data[r].total_paid
                    ).data("orig-value") ?
                    parseFloat(
                        $(data[r].total_paid).data("orig-value")
                    ) :
                    0;
                total_remaining += $(data[r].total_remaining).data("orig-value") ?
                    parseFloat($(data[r].total_remaining).data("orig-value")) :
                    0;
                before_discount_total += $(data[r].before_discount_total).data("orig-value") ?
                    parseFloat($(data[r].before_discount_total).data("orig-value")) :
                    0;
                discount_amount += $(data[r].discount_amount).data("orig-value") ?
                    parseFloat($(data[r].discount_amount).data("orig-value")) :
                    0;
                
            }
            $(".footer_final_total").html(
                __currency_trans_from_en(total_final_total)
            );
            $(".footer_before_discount_total").html(
                __currency_trans_from_en(before_discount_total)
            );
            $(".footer_discount_amount").html(
                __currency_trans_from_en(discount_amount)
            );
            $(".footer_total_paid").html(
                __currency_trans_from_en(total_paid)
            );
            $(".footer_total_remaining").html(__currency_trans_from_en(total_remaining));
        },
    });
    //Delete Sale
    $(document).on('click', '.delete-fee_transaction', function(e) {
        e.preventDefault();
        swal({
            title: LANG.sure,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).attr('href');
                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    success: function(result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            fee_transaction_table.ajax.reload();

                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });
    $(document).on('change', '#campus_id,#payment_status,#list_filter_date_range,#transaction_type', function() {
        fee_transaction_table.ajax.reload();
    });
    
    $(document).on("click", ".pay_delete_payment", function (e) {
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_payment,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: $(this).data("href"),
                    method: "delete",
                    dataType: "json",
                    success: function (result) {
                        if (result.success === true) {
                            toastr.success(result.msg);
                            $(".fee-heads-details").remove();
                            $("table.ajax_get tbody")
                                .find("tr:first")
                                .trigger("click");
                            $(".student_image").attr(
                                "src",
                                base_path + "/uploads/student_image/default.png"
                            );
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });
});