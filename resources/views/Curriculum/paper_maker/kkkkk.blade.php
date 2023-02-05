<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Students Particulars</title>
<style>
    @media print {
        @page {
            size: A4;
            margin: 5px;
            margin-bottom: 10px;
            margin-top: 30px;
            font-family: "Times New Roman", Times, serif;
            size: A4;
            -webkit-print-color-adjust: exact;



        }

        hr {
            background-color: #000;
        }

        [contentEditable=true]:empty:not(:focus):before {
            display: none;
        }

        .pagebreak {
            page-break-after: always;

        }

        /* page-break-after works, as well */
        .pto {
            text-align: center;
            color: red;
        }

        .print_data {
            display: none;
        }

        .page {
            background: #ffffff;
            display: block;

        }

        .page[size="A4"] {
            width: 22cm;
        }

        * {
            font-family: "Times New Roman", Times, serif;


        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-weight: inherit !important;
            font-size: inherit !important;
        }


        .row {
            margin: 8px;

        }

        p {
            font-weight: inherit !important;
            font-size: inherit !important;
        }

        .QuestHead {
            font-size: 22px !important;
        }

        .QuestHeadLeft {
            font-size: 19px !important;
        }

        p>span {
            font-weight: inherit !important;
            font-size: inherit !important;
        }




        * {
            margin: 0px;
            color: #000;
            font-family: "Times New Roman", Times, serif;
            -webkit-print-color-adjust: exact;



        }

        p {
            display: inline;
        }

        body {
            margin: 5px;
            background: #fff;
            zoom: 98%;
        }

        #tblMcqs {
            display: none;
        }


        table.TextCenter {
            text-align: center;
        }

        table {
            width: 100%;
            text-align: center;
            border-collapse: collapse;
            font-size: 16px;

        }

        table,
        th,
        td {
            border: 1.5px solid #000;
        }

        td.textleft {
            text-align: left;
        }

        td.textright {
            text-align: right;
        }

        td.halfdevider {
            width: 50%;
        }

        .th1 {
            border: none;
        }

        .tr1 {
            border: none;
        }

        .td1 {
            border: none;
        }


        #head1 {
            width: 100%;

            /* 70% of the parent*/
            background: #000;

            text-align: center;
            padding: 3px;
            margin-top: 20px;
            margin: 0px auto;
            border-radius: 5px;
            height: 20px;
            border-bottom: 1px solid #000;



        }

        #head1>h6 {
            color: #fff;
            font-size: 8px;
            -webkit-print-color-adjust: exact;
        }

        .numberCircle {
            border-radius: 50%;
            width: 36px;
            height: 36px;
            padding: 2px;

            background: #fff;
            border: 2px solid #666;
            color: #666;
            text-align: center;

        }

        span.circle {
            border: 2px solid #000;
            border-radius: 0.8em;
            -moz-border-radius: 0.8em;
            -webkit-border-radius: 0.8em;

            display: inline-block;
            font-weight: bold;
            line-height: 1em;
            text-align: center;
            width: 20px;
            height: 20px;
        }


    }

    * {
        font-family: "Times New Roman", Times, serif;



    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-weight: inherit !important;
        font-size: inherit !important;
    }



    .row {
        margin: 0px;


    }

    p {
        font-weight: inherit !important;
        font-size: inherit !important;
    }

    .QuestHead {
        font-size: 22px !important;
    }

    .QuestHeadLeft {
        font-size: 19px !important;
    }

    p>span {
        font-weight: inherit !important;
        font-size: inherit !important;
    }




    * {
        margin: 4px;
        color: #000;
        font-family: "Times New Roman", Times, serif;
        -webkit-print-color-adjust: exact;



    }

    p {
        display: inline;
    }

    body {
        margin: 5px;
        background: #fff;
        zoom: 98%;
    }

    #tblMcqs {
        display: none;
    }


    table.TextCenter {
        text-align: center;
    }

    table {
        width: 100%;
        text-align: center;
        border-collapse: collapse;
        font-size: 16px;

    }

    table,
    th,
    td {
        border: 1.5px solid #000;
    }

    td.textleft {
        text-align: left;
    }

    td.textright {
        text-align: right;
    }

    td.halfdevider {
        width: 50%;
    }

    .th1 {
        border: none;
    }

    .tr1 {
        border: none;
    }

    .td1 {
        border: none;
    }


    #head1 {
        width: 100%;

        /* 70% of the parent*/
        background: #000;

        text-align: center;
        padding: 3px;
        margin: 0px auto;
        border-radius: 5px;
        height: 20px;
        border-bottom: 1px solid #000;
        margin-top: 20px;



    }

    #head1>h6 {
        color: #fff;
        font-size: 8px;
        -webkit-print-color-adjust: exact;
    }

    .numberCircle {
        border-radius: 50%;
        width: 36px;
        height: 36px;
        padding: 2px;

        background: #fff;
        border: 2px solid #666;
        color: #666;
        text-align: center;

    }

    span.circle {
        border: 2px solid #000;
        border-radius: 0.8em;
        -moz-border-radius: 0.8em;
        -webkit-border-radius: 0.8em;

        display: inline-block;
        font-weight: bold;
        line-height: 1em;
        text-align: center;
        width: 20px;
        height: 20px;
    }

    .page {
        background: #ffffff;
        display: block;
        margin: 10px auto 10px auto;


    }

    .page[size="A4"] {
        width: 22cm;
    }

    button {
        background-color: #4CAF50;
        /* Green */
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
    }

    .pagebreak {
        page-break-after: always;

    }

    .pto {
        text-align: center;
        color: red;

    }

    [contentEditable=true]:empty:not(:focus):before {
        content: attr(data-text)
    }

    hr {
        border-top: 1px dashed #000;
    }

