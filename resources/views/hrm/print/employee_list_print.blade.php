<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.employee_list_print')</title>
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


    .clear {
        clear: both;

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
        
             @foreach ($HrmEmployees as $employee)
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

            </tr>
            @endforeach 
        </tbody>

    </table>

</body>

</html>
