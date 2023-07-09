
$(document).ready(function () {
   
    setInterval(function(){ EmployeeMapping() }, __mapping_attendance_count_interval);
    setInterval(function(){ StudentMapping() }, __mapping_attendance_count_interval);
    //setInterval(function(){  CheckWhatsAppLogin()}, 50000);

    fileinput_setting = {
        showUpload: false,
        showPreview: false,
        progress:false,
        browseLabel: LANG.file_browse_label,
        removeLabel: LANG.remove,
    };
    $(document).ajaxStart(function () {
        Pace.restart();
    });

    __select2($(".select2"));
    $('.date-picker').datepicker();
    $('#datetimepicker').datetimepicker({
        format: moment_date_format + ' ' + moment_time_format
    });
    $('.select2').select2({
        theme: 'bootstrap4',
        
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
    });
    $('.view_modal').on('show.bs.modal', function() {
        $('.view_modal')
            .find('.select2')
            .each(function() {
                var $p = $(this).parent();
                $(this).select2({
                    theme: 'bootstrap4'
                    , width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style'
                    , placeholder: $(this).data('placeholder')
                    , allowClear: Boolean($(this).data('allow-clear'))
                    , dropdownParent: $p
                });
            });
    })
 
    // popover
    $("body").on("mouseover", '[data-toggle="popover"]', function () {
        if ($(this).hasClass("popover-default")) {
            return false;
        }
        $(this).popover("show");
    });
    $(document).on('change', '#sms_service', function (e) {
        var sms_service = $(this).val();
        $('div.sms_service_settings').each(function () {
            if (sms_service == $(this).data('service')) {
                $(this).removeClass('d-none');
            } else {
                $(this).addClass('d-none');
            }
        });
    });
    //Date picker
    $(".start-date-picker").datepicker({
        autoclose: true,
        endDate: "today",
    });
  

    $(document).on("click", ".btn-modal", function (e) {
        e.preventDefault();
        var container = $(this).data("container");

        $.ajax({
            url: $(this).data("href"),
            dataType: "html",
            success: function (result) {
                $(container).html(result).modal("show");
            },
        });
    });


 


    var active = false;
    $(document).on("mousedown", ".drag-select", function (ev) {
        active = true;
        $(".active-cell").removeClass("active-cell"); // clear previous selection

        $(this).addClass("active-cell");
        cell_value = $(this).find("input").val();
    });
    $(document).on("mousemove", ".drag-select", function (ev) {
        if (active) {
            $(this).addClass("active-cell");
            $(this).find("input").val(cell_value);
        }
    });

    $(document).mouseup(function (ev) {
        active = false;
        if (
            !$(ev.target).hasClass("drag-select") &&
            !$(ev.target).hasClass("dpp") &&
            !$(ev.target).hasClass("dsp")
        ) {
            $(".active-cell").each(function () {
                $(this).removeClass("active-cell");
            });
        }
    });

    //End: CRUD for product variations
    $(document).on("change", ".toggler", function () {
        var parent_id = $(this).attr("data-toggle_id");
        if ($(this).is(":checked")) {
            $("#" + parent_id).removeClass("hide");
        } else {
            $("#" + parent_id).addClass("hide");
        }
    });

    $("#upload_document").fileinput(fileinput_setting);
    $(".upload_document").fileinput(fileinput_setting);
    //user profile
    $("form#edit_user_profile_form").validate();
    $("form#edit_password_form").validate({
        rules: {
            current_password: {
                required: true,
                minlength: 5,
            },
            new_password: {
                required: true,
                minlength: 5,
            },
            confirm_password: {
                equalTo: "#new_password",
            },
        },
    });

    $(document).on("ifChecked", ".check_all", function () {
        $(this)
            .closest(".check_group")
            .find(".input-icheck")
            .each(function () {
                $(this).iCheck("check");
            });
    });
    $(document).on("ifUnchecked", ".check_all", function () {
        $(this)
            .closest(".check_group")
            .find(".input-icheck")
            .each(function () {
                $(this).iCheck("uncheck");
            });
    });
    $(".check_all").each(function () {
        var length = 0;
        var checked_length = 0;
        $(this)
            .closest(".check_group")
            .find(".input-icheck")
            .each(function () {
                length += 1;
                if ($(this).iCheck("update")[0].checked) {
                    checked_length += 1;
                }
            });
        length = length - 1;
        if (checked_length != 0 && length == checked_length) {
            $(this).iCheck("check");
        }
    });

    if ($("#header_text").length) {
        init_tinymce("header_text");
    }

    if ($("#footer_text").length) {
        init_tinymce("footer_text");
    }

    $("button#full_screen").click(function (e) {
        element = document.documentElement;
        if (screenfull.isEnabled) {
            screenfull.toggle(element);
        }
    });

    //Search Settings
    //Set all labels as select2 options
    label_objects = [];
    search_options = [
        {
            id: "",
            text: "",
        },
    ];
    var i = 0;
    $(".pos-tab-container label").each(function () {
        label_objects.push($(this));
        var label_text = $(this)
            .text()
            .trim()
            .replace(":", "")
            .replace("*", "");
        search_options.push({
            id: i,
            text: label_text,
        });
        i++;
    });
    $("#search_settings").select2({
        data: search_options,
        placeholder: LANG.search,
    });
    $("#search_settings").change(function () {
        //Get label position and add active class to the tab
        var label_index = $(this).val();
        var label = label_objects[label_index];
        $(".pos-tab-content.active").removeClass("active");
        var tab_content = label.closest(".pos-tab-content");
        tab_content.addClass("active");
        tab_index = $(".pos-tab-content").index(tab_content);
        $(".list-group-item.active").removeClass("active");
        $(".list-group-item").eq(tab_index).addClass("active");

        //Highlight the label for three seconds
        $([document.documentElement, document.body]).animate(
            {
                scrollTop: label.offset().top - 100,
            },
            500
        );
        label.css("background-color", "yellow");
        setTimeout(function () {
            label.css("background-color", "");
        }, 3000);
    });

   //Used for Purchase & Sell invoice.
   $(document).on('click', 'a.multi-print-invoice', function(e) {
    e.preventDefault();
    var campus_id = $('.global-campuses').val();
    var class_id = $('.global-classes').val();
    var section_id = $('.global-class_sections').val();
     var month_year= $('#month_year').val();
     var only_unpaid= false;
     if ($('input:checkbox[name="only_unpaid"]').is(":checked")) {
       // alert($('input:checkbox[name="only_unpaid"]').is(":checked"));
        only_unpaid=true;
    } 

    var only_transport= false;
    if ($('input:checkbox[name="only_transport"]').is(":checked")) {
      // alert($('input:checkbox[name="only_unpaid"]').is(":checked"));
       only_transport=true;
   } 

    $.ajax({
        method: 'GET',
        url: '/class-wise-fee-card-printing',
        dataType: 'json',
        data: {campus_id: campus_id,class_id: class_id,section_id: section_id,month_year: month_year,only_unpaid:only_unpaid,only_transport:only_transport},
        success: function(result) {
            if (result.success == 1 && result.receipt.html_content != '') {
                $('#receipt_section').html(result.receipt.html_content);
                __currency_convert_recursively($('#receipt_section'));

                var title = document.title;
                if (typeof result.receipt.print_title != 'undefined') {
                    document.title = result.receipt.print_title;
                }
                if (typeof result.print_title != 'undefined') {
                    document.title = result.print_title;
                }

                __print_receipt('receipt_section');

                setTimeout(function() {
                    document.title = title;
                }, 1200);
            } else {
                toastr.error(result.msg);
            }
        },
    });
});
   //Used for Purchase & Sell invoice.
   $(document).on('click', 'a.print-invoice', function(e) {
    e.preventDefault();
    var href = $(this).data('href');
    $.ajax({
        method: 'GET',
        url: href,
        dataType: 'json',
        success: function(result) {
            $('.pace-active')
            if (result.success == 1 && result.receipt.html_content != '') {
                $('#receipt_section').html(result.receipt.html_content);
                __currency_convert_recursively($('#receipt_section'));

                var title = document.title;
                if (typeof result.receipt.print_title != 'undefined') {
                    document.title = result.receipt.print_title;
                }
                if (typeof result.print_title != 'undefined') {
                    document.title = result.print_title;
                }

                __print_receipt('receipt_section');

                setTimeout(function() {
                    document.title = title;
                }, 1200);
            } else {
                toastr.error(result.msg);
            }
        },
    });
});
});

