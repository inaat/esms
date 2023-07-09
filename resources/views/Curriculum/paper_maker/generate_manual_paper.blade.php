<!DOCTYPE html  >
<html >
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.paper')</title>
<style>
   

</style>
<link href="{{ asset('assets/css/rtl/paper.css?v=' . $asset_v) }}"rel="stylesheet" />
<link href="{{ asset('/js/tinymce/matheditor/html/css/math.css')}}" rel="stylesheet" />
<script src="{{ asset('assets/js/jquery.min.js?v=' . $asset_v) }}"></script>

</head>
@php
$question_count = 1;
$page_break_count=0;
$already_break=0;
@endphp


<body>
    <button class="print_data" onclick="printData()">Print me</button>

    <div class="page" size="A4" id="page">
     <div class="paper_top" >
                @include('common.logo_with_height')
                <h3 style="text-align: center;margin:0px;padding:0px;"><b> MAT (I)  (FEB 2023) <b></h3>
                <h3 style="text-align: center;margin:0px;padding:0px;"><b>Paper <strong>{{ $class_subject->name }}</strong> for Class {{ $class_subject->classes->title }}  <b></h3>

                <span style="margin:0px;padding:0px;display: inline;float: left;">Time: {{ $input['paper_time'] }}</span>
                <span style="margin:0px;padding:0px;display: inline;float: right;">@lang('english.total_marks'):
                    {{ $input['paper_total_marks'] }}</span>
            </div>
    @include('Curriculum.paper_maker.print_partials.mcq')
    @include('Curriculum.paper_maker.print_partials.fill_in_the_blank')
    @include('Curriculum.paper_maker.print_partials.true_and_false')
    @include('Curriculum.paper_maker.print_partials.column_matching')
    @include('Curriculum.paper_maker.print_partials.words_and_use')
    @include('Curriculum.paper_maker.print_partials.paraphrase')
    @include('Curriculum.paper_maker.print_partials.passage')
    @include('Curriculum.paper_maker.print_partials.stanza')
    @include('Curriculum.paper_maker.print_partials.short')
    @include('Curriculum.paper_maker.print_partials.long')
    @include('Curriculum.paper_maker.print_partials.translation_to_urdu')
    @include('Curriculum.paper_maker.print_partials.translation_to_english')
    @include('Curriculum.paper_maker.print_partials.grammar')
    @include('Curriculum.paper_maker.print_partials.contextual')
    @include('Curriculum.paper_maker.print_partials.singular_and_plural')
    @include('Curriculum.paper_maker.print_partials.masculine_and_feminine')
     

    </div>



    <script>
        function printData() {
            checkPto('mcq_pto');
            checkPto('fill_pto');
            checkPto('true_pto');
            checkPto('short_pto');
            checkPto('long_pto');


            window.print();
        }

        function checkPto(idpto) {
            var checkpto = $('#' + idpto);
            if (checkpto.text().length > 1 && checkpto) {
                checkpto.closest(".find").find(".custom_break").addClass("pagebreak");;
            }

        }

    </script>
</body>

</html>

