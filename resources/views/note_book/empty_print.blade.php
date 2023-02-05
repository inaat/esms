<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.note_book_status_empty_form')</title>
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
  @foreach ($subjects as $subject )
    <div class="card">

        <div style="float:left"><b>@lang('english.printed'): {{ @format_date('now') }} <b>
               <div style="float:right"><u>@lang('english.subject') : {{ $subject->name}} <u></div></div>

        <h5 class="head text-primary">{{ $subject->campuses->campus_name . '   Class:' . $subject->classes->title }}</span></h5>

        <div class="table-responsive" style="margin-top:20px;">
            <hr>

            <table class="table mb-0" width="100%">
                <thead class="table-light" width="100%">
                     <tr>
                            {{-- <th rowspan="2">Sr No</th> --}}
                            <th rowspan="2">#</th>
                             <th rowspan="2">@lang('english.roll_no')</th>
                            <th rowspan="2">@lang('english.student_name')</th>
                           <th rowspan="2">@lang('english.book_work')</th>
                        <th colspan="2">@lang('english.note_book_topic')</th>
                        <th rowspan="2">@lang('english.tutor')</th>
                        <th rowspan="2">@lang('english.remark')</th>

                        </tr>
                        <tr>
                            <th>@lang('english.date')</th>
                            <th>@lang('english.signature')</th>
                           
                        </tr>
                </thead>
                <tbody>
                @foreach ($students as $student )
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $student->roll_no}}</td>
                        <td>{{ ucwords($student->first_name .'  '.$student->last_name) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
                        <p style="page-break-after: always;"></p>

  @endforeach


</body>
</html>