$(document).on("click", "table.ajax_view tbody tr", function (e) {
    if (
        !$(e.target).is("td.selectable_td input[type=checkbox]") &&
        !$(e.target).is("td.selectable_td") &&
        !$(e.target).is("td.clickable_td") &&
        !$(e.target).is("a") &&
        !$(e.target).is("button") &&
        !$(e.target).hasClass("label") &&
        !$(e.target).is("li") &&
        $(this).data("href") &&
        !$(e.target).is("i")
    ) {
        $.ajax({
            url: $(this).data("href"),
            dataType: "html",
            success: function (result) {
                $(".view_modal").html(result).modal("show");
            },
        });
    }
});
$(document).on("click", "td.clickable_td", function (e) {
    e.preventDefault();
    e.stopPropagation();
    if (
        e.target.tagName == "SPAN" ||
        e.target.tagName == "TD" ||
        e.target.tagName == "I"
    ) {
        return false;
    }
    var link = $(this).find("a");
    if (link.length) {
        if (!link.hasClass("no-ajax")) {
            var href = link.attr("href");
            var container = $(".payment_modal");

            $.ajax({
                url: href,
                dataType: "html",
                success: function (result) {
                    $(container).html(result).modal("show");
                    __currency_convert_recursively(container);
                },
            });
        }
    }
});

