$(document).ready(function() {

    $(document).on('click', '#print_invoice', function() {
        $('.print_area').printThis();
    });
      //Date picker
      $('#trial_bal_end_date').datepicker({
        autoclose: true
        , format: datepicker_date_format
    });
  

    $('#trial_bal_end_date').change(function() {
        update_trial_balance();
        $('#trial_bal_hidden_date').text($(this).val());
    });
    $('#trial_bal_campus_id').change(function() {
        update_trial_balance();
    });
    //Date picker
    
    $('#end_date').datepicker({
        autoclose: true
        , format: datepicker_date_format
    });
    

    $('#end_date').change(function() {
        update_balance_sheet();
        $('#hidden_date').text($(this).val());
    });
    $('#bal_sheet_campus_id').change(function() {
        update_balance_sheet();
    });
    if ($("#trial_bal_end_date").length) {
        update_trial_balance();
    }
    if ($("#end_date").length) {
        update_balance_sheet();
    }
    $(document).on('click', 'button.close_account', function() {
        swal({
            title: LANG.sure
            , icon: "warning"
            , buttons: true
            , dangerMode: true
        , }).then((willDelete) => {
            if (willDelete) {
                var url = $(this).data('url');

                $.ajax({
                    method: "get"
                    , url: url
                    , dataType: "json"
                    , success: function(result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            capital_account_table.ajax.reload();
                            other_account_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }

                    }
                });
            }
        });
    });

    $(document).on('submit', 'form#edit_payment_account_form', function(e) {
        e.preventDefault();
        var data = $(this).serialize();
        $.ajax({
            method: "POST"
            , url: $(this).attr("action")
            , dataType: "json"
            , data: data
            , success: function(result) {
                if (result.success == true) {
                    $('div.account_model').modal('hide');
                    toastr.success(result.msg);
                    capital_account_table.ajax.reload();
                    other_account_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            }
        });
    });

    $(document).on('submit', 'form#payment_account_form', function(e) {
        e.preventDefault();
        var data = $(this).serialize();
        $.ajax({
            method: "post"
            , url: $(this).attr("action")
            , dataType: "json"
            , data: data
            , success: function(result) {
                if (result.success == true) {
                    $('div.account_model').modal('hide');
                    toastr.success(result.msg);
                    capital_account_table.ajax.reload();
                    other_account_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            }
        });
    });

    // capital_account_table
    capital_account_table = $('#capital_account_table').DataTable({
        processing: true
        , serverSide: true
        , ajax: '/account/account?account_type=capital'
        , columnDefs: [{
            "targets": 5
            , "orderable": false
            , "searchable": false
        }]
        , columns: [{
            data: 'name'
            , name: 'name'
        }, {
            data: 'account_number'
            , name: 'account_number'
        }, {
            data: 'note'
            , name: 'note'
        }, {
            data: 'balance'
            , name: 'balance'
            , searchable: false
        }, {
            data: 'action'
            , name: 'action'
        }]
        , "fnDrawCallback": function(oSettings) {
            __currency_convert_recursively($('#capital_account_table'));
        }
    });
    // capital_account_table
    other_account_table = $('#other_account_table').DataTable({
        processing: true
        , serverSide: true
        , ajax: {
            url: '/account/account?account_type=other'
            , data: function(d) {
                d.account_status = $('#account_status').val();
            }
        }
        , columnDefs: [{
            "targets": 7
            , "orderable": false
            , "searchable": false
        }]
        , columns: [{
            data: 'action'
            , name: 'action'
        }, {
            data: 'name'
            , name: 'accounts.name'
        }, {
            data: 'parent_account_type_name'
            , name: 'pat.name'
        }, {
            data: 'account_type_name'
            , name: 'ats.name'
        }, {
            data: 'account_number'
            , name: 'accounts.account_number'
        }, {
            data: 'note'
            , name: 'accounts.note'
        }, {
            data: 'balance'
            , name: 'balance'
            , searchable: false
        }, {
            data: 'added_by'
            , name: 'u.first_name'
        }]
        , "fnDrawCallback": function(oSettings) {
            __currency_convert_recursively($('#other_account_table'));
        }
    });

});

$('#account_status').change(function() {
    other_account_table.ajax.reload();
});

$(document).on('submit', 'form#deposit_form', function(e) {
    e.preventDefault();
    var data = $(this).serialize();

    $.ajax({
        method: "POST"
        , url: $(this).attr("action")
        , dataType: "json"
        , data: data
        , success: function(result) {
            if (result.success == true) {
                $('div.view_modal').modal('hide');
                toastr.success(result.msg);
                capital_account_table.ajax.reload();
                other_account_table.ajax.reload();
            } else {
                toastr.error(result.msg);
            }
        }
    });
});

$('.account_model').on('shown.bs.modal', function(e) {
    $('.account_model .select2').select2({
        theme: 'bootstrap4'
        , width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',

        dropdownParent: $(this)
    })
});

$(document).on('click', 'button.delete_account_type', function() {
    swal({
        title: LANG.sure
        , icon: "warning"
        , buttons: true
        , dangerMode: true
    , }).then((willDelete) => {
        if (willDelete) {
            $(this).closest('form').submit();
        }
    });
})

$(document).on('click', 'button.activate_account', function() {
    swal({
        title: LANG.sure
        , icon: "warning"
        , buttons: true
        , dangerMode: true
    , }).then((willActivate) => {
        if (willActivate) {
            var url = $(this).data('url');
            $.ajax({
                method: "get"
                , url: url
                , dataType: "json"
                , success: function(result) {
                    if (result.success == true) {
                        toastr.success(result.msg);
                        capital_account_table.ajax.reload();
                        other_account_table.ajax.reload();
                    } else {
                        toastr.error(result.msg);
                    }

                }
            });
        }
    });
});


//account Book
$(document).ready(function(){
 

    update_account_balance();

    dateRangeSettings.startDate = moment().subtract(6, 'days');
    dateRangeSettings.endDate = moment();
    $('#transaction_date_range').daterangepicker(
        dateRangeSettings,
        function (start, end) {
            $('#transaction_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
            
            account_book.ajax.reload();
        }
    );
    var account_id=$('#account_id').val();
    // Account Book
    account_book = $('#account_book').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            
                            url: '/account/account/'+account_id,
                            data: function(d) {
                                var start = '';
                                var end = '';
                                if($('#transaction_date_range').val()){
                                    start = $('input#transaction_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                                    end = $('input#transaction_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                                }
                                var transaction_type = $('select#transaction_type').val();
                                d.start_date = start;
                                d.end_date = end;
                                d.type = transaction_type;
                            }
                        },
                        "ordering": false,
                        "searching": false,
                        columns: [
                            {data: 'operation_date', name: 'operation_date'},
                            {data: 'sub_type', name: 'sub_type'},
                            {data: 'note', name: 'note'},
                            {data: 'added_by', name: 'added_by'},
                            {data: 'credit', name: 'amount'},
                            {data: 'debit', name: 'amount'},
                            {data: 'balance', name: 'balance'},
                            {data: 'action', name: 'action'}
                        ],
                        "fnDrawCallback": function (oSettings) {
                            __currency_convert_recursively($('#account_book'));
                        },
                        "footerCallback": function ( row, data, start, end, display ) {
                            var footer_total_debit = 0;
                            var footer_total_credit = 0;

                            for (var r in data){
                                footer_total_debit += $(data[r].credit).data('orig-value') ? parseFloat($(data[r].credit).data('orig-value')) : 0;
                                footer_total_credit += $(data[r].debit).data('orig-value') ? parseFloat($(data[r].debit).data('orig-value')) : 0;
                            }

                            $('.footer_total_debit').html(__currency_trans_from_en(footer_total_debit));
                            $('.footer_total_credit').html(__currency_trans_from_en(footer_total_credit));
                        }
                    });

    $('#transaction_type').change( function(){
        account_book.ajax.reload();
    });
    $('#transaction_date_range').on('cancel.daterangepicker', function(ev, picker) {
        $('#transaction_date_range').val('');
        account_book.ajax.reload();
    });

    $('#edit_account_transaction').on('shown.bs.modal', function(e) {
        $('#edit_account_transaction_form').validate({
            submitHandler: function(form) {
                e.preventDefault();
                var data = $(form).serialize();
                $.ajax({
                    method: 'POST',
                    url: $(form).attr('action'),
                    dataType: 'json',
                    data: data,
                    beforeSend: function(xhr) {
                        __disable_submit_button($(form).find('button[type="submit"]'));
                    },
                    success: function(result) {
                        if (result.success == true) {
                            $('#edit_account_transaction').modal('hide');
                            toastr.success(result.msg);

                            if (typeof(account_book) != 'undefined') {
                                account_book.ajax.reload();
                            }
                            
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            },
        });
    })

});

$(document).on('click', '.delete_account_transaction', function(e){
    e.preventDefault();
    swal({
      title: LANG.sure,
      icon: "warning",
      buttons: true,
      dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            var href = $(this).data('href');
            $.ajax({
                url: href,
                dataType: "json",
                success: function(result){
                    if(result.success === true){
                        toastr.success(result.msg);
                        account_book.ajax.reload();
                        update_account_balance();
                    } else {
                        toastr.error(result.msg);
                    }
                }
            });
        }
    });
});

function update_account_balance(argument) {
    $('span#account_balance').html('<i class="fas fa-sync fa-spin"></i>');
    var account_id=$('#account_id').val();

    $.ajax({
        url: '/account/get-account-balance/'+account_id,
        dataType: "json",
        success: function(data){
            $('span#account_balance').text(__currency_trans_from_en(data.balance, true));
        }
    });
}
function update_balance_sheet() {
    var loader = '<i class="fas fa-sync fa-spin fa-fw"></i>';
    $('span.remote-data').each(function() {
        $(this).html(loader);
    });

    $('table#assets_table tbody#account_balances').html('<tr><td colspan="2"><i class="fas fa-sync fa-spin fa-fw"></i></td></tr>');
    $('table#assets_table tbody#capital_account_balances').html('<tr><td colspan="2"><i class="fas fa-sync fa-spin fa-fw"></i></td></tr>');

    var end_date = $('input#end_date').val();
    var campus_id = $('#bal_sheet_campus_id').val()
    $.ajax({
        url: "/account/balance-sheet?end_date=" + end_date + '&campus_id=' + campus_id
        , dataType: "json"
        , success: function(result) {
            $('span#payroll_due').text(__currency_trans_from_en(result.payroll_due, true));
            __write_number($('input#hidden_payroll_due'), result.payroll_due);

            $('span#fee_due').text(__currency_trans_from_en(result.fee_due, true));
            __write_number($('input#hidden_fee_due'), result.fee_due);

            $('span#expense_due').text(__currency_trans_from_en(result.expense_due, true));
            __write_number($('input#hidden_expense_due'), result.expense_due);
            var account_balances = result.account_balances;
            $('table#assets_table tbody#account_balances').html('');
            for (var key in account_balances) {
                var accnt_bal = __currency_trans_from_en(result.account_balances[key]);
                var accnt_bal_with_sym = __currency_trans_from_en(result.account_balances[key], true);
                var account_tr = '<tr><td class="pl-20-td">' + key + ':</td><td><input type="hidden" class="asset" value="' + accnt_bal + '">' + accnt_bal_with_sym + '</td></tr>';
                $('table#assets_table tbody#account_balances').append(account_tr);
            }
            var capital_account_details = result.capital_account_details;
            $('table#assets_table tbody#capital_account_balances').html('');
            for (var key in capital_account_details) {
                var accnt_bal = __currency_trans_from_en(result.capital_account_details[key]);
                var accnt_bal_with_sym = __currency_trans_from_en(result.capital_account_details[key], true);
                var account_tr = '<tr><td class="pl-20-td">' + key + ':</td><td><input type="hidden" class="asset" value="' + accnt_bal + '">' + accnt_bal_with_sym + '</td></tr>';
                $('table#assets_table tbody#capital_account_balances').append(account_tr);
            }


            var total_liabilty = 0;
            var total_assets = 0;
            $('input.liability').each(function() {
                total_liabilty += __read_number($(this));
            });
            $('input.asset').each(function() {
                total_assets += __read_number($(this));
            });

            $('span#total_liabilty').text(__currency_trans_from_en(total_liabilty, true));
            $('span#total_assets').text(__currency_trans_from_en(total_assets, true));

        }
    });
}


function update_trial_balance() {
    var loader = '<i class="fas fa-sync fa-spin fa-fw"></i>';
    $('span.remote-data').each(function() {
        $(this).html(loader);
    });

    $('table#trial_balance_table tbody#capital_account_balances_details').html('<tr><td colspan="3"><i class="fas fa-sync fa-spin fa-fw"></i></td></tr>');
    $('table#trial_balance_table tbody#account_balances_details').html('<tr><td colspan="3"><i class="fas fa-sync fa-spin fa-fw"></i></td></tr>');

    var end_date = $('input#trial_bal_end_date').val();
    var campus_id = $('#trial_bal_campus_id').val()
    $.ajax({
        url: "/account/trial-balance?end_date=" + end_date + '&campus_id=' + campus_id
        , dataType: "json"
        , success: function(result) {
            $('span#payroll_due').text(__currency_trans_from_en(result.payroll_due, true));
            __write_number($('input#hidden_payroll_due'), result.payroll_due);
            $('span#expense_due').text(__currency_trans_from_en(result.expense_due, true));
            __write_number($('input#hidden_expense_due'), result.expense_due);

            $('span#fee_due').text(__currency_trans_from_en(result.fee_due, true));
            __write_number($('input#hidden_fee_due'), result.fee_due);

            var account_balances = result.account_balances;
            $('table#trial_balance_table tbody#account_balances_details').html('');
            for (var key in account_balances) {
                var accnt_bal = __currency_trans_from_en(result.account_balances[key]);
                var accnt_bal_with_sym = __currency_trans_from_en(result.account_balances[key], true);
                var account_tr = '<tr><td class="pl-20-td">' + key + ':</td><td><input type="hidden" class="credit" value="' + accnt_bal + '">' + accnt_bal_with_sym + '</td><td>&nbsp;</td></tr>';
                $('table#trial_balance_table tbody#account_balances_details').append(account_tr);
            }



            var total_debit = 0;
            var total_credit = 0;
            $('input.debit').each(function() {
                total_debit += __read_number($(this));
            });
            $('input.credit').each(function() {
                total_credit += __read_number($(this));
            });

            $('span#total_debit').text(__currency_trans_from_en(total_debit, true));
            $('span#total_credit').text(__currency_trans_from_en(total_credit, true));
        }
    });
}