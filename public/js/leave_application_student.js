$(document).ready(function () {

    
    $(document).on('click', '.update_leave_status', function(e) {
        e.preventDefault();
        $('#update_leave_status_form').find('#status').val($(this).data('status'));
        $('#update_leave_status_form').find('#leave_id').val($(this).data('leave_id'));
        $('#update_leave_status_modal').modal('show');
        });

        
    $(document).on('submit', '#update_leave_status_form', function(e) {
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
                    $('#update_leave_status_modal').modal('hide');
                    toastr.success(result.msg);
                    leave_applications_for_student_table.ajax.reload();
                    $('#update_leave_status_form')
                        .find('button[type="submit"]')
                        .attr('disabled', false);
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });

 
    $(document).on("click", "button.add_new_leave_application", function (e) {
       
        e.preventDefault();
        var container = $(this).data("container");

        $.ajax({
            url: $(this).data("href"),
            dataType: "html",
            success: function (result) {
                $(container).html(result).modal("show");
                dateRangeSettings.startDate = moment();
                dateRangeSettings.endDate = moment();
                $("#date_range").daterangepicker(
                    dateRangeSettings,
                    function (start, end) {
                        $("#date_range").val(
                            start.format(moment_date_format) +
                                " ~ " +
                                end.format(moment_date_format)
                        );
                    }
                );
                $(container).find('form#add_new_leave_application_form').validate();
            },
        });
    });

    //leave_applications_for_student_table
    var leave_applications_for_student_table = $(
        "#leave_applications_for_student_table"
    ).DataTable({
        processing: true,
        serverSide: true,
        scrollY: "75vh",
        scrollX: true,
        scrollCollapse: false,
        ajax: {
            url: "/leave_application_students",
            data: function (d) {
                if ($("#campus_id").length) {
                    d.campus_id = $(
                        "#campus_id"
                    ).val();
                }
                if ($("#leave_status").length) {
                    d.status = $(
                        "#leave_status"
                    ).val();
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
                data: "campus_name",
                name: "campus_name",
                orderable: false,
                searchable: false,
            },
            {
                data: "class_name",
                name: "class_name",
                orderable: false,
                searchable: false,
            },
            {
                data: "student_name",
                name: "student_name",
                orderable: false,

            },
            {
                data: "status",
                name: "status",
                orderable: false,

            },
            {
                data: "reason",
                name: "reason",
                orderable: false,

            },
            {
                data: "apply_date",
                name: "apply_date",
            },
            {
                data: "from_date",
                name: "from_date",
                orderable: false,

            },
            {
                data: "to_date",
                name: "to_date",
                orderable: false,
                searchable: false,
            },
            {
                data: "approve_by",
                name: "approve_by",
                orderable: false,
                searchable: false,
            },
        ],
    });

    $(document).on(
        "change",
        "#leave_status,#list_filter_date_range",
        function () {
            leave_applications_for_student_table.ajax.reload();
        }
    );
});