$(document).on("click", "button.select-all", function () {
    var this_select = $(this).closest(".form-group").find("select");
    this_select.find("option").each(function () {
        $(this).prop("selected", "selected");
    });
    this_select.trigger("change");
});
$(document).on("click", "button.deselect-all", function () {
    var this_select = $(this).closest(".form-group").find("select");
    this_select.find("option").each(function () {
        $(this).prop("selected", "");
    });
    this_select.trigger("change");
});

$(document).on("change", "input.row-select", function () {
    if (this.checked) {
        $(this).closest("tr").addClass("selected");
    } else {
        $(this).closest("tr").removeClass("selected");
    }
});

$(document).on("click", "#select-all-row", function (e) {
    var table_id = $(this).data("table-id");
    if (this.checked) {
        $("#" + table_id)
            .find("tbody")
            .find("input.row-select")
            .each(function () {
                if (!this.checked) {
                    $(this).prop("checked", true).change();
                }
            });
    } else {
        $("#" + table_id)
            .find("tbody")
            .find("input.row-select")
            .each(function () {
                if (this.checked) {
                    $(this).prop("checked", false).change();
                }
            });
    }
});

$(document).on("shown.bs.modal", ".view_modal", function (e) {
    if ($("#shipping_documents_dropzone").length) {
        $(this)
            .find("div#shipping_documents_dropzone")
            .dropzone({
                url: $("#media_upload_url").val(),
                paramName: "file",
                uploadMultiple: true,
                autoProcessQueue: false,
                addRemoveLinks: true,
                params: {
                    model_id: $("#model_id").val(),
                    model_type: $("#model_type").val(),
                    model_media_type: $("#model_media_type").val(),
                },
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (file, response) {
                    if (response.success) {
                        toastr.success(response.msg);
                        $("div.view_modal").modal("hide");
                    } else {
                        toastr.error(response.msg);
                    }
                },
            });
    }
});

$(document).on("show.bs.modal", function () {
    __currency_convert_recursively($(this));
});


$(document).on("shown.bs.modal", ".view_modal", function (e) {
    if ($(this).find("#email_body").length) {
        tinymce.init({
            selector: "textarea#email_body",
        });
    }
});
$(document).on("hidden.bs.modal", ".view_modal", function (e) {
    if ($(this).find("#email_body").length) {
        tinymce.remove("textarea#email_body");
    }

    //check if modal opened then make it scrollable
    if ($(".modal.in").length > 0) {
        $("body").addClass("modal-open");
    }
});

$(window).scroll(function () {
    if ($(this).scrollTop() > 100) {
        $(".scrolltop:hidden").stop(true, true).fadeIn();
    } else {
        $(".scrolltop").stop(true, true).fadeOut();
    }
});
$(document).on('change', '.payment_types_dropdown', function () {
    var payment_type = $(this).val();
    var to_show = null;

    $(this)
        .closest('.payment_row')
        .find('.payment_details_div')
        .each(function () {
            if ($(this).attr('data-type') == payment_type) {
                to_show = $(this);
            } else {
                if (!$(this).hasClass('d-none')) {
                    $(this).addClass('d-none');
                }
            }
        });

    if (to_show && to_show.hasClass('d-none')) {
        to_show.removeClass('d-none');
        to_show.find('input').filter(':visible:first').focus();
    }
});

