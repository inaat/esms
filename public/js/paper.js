$(document).ready(function () {
    // all chapters
     // Fees Assign
     $("#allchap").on("click", function() {
        $(".chapterName").prop("checked", this.checked);
    });

    $(".chapterName").on("click", function() {
        if (!$(this).is(":checked")) {
            $("#allchap").prop("checked", false);
        }
        var numberOfChecked = $(".chapterName:checked").length;
        var totalCheckboxes = $(".chapterName").length;
        var totalCheckboxes = totalCheckboxes - 1;

        if (numberOfChecked == totalCheckboxes) {
            $("#allchap").prop("checked", true);
        }
    });
   
    //Validate minimum selling price if hidden
    mark_form_obj = $('form#get_chapter_question_form');

    mark_form_validator = mark_form_obj.validate({
        submitHandler: function(form) {
            form.submit();
        }
    , });
    $("form#get_chapter_form").validate();

    $('form#get_chapter_form').on("submit", function(e) {
        e.preventDefault();
        var form = $(this);
        var data = form.serialize();

        $.ajax({
            method: "GET"
            , url: $(this).attr("action")
            , dataType: "html"
            , data: data
            , beforeSend: function(xhr) {
                __disable_submit_button(form.find('button[type="submit"]'));
            }
            , success: function(result) {
                __enable_submit_button(form.find('button[type="submit"]'));
                if (result) {

                    $("#ChapTopicGrid").html(result);


                }
            }
        , });
    });



    ///paper selection question validate
    $('input.mcq_question').unbind('change').bind('change', function() {
        var select_mcqs = $('input.mcq_question:checked').length;
        var mcq_question_number = $('#mcq_question_number').val();
        if (select_mcqs > mcq_question_number) {
            $(this).prop('checked', false);
            toastr.error('You cannot select more questions' + mcq_question_number);
        }

    });
    $('input.fill_in_the_blank_question').unbind('change').bind('change', function() {
        var select_fill_in_the_blank_question = $('input.fill_in_the_blank_question:checked')
            .length;

        var fill_in_the_blank_question_number = $('#fill_in_blanks_question_number').val();
        if (select_fill_in_the_blank_question > fill_in_the_blank_question_number) {
            $(this).prop('checked', false);

            toastr.error('You cannot select more questions' + fill_in_the_blank_question_number);

        }
    });
    $('input.true_and_false_question').unbind('change').bind('change', function() {
        var select_true_and_false_question = $('input.true_and_false_question:checked').length;

        var true_and_false_question_number = $('#true_and_false_question_number').val();
        if (select_true_and_false_question > true_and_false_question_number) {
            $(this).prop('checked', false);

            toastr.error('You cannot select more questions' + true_and_false_question_number);

        }
    });
    $('input.short_question_question').unbind('change').bind('change', function() {
        var select_short_question_question = $('input.short_question_question:checked').length;
        var short_question_question_number = $('#short_question_question_number').val();
        if (select_short_question_question > short_question_question_number) {
            $(this).prop('checked', false);

            toastr.error('You cannot select more questions' + short_question_question_number);

        }
    });
    $('input.long_question_question').unbind('change').bind('change', function() {
        var select_long_question_question = $('input.long_question_question:checked').length;
        var long_question_question_number = $('#long_question_question_number').val();
        if (select_long_question_question > long_question_question_number) {
            $(this).prop('checked', false);

            toastr.error('You cannot select more questions' + long_question_question_number);

        }
    });




    $(document).on('keyup', 'input.bindQs,input.questionNumber,input.QuestionMarks ,input.choiceQuestion', function() {
        var total = 0;
        var questionNumber = 0;
        var table = $(this).closest('table');
        var row = $(this).closest('tr');
        table.find('tbody tr').each(function() {

            var check = $(this).find('input.bindQs').is(':checked');
            if (check) {
                $(this).find('input.QuestionMarks').attr('required', true);

            } else {
                $(this).find('input.QuestionMarks').attr('required', false);

            }
            if ($(this).find('input.bindQs').is(':checked')) {
                var questionNumber = $(this).find('input.questionNumber').val() ? parseInt(
                    $(this)
                    .find('input.questionNumber').val()) : 0;
                var QuestionMarks = $(this).find('input.QuestionMarks').val() ? parseInt($(
                        this)
                    .find('input.QuestionMarks').val()) : 0;


                var choiceQuestion = $(this).find('input.choiceQuestion').val() ? parseInt($(
                        this)
                    .find('input.choiceQuestion').val()) : 0;
                if (QuestionMarks > 0) {
                    __write_number(
                        $(this).find('input.TotalQuesMarks'), (questionNumber * QuestionMarks) - (choiceQuestion * QuestionMarks)
                    );
                    total = total + (questionNumber * QuestionMarks) - (choiceQuestion * QuestionMarks);

                }
            }
        });
        $('input#overallMark').val(total);


    });

    $('button#generate_paper').click(function() {
        if ($('form#post_generate_manual_paper_form').length > 0) {
            var url = base_path + '/paper/preview?' + $('form#post_generate_manual_paper_form').serialize();

            window.open(url, 'newwindow');


        } else {
            swal(LANG.label_no_product_error).then(value => {
                $('#search_product_for_label').focus();
            });
        }
    });

    $(document).on('click', 'button#print_label', function() {
        window.print();
    });
  
  

    $("form#post_generate_manual_paper_form").validate();

});
