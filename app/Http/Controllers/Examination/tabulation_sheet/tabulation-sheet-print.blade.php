<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.tabulation_sheet_print')</title>
<style>
    @page {
        margin: 8px;
        padding: 3px;
    }

    @media print {
        .page-footer{
        display:none;
    }
        .pace-progress {
            display: none;
        }

        * {
            margin: 0px;
            padding: 0px;
            font-family: sans serif;
            color: #000;

        }

        @page {

            size: A4 ;
            -webkit-print-color-adjust: exact;
            color: #000;

        }

        h3 {
            text-align: center;
        }


        .info {

            display: flex;
            flex-direction: column;
        }


        .timecard {
            margin: auto;
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
            /*for older IE*/
            border-style: hidden;
            zoom: 85%;


        }



        .timecard thead th {
            padding: 8px;
            background-color: #eee;
            color: #000;
            text-align: center;
        }





        .timecard th,
        .timecard td {
            padding: 0px;
            border-width: 1px;
            border-style: solid;
            border-color: #000 #157D4C;
        }

        .timecard td {
            text-align: center;
        }

        .timecard tbody th {
            text-align: left;
            font-weight: normal;
        }



        .timecard tr.even {
            background-color: #eee;
        }

        .underline:after {
            content: '';
            background: black;
            width: 100%;
            height: 2px;
            display: block;

        }



    }

</style>
</head>

<body style="color:#000">

    @php
    // $f = new \NumberFormatter('eng', \NumberFormatter::SPELLOUT);
    $nf = new NumberFormatter('eng', NumberFormatter::ORDINAL);
    @endphp
    @foreach ($details as $detail)

    @if(!empty($detail['students'][0]))
    <table class="timecard">
        <thead>

            <tr>
                <th colspan="{{ 10+$detail['subjects']->count() }}" style="text-align: center;">
                    @include('common.logo')

                </th>
            <tr>
            <tr>
                <th colspan="{{ 10+$detail['subjects']->count() }}" style="text-align: center;">
                    <h5 class="card-title text-primary">Tabulation Sheet  Examination {{ $detail['exam']->session->title.'  '.$detail['exam']->term->name  }}</h5>

                </th>
            <tr>
            <tr>
                <th colspan="{{ 10+$detail['subjects']->count() }}" style="text-align: center;">
                    <h5 class="card-title text-primary">@lang('english.class'){{ $detail['class']->title.'  Section'. $detail['section']->section_name }}</h5>

                </th>
            <tr>
            <tr>
                <th>#</th>
                <th>@lang('english.roll_no')</th>
                <th>@lang('english.student_name')</th>
                <th>@lang('english.father_name')</th>
                @php
                 foreach($detail['students'][0]->subject_result as $subject){

                                 echo '<th>' . $subject->subject_name->name. " (" . $subject->total_mark . ')</th>';
                                 }
                @endphp
                <th>@lang('english.total_marks')</th>
                <th>@lang('english.per.%')</th>
                <th>@lang('english.class') @lang('english.position')</th>
                <th>@lang('english.section') @lang('english.position')</th>
                <th>@lang('english.grade')</th>
                <th>@lang('english.remarks')</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($detail['students'] as $student)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $student->student->roll_no }}</td>
                <td>{{ ucwords($student->student->first_name.'  '.$student->student->last_name) }}</td>
                <td>{{ ucwords($student->student->father_name)}}</td>
                @foreach ($student->subject_result as $subject)
                {{-- {{ $subject->subject_name->name }} --}}
                <td>{{ $subject->total_obtain_mark }}/{{ $subject->total_mark }}</td>
                @endforeach
                <td>{{ number_format($student->obtain_mark,0)}}/{{ number_format($student->total_mark,0) }}</td>
                <td>{{ @num_format($student->final_percentage)}}%</td>
                <td>
                    {{ $nf->format($student->class_position) }}
                </td>
                <td>
                    {{ $nf->format($student->class_section_position) }}
                </td>
                <td>
                    @if(!empty($student->grade))
                    {{ ucwords($student->grade->name) }}
                    @endif
                </td>
                <td>
                    {{ ucwords($student->remark) }}
                </td>
            </tr>
            @endforeach
            
        </tbody>
    </table>
    <p style="page-break-after: always;"></p>
     @endif
    @endforeach
</body>

</html>

