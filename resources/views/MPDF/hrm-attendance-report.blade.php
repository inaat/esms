<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.attendance_list')</title>
<body>  
     @include('common.mpdfheaderfooter')
    
    <div class="card">
               <div class='date '><b>@lang('english.from') {{ @format_date($data['start_date']) }} @lang('english.to') {{ @format_date($data['end_date']) }} <b></div>

                 <h5 class="head text-primary">@lang('english.employees') @lang('english.attendance_list')</span></h5>
                 
                 <div class="table-responsive">
                                

                     <table class="table mb-0" width="100%" >
                         <thead class="table-light" width="100%">
                             <tr>
                                 <th>#</th>
                                 <th>@lang('english.employee_name')</th>
                                @foreach ($data['days'] as $day)
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
         @foreach ($data['data'] as $dt)
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
                <th>#</th>
                <th>@lang('english.total')</th>
                @foreach ($data['days'] as $day)
                      <th></th>                                    
                @endforeach
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>@lang('english.average')</th>
                <th>{{ @num_format($data['average']) }}</th>
                <th></th>
                                <th></th>

            </tr>
          
        </tbody>
                     </table>
                 </div>
              

    </div> 
      </body>

</html>