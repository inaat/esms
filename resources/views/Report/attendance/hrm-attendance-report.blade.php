<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.attendance_list')</title>
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
       word-wrap:break-word;


    }

    /* Zebra striping */
    tr:nth-of-type(odd) {}

    td,
    th {
        padding: 5px;
        border: 1px solid black;
        text-align: center;
        font-size: 12px;
        word-wrap:break-word;
    }



 
    .head{
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
      thead { display: table-header-group }
tfoot { display: table-row-group }
tr { page-break-inside: avoid }
</style>
</head>

<body>  
    <div class="card">
               <div class='date '><b>@lang('english.from') {{ @format_date($start_date) }} @lang('english.to') {{ @format_date($end_date) }} <b></div>

                 <h5 class="head text-primary">@lang('english.employees') @lang('english.attendance_list')</span></h5>
                 
                 <div class="table-responsive" style="margin-top:20px;">
                                  <hr>

                     <table class="table mb-0" width="100%" >
                         <thead class="table-light" width="100%">
                             <tr>
                                 <th>#</th>
                                 <th>@lang('english.employee_name')</th>
                                @foreach ($days as $day)
                                      <th>{{$loop->iteration}}</th>                                    
                                @endforeach
                                <th>@lang('english.b/f')</th>
                                  <th>@lang('english.absent')</th>
                                 <th>@lang('english.b/f')</th>
                                  <th>@lang('english.leave')</th>
                                 <th>@lang('english.no_of_att_b_f')</th>
                                 <th>@lang('english.no_of_att_m')</th>
                                 <th>@lang('english.total_no_of_att')</th>
                                 <th>@lang('english.fine')</th>

                             </tr>
                         </thead>
<tbody>
         @foreach ($data as $dt)
              <tr>
               <td>{{$loop->iteration}}</td>
            <td>{{ $dt['name'] }}</td>
               
             @foreach ($dt['attendances'] as $at)
         
                <td>{{ $at['status'] }}</td>
         
            @endforeach 
                    <td>{{ $dt['B/F'] }}</td>
                    <td>{{ $dt['absent'] }}</td>
                    <td>{{ $dt['BFLeave'] }}</td>
                    <td>{{ $dt['leave'] }}</td>
                    <td>{{ $dt['BFAttendance'] }}</td>
                    <td>{{ $dt['Attendance'] }}</td>
                    <td>{{ $dt['total_no_attendance'] }}</td>
                    <td></td>

               </tr>
         @endforeach
        
            <tr>
                 <tr>
                <th>#</th>
                <th>@lang('english.total')</th>
                @foreach ($days as $day)
                      <th></th>                                    
                @endforeach
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>@lang('english.average')</th>
                <th>{{ @num_format($average) }}</th>
                <th></th>
                                <th></th>

            </tr>
          
        </tbody>
                     </table>
                 </div>
              
         <div class='row' style='margin-top:50px;'>
        <div class='label'> <strong>@lang('english.prepared_by'):</strong></div>
        <div class="underline"><strong></strong></div>
        <div class='label extra-left'> <strong>@lang('english.certified_by'):</strong></div>
        <div class="underline"><strong></strong></div>
        <div class='label extra-left'> <strong>@lang('english.approved_by'):</strong></div>
        <div class="underline"><strong></strong></div>

    </div> 
      </body>

</html>