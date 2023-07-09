$(document).ready(function () {
    $("#exam_date_sheet_modal").on("shown.bs.modal", function (e) {
        var $p = $(this);
        $("#exam_date_sheet_modal .select2").select2({
            dropdownParent: $p,
        });
        $("#start_timepicker,#end_timepicker").datetimepicker({
            format: moment_time_format,
            ignoreReadonly: true,
        });
    });
    $(document).on(
        "change",
        "#filter_campus_id,#filter_class_id,#filter_class_section_id,#filter_exam_create_id",
        function () {
            date_sheet.ajax.reload();
        }
    );
    //class_time table_date_sheettable
    var date_sheet = $("#date_sheet").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "/exam/date_sheets",
            data: function (d) {
                if ($("#filter_campus_id").length) {
                    d.campus_id = $("#filter_campus_id").val();
                }
                if ($("#filter_class_id").length) {
                    d.class_id = $("#filter_class_id").val();
                }
                // if ($("#filter_class_section_id").length) {
                //     d.class_section_id = $("#filter_class_section_id").val();
                // }
                if ($("#filter_session_id").length) {
                    d.session_id = $("#filter_session_id").val();
                }
                if ($("#filter_exam_create_id").length) {
                    d.exam_create_id = $("#filter_exam_create_id").val();
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
                data: "date",
                name: "date",
            },
            {
                data: "day",
                name: "day",
            },
            {
                data: "exam_term",
                name: "exam_term",
                orderable: false,
                searchable: false,
            },
            {
                data: "session_title",
                name: "session_title",
                orderable: false,
                searchable: false,
            },
            {
                data: "name",
                name: "name",
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
                data: "title",
                name: "title",
                orderable: false,
                searchable: false,
            },
            // {
            //     data: "section_name",
            //     name: "section_name",
            //     orderable: false,
            //     searchable: false,
            // },
            {
                data: "start_time",
                name: "start_time",
                orderable: false,
                searchable: false,
            },
            {
                data: "end_time",
                name: "end_time",
                orderable: false,
                searchable: false,
            },
        ],
    });

    $(document).on("submit", "form#add_date_sheet_form", function (e) {
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
                    // $("div.exam_date_sheet_modal").modal("hide");
                    toastr.success(result.msg);
                    __enable_submit_button(form.find('button[type="submit"]'));

                    date_sheet.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });

    $(document).on("click", "a.edit_date_sheet_button", function () {
        $("div.exam_date_sheet_modal").load($(this).data("href"), function () {
            $(this).modal("show");

            $("form#date_sheet_edit_form").submit(function (e) {
                e.preventDefault();
                var form = $(this);
                var data = form.serialize();

                $.ajax({
                    method: "POST",
                    url: $(this).attr("action"),
                    dataType: "json",
                    data: data,
                    beforeSend: function (xhr) {
                        __disable_submit_button(
                            form.find('button[type="submit"]')
                        );
                    },
                    success: function (result) {
                        if (result.success == true) {
                            $("div.exam_date_sheet_modal").modal("hide");
                            toastr.success(result.msg);
                            date_sheet.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });
        });
    });

    $(document).on("click", "a.delete_date_sheet_button", function () {
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_class_subject,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                var href = $(this).data("href");
                var data = $(this).serialize();

                $.ajax({
                    method: "DELETE",
                    url: href,
                    dataType: "json",
                    data: data,
                    success: function (result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            date_sheet.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });



    ////top posit
});