$(document).on('change', '.payment_types_dropdown', function(e) {
    var payment_type = $('#transaction_payment_add_form .payment_types_dropdown').val();
    account_dropdown = $('#transaction_payment_add_form #account_id');
    if (payment_type == 'advance') {
        if (account_dropdown) {
            account_dropdown.prop('disabled', true);
            account_dropdown.closest('.form-group').addClass('d-none');
        }
    } else {
        if (account_dropdown) {
            account_dropdown.prop('disabled', false); 
            account_dropdown.closest('.form-group').removeClass('d-none');
        }    
    }
});
$(document).on('click', 'a.pay_fee_due', function (e) {
    e.preventDefault();
    $.ajax({
        url: $(this).attr('href'),
        dataType: 'html',
        success: function (result) {
            $('.pay_fee_due_modal').html(result).modal('show');
            __currency_convert_recursively($('.pay_fee_due_modal'));
            $('#datetimepicker').datetimepicker({
                format: moment_date_format + ' ' + moment_time_format,
                
            });
            $('.pay_fee_due_modal').find('form#pay_student_due_form').validate();
        },
    });
});
$(document).on('click', 'a.pay_payroll_due', function (e) {
    e.preventDefault();
    $.ajax({
        url: $(this).attr('href'),
        dataType: 'html',
        success: function (result) {
            $('.pay_payroll_due_modal').html(result).modal('show');
            __currency_convert_recursively($('.pay_payroll_due_modal'));
            $('#datetimepicker').datetimepicker({
                format: moment_date_format + ' ' + moment_time_format,
                
            });
            $('.pay_payroll_due_modal').find('form#pay_employee_due_form').validate();
        },
    });
});
$(document).on('click', '.view_payment_modal', function(e) {
    e.preventDefault();
    var container = $('.payment_modal');

    $.ajax({
        url: $(this).attr('href'),
        dataType: 'html',
        success: function(result) {
            $(container)
                .html(result)
                .modal('show');
            __currency_convert_recursively(container);
        },
    });
});
$(document).on('shown.bs.modal', '.exam_date_sheet_modal', function(e) {
    $('.date-sheet-date').datepicker();

});

  //single exam Print.
   $(document).on('click', 'a.exam-print-single', function(e) {
    e.preventDefault();

    var session_id = $('.exam-session').val();
    var student_id = $('#student_id').val();
    var exam_create_id = $('.exam_create_id').val();

    var href = $(this).data('href');
    $.ajax({
        method: 'GET',
        url: href,
        dataType: 'json',
        data: {student_id: student_id,session_id: session_id,exam_create_id: exam_create_id},
        success: function(result) {
            $('.pace-active')
            if (result.success == 1 && result.receipt.html_content != '') {
                $('#receipt_section').html(result.receipt.html_content);
                __currency_convert_recursively($('#receipt_section'));

                var title = document.title;
                if (typeof result.receipt.print_title != 'undefined') {
                    document.title = result.receipt.print_title;
                }
                if (typeof result.print_title != 'undefined') {
                    document.title = result.print_title;
                }

                __print_receipt('receipt_section');

                setTimeout(function() {
                    document.title = title;
                }, 1200);
            } else {
                toastr.error(result.msg);
            }
        },
    });
    });
  //exam Print.
   $(document).on('click', 'a.exam-print-invoice', function(e) {
    e.preventDefault();
    var campus_id = $('.global-campuses').val();
    var class_id = $('.global-classes').val();
    var class_section_id = $('.global-class_sections').val();
    var session_id = $('.exam-session').val();
    var exam_create_id = $('.exam_create_id').val();

    var href = $(this).data('href');
    $.ajax({
        method: 'GET',
        url: href,
        dataType: 'json',
        data: {campus_id: campus_id,class_id: class_id,class_section_id: class_section_id,session_id: session_id,exam_create_id: exam_create_id},
        success: function(result) {
            $('.pace-active')
            if (result.success == 1 && result.receipt.html_content != '') {
                $('#receipt_section').html(result.receipt.html_content);
                __currency_convert_recursively($('#receipt_section'));

                var title = document.title;
                if (typeof result.receipt.print_title != 'undefined') {
                    document.title = result.receipt.print_title;
                }
                if (typeof result.print_title != 'undefined') {
                    document.title = result.print_title;
                }

                __print_receipt('receipt_section');

                setTimeout(function() {
                    document.title = title;
                }, 1200);
            } else {
                toastr.error(result.msg);
            }
        },
    });
    });
  //clear Attandance.
   $(document).on('click', 'a.clear-att', function(e) {
    e.preventDefault();
    
    var href = $(this).data('href');
    $.ajax({
        method: 'GET',
        url: href,
        dataType: 'json',
        success: function(result) {
            $('.pace-active')
            if (result.success == 1) {
                toastr.success(result.msg);

            } else {
                toastr.error(result.msg);
            }
        },
    });
    });
    //  //whatsapp.
    //  var socket = io('whatsapp.sfsc.edu.pk');

    //  socket.on('message', function(msg) {
    //     $(".whatappstatus").html(msg);

    //  });
  
    $(document).on('click', '#add_video_link', function() {
        var html =
            '<div class="row mt-5" ><label class="col-sm-3 control-label">Add Video Link:*</label><div class="col-sm-7 col-sm-offset-3"><input type="text" name="video_link[]" class="form-control" required></div><div class="col-sm-2"><button type="button" class="btn btn-danger delete_video_link">-</button></div></div>';
        $('#video_link').append(html);
    });
    $(document).on('click', '.delete_video_link', function() {
        $(this)
            .closest('.row')
            .remove();
    });
   
