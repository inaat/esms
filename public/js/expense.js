$(document).ready(function() {

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
                            expenses_transaction_table.ajax.reload();

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
    //expenses_transaction_table
    var expenses_transaction_table = $("#expenses_transaction_table").DataTable({
        processing: true
        , serverSide: true
        , scrollY: "75vh"
        , scrollX: true
        , scrollCollapse: false
        , "ajax": {
            "url": "/expenses"
            , "data": function(d) {

                if ($('#campus_id').length) {
                    d.campus_id = $('#campus_id').val();
                }
                if ($('#payment_status').length) {
                    d.payment_status = $('#payment_status').val();
                }
                if ($('#vehicle_id').length) {
                    d.vehicle_id = $('#vehicle_id').val();
                }
                if ($('#expense_category_id').length) {
                    d.expense_category_id = $('#expense_category_id').val();
                }
                 var start = '';
                 var end = '';
               if($('#list_filter_date_range').val()){
                   start = $('input#list_filter_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                 end = $('input#list_filter_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                    }
               d.start_date = start;
               d.end_date = end;

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
                data: "ref_no"
                , name: "ref_no"
            }, {
                data: "campus_name"
                , name: "campus_name"
                , orderable: false
                , "searchable": false
            }
            , {
                data: "category_name"
                , name: "category_name",

            }
            , {
                data: "additional_notes"
                , name: "additional_notes",

            }
            , {
                data: "vehicle_name"
                , name: "vehicle_name",

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
            }, 
        ],

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
                            expenses_transaction_table.ajax.reload();

                        } else {
                            toastr.error(result.msg);
                        }
                    }
                , });
            }
        });
    });
    $(document).on('change', '#campus_id,#vehicle_id,#expense_category_id,#payment_status,#list_filter_date_range', function() {
        expenses_transaction_table.ajax.reload();
    });

});