</style>
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
        <div>
            <div class="paper_top" style="height:100px">
                @include('common.logo_with_height')
                <h3 style="text-align: center;margin:0px;padding:0px;"><b>Paper <strong>{{ $class_subject->name }}</strong> for Class {{ $class_subject->classes->title }} Second Term Examination August 2022 <b></h3>

                <span style="margin:0px;padding:0px;display: inline;float: left;">Time: {{ $input['paper_time'] }}</span>
                <span style="margin:0px;padding:0px;display: inline;float: right;">@lang('english.total_marks'):
                    {{ $input['paper_total_marks'] }}</span>
            </div>

            @if (!empty($input['single_page']))
            <div style="display:flex;">
                <span style="margin-top:5px">@lang('english.roll_no'):</span>
                <input type="text" style="width: 30px;border-radius: 2%;">
                <input type="text" style="width: 30px;border-radius: 2%;">
                <input type="text" style="width: 30px;border-radius: 2%;">
                <input type="text" style="width: 30px;border-radius: 2%;">
                <input type="text" style="width: 30px;border-radius: 2%;">
                <input type="text" style="width: 30px;border-radius: 2%;">
                <input type="text" style="width: 30px;border-radius: 2%;">
                <input type="text" style="width: 30px;border-radius: 2%;">
                <input type="text" style="width: 30px;border-radius: 2%;">
                <span style="margin-top:5px; margin-left:10px">@lang('english.name'):___________________________________</span>

            </div>
            @endif
            @if (!empty($mcq_questions))
            @if (!empty($input['mcq_header']))
            <div id="head1">
                <h6>{{ $input['mcq_header'] }}
                </h6>
            </div>
            @endif
            <div class="row" style=" ">

                <table style="border:none">
                    <tbody>
                        <tr class="tr1">
                            <td colspan="4" class="td1 QuestHeadLeft" style="font-weight:bold;text-align:left;font-size:16px ">
                                Q.{{ $question_count }}: {{ $input['mcq_top_text'] }}
                                @php
                                $question_count++;
                                @endphp
                            </td>

                            <td colspan="4" class="QuestHeadLeft textright  td1" style="">

                                <span style="float:right;font-size:16px" id="qmrks"><strong>({{ $input['total_mcq_marks'] }})</strong></span>

                            </td>
                        </tr>

                    </tbody>
                </table>

                @foreach ($mcq_questions as $mcq)
                <table style="border:none; margin-left:15px; " class="mcqstable">
                    <tbody>
                        <tr class="tr1 mcqstable">
                            <td colspan="8" class=" halfdevider td1 mcqstable" style="text-align:left !important;font-size:14px !important">

                                <span style="font-weight:bold !important font-size:14px">
                                    {{ numberToRoman($loop->iteration) }}: &nbsp;</span>
                                {!! $mcq->question !!}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table style="border:none; margin-left:20px;" class="mcqstable">
                    <tbody>
                        <tr class="tr1 mcqstable">

                            <td style="" class="td1 mcqstable">
                                <span class="circle">a</span>

                                <span style="text-align:center;font-size:14px !important">{!! $mcq->option_a !!}</span>

                            </td>
                            <td style="" class="td1 mcqstable">
                                <span class="circle">b</span>

                                <span style="text-align:center;font-size:14px !important">{!! $mcq->option_b !!}</span>

                            </td>
                            <td style="" class="td1 mcqstable">
                                @if(!empty($mcq->option_c))

                                <span class="circle">c</span>

                                <span style="text-align:center;font-size:14px !important">{!! $mcq->option_c !!}</span>
                                @endif

                            </td>
                            <td style="" class="td1 mcqstable">
                                @if(!empty($mcq->option_d))
                                <span class="circle">d</span>
                                <span style="text-align:center;font-size:14px !important">{!! $mcq->option_d !!}</span>
                                @endif
                            </td>

                        </tr>
                    </tbody>
                </table>
                @endforeach
            </div>
            <div class="find">
                <div contenteditable="true" class="pto" id="mcq_pto" style="text-align:center;" data-text="Page break "></div>
                <div class="custom_break" id=""> </div>
            </div>
        </div>
        @endif


        @if (!empty($fill_in_the_blank_questions))
        <div class="row" style=" ">
            @if (!empty($input['fill_in_the_blanks_header']))

            <div id="head1">
                <h6>
                    {{ $input['fill_in_the_blanks_header'] }}
                </h6>
            </div>
            @endif
            <table style="border:none">
                <tbody>
                    <tr class="tr1">
                        <td colspan="4" class="td1 QuestHeadLeft" style="font-weight:bold;text-align:left;font-size:16px ">
                            Q.{{ $question_count }}: {{ $input['fill_in_the_blanks_top_text'] }}
                        </td>

                        <td colspan="4" class="QuestHeadLeft textright  td1" style="">

                            <span style="float:right;font-size:16px" id="qmrks"><strong>({{ $input['total_fill_marks'] }})</strong></span>

                        </td>
                    </tr>

                </tbody>
            </table>
            @foreach ($fill_in_the_blank_questions as $fill)
            <table style="border:none; margin-left:15px;" class="fillstable">
                <tbody>
                    <tr class="tr1 fillstable">
                        <td colspan="8" class=" halfdevider td1 fillstable" style="text-align:left !important;font-size:14px !important">

                            <span style="font-weight:bold !important font-size:14px; display: inline-block;width: 2%;text-align: center;">
                                {{ numberToRoman($loop->iteration) }}:</span>

                            <span style="margin-left:15px;">
                                {!! $fill->question !!}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
            @endforeach



        </div>

        <div class="find">
            <div contenteditable="true" class="pto" id="fill_pto" style="text-align:center;" data-text="Page break "></div>
            <div class="custom_break" id=""> </div>
        </div @endif @if (!empty($true_and_false_questions)) <div class="row" style="zoom:80%">

        <table style="border:none">
            <tbody>
                <tr class="tr1">
                    <td colspan="4" class="td1 QuestHeadLeft" style="font-weight:bold;text-align:left;font-size:16px ">
                        Q.{{ $question_count }}: {{ $input['true_and_false_top_text'] }}
                        @php
                        $question_count++;

                        @endphp
                    </td>

                    <td colspan="4" class="QuestHeadLeft textright  td1" style="">

                        <span style="float:right;font-size:16px" id="qmrks"><strong>({{ $input['total_true_marks'] }})</strong></span>

                    </td>
                </tr>

            </tbody>
        </table>
        @foreach ($true_and_false_questions as $true_and_false)
        <table style="border:none ; margin-left:15px;" class="true_and_falsestable">
            <tbody>
                <tr class="tr1 true_and_falsestable">
                    <td colspan="8" class=" halfdevider td1 true_and_falsestable" style="text-align:left !important;font-size:14px !important">

                        <span style="font-weight:bold !important font-size:14px">
                            {{ numberToRoman($loop->iteration) }}: &nbsp;</span>
                        {!! $true_and_false->question !!}
                    </td>
                    <td style="width:5%;"></td>

                </tr>
            </tbody>
        </table>
        @endforeach

    </div>
    <div class="find">
        <div contenteditable="true" class="pto" id="true_pto" style="text-align:center;" data-text="Page break "></div>
        <div class="custom_break" id=""> </div>
    </div @endif @if(!empty($column_matching_questions)) <div class="row">

    @if (!empty($input['column_matching_header']))

    <div id="head1">
        <h6>{{ $input['column_matching_question_header'] }}
        </h6>
    </div>
    @endif
    @foreach ($column_matching_questions as $cm)

    <table style="border:none">
        <tbody>
            <tr class="tr1">
                <td colspan="4" class="td1 QuestHeadLeft" style="font-weight:bold;text-align:left;font-size:16px ">
                    Q.{{ $question_count }}: {!! $cm->question !!}
                    @php
                    $question_count++;
                    $column_a=explode(',,',$cm->column_a);
                    $column_b=explode(',,',$cm->column_b);
                    @endphp
                </td>

                <td colspan="4" class="QuestHeadLeft textright  td1" style="">

                    <span style="float:right;font-size:16px" id="qmrks"><strong>({{ $input['total_column_matching_marks'] }})</strong></span>

                </td>
            </tr>

        </tbody>
    </table>
    <table style="border:none">
        <tbody style="border:none">
            <tr>
                <td style="width:2%" class="td1"></td>
                <td style="width:30%;border:1px solid black"><strong style="font-weight:bold">Column A </strong></td>
                <td style="width:20%" class="td1"></td>
                <td style="width:30%;border:1px solid black"><strong style="font-weight:bold"> Column B </strong></td>
                <td style="width:2%" class="td1"></td>

            </tr>
            @for($i=0; $i<count($column_a); $i++) <tr>

                <td style="width:2%" class="td1"></td>
                <td style="width:30%;border:1px solid black"><strong style="font-weight:bold">{!! $column_a[$i] !!} </strong></td>
                <td style="width:20%" class="td1"></td>
                <td style="width:30%;border:1px solid black"><strong style="font-weight:bold"> {!! $column_b[$i] !!}</strong></td>
                <td style="width:2%" class="td1"></td>

                </tr>
                @endfor
        </tbody>
    </table>

    @endforeach

    </div>
    @endif


    @if (!empty($paraphrase_questions))

    <div class="row">
        @if (!empty($input['paraphrase_question_header']))
        <div id="head1">
            <h6>{{ $input['paraphrase_question_header'] }}
            </h6>
        </div>
        @endif
        <table style="border:none">
            <tbody>
                <tr class="tr1">
                    <td colspan="4" class="td1 QuestHeadLeft" style="text-align:left;font-size:12px ">
                        Q.{{ $question_count }}:{{ $input['paraphrase_question_top_text'] }}
                        @php
                        $question_count++;
                        @endphp
                    </td>

                    <td colspan="4" class="QuestHeadLeft textright  td1" style="">

                        <span style="float:right;font-size:16px" id="qmrks"><strong>({{ $input['total_paraphrase_marks'] }})</strong></span>

                    </td>
                </tr>

            </tbody>
        </table>
        @foreach ($paraphrase_questions as $paraphrase)

        <table style="border:none ; margin-left:15px;" class="shortstable">
            <tbody>
                <tr class="tr1 shortstable">
                    <td colspan="" class=" halfdevider td1 shortstable" style="width: 2%; vertical-align: baseline; font-size:14px !important">

                        {{ numberToRoman($loop->iteration) }}:

                    </td>
                    <td colspan="10" class=" halfdevider td1 shortstable" style="text-align:left!important;font-size:14px !important">

                        <span style="margin-left:15px;">
                            {!! $paraphrase->question !!}</span>
                    </td>

                </tr>
            </tbody>
        </table>
        @endforeach
    </div>
    @endif


    @if (!empty($stanza_questions))

    <div class="row">
        @if (!empty($input['stanza_question_header']))
        <div id="head1">
            <h6>{{ $input['stanza_question_header'] }}
            </h6>
        </div>
        @endif
        <table style="border:none">
            <tbody>
                <tr class="tr1">
                    <td colspan="4" class="td1 QuestHeadLeft" style="text-align:left;font-size:12px ">
                        Q.{{ $question_count }}:{{ $input['stanza_question_top_text'] }}
                        @php
                        $question_count++;
                        @endphp
                    </td>

                    <td colspan="4" class="QuestHeadLeft textright  td1" style="">

                        <span style="float:right;font-size:16px" id="qmrks"><strong>({{ $input['total_stanza_marks'] }})</strong></span>

                    </td>
                </tr>

            </tbody>
        </table>

        @foreach ($stanza_questions as $stanza)

        <table style="border:none ; margin-left:15px;" class="shortstable">
            <tbody>
                <tr class="tr1 shortstable">
                    <td colspan="" class=" halfdevider td1 shortstable" style="width: 2%; vertical-align: baseline; font-size:14px !important">

                        {{ numberToRoman($loop->iteration) }}:

                    </td>
                    <td colspan="10" class=" halfdevider td1 shortstable" style="text-align:left!important;font-size:14px !important">

                        <span style="margin-left:15px;">
                            {!! $stanza->question !!}</span>
                    </td>

                </tr>
            </tbody>
        </table>
        @endforeach
    </div>
    @endif



    @if (!empty($passage_questions))

    <div class="row">
        @if (!empty($input['passage_question_header']))
        <div id="head1">
            <h6>{{ $input['passage_question_header'] }}
            </h6>
        </div>
        @endif
        <table style="border:none">
            <tbody>
                <tr class="tr1">
                    <td colspan="4" class="td1 QuestHeadLeft" style="text-align:left;font-size:12px ">
                        Q.{{ $question_count }}:{{ $input['passage_question_top_text'] }}
                        @php
                        $question_count++;
                        @endphp
                    </td>

                    <td colspan="4" class="QuestHeadLeft textright  td1" style="">

                        <span style="float:right;font-size:16px" id="qmrks"><strong>({{ $input['total_passage_marks'] }})</strong></span>

                    </td>
                </tr>

            </tbody>
        </table>
        @foreach ($passage_questions as $passage)

        <table style="border:none ; margin-left:15px;" class="shortstable">
            <tbody>
                <tr class="tr1 shortstable">
                    <td colspan="" class=" halfdevider td1 shortstable" style="width: 2%; vertical-align: baseline; font-size:14px !important">

                        {{ numberToRoman($loop->iteration) }}:

                    </td>
                    <td colspan="10" class=" halfdevider td1 shortstable" style="text-align:left!important;font-size:14px !important">

                        <span style="margin-left:15px;">
                            {!! $passage->question !!}</span>
                    </td>

                </tr>
            </tbody>
        </table>
        @endforeach
    </div>
    @endif


    @if (!empty($short_questions))

    <div class="row">
        @if (!empty($input['short_question_header']))
        <div id="head1">
            <h6>{{ $input['short_question_header'] }}
            </h6>
        </div>
        @endif
        <table style="border:none">
            <tbody>
                <tr class="tr1">
                    <td colspan="4" class="td1 QuestHeadLeft" style="font-weight:bold;text-align:left;font-size:16px ">
                        Q.{{ $question_count }}: {{ $input['short_question_top_text'] }}
                        @php
                        $question_count++;

                        @endphp
                    </td>

                    <td colspan="4" class="QuestHeadLeft textright  td1" style="">

                        <span style="float:right;font-size:16px" id="qmrks"><strong>({{ $input['total_short_marks'] }})</strong></span>

                    </td>
                </tr>

            </tbody>
        </table>
        @foreach ($short_questions as $short)
        <table style="border:none ; margin-left:15px;" class="shortstable">
            <tbody>
                <tr class="tr1 shortstable">
                    <td colspan="8" class=" halfdevider td1 shortstable" style="text-align:left!important; font-size:14px !important">
                        <span style="font-weight:bold !important font-size:14px; display: inline-block;width: 2%;text-align: center;">
                            {{ numberToRoman($loop->iteration) }}:</span>

                        <span style="margin-left:15px;">
                            {!! $short->question !!}</span>
                    </td>

                </tr>
            </tbody>
        </table>
        {{-- Ans.<hr><br><br><hr> --}}
        @endforeach

        <div class="find">

            <div contenteditable="true" class="pto" id="short_pto" style="text-align:center;" data-text="Page break "></div>
            <div class="custom_break" id=""> </div>
        </div>

        @endif

        {{-- ok --}}
        @if (!empty($long_questions))

        <div class="row">
            @if (!empty($input['long_question_header']))

            <div id="head1">
                <h6>{{ $input['long_question_header'] }}
                </h6>
            </div>
            @endif
            @if($input['long_question_choice'] >0)


            <table style="border:none">
                <tbody>
                    <tr class="tr1">
                        <td colspan="4" class="td1 QuestHeadLeft" style="font-weight:bold;text-align:left;font-size:16px; ">
                            Q.{{ $question_count }}: {{ $input['long_top_text'] }}
                            @php
                            $question_count++;
                            @endphp
                        </td>

                        <td colspan="4" class="QuestHeadLeft textright  td1" style="">

                            <span style="float:right;font-size:16px" id="qmrks"><strong>({{ $input['total_long_marks'] }})</strong></span>

                        </td>
                    </tr>

                </tbody>
            </table>
            @foreach ($long_questions as $long)

            <table style="border:none">
                <tbody>

                    <tr class="tr1 shortstable">
                        <td colspan="8" class=" halfdevider td1 shortstable" style="text-align:left !important;font-size:14px !important">
                            <span style="font-weight:bold !important font-size:14px; display: inline-block;width: 2%;text-align: center;">
                                {{ numberToRoman($loop->iteration) }}:</span>

                            <span style="margin-left:15px;">
                                {!! $long->question !!}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
            @endforeach
            @else

            @foreach ($long_questions as $long)

            <table style="border:none">
                <tbody>
                    <tr class="tr1">
                        <td colspan="4" class="td1 QuestHeadLeft" style="text-align:left;font-size:12px ">
                            Q.{{ $question_count }}: {!! $long->question !!}</p>
                            @php
                            $question_count++;
                            @endphp
                        </td>

                        <td colspan="4" class="QuestHeadLeft textright  td1" style="">

                            <span style="float:right;font-size:16px" id="qmrks"><strong>({{ $input['long_question_question_marks'] }})</strong></span>

                        </td>
                    </tr>

                </tbody>
            </table>
            @endforeach

            @endif

        </div>
        <div class="find">
            <div contenteditable="true" class="pto" id="long_pto" style="text-align:center;" data-text="Page break "></div>
            <div class="custom_break" id=""> </div>
        </div>
        @endif
        {{-- ok --}}
        @if (!empty($translation_to_urdu_questions))

        <div class="row">
            @if (!empty($input['translation_to_urdu_question_header']))

            <div id="head1">
                <h6>{{ $input['translation_to_urdu_question_header'] }}
                </h6>
            </div>
            @endif

            @foreach ($translation_to_urdu_questions as $translation_to_urdu)

            <table style="border:none">
                <tbody>
                    <tr class="tr1">
                        <td colspan="4" class="td1 QuestHeadLeft" style="text-align:left;font-size:12px ">
                            Q.{{ $question_count }}: {!! $translation_to_urdu->question !!}</p>
                            @php
                            $question_count++;
                            @endphp
                        </td>

                        <td colspan="4" class="QuestHeadLeft textright  td1" style="vertical-align: baseline;">

                            <span style="float:right;font-size:16px" id="qmrks"><strong>({{ $input['total_translation_to_urdu_marks'] }})</strong></span>

                        </td>
                    </tr>

                </tbody>
            </table>
            @endforeach

        </div>
        @endif
        @if (!empty($translation_to_english_questions))

        <div class="row">
            @if (!empty($input['translation_to_english_question_header']))

            <div id="head1">
                <h6>{{ $input['translation_to_english_question_header'] }}
                </h6>
            </div>
            @endif

            @foreach ($translation_to_english_questions as $translation_to_english)

            <table style="border:none">
                <tbody>
                    <tr class="tr1">
                        <td colspan="4" class="td1 QuestHeadLeft" style="text-align:left;font-size:12px ">
                            Q.{{ $question_count }}: {!! $translation_to_english->question !!}</p>
                            @php
                            $question_count++;
                            @endphp
                        </td>

                        <td colspan="4" class="QuestHeadLeft textright  td1" style="vertical-align: baseline;">

                            <span style="float:right;font-size:16px" id="qmrks"><strong>({{ $input['total_translation_to_english_marks'] }})</strong></span>

                        </td>
                    </tr>

                </tbody>
            </table>
            @endforeach

        </div>
        @endif
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

