<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.syllabus')</title>
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
table-layout: fixed

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

    @foreach ($class_syllabus_list as $syllabus)

    @if(!empty($syllabus['data']))
    <div class="card">

        <div class=' '><b>@lang('english.printed') {{ @format_date('now') }} <b></div>
        <h5 class="head text-primary">@lang('english.syllabus') @lang('english.of') @lang('english.class') {{ $syllabus['classes']->title }} @lang('english.exam') {{ $term->name }}</h5>


        <div class="table-responsive" style="margin-top:20px;">
            <hr>

            <table class="table mb-0" width="100%">
                <thead class="table-light" width="100%">
                    <tr>
                        <th  style="width:20%">@lang('english.subject') </th>
                        <th  style="width:80%">@lang('english.details')</th>

                    </tr>
                </thead>
                <tbody>

                    @foreach ($syllabus['data'] as $subject)
                    <tr>
                        <td>{{ $subject['subject']->name }}</td>
                        @if(!$subject['syllabus']->isEmpty())
                        <td>
                           <table >
                           <tr>
                           <th>@lang('english.chapters')</th>
                           <th>@lang('english.description')</th>
                           <th>@lang('english.lesson')</th>
                           </tr>
                            @foreach($subject['syllabus'] as $sub)
                           
                            
                                <tr>
                                    <td style="width:60%">
                                       {{ $sub->chapter->chapter_name }}
                                    </td>
                                    <td style="width:10%">
                                      {{ $sub->description }}
                                    </td>
                                     <td style="width:30%">
                                    @foreach ($sub->chapter->lesson as  $lesson)
                                       
                                      {{ $lesson->name }},
                                    
                                    @endforeach
                                    </td>
                                    
                                </tr>
                            
                            @endforeach
                            </table>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p style="page-break-after: always;"></p>


    </div>
    @endif
    @endforeach





    </html>