//    $(document).on('click', 'a.whatsapp-check-login', function(e) {
//     e.preventDefault();    
//     var container = $(this).data("container");

//     $.ajax({
//         url: $(this).data("href"),
//         dataType: "html",
//         success: function (result) {
            
//             $(container).html(result).modal("show");
            
//      socket.on('qr', function(src) {
//         $('#qrcode').attr('src', src);
//         $('#qrcode').show();
//         $('.hideqr').hide();

//     });

//     socket.on('ready', function(data) {
//         $('#qrcode').hide();
//     });
//     socket.on('authenticated', function(data) {
//         $('#qrcode').hide();
//     });
//         },
//     });
//     });




    $(document).on("submit", "form#print_report_form", function (e) {
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
         success: function(result) {
            
            if (result.success == 1 && result.receipt.html_content != '') {
                $('#receipt_section').html(result.receipt.html_content);
                __currency_convert_recursively($('#receipt_section'));

                var title = document.title;
                if (typeof result.receipt.print_title != 'undefined') {
                    document.title = result.receipt.print_title;
                }
                if (typeof result.print_title != 'undefined') {
                    document.title = result.print_title;
                }
                __enable_submit_button(form.find('button[type="submit"]'));

                __print_receipt('receipt_section');

                setTimeout(function() {
                    document.title = title;
                }, 1200);
            } else {
                toastr.error(result.msg);
            }
        },
        });   
    });
    $(document).on('submit', 'form#certificate-print-form', function(e) {
        e.preventDefault();
        var form = $(this);
        var data = form.serialize();

        $.ajax({
            method: 'POST'
            , url: $(this).attr('action')
            , dataType: 'json'
            , data: data
            , beforeSend: function(xhr) {
                __disable_submit_button(form.find('button[type="submit"]'));
            }
            , success: function(result) {

                __enable_submit_button(form.find('button[type="submit"]'));
                $('.pace-active')
                if (result.success == 1 && result.receipt.html_content != '') {
                    $('#receipt_section').html(result.receipt.html_content);
                    __currency_convert_recursively($('#receipt_section'));

                    var title = document.title;
                    if (typeof result.receipt.print_title != 'undefined') {
                        document.title = result.receipt.print_title;
                    }
                    if (typeof result.print_title != 'undefined') {
                        document.title = result.print_title;
                    }

                    __print_receipt('receipt_section');

                    setTimeout(function() {
                        document.title = title;
                    }, 1200);
                } else {
                    toastr.error(result.msg);
                }
            }
        , });
    });
$(function () {
    $(".scroll").click(function () {
        $("html,body").animate(
            { scrollTop: $(".thetop").offset().top },
            "1000"
        );
        return false;
    });
});

function StudentMapping(){
        var href = '/map-attendance';
        $.ajax({
            url: href,
            dataType: 'json',
            global: false,
            success: function(data) {
            //    console.log(data);
            },
        });
}
function EmployeeMapping(){
        var href = '/map-attendance-students';
        $.ajax({
            url: href,
            dataType: 'json',
            global: false,
            success: function(data) {
            //    console.log(data);
            },
        });
}

function CheckWhatsAppLogin(){
    var href = '/whatsapp-check-auth';
    
$.ajax({
    method: 'GET',
    url: href,
    global: false,
    dataType: 'json',
    success: function(result) {
      
        if (result.success == 1) {
            $(".whatappstatus").html(result.data);

        } else {
            $(".whatappstatus").html(result.data);

        }
    },
});

}


