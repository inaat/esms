$(document).ready(function () {
    //withdraw_students
    var withdraw_students = $("#withdraw_students").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "/withdrawal-students-list",
            data: function (d) {
                if ($("#students_list_filter_campus_id").length) {
                    d.campus_id = $("#students_list_filter_campus_id").val();
                }

                if ($("#students_list_filter_class_id").length) {
                    d.class_id = $("#students_list_filter_class_id").val();
                }
                if ($("#students_list_filter_class_section_id").length) {
                    d.class_section_id = $(
                        "#students_list_filter_class_section_id"
                    ).val();
                }
                if ($("#students_list_filter_admission_no").length) {
                    d.admission_no = $(
                        "#students_list_filter_admission_no"
                    ).val();
                }
                if ($("#students_list_filter_roll_no").length) {
                    d.roll_no = $("#students_list_filter_roll_no").val();
                }

                if ($("#class_level_id").length) {
                    d.class_level_id = $("#class_level_id").val();
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
                data: "rownum",
                name: "rownum",
                orderable: false,
                searchable: false,
            },
            {
                data: "student_name",
                name: "student_name",
                orderable: false,
            },
            {
                data: "birth_date",
                name: "birth_date",
                orderable: false,
                searchable: false,
            },
            {
                data: "father_name",
                name: "father_name",
                orderable: false,
            },
            {
                data: "roll_no",
                name: "roll_no",
                orderable: false,
            },
            {
                data: "slc_no",
                name: "slc_no",
                orderable: false,
                searchable: false,
            },
            {
                data: "admission_class",
                name: "admission_class",
                orderable: false,
                searchable: false,
            },
            {
                data: "leaving_class",
                name: "leaving_class",
                orderable: false,
                searchable: false,
            },
            {
                data: "date_of_leaving",
                name: "date_of_leaving",
                orderable: false,
                searchable: false,
            },
            {
                data: "remarks",
                name: "remarks",
                orderable: false,
                searchable: false,
            },
        ],
    });

    $(document).on(
        "change",
        "#students_list_filter_campus_id,#students_list_filter_class_id,#class_level_id",
        function () {
            withdraw_students.ajax.reload();
        }
    );
    $(document).on(
        "keyup",
        "#students_list_filter_admission_no,#students_list_filter_roll_no",
        function () {
            withdraw_students.ajax.reload();
        }
    );

    

    //withdrawal_register_table
    var withdrawal_register_table = $("#withdrawal_register_table").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "/withdrawal_register",
            data: function (d) {
                if ($("#campus_id").length) {
                    d.campus_id = $("#campus_id").val();
                }
                if ($("#class_level_id").length) {
                    d.class_level_id = $("#class_level_id").val();
                }
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
                data: "admission_date",
                name: "admission_date",
                orderable: false,
                searchable: false,
            },
            {
                data: "rownum",
                name: "rownum",
                orderable: false,
                searchable: false,
            },
            {
                data: "student_name",
                name: "student_name",
                orderable: false,
            },
            {
                data: "birth_date",
                name: "birth_date",
                orderable: false,
                searchable: false,
            },
            {
                data: "father_name",
                name: "father_name",
                orderable: false,
            },
            {
                data: "father_occupation",
                name: "father_occupation",
                orderable: false,
                searchable: false,
            },
            {
                data: "std_permanent_address",
                name: "std_permanent_address",
                orderable: false,
                searchable: false,
            },
            {
                data: "admission_class",
                name: "admission_class",
                orderable: false,
                searchable: false,
            },
            {
                data: "leaving_class",
                name: "leaving_class",
                orderable: false,
                searchable: false,
            },
            {
                data: "date_of_leaving",
                name: "date_of_leaving",
                orderable: false,
                searchable: false,
            },
            {
                data: "remarks",
                name: "remarks",
                orderable: false,
                searchable: false,
            },
        ],
    });

    $(document).on("change", "#campus_id,#class_level_id", function () {
        withdrawal_register_table.ajax.reload();
    });
    $(document).on("submit", "form#withdrawal_update_form", function (e) {
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
                    $("div.withdrawal_modal").modal("hide");
                    toastr.success(result.msg);
                    withdrawal_register_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });
    $(document).on("click", "a.withdrawal_register_print", function (e) {
        e.preventDefault();

        var campus_id = $("#campus_id").val();
        var class_level_id = $("#class_level_id").val();

        var href = $(this).data("href");
        console.log(href);
        $.ajax({
            method: "GET",
            url: href,
            dataType: "json",
            data: { campus_id: campus_id, class_level_id: class_level_id },
            success: function (result) {
                $(".pace-active");
                if (result.success == 1 && result.receipt.html_content != "") {
                    $("#receipt_section").html(result.receipt.html_content);
                    __currency_convert_recursively($("#receipt_section"));

                    var title = document.title;
                    if (typeof result.receipt.print_title != "undefined") {
                        document.title = result.receipt.print_title;
                    }
                    if (typeof result.print_title != "undefined") {
                        document.title = result.print_title;
                    }

                    __print_receipt("receipt_section");

                    setTimeout(function () {
                        document.title = title;
                    }, 1200);
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });
});
