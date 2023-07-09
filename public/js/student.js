$(document).ready(function() {
    //students_table
    var students_table = $("#students_table").DataTable({
        processing: true,
        serverSide: true,
        scrollY: "75vh",
        scrollX: true,
        scrollCollapse: false,
        ajax: {
            url: "/students",
            data: function(d) {
                if ($("#students_list_filter_campus_id").length) {
                    d.campus_id = $("#students_list_filter_campus_id").val();
                }
                if ($("#only_transport:checked").length) {
                    d.only_transport = $("#only_transport").val();
                }
                if ($("#students_list_filter_status").length) {
                    d.status = $("#students_list_filter_status").val();
                }
                if ($("#students_list_filter_class_id").length) {
                    d.class_id = $("#students_list_filter_class_id").val();
                }
                if ($("#students_list_filter_class_section_id").length) {
                    d.class_section_id = $(
                        "#students_list_filter_class_section_id"
                    ).val();
                }
                if ($("#students_list_filter_vehicle_id").length) {
                    d.vehicle_id = $("#students_list_filter_vehicle_id").val();
                }
                if ($("#students_list_filter_admission_no").length) {
                    d.admission_no = $(
                        "#students_list_filter_admission_no"
                    ).val();
                }
                if ($("#students_list_filter_roll_no").length) {
                    d.roll_no = $("#students_list_filter_roll_no").val();
                }
                d = __datatable_ajax_callback(d);
            },
        },

        columns: [{
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
            },

            {
                data: "student_name",
                name: "student_name",
            },
            {
                data: "gender",
                name: "gender",
                orderable: false,
                searchable: false,
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
            },
            {
                data: "status",
                name: "status",
                orderable: false,
                searchable: false,
            },
            {
                data: "roll_no",
                name: "roll_no",
            },
            {
                data: "old_roll_no",
                name: "old_roll_no",
            },
            {
                data: "mobile_no",
                name: "mobile_no",
            },
            {
                data: "std_permanent_address",
                name: "std_permanent_address",
            },
            {
                data: "admission_no",
                name: "admission_no",
            },
            {
                data: "admission_date",
                name: "admission_date",
            },
            {
                data: "campus_name",
                name: "campus_name",
                orderable: false,
                searchable: false,
            },
            {
                data: "adm_class",
                name: "adm_class",
                orderable: false,
                searchable: false,
            },
            {
                data: "current_class",
                name: "current_class",
                orderable: false,
                searchable: false,
            },
            {
                data: "vehicle_name",
                name: "vehicle_name",
                orderable: false,
                searchable: false,
            },
            {
                data: "student_tuition_fee",
                name: "student_tuition_fee",
                orderable: false,
                searchable: false,
            },
            {
                data: "student_transport_fee",
                name: "student_transport_fee",
                orderable: false,
                searchable: false,
            },
            {
                data: "total_due_transport_fee",
                name: "total_due_transport_fee",
                orderable: false,
                searchable: false,
            },
            {
                data: "total_due",
                name: "total_due",
                orderable: false,
                searchable: false,
            },
        ],
        fnDrawCallback: function(oSettings) {
            __currency_convert_recursively($("#students_table"));
        },
        footerCallback: function(row, data, start, end, display) {
            var total_student_tuition_fee = 0;
            var total_student_transport_fee = 0;
            var total_due = 0;
            var total_due_transport_fee = 0;
            for (var r in data) {
                total_student_tuition_fee += $(
                        data[r].student_tuition_fee
                    ).data("orig-value") ?
                    parseFloat(
                        $(data[r].student_tuition_fee).data("orig-value")
                    ) :
                    0;
                total_student_transport_fee += $(
                        data[r].student_transport_fee
                    ).data("orig-value") ?
                    parseFloat(
                        $(data[r].student_transport_fee).data("orig-value")
                    ) :
                    0;
                total_due += $(data[r].total_due).data("orig-value") ?
                    parseFloat($(data[r].total_due).data("orig-value")) :
                    0;
                total_due_transport_fee += $(data[r].total_due_transport_fee).data("orig-value") ?
                    parseFloat($(data[r].total_due_transport_fee).data("orig-value")) :
                    0;
            }
            $(".footer_student_tuition_fee").html(
                __currency_trans_from_en(total_student_tuition_fee)
            );
            $(".footer_student_transport_fee").html(
                __currency_trans_from_en(total_student_transport_fee)
            );
            $(".footer_total_due").html(__currency_trans_from_en(total_due));
            $(".footer_total_due_transport_fee").html(__currency_trans_from_en(total_due_transport_fee));
        },
    });

    $(document).on("click", ".update_status", function(e) {
        e.preventDefault();
        $("#update_student_status_form")
            .find("#status")
            .val($(this).data("status"));
        $("#update_student_status_form")
            .find("#student_id")
            .val($(this).data("student_id"));
        $("#update_student_status_modal").modal("show");
    });

    $(document).on("submit", "#update_student_status_form", function(e) {
        e.preventDefault();
        var form = $(this);
        var data = form.serialize();

        $.ajax({
            method: "POST",
            url: $(this).attr("action"),
            dataType: "json",
            data: data,
            beforeSend: function(xhr) {
                __disable_submit_button(form.find('button[type="submit"]'));
            },
            success: function(result) {
                if (result.success == true) {
                    $("#update_student_status_modal").modal("hide");
                    toastr.success(result.msg);
                    students_table.ajax.reload();
                    $("#update_student_status_form")
                        .find('button[type="submit"]')
                        .attr("disabled", false);
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });

    $(document).on("change", "input.amount,input.fee-head-check", function() {
        var total = 0;
        var table = $(this).closest("table");
        table.find("tbody tr").each(function() {
            if ($(this).find("input.fee-head-check").is(":checked")) {
                var line = __read_number($(this).find("input.amount"));
                var subtotal = line;
                total = total + subtotal;
            }
        });
        table
            .find("span.final_total")
            .text(__currency_trans_from_en(total, true));
        $("input#final_total").val(total);
    });

    $(document).on(
        "change",
        "#students_list_filter_campus_id,#students_list_filter_class_id,#students_list_filter_class_section_id,#students_list_filter_status,#only_transport,#students_list_filter_vehicle_id",
        function() {
            students_table.ajax.reload();
        }
    );
    $(document).on(
        "keyup",
        "#students_list_filter_admission_no,#students_list_filter_roll_no",
        function() {
            students_table.ajax.reload();
        }
    );

    $(document).on("click", "a.admission_add_button", function() {
        $("div.admission_fee_modal").load($(this).data("href"), function() {
            $(this).modal("show");

            $("form#admission_fee_add_form").submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var data = form.serialize();
                var boxes = $(".fee-head-check");
                if (boxes.length > 0) {
                    if ($(".fee-head-check:checked").length < 1) {
                        toastr.error(LANG.fee_heads);
                        boxes[0].focus();
                        return false;
                    }
                }
                $.ajax({
                    method: "POST",
                    url: $(this).attr("action"),
                    dataType: "json",
                    data: data,
                    beforeSend: function(xhr) {
                        __disable_submit_button(
                            form.find('button[type="submit"]')
                        );
                    },
                    success: function(result) {
                        if (result.success == true) {
                            $("div.admission_fee_modal").modal("hide");;
                            toastr.success(result.msg);
                            students_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });
        });
    });

    ///students
    $(".student_mobile").intlTelInput({
        initialCountry: "pk",
        separateDialCode: true,
        hiddenInput: "mobile_no",

        utilsScript: base_path + "/js/intl-tel-input/utils.js",
    });

    $(".mother_mobile").intlTelInput({
        initialCountry: "pk",
        separateDialCode: true,
        hiddenInput: "mother_phone",

        utilsScript: base_path + "/js/intl-tel-input/utils.js",
    });

    $(".father_mobile").intlTelInput({
        initialCountry: "pk",
        separateDialCode: true,
        hiddenInput: "father_phone",

        utilsScript: base_path + "/js/intl-tel-input/utils.js",
    });

    $(".guardian_mobile").intlTelInput({
        initialCountry: "pk",
        separateDialCode: true,
        hiddenInput: "guardian[guardian_phone]",

        utilsScript: base_path + "/js/intl-tel-input/utils.js",
    });

    $(document).on("change", "#region_ids", function() {
        var doc = $(this);
        __get_region_transport(doc);
    });
    $(".date-picker").datepicker();
    if ($(".cnic").length > 0) {
        $(".cnic").inputmask("99999-9999999-9");
        // $('.mobile').inputmask('09999999999');
    }
    $(".upload_student_image").on("change", function() {
        __readURL(this, ".student_image");
    });
    //toggle the component with class accordion_body
    $(".accordion-panel").on("click", ".accordion__header", function() {
        $(".accordion__body").slideUp().removeClass("flipInX");
        $(".accordion__button").removeClass("bx-plus");
        if ($(this).next().is(":hidden")) {
            $(this).next().slideDown().addClass("flipInX");
            $(this).find(".accordion__button").addClass("bx-minus");
        } else {
            $(this).next().slideUp();
            $(this).find(".accordion__button").addClass("bx-plus");
        }
    });
    $(document).on("change", ".campuses", function() {
        var doc = $(this);
        get_campus_class(doc);
    });
    $(document).on("change", ".class_sections", function() {
        var doc = $(this);
        getByClassAndSection(doc);
        var subjectExist = document.getElementById("global-section-subjects");
        if(subjectExist){
            get_section_subjects(doc);
        }
    });
    $(document).on("change", ".classes", function() {
        var doc = $(this);
        get_class_fee(doc);
        get_class_Section(doc);
    });
    $(document).on("change", "#session_id", function() {
        get_get_roll_no();
    });
    function get_section_subjects(doc) {
        var class_id = doc.closest(".row").find(".classes").val();
        var class_section_id = doc.closest(".row").find(".class_sections").val();
        $.ajax({
            method: "GET",
            url: "/get-section-subjects",
            dataType: "html",
            data: {
                class_id: class_id,
                class_section_id:class_section_id,
            },
            success: function (result) {
                if (result) {
                    doc.closest(".row").find(".global-section-subjects").html(result);
                    doc.closest(".row").find("#multi_subject_ids").html(result);
                }
            },
        });
    }
    function getByClassAndSection(doc) {
        var class_id = doc.closest(".row").find(".classes").val();
        var section_id = doc.closest(".row").find(".class_sections").val();
        $.ajax({
            method: "GET",
            url: "/student/getByClassAndSection",
            dataType: "html",
            data: {
                class_id: class_id,
                section_id: section_id,
            },
            success: function(result) {
                if (result) {
                    doc.closest(".row")
                        .find("#sibiling_student_id")
                        .html(result);
                }
            },
        });
    }

    function get_campus_class(doc) {
        //Add dropdown for sub units if sub unit field is visible
        var campus_id = doc.closest(".row").find(".campuses").val();
        $.ajax({
            method: "GET",
            url: "/classes/get_campus_classes",
            dataType: "html",
            data: {
                campus_id: campus_id,
            },
            success: function(result) {
                if (result) {
                    doc.closest(".row").find(".classes").html(result);
                }
            },
        });
    }

    function get_class_Section(doc) {
        //Add dropdown for sub units if sub unit field is visible
        var class_id = doc.closest(".row").find(".classes").val();
        $.ajax({
            method: "GET",
            url: "/classes/get_class_section",
            dataType: "html",
            data: {
                class_id: class_id,
            },
            success: function(result) {
                if (result) {
                    doc.closest(".row").find(".class_sections").html(result);
                }
            },
        });
    }

    function get_class_fee(doc) {
        //Add dropdown for sub units if sub unit field is visible
        var class_id = doc.closest(".row").find(".classes").val();
        $.ajax({
            method: "GET",
            url: "/classes/get_class_fee",
            dataType: "json",
            data: {
                class_id: class_id,
            },
            success: function(result) {
                if (result) {
                    $("#student_tuition_fee").val(result.tuition_fee);
                    $("#student_transport_fee").val(result.transport_fee);
                }
            },
        });
    }

    function get_get_roll_no() {
        //Add dropdown for get_roll_no if sub unit field is visible
        var session_id = $("#session_id").val();
        $.ajax({
            method: "GET",
            url: "/sessions/get_roll_no",
            dataType: "html",
            data: {
                session_id: session_id,
            },
            success: function(result) {
                if (result) {
                    $("#roll_no").val(result);
                    $("#student_email").val(result + "@edu.pk");
                }
            },
        });
    }
    $("form#student_add_form").validate({
        rules: {
            first_name: {
                required: true,
            },
        },
        messages: {
            first_name: {
                required: "Please enter first name",
            },
        },
    });
    $('input:radio[name="guardian_is"]').change(function() {
        if ($(this).is(":checked")) {
            var value = $(this).val();
            if (value == "Father") {
                $("#guardian_name").val($("#father_name").val());
                $("#guardian_phone").val($("#father_phone").val());
                $("#guardian_occupation").val($("#father_occupation").val());
                $("#guardian_relation").val("Father");
            } else if (value == "Mother") {
                $("#guardian_name").val($("#mother_name").val());
                $("#guardian_phone").val($("#mother_phone").val());
                $("#guardian_occupation").val($("#mother_occupation").val());
                $("#guardian_relation").val("Mother");
            } else {
                $("#guardian_name").val("");
                $("#guardian_phone").val("");
                $("#guardian_occupation").val("");
                $("#guardian_relation").val("");
            }
        }
    });
    $('input:radio[name="is_transport"]').change(function() {
        if ($(this).is(":checked")) {
            var value = $(this).val();
            if (value == 0) {
                $("#student_transport_fee").val(0);
                $("#student_transport_fee").attr("readonly", true);
                $("#vehicle_id").attr("readonly", true);
            } else {
                $("#student_transport_fee").removeAttr("readonly");
                $("#vehicle_id").removeAttr("readonly");
            }
        }
    });

    $(document).on("click", ".add_sibling", function() {
        var student_id = $("#sibiling_student_id").val();

        if (student_id.length > 0) {
            $.ajax({
                type: "GET",
                url: "/student/getStudentRecordByID",
                data: {
                    student_id: student_id,
                },
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    $("#sibling_name").text("Sibling: " + data.full_name);
                    $("#sibling_id").val(student_id);
                    $("#guardian_link_id").val(data.guardian.guardian_id);
                    $(".remove").remove();
                    $(".sibling_modal").modal("hide");
                },
            });
        } else {
            $(".sibling_msg").html(
                "<div class='alert alert-danger text-center'>'no_student_selected'</div>"
            );
        }
    });

    $(document).on("click", ".remove-sibling", function() {
        var removesibling = $("#remove-sibling").val("remove-sibling");
        $(".remove-sibling ,.sibling_name ").hide();
    });
});