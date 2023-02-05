<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.top_position_list') @if($type=='class_wise')@lang('english.class') @lang('english.wise') @else @lang('english.section') @lang('english.wise') @endif</title>
<style>
    * {
        margin: 0;
        padding: 0;
    }


    body {
        margin: 0;
        padding: 0px;
        font-size: 12px;
        width: 100%;
        background-color: rgba(204, 204, 204);

        font-family: 'Roboto Condensed', sans-serif;
        zoom: 80%;

    }

    h2,
    h4,
    p {
        margin: 0;


    }

    .head {
        width: 50%;
        /* 70% of the parent*/
        background: {{  config('constants.head_bg_color') }};
        text-align: center;
        color: white;
        padding: 3px;
        margin: 1px auto;
        border-radius: 5px;

    }

    #head1 {
        width: 80%;
        /* 70% of the parent*/
        background: {{  config('constants.head_bg_color') }};
        text-align: center;
        color: white;
        padding: 3px;
        margin: 1px auto;
        border-radius: 5px;

    }



    table {
        width: 100%;

        border-collapse: collapse;
        border-bottom: 2px solid black;
        word-wrap: break-word;


    }

    /* Zebra striping */
    tr:nth-of-type(odd) {}

    td,
    th {
        padding: 5px;
        border: 1px solid black;
        text-align: center;
        font-size: 12px;
        word-wrap: break-word;
    }




    .head {
        background: {{  config('constants.head_bg_color') }};
        text-align: center;
        color: white;
        padding: 3px;
        border-radius: 5px;

    }

    .date {
        text-align: center;

        text-align: center;
        position: absolute;
        left: 0%;
        top: 4px;
    }

    .row {
        display: -webkit-box;
        display: -webkit-flex;
        display: flex;
        padding: 2px;

    }

    .underline {
        -webkit-box-flex: 1;
        -webkit-flex: 1;
        flex: 1;

        flex-grow: 1;
        border-bottom: 1px solid black;
        margin-left: 5px;
    }

    .mg-left {
        margin-left: 10px;
    }

    .extra-left {
        margin-left: 80px;
    }

    thead {
        display: table-header-group
    }

    tfoot {
        display: table-row-group
    }

    tr {
        page-break-inside: avoid
    }

</style>
</head>

<body>
 
    <div class="card">

        <div class=' '><b>@lang('english.printed') {{ @format_date('now') }} <b></div>
        <h5 class="head text-primary">{{ $students[0]->campuses->campus_name.' ' }} {{ ucwords($students[0]->exam_create->term->name).'  (' }}{{ $students[0]->session->title.')  ' }} @lang('english.top_position_list')</h5>
        <h5 class="head text-primary">@lang('english.top_position_list') @if($type=='class_wise')@lang('english.class') @lang('english.wise') @else @lang('english.section') @lang('english.wise') @endif</h5>


        <div class="table-responsive" style="margin-top:20px;">
            <hr>

            <table class="table mb-0" width="100%">
                <thead class="table-light" width="100%">
                    <tr>
                        <th>#</th>
                        <th>@lang('english.roll_no')</th>
                        <th>@lang('english.student_name')</th>
                        <th>@lang('english.father_name')</th>
                        <th>@lang('english.class')</th>
                        <th>@lang('english.section')</th>
                        <th>@lang('english.position_in_class')</th>
                        <th>@lang('english.position_in_section')</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach ($students as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                         <td>{{ $student->student->roll_no}}</td>
                        <td>{{ $student->student->first_name.' '.$student->student->last_name}}</td>
                        <td>{{ $student->student->father_name}}</td>
                        <td>{{ $student->current_class->title}}</td>
                        <td>{{ $student->current_class_section->section_name}}</td>
                        {{-- <td>{{ $student->class_position}}</td> --}}
                        <td>{!! str_ordinal($student->class_position,true) !!}</td>
                        <td>{!! str_ordinal($student->class_section_position,true)!!}</td>
                    </tr>
                 
                    @endforeach

                </tbody>
            </table>
        </div>


    </div>
    
     

    </html>
