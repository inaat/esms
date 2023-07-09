


$(document).ready(function() {


    $.fn.modal.Constructor.prototype._enforceFocus = function() {
        modal_this = this
        $(document).on('focusin', function (e) {
          
          
        })
      };

  $.fn.datetimepicker.Constructor.Default = $.extend({}, $.fn.datetimepicker.Constructor.Default, {
    format: moment_date_format + ' ' + moment_time_format,
     icons: {
         time: 'fa fa-clock',
         date: 'fa fa-calendar',
         up: 'fa fa-arrow-up',
         down: 'fa fa-arrow-down',
         previous: 'fa fa-chevron-left',
         next: 'fa fa-chevron-right',
         today: 'fa fa-calendar-check-o',
         clear: 'fa fa-trash',
         close: 'fa fa-times'
     } });

//This file contains all common functionality for the application
$(document).on('submit', 'form', function(e) {
    if (!__is_online()) {
        e.preventDefault();
        toastr.error(LANG.not_connected_to_a_network);
        return false;
    }

    $(this).find('button[type="submit"]')
            .attr('disabled', true);
});
    window.addEventListener('online',  updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);

    $.ajaxSetup({
        beforeSend: function(jqXHR, settings) {
            if (!__is_online()) {
                toastr.error(LANG.not_connected_to_a_network);
                return false;
            }
            if (settings.url.indexOf('http') === -1) {
                settings.url = base_path + settings.url;
            }
        },
    });

    update_font_size();
    if ($('#status_span').length) {
        var status = $('#status_span').attr('data-status');
        if (status === '1') {
            toastr.success($('#status_span').attr('data-msg'));
        } else if (status == '' || status === '0') {
            toastr.error($('#status_span').attr('data-msg'));
        }
    }

    //Default setting for select2
    $.fn.select2.defaults.set('minimumResultsForSearch', 6);
    if ($('html').attr('dir') == 'rtl') {
        $.fn.select2.defaults.set('dir', 'rtl');
    }
    $.fn.datepicker.defaults.todayHighlight = true;
    $.fn.datepicker.defaults.autoclose = true;
    $.fn.datepicker.defaults.format = datepicker_date_format;

    //Toastr setting
    toastr.options.preventDuplicates = true;
    toastr.options.timeOut = "3000";

    //Play notification sound on success, error and warning
    toastr.options.onShown = function() {
        if ($(this).hasClass('toast-success')) {
            var audio = $('#success-audio')[0];
            if (audio !== undefined) {
                audio.play();
            }
        } else if ($(this).hasClass('toast-error')) {
            var audio = $('#error-audio')[0];
            if (audio !== undefined) {
                audio.play();
            }
        } else if ($(this).hasClass('toast-warning')) {
            var audio = $('#warning-audio')[0];
            if (audio !== undefined) {
                audio.play();
            }
        }
    };

    //Default setting for jQuey validator
    jQuery.validator.setDefaults({
        errorPlacement: function(error, element) {
            if (element.hasClass('select2') && element.parent().hasClass('input-group')) {
                error.insertAfter(element.parent());
            } else if (element.hasClass('select2')) {
                error.insertAfter(element.next('span.select2-container'));
            } else if (element.parent().hasClass('input-group')) {
                error.insertAfter(element.parent());
            } else if (element.parent().hasClass('multi-input')) {
                error.insertAfter(element.closest('.multi-input'));
            } else if (element.parent().hasClass('input_inline')) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },

        invalidHandler: function() {
            toastr.error(LANG.some_error_in_input_field);
        },
    });

    
    //Default setting for jQuey validator
    jQuery.validator.setDefaults({
        errorPlacement: function(error, element) {
            if (element.hasClass('select2') && element.parent().hasClass('input-group')) {
                error.insertAfter(element.parent());
            } else if (element.hasClass('select2')) {
                error.insertAfter(element.next('span.select2-container'));
            } else if (element.parent().hasClass('input-group')) {
                error.insertAfter(element.parent());
            } else if (element.parent().hasClass('multi-input')) {
                error.insertAfter(element.closest('.multi-input'));
            } else if (element.parent().hasClass('input_inline')) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },

        invalidHandler: function() {
            toastr.error(LANG.some_error_in_input_field);
        },
    });
    jQuery.validator.addMethod(
        'max-value',
        function(value, element, param) {
            return this.optional(element) || !(param < __number_uf(value));
        },
        function(params, element) {
            return $(element).data('msg-max-value');
        }
    );

    jQuery.validator.addMethod('abs_digit', function(value, element) {
        return this.optional(element) || Number.isInteger(Math.abs(__number_uf(value)));
    });

    //Set global currency to be used in the application
    __currency_symbol = $('input#__symbol').val();
    __currency_thousand_separator = $('input#__thousand').val();
    __currency_decimal_separator = $('input#__decimal').val();
    __currency_symbol_placement = $('input#__symbol_placement').val();
    if ($('input#__precision').length > 0) {
        __currency_precision = $('input#__precision').val();
    } else {
        __currency_precision = 2;
    }

    if ($('input#__quantity_precision').length > 0) {
        __quantity_precision = $('input#__quantity_precision').val();
    } else {
        __quantity_precision = 2;
    }

    //Set page level currency to be used for some pages. (Purchase page)
    if ($('input#p_symbol').length > 0) {
        __p_currency_symbol = $('input#p_symbol').val();
        __p_currency_thousand_separator = $('input#p_thousand').val();
        __p_currency_decimal_separator = $('input#p_decimal').val();
    }

    __currency_convert_recursively($(document), $('input#p_symbol').length);

    var buttons = [
        {
            extend: 'copyHtml5',
            text: '<i class="far fa-copy"></i>',
            titleAttr: 'Copy',
            title: $('.export_title').html(),
            exportOptions: {
                columns: ':visible'
            }
        },
        {
            extend: 'excelHtml5',
            text: '<i class="fa fa-file-excel"></i>',
            titleAttr: 'Excel',
            title: $('.export_title').html(),
            exportOptions: {
                columns: ':visible'
            }
        },
        {
            extend: 'csvHtml5',
            text: '<i class="fa fa-file-alt"></i>',
            titleAttr: 'CSV',
            title: $('.export_title').html(),
            exportOptions: {
                columns: ':visible'
            }
        },
        {
            extend: 'pdfHtml5',
            text: '<i class="fa fa-file-pdf"></i>',
            titleAttr: 'PDF',
            title: $('.export_title').html(),
            footer: true,
            customize: function ( win ) {
                win.styles.tableHeader.fontSize = 10;
                win.styles.tableFooter.fontSize = 10;
                win.styles.tableHeader.alignment = 'left';
            },
            exportOptions: {
                columns: ':visible'
                
            }
        },
       
  
        {
            extend: 'print',
            exportOptions: {
                columns: ':visible',
                stripHtml: false,
            },
           
            footer: true,
            customize: function ( win ) {
                if ($('.print_table_part').length > 0 ) {
                    $($('.print_table_part').html()).insertBefore($(win.document.body).find( 'table' ));
                    $($('.print_table_part').html()).insertBefore($(win.document.body).find( 'table' ));
                }
                
                if ($(win.document.body).find( 'table.hide-footer').length) {
                    $(win.document.body).find( 'table.hide-footer tfoot' ).remove();
                }
            
                 

                 __currency_convert_recursively($(win.document.body).find( 'table' ));
            }
        },
        {
            extend: 'colvis',
           
        },
    ];

    //Datables
    jQuery.extend($.fn.dataTable.defaults, {
        // fixedHeader: true,
         dom:
         '<"row margin-bottom-20 text-center"<"col-sm-2"l><"col-sm-7"B><"col-sm-3"f> r>tip',
        buttons: buttons,
        aLengthMenu: [[25, 50, 100, 200, 500, 1000, -1], [25, 50, 100, 200, 500, 1000, LANG.all]],
        iDisplayLength: __default_datatable_page_entries,
        language: {
            searchPlaceholder: LANG.search + ' ...',
            search: '',
            lengthMenu: LANG.show + ' _MENU_ ' + LANG.entries,
            emptyTable: LANG.table_emptyTable,
            info: LANG.table_info,
            infoEmpty: LANG.table_infoEmpty,
            loadingRecords: LANG.table_loadingRecords,
            processing: LANG.table_processing,
            zeroRecords: LANG.table_zeroRecords,
            paginate: {
                first: LANG.first,
                last: LANG.last,
                next: LANG.next,
                previous: LANG.previous,
            },
        },
        
    });

    var tableExport = $('#tableExport').DataTable({
        "dom": '<"row"<"col-sm-6 mb-xs"B><"col-sm-6"f>><"table-responsive"t>p',
        "lengthChange": false,
        "pageLength": -1,
        "columnDefs": [
            {targets: [-1], orderable: false}
        ],
        buttons: buttons,
          
    });


    if ($('input#iraqi_selling_price_adjustment').length > 0) {
        iraqi_selling_price_adjustment = true;
    } else {
        iraqi_selling_price_adjustment = false;
    }

    //Input number
    $(document).on('click', '.input-number .quantity-up, .input-number .quantity-down', function() {
        var input = $(this)
            .closest('.input-number')
            .find('input');
        var qty = __read_number(input);
        var step = 1;
        if (input.data('step')) {
            step = input.data('step');
        }
        var min = parseFloat(input.data('min'));
        var max = parseFloat(input.data('max'));

        if ($(this).hasClass('quantity-up')) {
            //if max reached return false
            if (typeof max != 'undefined' && qty + step > max) {
                return false;
            }

            __write_number(input, qty + step);
            input.change();
        } else if ($(this).hasClass('quantity-down')) {
            //if max reached return false
            if (typeof min != 'undefined' && qty - step < min) {
                return false;
            }

            __write_number(input, qty - step);
            input.change();
        }
    });

	/* Back To Top */
	$(document).ready(function () {
		$(window).on("scroll", function () {
			if ($(this).scrollTop() > 300) {
				$('.back-to-top').fadeIn();
			} else {
				$('.back-to-top').fadeOut();
			}
		});
		$('.back-to-top').on("click", function () {
			$("html, body").animate({
				scrollTop: 0
			}, 600);
			return false;
		});
	});


//Default settings for daterangePicker
var ranges = {};
ranges[LANG.today] = [moment(), moment()];
ranges[LANG.yesterday] = [moment().subtract(1, 'days'), moment().subtract(1, 'days')];
ranges[LANG.last_7_days] = [moment().subtract(6, 'days'), moment()];
ranges[LANG.last_30_days] = [moment().subtract(29, 'days'), moment()];
ranges[LANG.this_month] = [moment().startOf('month'), moment().endOf('month')];
ranges[LANG.last_month] = [
    moment()
        .subtract(1, 'month')
        .startOf('month'),
    moment()
        .subtract(1, 'month')
        .endOf('month'),
];
ranges[LANG.this_month_last_year] = [
    moment()
        .subtract(1, 'year')
        .startOf('month'),
    moment()
        .subtract(1, 'year')
        .endOf('month'),
];
ranges[LANG.this_year] = [moment().startOf('year'), moment().endOf('year')];
ranges[LANG.last_year] = [
    moment().startOf('year').subtract(1, 'year'), 
    moment().endOf('year').subtract(1, 'year') 
];
ranges[LANG.this_financial_year] = [financial_year.start, financial_year.end];
ranges[LANG.last_financial_year] = [
    moment(financial_year.start._i).subtract(1, 'year'), 
    moment(financial_year.end._i).subtract(1, 'year')
];

window.dateRangeSettings = {
    ranges: ranges,
    startDate: financial_year.start,
    endDate: financial_year.end,
    locale: {
        cancelLabel: LANG.clear,
        applyLabel: LANG.apply,
        customRangeLabel: LANG.custom_range,
        format: moment_date_format,
        toLabel: '~',
    },
};
 //Date range as a button
 $('#list_filter_date_range').daterangepicker(
    dateRangeSettings,
    function(start, end) {
        $('#list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end
            .format(moment_date_format));
        
    }
);
if ($('#month_list_filter_date_range').length == 1) {
    dateRangeSettings.startDate = moment().startOf('month');
    dateRangeSettings.endDate = moment().endOf('month');
    $('#month_list_filter_date_range').daterangepicker(dateRangeSettings, function(start, end) {
        $('#month_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end
             .format(moment_date_format));
    });

}
if ($('#today_list_filter_date_range').length == 1) {
    dateRangeSettings.startDate = moment().startOf('today');
    dateRangeSettings.endDate = moment().endOf('today');
    $('#today_list_filter_date_range').daterangepicker(dateRangeSettings, function(start, end) {
        $('#today_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end
             .format(moment_date_format));
    });

}
 
//Check for number string in input field, if data-decimal is 0 then don't allow decimal symbol
$(document).on('keypress', 'input.input_number', function(event) {
    var is_decimal = $(this).data('decimal');

    if (is_decimal == 0) {
        if (__currency_decimal_separator == '.') {
            var regex = new RegExp(/^[0-9,-]+$/);
        } else {
            var regex = new RegExp(/^[0-9.-]+$/);
        }
    } else {
        var regex = new RegExp(/^[0-9.,-]+$/);
    }

    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
});

//Select all input values on click
$(document).on('click', 'input', function(event) {
    $(this).select();
});

$(document).on('click', '.toggle-font-size', function(event) {
    localStorage.setItem('upos_font_size', $(this).data('size'));
    update_font_size();
});
$(document).on('click', '.sidebar-toggle', function() {
    var sidebar_collapse = localStorage.getItem('upos_sidebar_collapse');
    if ($('body').hasClass('sidebar-collapse')) {
        localStorage.setItem('upos_sidebar_collapse', 'false');
    } else {
        localStorage.setItem('upos_sidebar_collapse', 'true');
    }
});

//Ask for confirmation for links
$(document).on('click', 'a.link_confirmation', function(e) {
    e.preventDefault();
    swal({
        title: LANG.sure,
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    }).then(confirmed => {
        if (confirmed) {
            window.location.href = $(this).attr('href');
        }
    });
});

//Change max quantity rule if lot number changes
$('table#stock_adjustment_product_table tbody').on('change', 'select.lot_number', function() {
    var qty_element = $(this)
        .closest('tr')
        .find('input.product_quantity');
    if ($(this).val()) {
        var lot_qty = $('option:selected', $(this)).data('qty_available');
        var max_err_msg = $('option:selected', $(this)).data('msg-max');
        qty_element.attr('data-rule-max-value', lot_qty);
        qty_element.attr('data-msg-max-value', max_err_msg);

        qty_element.rules('add', {
            'max-value': lot_qty,
            messages: {
                'max-value': max_err_msg,
            },
        });
    } else {
        var default_qty = qty_element.data('qty_available');
        var default_err_msg = qty_element.data('msg_max_default');
        qty_element.attr('data-rule-max-value', default_qty);
        qty_element.attr('data-msg-max-value', default_err_msg);

        qty_element.rules('add', {
            'max-value': default_qty,
            messages: {
                'max-value': default_err_msg,
            },
        });
    }
    qty_element.trigger('change');
});


jQuery.validator.addMethod(
    'min-value',
    function(value, element, param) {
        return this.optional(element) || !(param > __number_uf(value));
    },
    function(params, element) {
        return $(element).data('min-value');
    }
);

$(document).on('click', '.view_uploaded_document', function(e) {
    e.preventDefault();
    var src = $(this).data('href');
     var html ='<div class="modal fade leave_applications_for_employee_modal contains_select2 show" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" aria-modal="true" style="display: block; padding-left: 0px;"><div class="modal-dialog modal-lg" role="document"><div class="modal-content">'
 
     +'<div class="modal-header bg-primary"><h5 class="modal-title" id="exampleModalLabel">View Document</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>'
 
        +' <div class="modal-body"><div class="row"><img src="'+
        src +
        '" class="img-responsive" alt="Uploaded Document"></div><div class="modal-footer"><button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button> <a href="' +
        src +
        '" class="btn btn-success" download=""><i class="fa fa-download"></i> Download</a></div></div></div>';
    $('div.view_modal')
        .html(html)
        .modal('show');
});

$(document).on('click', '#accordion .box-header', function(e) {
    if (e.target.tagName == 'A' || e.target.tagName == 'I') {
        return false;
    }
    $(this)
        .find('.box-title a')
        .click();
});

$(document).on('shown.bs.modal', '.contains_select2', function(){
    $(this).find('.select2').each( function(){
        var $p = $(this).parent();
        $(this).select2(
            {
               
                dropdownParent: $p,
                theme: 'bootstrap4',
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                placeholder: $(this).data('placeholder'),
                allowClear: Boolean($(this).data('allow-clear')),
            }
        );
       // $(this).select2({ dropdownCssClass: "urdu_input urdu" });

    });
})

//common configuration : tinyMCE editor
tinymce.overrideDefaults({
    height: 400,
    theme: 'silver',
    plugins: [
      'advlist autolink link image lists charmap print preview hr anchor pagebreak',
      'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
      'table template paste help mathEditor '
    ],
    content_css: [mathcss],
    external_plugins: {
      'mathEditor': 'matheditor/plugin.js',
    },
 
    toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify |' +
      ' bullist numlist outdent indent | link image | print preview media fullpage | mathEditor |' +
      'forecolor backcolor fontselect fontsizeselect formatselect',
    menu: {
      favs: {title: 'My Favorites', items: 'code | searchreplace'}
    },
    setup: function(ed) {
        ed.on('change', function(e) {
            tinyMCE.triggerSave();
        });
    },
    menubar: 'favs file edit view insert format tools table help'
});

// Prevent Bootstrap dialog from blocking focusin
$(document).on('focusin', function(e) {
  if ($(e.target).closest(".tox-tinymce-aux, .moxman-window, .tam-assetmanager-root").length) {
    e.stopImmediatePropagation();
  }
});


//search parameter in url
function urlSearchParam(param){
    var results = new RegExp('[\?&]' + param + '=([^&#]*)').exec(window.location.href);
    if (results == null){
       return null;
    } else{
       return results[1];
    }
}

// For dropdown hidden issue
(function() {
  var dropdownMenu;
  $('table').on('show.bs.dropdown', function(e) {
    dropdownMenu = $(e.target).find('.dropdown-menu');
    $('body').append(dropdownMenu.detach());
    var eOffset = $(e.target).offset();
    if(dropdownMenu.hasClass('dropdown-menu-right')) {
        dropdownMenu.css({
            'display': 'block',
            'top': eOffset.top + $(e.target).outerHeight(),
            'left': 'auto',
            'right': 0
        });
    } else {
        dropdownMenu.css({
            'display': 'block',
            'top': eOffset.top + $(e.target).outerHeight(),
            'left': eOffset.left
        });
    }                                              
  });
  $('table').on('hide.bs.dropdown', function(e) {        
    $(e.target).append(dropdownMenu.detach());        
    dropdownMenu.hide();                              
  });                                                   
})();

function updateOnlineStatus(){ 
    if (!__is_online()) {
        $('#online_indicator').removeClass('text-success');
        $('#online_indicator').addClass('text-danger');
    } else {
        $('#online_indicator').removeClass('text-danger');
        $('#online_indicator').addClass('text-success');
    }
};

$(document).on('keyup', '.cash_denomination', function(){
    var total = 0;
    var table = $(this).closest('table');
    table.find('tbody tr').each( function(){
        var denomination = parseFloat($(this).find('.cash_denomination').attr('data-denomination'));
        var count = $(this).find('.cash_denomination').val() ? parseInt($(this).find('.cash_denomination').val()) : 0;
        var subtotal = denomination * count;
        total = total + subtotal;
        $(this).find('span.denomination_subtotal').text(__currency_trans_from_en(subtotal, true));
    });
    
    table.find('span.denomination_total').text(__currency_trans_from_en(total, true));
    $('input#amount').val(total);
    
})
/// Counties Provinces Districts Cities
$(document).on('change', '#country_id', function() {
    __get_provinces();
}); 
$(document).on('change', '#provinces_ids', function() {
    __get_districts();
}); 
$(document).on('change', '#district_ids', function() {
    __get_cities();
}); 
$(document).on('change', '#city_ids', function() {
    __get_regions();
}); 

$(document).on('change', '.exam-session', function() {
    var doc = $(this);
    __get_exam_term(doc);

  
});
$(document).on('change', '.global-campuses', function() {
    var doc = $(this);
    __get_campus_class(doc);
    var periodExist = document.getElementById("periods");
    if(periodExist){
        __get_periods(doc);
    }
  
});

$(document).on('change', '.global-classes', function() {
    var doc = $(this);
    var global_class_sections= document.getElementsByClassName("global-class_sections")
    if(global_class_sections){
     __get_class_Section(doc);
    }
    var subjectExist = document.getElementsByClassName("global-subjects")
    if(subjectExist){
        __get_subjects(doc);
    }
});
// $(document).on("change", "#chapter_id", function () {
//     var chapter_id = $("#chapter_id").val();
//     var subject_id = $("#subject_id").val();

//     __get_chapter_lessons(subject_id, chapter_id);
// });
$(document).on('change', '.global-subjects', function() {
    var doc = $(this);
    var subjectExist = document.getElementsByClassName("global-chapter");
    var subject_id = doc.closest(".row").find(".global-subjects").val();

    if(subjectExist){
        __get_chapters(doc,subject_id)
    }
});

$(document).on('change', '.global-class_sections', function() {
    var doc = $(this);
    var subjectExist = document.getElementById("global-section-subjects");
    if(subjectExist){
        __get_section_subjects(doc);
    }
});

$(document).on('keyup', '#discount_amount,input.amount',  function() {
    var discount_amount = __number_uf($('input.discount_amount').val(),false);
    var amount =__number_uf($('input.amount').val(),false);
    var student_due =__number_uf($('input#student_due').val(),false);
    var discount_amount=amount - discount_amount;
    // if(discount_amount<0){
    //    $('.amount').val(amount);
    //    $('.amount').attr('data-rule-max-value',amount);
    //    $(this).val(0);
    //    toastr.error(LANG.discount_amount_exceeds_total_amount);
    // }

     total_amount = student_due-(amount + __number_uf($('input.discount_amount').val(),false));
    $('span.remaining-balance').text(__currency_trans_from_en(total_amount, true));
    if(total_amount<0){__write_number($('input#balance'),Math.abs(total_amount));
}

  
});


});
$(document).on("click", "a.sync-with-device", function() {
    swal({
        title: LANG.sure,
        text: LANG.confirm_action_sync_data_with_device,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            var href = $(this).attr('data-href');
            var data = $(this).serialize();

            $.ajax({
                method: "GET",
                url: href,
                dataType: "json",
                data: data,
                success: function(result) {
                    if (result.success == true) {
                        toastr.success(result.msg);
                    } else {
                        toastr.error(result.msg);
                    }
                },
            });
        }
    });
});
