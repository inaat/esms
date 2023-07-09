<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.roll_no_slip')</title>
<style>
    @page {
        margin: 0px;
        padding: 3px;
        font-size: 12px;
        font-weight: 700;
    }

    @media print {

        .pace-progress {
            display: none;
        }

        * {
            margin: 0px;
            padding: 0px;
            color: #000;
            font-family: sans serif;

        }


        @page {

            size: A4;
            -webkit-print-color-adjust: exact;
            margin: 15px !important;
            padding: 10px !important;
            width: 100%;
            height: 100%;
        }


        h3 {
            text-align: center;
        }


        .info {

            display: flex;
            flex-direction: column;

        }

        .content {
            display: flex;
            flex: 1;
            color: #000;
        }

        .columns {
            display: flex;
            flex: 1;
        }

        .main {
            flex: 1;
            order: 1;
        }

        .sidebar-first {
            width: 20%;
            order: 1;
        }

        body {
            background-color: #fff font-family: Calibri, Myriad;
             
        }

        #main {
            width: 100%;
            padding: 20px;
            margin: auto;
        }

        .timecard {
            margin: auto;
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
            /*for older IE*/
            border-style: hidden;
           
            font-weight: bold;


        }

        .timecard caption {
            background-color: #f79646;
            color: #fff;
            font-size: x-large;
            font-weight: bold;
            letter-spacing: .3em;
        }

        .timecard thead th {
            padding: 8px;
            background-color: #fde9d9;
            font-size: large;
        }





        .timecard th,
        .timecard td {
            padding: 0px;
            border-width: 1px;
            border-style: solid;
            border-color: #f79646 #ccc;
            color: #000;
        }

        .timecard td {
            text-align: center;
        }

        .timecard tbody th {
            text-align: left;
            font-weight: normal;
        }



        .timecard tr.even {
            background-color: #fde9d9;
        }
    }

</style>
</head>

<body style="color:#000;background-color:#ffff;">
    @foreach ($details as $std)
    {{-- <div style=" border:1px solid black; margin:5px; page-break-after: always;"> --}}
    <div style="padding:10px; margin-top:5px;page-break-inside: avoid;page-break-after: always;  width: 210mm;
    height: 148.5mm;">
    <div style=" padding:10px;border:1px solid black;  ">

<img src="{{ url('/uploads/business_logos/'.session()->get("system_details.page_header_logo")) }}" width="100%"   style="height:60px">
        <div id="head">
            <h3><b>@lang('english.roll_no_slip')<b></h3>
            <hr>
        </div>
<div class="info">

            <section class="content">
                <div class="columns">
                    <div class="main">
                        <table width="100%" class="">

                            <tbody>
                                <tr>
                                    <th width=""><strong>@lang('english.name') : {{ ucwords($std['student_exam']->student->first_name . ' ' . $std['student_exam']->student->last_name) }}</strong></th>
                                    <th width=""><strong>@lang('english.roll_no') : {{ ucwords($std['student_exam']->student->roll_no) }}</strong></th>

                                </tr>
                                <tr>
                                    <th width=""><strong>@lang('english.father_name') :{{ ucwords($std['student_exam']->student->father_name) }}</strong></th>
                                    <th width=""><strong>@lang('english.exam') :{{ ucwords($std['student_exam']->exam_create->term->name) }}</strong></th>
                                </tr>
                                <tr>
                                    <th width=""><strong>@lang('english.class'):{{ ucwords($std['student_exam']->student->current_class->title) . ' '.$std['student_exam']->student->current_class_section->section_name }}</strong></th>
                                    <th width=""><strong>@lang('english.session') :{{ ucwords($std['student_exam']->session->title) }}</strong></th>

                                </tr>
                                <tr>
                                    <th width=""><strong>@lang('english.address'):{{ ucwords($std['student_exam']->student->std_permanent_address) }}</strong></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="sidebar-first">
                        @if (file_exists(public_path('uploads/student_image/' . $std['student_exam']->student->student_image)))
                        <img width="100%" height="100" src="{{ url('uploads/student_image/' . $std['student_exam']->student->student_image) }}" />
                        @else
                        <img width="100%" height="100" src="{{ url('uploads/student_image/default.png') }}" />
                        @endif

                    </div>
                </div>
            </section>
        </div>
 <div id="main">

            <table class="timecard" @if($std['dateSheet']->count()<7) style="zoom:75%;" @else style="zoom:80%; @endif>
                <thead>

                    <tr>
                        {{-- <th id="thDay">Day</th>
                            <th id="thRegular">Regular</th>
                            <th id="thOvertime">Overtime</th>
                            <th id="thTotal">Total</th> --}}
                        <th>#</th>
                        <th>@lang('english.date')</th>
                        <th>@lang('english.day')</th>
                        <th>@lang('english.subject')</th>
                        {{-- <th>Topic</th> --}}
                        <th>@lang('english.from')</th>
                        <th>@lang('english.to')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $std['dateSheet'] as $dt)

                    <tr class="@if($loop->iteration%2==0) even @else odd @endif">
                        <td>{{$loop->iteration}}</td>
                        <td>{{@format_date($dt->date) }}</td>
                        <td>{{ $dt->day }}</td>
                        <td>{{ $dt->subject->name }} ({{ $dt->type }})</td>
                        {{-- <td style="@if(strlen($dt->topic) >=60) zoom:60%;@endif" >{{ $dt->topic }}</td> --}}
                        <td>{{ @format_time($dt->start_time) }}</td>
                        <td>{{ @format_time($dt->end_time) }}</td>
                    </tr>


                    @endforeach

                <tfoot>
                    <tr>
                        <td colspan="{{ $std['dateSheet']->count() }}">
                            <p style="float:right; margin-top:10px;">@lang('english.controller_of_examination')<br>Mr Asif Khan</p>
                        </td>
                    <tr>
                </tfoot>

                </tbody>


            </table>

        </div>
    </div>
</div>

    @endforeach

</body>

</html>

