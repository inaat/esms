<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.class')@lang('english.class') @lang('english.wise') @lang('english.time_table')</title>
<style>
    * {
        margin: 0;
        padding: 0;
    }


    body {
        margin: 0;
        padding: 0px;

        width: 100%;
        background-color: rgba(204, 204, 204);

        font-family: 'Roboto Condensed', sans-serif;

    }

    h2,
    h4,
    p {
        margin: 0;


    }

    .fee-table-area {
        margin-top: 80px;
        border: 1px solid #000;

        width: 70%;
        overflow: hidden;
        overflow-x: hidden;
        overflow-y: hidden;
        float: left;
        border: 1px solid #000;
        page-break-inside: avoid;




    }

    .fee-received {
        margin-top: 80px;
        border: 1px solid #000;
        width: 29%;
        height: 524px;
        float: right;
        font-size: 15px;
        page-break-inside: avoid;





    }

    #head {
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





    .column1 {
        float: left;
        width: 75%;
        overflow: hidden;
        /* Should be removed. Only for demonstration */
    }

    .column2 {
        float: left;
        width: 25%;
        overflow: hidden;

        /* Should be removed. Only for demonstration */
    }

    /* Clear floats after the columns */
    .info:after {
        content: "";
        display: table;
        clear: both;
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


    .clear {
        clear: both;

    }
    .vertical-text {
            writing-mode:vertical-rl;
            -webkit-writing-mode:vertical-rl;
            -ms-writing-mode:tb-rl;
}
   thead { display: table-header-group }
tfoot { display: table-row-group }
tr { page-break-inside: avoid }
</style>
</head>

<body>

    <div class="space" style="width:100%;  height:1px;">
    </div>
    <div id="head">
        <h3>@lang('english.class') @lang('english.wise') @lang('english.time_table')</h3>
    </div>
    <div class="space" style="width:100%;  height:1px;">
    </div>
    <table class="table mb-0" width="100%" id="employees_table">

        <thead class="table-light" width="100%">

            <tr style="background:#eee">
                <th>#</th>
                <th>Clsases</th>
                @php
                foreach ($class_time_table_title as $t) {
                echo '<th>'.$t.'</th>';
                }
                @endphp

            <tr>
        </thead>
        <tbody>
            @foreach ( $sections as $section)

            <tr>
                <td> {{$loop->iteration}}
                </td>
                <td>{{ $section->classes->title }} {{ $section->section_name }}</td>
                @foreach ($section->time_table as $time_table)
                @if(!empty($time_table->subjects))
                <td> {{ $time_table->subjects->name }}  {{ $time_table->other ? '('.__('english.'.$time_table->other).')' : null }} <br>@if(!empty($time_table->teacher)) <strong>({{ ucwords($time_table->teacher->first_name . ' ' . $time_table->teacher->last_name) }})@endif</strong></td>
                @else
                @if($time_table->periods->type=='lunch_break' || $time_table->periods->type=='paryer_time')
                <td ><span class="vertical-text">@lang('english.'.$time_table->periods->type)</span></td>
                @else
                <td >
                    @if(!empty($time_table->other))
                                @lang('english.'.$time_table->other) 
                    @endif
                       @if(!empty($time_table->multi_subject_ids))
                                @foreach ($time_table->multi_subject_ids as $multi_subject )
                                    {{ $all_subjects[$multi_subject] }} +
                                    
                                @endforeach
                                <br>
                                @foreach ($time_table->multi_teacher as $multi_teach )
                                   <strong>( {{ $teachers[$multi_teach] }})</strong>
                                    
                                @endforeach
                                
                                @endif
                    <br>@if(!empty($time_table->note)) <strong>({{ ucwords($time_table->note ) }})@endif</strong>
                </td>
                @endif
                @endif
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
