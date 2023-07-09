//This file contains all functions used in the app.

function __calculate_amount(calculation_type, calculation_amount, amount) {
    var calculation_amount = parseFloat(calculation_amount);
    calculation_amount = isNaN(calculation_amount) ? 0 : calculation_amount;

    var amount = parseFloat(amount);
    amount = isNaN(amount) ? 0 : amount;

    switch (calculation_type) {
        case "fixed":
            return parseFloat(calculation_amount);
        case "percentage":
        case "percent":
            var div = Decimal.div(calculation_amount, 100).toNumber();
            return Decimal.mul(div, amount).toNumber();
        default:
            return 0;
    }
}
function __readURL(input, dest) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        console.log(reader);
        reader.onload = function (e) {
            $(dest).attr("src", e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}
//Add specified percentage to the input amount.
function __add_percent(amount, percentage = 0) {
    var amount = parseFloat(amount);
    var percentage = isNaN(percentage) ? 0 : parseFloat(percentage);

    var div = Decimal.div(percentage, 100).toNumber();
    var mul = Decimal.mul(div, amount).toNumber();
    return Decimal.add(amount, mul).toNumber();
}

//Substract specified percentage to the input amount.
function __substract_percent(amount, percentage = 0) {
    var amount = parseFloat(amount);
    var percentage = isNaN(percentage) ? 0 : parseFloat(percentage);

    var div = Decimal.div(percentage, 100).toNumber();
    var mul = Decimal.mul(div, amount).toNumber();
    return Decimal.sub(amount, mul).toNumber();
}

//Returns the principle amount for the calculated amount and percentage
function __get_principle(amount, percentage = 0, minus = false) {
    var amount = parseFloat(amount);
    var percentage = isNaN(percentage) ? 0 : parseFloat(percentage);
    var mul = Decimal.mul(100, amount).toNumber();
    var sum = 1;
    if (minus) {
        sum = Decimal.sub(100, percentage).toNumber();
    } else {
        sum = Decimal.add(100, percentage).toNumber();
    }
    return Decimal.div(mul, sum).toNumber();
}

//Returns the rate at which amount is calculated from principal
function __get_rate(principal, amount) {
    var principal = isNaN(principal) ? 0 : parseFloat(principal);
    var amount = isNaN(amount) ? 0 : parseFloat(amount);
    var interest = Decimal.sub(amount, principal).toNumber();
    var div = Decimal.div(interest, principal).toNumber();
    return Decimal.mul(div, 100).toNumber();
}

function __tab_key_up(e) {
    if (e.keyCode == 9) {
        return true;
    }
}

function __currency_trans_from_en(
    input,
    show_symbol = true,
    use_page_currency = false,
    precision = __currency_precision,
    is_quantity = false
) {
    if (use_page_currency && __p_currency_symbol) {
        var s = __p_currency_symbol;
        var thousand = __p_currency_thousand_separator;
        var decimal = __p_currency_decimal_separator;
    } else {
        var s = __currency_symbol;
        var thousand = __currency_thousand_separator;
        var decimal = __currency_decimal_separator;
    }

    symbol = "";
    var format = "%s%v";
    if (show_symbol) {
        symbol = s;
        format = "%s %v";
        if (__currency_symbol_placement == "after") {
            format = "%v %s";
        }
    }

    if (is_quantity) {
        precision = __quantity_precision;
    }

    return accounting.formatMoney(
        input,
        symbol,
        precision,
        thousand,
        decimal,
        format
    );
}

function __currency_convert_recursively(element, use_page_currency = false) {
    element.find(".display_currency").each(function () {
        var value = $(this).text();

        var show_symbol = $(this).data("currency_symbol");
        if (show_symbol == undefined || show_symbol != true) {
            show_symbol = false;
        }

        var highlight = $(this).data("highlight");
        if (highlight == true) {
            __highlight(value, $(this));
        }

        var is_quantity = $(this).data("is_quantity");
        if (is_quantity == undefined || is_quantity != true) {
            is_quantity = false;
        }

        if (is_quantity) {
            show_symbol = false;
        }

        $(this).text(
            __currency_trans_from_en(
                value,
                show_symbol,
                use_page_currency,
                __currency_precision,
                is_quantity
            )
        );
    });
}

function __translate(str, obj = []) {
    var trans = LANG[str];
    $.each(obj, function (key, value) {
        trans = trans.replace(":" + key, value);
    });
    if (trans) {
        return trans;
    } else {
        return str;
    }
}

//If the value is positive, text-success class will be applied else text-danger
function __highlight(value, obj) {
    obj.removeClass("text-success").removeClass("text-danger");
    if (value > 0) {
        obj.addClass("text-success");
    } else if (value < 0) {
        obj.addClass("text-danger");
    }
}

//Unformats the currency/number
function __number_uf(input, use_page_currency = false) {
    if (use_page_currency && __currency_decimal_separator) {
        var decimal = __p_currency_decimal_separator;
    } else {
        var decimal = __currency_decimal_separator;
    }

    return accounting.unformat(input, decimal);
}

//Alias of currency format, formats number
function __number_f(
    input,
    show_symbol = false,
    use_page_currency = false,
    precision = __currency_precision
) {
    return __currency_trans_from_en(
        input,
        show_symbol,
        use_page_currency,
        precision
    );
}

//Read input and convert it into natural number
function __read_number(input_element, use_page_currency = false) {
    return __number_uf(input_element.val(), use_page_currency);
}

//Write input by converting to formatted number
function __write_number(
    input_element,
    value,
    use_page_currency = false,
    precision = __currency_precision
) {
    if (input_element.hasClass("input_quantity")) {
        precision = __quantity_precision;
    }

    input_element.val(__number_f(value, false, use_page_currency, precision));
}

//Return the font-awesome html based on class value
function __fa_awesome($class = "fa-sync fa-spin fa-fw ") {
    return '<i class="fa ' + $class + '"></i>';
}

//Converts standard dates (YYYY-MM-DD) to human readable dates
function __show_date_diff_for_human(element) {
    moment.locale(app_locale);
    element.find(".time-to-now").each(function () {
        var string = $(this).text();
        $(this).text(moment(string).toNow(true));
    });

    element.find(".time-from-now").each(function () {
        var string = $(this).text();
        $(this).text(moment(string).from(moment()));
    });
}

//Rounds a number to Iraqi dinnar
function round_to_iraqi_dinnar(value) {
    //Adjsustment
    var remaining = value % 250;
    if (remaining >= 125) {
        value += 250 - remaining;
    } else {
        value -= remaining;
    }

    return value;
}

function __select2(selector) {
    if ($("html").attr("dir") == "rtl") selector.select2({ dir: "rtl" });
    else selector.select2();
}

function update_font_size() {
    var font_size = localStorage.getItem("upos_font_size");
    var font_size_array = [];
    font_size_array["s"] = " - 3px";
    font_size_array["m"] = "";
    font_size_array["l"] = " + 3px";
    font_size_array["xl"] = " + 6px";
    if (typeof font_size !== "undefined") {
        $("header").css(
            "font-size",
            "calc(100% " + font_size_array[font_size] + ")"
        );
        $("footer").css(
            "font-size",
            "calc(100% " + font_size_array[font_size] + ")"
        );
        $("section").each(function () {
            if (!$(this).hasClass("print_section")) {
                $(this).css(
                    "font-size",
                    "calc(100% " + font_size_array[font_size] + ")"
                );
            }
        });
        $("div.modal").css(
            "font-size",
            "calc(100% " + font_size_array[font_size] + ")"
        );
    }
}

function sum_table_col(table, class_name) {
    var sum = 0;
    table
        .find("tbody")
        .find("tr")
        .each(function () {
            if (
                parseFloat(
                    $(this)
                        .find("." + class_name)
                        .data("orig-value")
                )
            ) {
                sum += parseFloat(
                    $(this)
                        .find("." + class_name)
                        .data("orig-value")
                );
            }
        });

    return sum;
}

function __count_status(data, key) {
    var statuses = [];
    for (var r in data) {
        var element = $(data[r][key]);
        if (element.data("orig-value")) {
            var status_name = element.data("orig-value");
            if (!(status_name in statuses)) {
                statuses[status_name] = [];
                statuses[status_name]["count"] = 1;
                statuses[status_name]["display_name"] =
                    element.data("status-name");
            } else {
                statuses[status_name]["count"] += 1;
            }
        }
    }

    //generate html
    var html = '<p class="text-left"><small>';
    for (var key in statuses) {
        html +=
            statuses[key]["display_name"] +
            " - " +
            statuses[key]["count"] +
            "</br>";
    }

    html += "</small></p>";

    return html;
}

function __sum_status(table, class_name) {
    var statuses = [];
    var status_html = [];
    table
        .find("tbody")
        .find("tr")
        .each(function () {
            element = $(this).find("." + class_name);
            if (element.data("orig-value")) {
                var status_name = element.data("orig-value");
                if (!(status_name in statuses)) {
                    statuses[status_name] = [];
                    statuses[status_name]["count"] = 1;
                    statuses[status_name]["display_name"] =
                        element.data("status-name");
                } else {
                    statuses[status_name]["count"] += 1;
                }
            }
        });

    return statuses;
}

function __sum_status_html(table, class_name) {
    var statuses_sum = __sum_status(table, class_name);
    var status_html = '<p class="text-left"><small>';
    for (var key in statuses_sum) {
        status_html +=
            statuses_sum[key]["display_name"] +
            " - " +
            statuses_sum[key]["count"] +
            "</br>";
    }

    status_html += "</small></p>";

    return status_html;
}

function __sum_stock(table, class_name, label_direction = "right") {
    var stocks = [];
    table
        .find("tbody")
        .find("tr")
        .each(function () {
            element = $(this).find("." + class_name);
            if (element.data("orig-value")) {
                var unit_name = element.data("unit");
                if (!(unit_name in stocks)) {
                    stocks[unit_name] = parseFloat(element.data("orig-value"));
                } else {
                    stocks[unit_name] += parseFloat(element.data("orig-value"));
                }
            }
        });
    var stock_html = '<p class="text-left"><small>';

    for (var key in stocks) {
        if (label_direction == "left") {
            stock_html +=
                key +
                ' : <span class="display_currency" data-is_quantity="true">' +
                stocks[key] +
                "</span> " +
                "</br>";
        } else {
            stock_html +=
                '<span class="display_currency" data-is_quantity="true">' +
                stocks[key] +
                "</span> " +
                key +
                "</br>";
        }
    }

    stock_html += "</small></p>";

    return stock_html;
}

function __print_receipt(section_id = null) {
    if (section_id) {
        var imgs = document.getElementById(section_id).getElementsByTagName("img");
    } else {
        var imgs = document.images;
    }
    img_len = imgs.length;
    if (img_len) {
        img_counter = 0;

        [].forEach.call( imgs, function( img ) {
            img.addEventListener( 'load', incrementImageCounter, false );
        } );
    } else {
        setTimeout(function() {
            window.print();

        }, 1000);
    }
}

function incrementImageCounter() {
    img_counter++;
    if ( img_counter === img_len ) {
        window.print();
        
    }
}
function __getUnitMultiplier(row) {
    multiplier = row
        .find("select.sub_unit")
        .find(":selected")
        .data("multiplier");
    if (multiplier == undefined) {
        return 1;
    } else {
        return parseFloat(multiplier);
    }
}

//Rounds a number to the nearest given multiple
function __round(number, multiple = 0) {
    rounded_number = number;
    if (multiple > 0) {
        x = new Decimal(number);
        rounded_number = x.toNearest(multiple);
    }

    var output = {
        number: rounded_number,
        diff: rounded_number - number,
    };

    return output;
}

//This method removes unwanted get parameter from the data.
function __datatable_ajax_callback(data) {
    for (var i = 0, len = data.columns.length; i < len; i++) {
        if (!data.columns[i].search.value) delete data.columns[i].search;
        if (data.columns[i].searchable === true)
            delete data.columns[i].searchable;
        if (data.columns[i].orderable === true)
            delete data.columns[i].orderable;
        if (data.columns[i].data === data.columns[i].name)
            delete data.columns[i].name;
    }
    delete data.search.regex;

    return data;
}

//Confirmation before page load.
function __page_leave_confirmation(form) {
    var form_obj = $(form);
    var orig_form_data = form_obj.serialize();

    setTimeout(function () {
        orig_form_data = form_obj.serialize();
    }, 1000);

    $(document).on("submit", "form", function (event) {
        window.onbeforeunload = null;
    });
    window.onbeforeunload = function () {
        if (form_obj.serialize() != orig_form_data) {
            return LANG.sure;
        }
    };
}
function insert_contents(inst) {
    inst.setContent('لکھنا شروع کریں');
}
//initialize tinyMCE editor for invoice template
function init_tinymce(editor_id) {
    // tinymce.init({
    //     selector: "textarea#" + editor_id,
    //     plugins: [
    //         "advlist autolink link image lists charmap print preview hr anchor pagebreak",
    //         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
    //         "table template paste help",
    //     ],
    //     toolbar:
    //         "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify |" +
    //         " bullist numlist outdent indent | link image | print preview fullpage | " +
    //         "forecolor backcolor",
    //     menu: {
    //         favs: { title: "My Favorites", items: "code | searchreplace" },
    //     },
    //     menubar: "favs file edit view insert format tools table help",
    // });
    tinyMCE.init({
        selector: "textarea#" + editor_id,
        plugins: 'lists media table',
        // toolbar: 'ltr rtl a11ycheck checklist code formatpainter pageembed permanentpen table',
        toolbar: 'fullscreen print forecolor backcolor removeformat | undo redo | bold italic underline strikethrough fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor | ltr rtl',
        // toolbar_location: 'bottom',
        toolbar_mode: 'floating',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Write Urdu',
        directionality: "rtl",
        content_style: "@import url('https://fonts.googleapis.com/earlyaccess/notonastaliqurdu.css?family=Noto Nastaliq Urdu&display=Noto Nastaliq Urdu);body { font-family: 'Noto Nastaliq Urdu'; }",
        font_formats: "Noto Nastaliq Urdu;Noto Naskh Arabic;Amiri;Harmattan;Katibeh;Lateef;Scheherazade;Tajawal;Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Oswald=oswald; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats",
        "content_css": 'https://fonts.googleapis.com/earlyaccess/notonastaliqurdu.css,https://fonts.googleapis.com/earlyaccess/notonaskharabic.css,https://fonts.googleapis.com/css2?family=Amiri&family=Harmattan:wght@400;700&family=Katibeh&family=Lateef&family=Markazi+Text&family=Scheherazade:wght@400;700&family=Tajawal:wght@300;400&display=swap" rel="stylesheet',
        setup: function (ed) {
            ed.on('init', function () {

                $(this.getDoc()).find('head').append("<style>p{margin:0; font-size: 12px !important;font-family: 'Noto Nastaliq Urdu';}</style>");
                $('#spinner').hide();
                //  $('[data-toggle="tooltip"]').tooltip();
            });
        },
       // init_instance_callback: "insert_contents",
    });
 
}

function getSelectedRows() {
    var selected_rows = [];
    var i = 0;
    $(".row-select:checked").each(function () {
        selected_rows[i++] = $(this).val();
    });

    return selected_rows;
}

// function __is_online() {
//     return window.navigator.onLine;
// }
function __is_online() {
    return true;
}

function __disable_submit_button(element) {
    element.attr("disable", true);
}
function __enable_submit_button(element) {
    element.removeAttr("disabled");
}
function __removeAttr(element) {
    element.removeAttr("required");
}

//Add dropdown for periods
function __get_subjects(doc) {
    var class_id = doc.closest(".row").find(".global-classes").val();
    $.ajax({
        method: "GET",
        url: "/get-subjects",
        dataType: "html",
        data: {
            class_id: class_id,
        },
        success: function (result) {
            if (result) {
                doc.closest(".row").find(".global-subjects").html(result);
            }
        },
    });
}
//Add dropdown for periods
function __get_section_subjects(doc) {
    var class_id = doc.closest(".row").find(".global-classes").val();
    var class_section_id = doc.closest(".row").find(".global-class_sections").val();
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
//Add dropdown for periods
function __get_periods(doc) {
    var campus_id = doc.closest(".row").find(".global-campuses").val();
    $.ajax({
        method: "GET",
        url: "/get-periods",
        dataType: "html",
        data: {
            campus_id: campus_id,
        },
        success: function (result) {
            if (result) {
                doc.closest(".row").find(".global-periods").html(result);
            }
        },
    });
}
//Add dropdown for chapter-lesson
function __get_chapter_lessons(subject_id, chapter_number) {
    $.ajax({
        method: "GET",
        url: "/get-chapter-lessons",
        dataType: "html",
        data: {
            subject_id: subject_id,
            chapter_number: chapter_number,
        },
        success: function (result) {
            if (result) {
                $(".lessons").html(result);
            }
        },
    });
}
//Add dropdown for chapter
function __get_chapters(doc,subject_id) {
    $.ajax({
        method: "GET",
        url: "/get-chapters",
        dataType: "html",
        data: {
            subject_id: subject_id,
        },
        success: function (result) {
            if (result) {
                doc.closest(".row").find(".global-chapter").html(result);
            }
        },
    });
}
//Add dropdown for Provinces
function __get_provinces() {
    var country_id = $("#country_id").val();
    $.ajax({
        method: "GET",
        url: "/get_provinces",
        dataType: "html",
        data: {
            country_id: country_id,
        },
        success: function (result) {
            if (result) {
                $("#provinces_ids").html(result);
            }
        },
    });
}
//Add dropdown for Districts
function __get_districts() {
    var province_id = $("#provinces_ids").val();
    $.ajax({
        method: "GET",
        url: "/get_districts",
        dataType: "html",
        data: {
            province_id: province_id,
        },
        success: function (result) {
            if (result) {
                $("#district_ids").html(result);
            }
        },
    });
}
//Add dropdown for Cities
function __get_cities() {
    var district_id = $("#district_ids").val();
    $.ajax({
        method: "GET",
        url: "/get_cities",
        dataType: "html",
        data: {
            district_id: district_id,
        },
        success: function (result) {
            if (result) {
                $("#city_ids").html(result);
            }
        },
    });
}
//Add dropdown for Regions
function __get_regions() {
    var city_id = $("#city_ids").val();
    $.ajax({
        method: "GET",
        url: "/get_regions",
        dataType: "html",
        data: {
            city_id: city_id,
        },
        success: function (result) {
            if (result) {
                $("#region_ids").html(result);
            }
        },
    });
}
function __get_region_transport(doc) {
    //Add dropdown for sub units if sub unit field is visible
    var region_id = doc.closest(".row")
        .find('#region_ids').val();
    $.ajax({
        method: 'GET'
        , url: '/get_regions_transport_fee'
        , dataType: 'json'
        , data: {
            region_id: region_id
        }
        , success: function(result) {
            if (result) {
                if ($('input:radio[name="is_transport"]').is(":checked")===false) {
                    $('#student_transport_fee').val(result.transport_fee) ;
                }            }
        }
    , });

}
function __get_campus_class(doc) {
    //Add dropdown for sub units if sub unit field is visible
    var campus_id = doc.closest(".row").find(".global-campuses").val();
    $.ajax({
        method: "GET",
        url: "/classes/get_campus_classes",
        dataType: "html",
        data: {
            campus_id: campus_id,
        },
        success: function (result) {
            if (result) {
                doc.closest(".row").find(".global-classes").html(result);
            }
        },
    });
}

function __get_class_Section(doc) {
    //Add dropdown for sub units if sub unit field is visible
    var class_id = doc.closest(".row").find(".global-classes").val();
    $.ajax({
        method: "GET",
        url: "/classes/get_class_section",
        dataType: "html",
        data: {
            class_id: class_id,
        },
        success: function (result) {
            if (result) {
                doc.closest(".row").find(".global-class_sections").html(result);
            }
        },
    });
}
function __printErrorMsg(msg) {
    $.each(msg, function (key, value) {
        $("." + key + "_err").text(value);
        toastr.error(value);
        $('input[name="' + key + '"]').focus();
    });
}
function __get_exam_term(doc) {
    //Add dropdown for sub units if sub unit field is visible
    var campus_id = doc.closest(".row").find(".global-campuses").val();
    var session_id = doc.closest(".row").find(".exam-session").val();
    $.ajax({
        method: "GET",
        url: "/exam/get_term",
        dataType: "html",
        data: {
            campus_id: campus_id,
            session_id: session_id
        },
        success: function (result) {
            if (result) {
                doc.closest(".row").find(".exam_term_id").html(result);
            }
        },
    });
}
///Hrm///
function __calculateTotalGrossAmount() {
    var transaction_final_total = __read_number($("#transaction_final_total"));

    let total_gross_amount = transaction_final_total;
    let total_allowance = 0;
    let total_deduction = 0;

    var allowance_table = $(".allowance-table");
    allowance_table.find("tbody tr").each(function () {
        if ($(this).find("input.allowance-check").is(":checked")) {
            var allowance = __read_number(
                $(this).find("input.allowance-amount")
            );
            total_gross_amount += allowance;
            total_allowance += allowance;
        }
    });
    $("span.allowance_final_total").text(
        __currency_trans_from_en(total_allowance, true)
    );
    __write_number($("#allowance_final_total").first(), total_allowance);

    var deduction_table = $(".deduction-table");
    deduction_table.find("tbody tr").each(function () {
        if ($(this).find("input.deduction-check").is(":checked")) {
            var deduction = __read_number(
                $(this).find("input.deduction-amount")
            );
            total_gross_amount -= deduction;
            total_deduction += deduction;
        }
    });
    $("span.deduction_final_total").text(
        __currency_trans_from_en(total_deduction, true)
    );
    __write_number($("#deduction_final_total").first(), total_deduction);
    $("span.gross_final_total").text(
        __currency_trans_from_en(total_gross_amount, true)
    );
    __write_number($("#gross_final_total"), total_gross_amount);
}
function __changeKey(evt) {
  
    var keyCode = evt.keyCode ? evt.keyCode :
      evt.charCode ? evt.charCode :
      evt.which ? evt.which : void 0;
    var key;
    if (keyCode) {
      key = String.fromCharCode(keyCode);
      switch(key) {
        case ' ': return ' ';
        case '!': return '!';
        case ':': return ':';
        case '?': return String.fromCharCode(1567);
        case '+': return String.fromCharCode(1570);
        case 'A': return String.fromCharCode(1619);
        case 's': return String.fromCharCode(1587);
        case 'S': return String.fromCharCode(1589);
        case 'd': return String.fromCharCode(1583);
        case 'D': return String.fromCharCode(1672);
        case 'f': return String.fromCharCode(1601);
        case 'g': return String.fromCharCode(1711);
        case 'G': return String.fromCharCode(1594);
        case 'h': return String.fromCharCode(1726);
        case 'H': return String.fromCharCode(1581);
        case 'j': return String.fromCharCode(1580);
        case 'J': return String.fromCharCode(1590);
        case 'k': return String.fromCharCode(1705);
        case 'K': return String.fromCharCode(1582);
        case 'l': return String.fromCharCode(1604);
        case 'L': return String.fromCharCode(1554);
        case 'z': return String.fromCharCode(1586);
        case 'Z': return String.fromCharCode(1584);
        case 'x': return String.fromCharCode(1588);
        case 'X': return String.fromCharCode(1688);
        case 'c': return String.fromCharCode(1670);
        case 'C': return String.fromCharCode(1579);
        case 'v': return String.fromCharCode(1591);
        case 'V': return String.fromCharCode(1592);
        case 'a': return String.fromCharCode(1575);
        case 'b': return String.fromCharCode(1576);
        case 'B': return String.fromCharCode(1555);
        case 'n': return String.fromCharCode(1606);
        case 'N': return String.fromCharCode(1722);
        case 'm': return String.fromCharCode(1605);
        case 'q': return String.fromCharCode(1602);
        case 'w': return String.fromCharCode(1608);
        case 'W': return String.fromCharCode(65018);
        case 'e': return String.fromCharCode(1593);
        case 'E': return String.fromCharCode(1553);
        case 'r': return String.fromCharCode(1585);
        case 'R': return String.fromCharCode(1681);
        case 't': return String.fromCharCode(1578);
        case 'T': return String.fromCharCode(1657);
        case 'y': return String.fromCharCode(1746);
        case 'Y': return String.fromCharCode(1537);
        case 'u': return String.fromCharCode(1574);

        case 'o': return String.fromCharCode(1729);
        case 'p': return String.fromCharCode(1662);
        case 'i': return String.fromCharCode(1740);
        case 'O': return String.fromCharCode(1731);
        case 'I': return String.fromCharCode(1648);
        case '$': return String.fromCharCode(1569);
        case '0': return String.fromCharCode(1776);
        case '1': return String.fromCharCode(1777);
        case '2': return String.fromCharCode(1778);
        case '3': return String.fromCharCode(1779);
        case '4': return String.fromCharCode(1780);
        case '5': return String.fromCharCode(1781);
        case '6': return String.fromCharCode(1782);
        case '7': return String.fromCharCode(1783);
        case '8': return String.fromCharCode(1784);
        case '9': return String.fromCharCode(1785);

        case '.': return String.fromCharCode(1748);
        case '\'': return String.fromCharCode(1748);
        case '\"': return String.fromCharCode(1600);
        case ';': return String.fromCharCode(1563);
        case '-': return String.fromCharCode(1652);
        case 'P': return String.fromCharCode(1615);
        case '<': return String.fromCharCode(1616);
        case '>': return String.fromCharCode(1614);
        case '=': return String.fromCharCode(1572);
        case '*': return String.fromCharCode(1612);
        case '~': return String.fromCharCode(1611);

        case '`': return String.fromCharCode(1613);
        case '_': return String.fromCharCode(1617);
        case 'Q': return String.fromCharCode(1618);
        case '/': return String.fromCharCode(1618);
        case '@': return String.fromCharCode(1548);
        case '#': return String.fromCharCode(1549);
        case '%': return String.fromCharCode(1610);
        case '^': return String.fromCharCode(1552);

        case '-': return String.fromCharCode(1571);
        case 'U': return String.fromCharCode(1623);
        case '{': return String.fromCharCode(8216);
        case '}': return String.fromCharCode(8217);
        case '\\': return String.fromCharCode(1550);
        case '|': return String.fromCharCode(1556);
        case 'F': return String.fromCharCode(1622);
        case 'M': return String.fromCharCode(1573);

        case ',': return String.fromCharCode(1548);

        case '(': return '(';
        case ')': return ')';
        case '[': return '[';
        case ']': return ']';


        /*default: return '';*/
        default: return key;
              
        /*default: return String.fromCharCode(keyCode);*/

      }

    }
  }