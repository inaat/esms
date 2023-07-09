<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.employee_list_print')</title>

</head>

<body>
       @include('common.mpdfheaderfooter')
    <div  class="head text-primary">
        <h4>@lang('english.teacher&staff_list')</h4>
    </div>
    <div class="space" style="width:100%;  height:1px;">
    </div>
    <table class="table mb-0" width="100%" id="employees_table">
        <thead class="table-light" width="100%">
            <tr style="background:#eee">
                <th>#</th>
                <th>@lang('english.campus_name')</th>
                <th>@lang('english.employeeID')</th>
                <th>@lang('english.employee_name')</th>
                <th>@lang('english.father_name')</th>
                <th>@lang('english.designation')</th>
                <th>@lang('english.basic_salary')</th>
                <th>@lang('english.email')</th>
                <th>@lang('english.gender')</th>
                <th>@lang('english.mobile')</th>
                <th>@lang('english.cnic_number')</th>
                <th>@lang('english.date_of_birth')</th>
                <th>@lang('english.permanent_address')</th>
                <th>@lang('english.arrears')</th>


            </tr>
        </thead>
        <tbody>
        @php
                 $basic_salary=0;
                 
                 $arrears=0;

             @endphp
             @foreach ($data as $employee)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{ $employee->campus_name}}</td>
                <td>{{ $employee->employeeID }}</td>
                <td style="background:#eee">{{ $employee->employee_name }}</td>
                <td>{{ $employee->father_name }}</td>
                <td>{{ $employee->designation }}</td>
                <td>{{ @num_format($employee->basic_salary )}}</td>
                <td>{{ $employee->email}}</td>
                <td>{{ $employee->gender}}</td>
                <td>{{ $employee->mobile_no}}</td>
                <td>{{ $employee->cnic_no}}</td>
                <td>{{ @format_date($employee->birth_date)}}</td>
                <td>{{ $employee->permanent_address}}</td>
                <td style="background:#eee">{{ @num_format($employee->arrears)}}</td>
                @php
                $basic_salary +=$employee->basic_salary;
                $arrears +=$employee->arrears;

            @endphp
            </tr>
            @endforeach 
             <tr>
                <td colspan="6">@lang('english.total')</td>
                <td> {{ @num_format($basic_salary)}}</td>
                <td colspan="6"></td>
                <td> {{ @num_format($arrears)}}</td>
            </tr> 
        </tbody>

    </table>

</body>

</html>
