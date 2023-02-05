<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Income Report</title>
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
    @php
    $total_paid=0;
    $total_hrm_paid=0;
    $total_discount_amount=0;
    $total_expenses_paid=0;
    @endphp
    <div class="card">

        <div class=''><b>Printed {{ @format_date('now') }} <b></div>
        <h5 class="head text-primary">@if(empty($campus_id)) All Your Campuses @else @if(!empty($student_payments[0]->campus->campus_name)){{ $student_payments[0]->campus->campus_name }}@endif @endif Fee Payment's</h5>


        <div class="table-responsive" style="margin-top:20px;">
            <hr>

            <table class="table mb-0" width="100%">
                <thead class="table-light" width="100%">
                    <tr>
                        <th>#</th>
                        <th>@lang('english.date')</th>
                        <th>Ref No</th>
                        <th>@lang('english.roll_no')</th>
                        <th>@lang('english.student_name')</th>
                        <th>discount_amount</th>
                        <th>Paid</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach ($student_payments as $pay)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ @format_date($pay->paid_on)}}</td>
                        <td>{{ $pay->payment_ref_no}}</td>
                        <td>{{ $pay->student ? $pay->student->roll_no : ' '}}</td>
                        <td>{{ $pay->student ? $pay->student->first_name.' '.$pay->student->last_name : ''}}</td>
                        <td>{{ @num_format($pay->discount_amount)}}</td>
                        <td>{{ @num_format($pay->amount)}}</td>
                    </tr>
                    @php
                    $total_paid += $pay->amount;
                    $total_discount_amount += $pay->discount_amount;

                    @endphp
                    @endforeach

                     <tr>
                        <td colspan='5'>Total</td>
                        <td>{{@num_format($total_discount_amount)}}</td>
                        <td>{{@num_format($total_paid)}}</td>
                    </tr>

                </tbody>
            </table>
        </div>


    </div>
             <p style="page-break-after: always;"></p>

    <div class="card">

        <div class=' '><b>Printed {{ @format_date('now') }} <b></div>
        <h5 class="head text-primary">@if(empty($campus_id)) All Your Campuses @else @if(!empty($hrm_payments[0]->campus->campus_name)){{$hrm_payments[0]->campus->campus_name}}@endif @endif Hrm Payment's </h5>


        <div class="table-responsive" style="margin-top:20px;">
            <hr>

            <table class="table mb-0" width="100%">
                <thead class="table-light" width="100%">
                    <tr>
                        <th>#</th>
                        <th>@lang('english.date')</th>
                        <th>Ref No</th>
                        <th>@lang('english.employee_no')</th>
                        <th>@lang('english.employee_name')</th>
                        <th>Paid</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach ($hrm_payments as $pay_hrm)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ @format_date($pay_hrm->paid_on)}}</td>
                        <td>{{ $pay_hrm->pay_hrmment_ref_no}}</td>
                        <td>{{ $pay_hrm->employee->employeeID}}</td>
                        <td>{{ $pay_hrm->employee->first_name.' '.$pay_hrm->employee->last_name}}</td>
                        <td>{{ @num_format($pay_hrm->amount)}}</td>
                    </tr>
                    @php
                    $total_hrm_paid += $pay_hrm->amount;

                    @endphp
                    @endforeach

                     <tr>
                        <td colspan='5'>Total</td>
                        <td>{{@num_format($total_hrm_paid)}}</td>
                    </tr>

                </tbody>
            </table>
        </div>


    </div>
         <p style="page-break-after: always;"></p>

    <div class="card">

        <div class=' '><b>Printed {{ @format_date('now') }} <b></div>
        <h5 class="head text-primary">@if(empty($campus_id)) All Your Campuses @else @if(!empty($expenses_payments[0]->campus->campus_name )){{$expenses_payments[0]->campus->campus_name }}@endif @endif  Expenses Payment's </h5> </h5>


        <div class="table-responsive" style="margin-top:20px;">
            <hr>

            <table class="table mb-0" width="100%">
                <thead class="table-light" width="100%">
                    <tr>
                        <th>#</th>
                        <th>@lang('english.date')</th>
                        <th>Ref No</th>
                        <th>ExpenseCategory</th>
                        <th>Note</th>
                        <th>@lang('english.employee_no')</th>
                        <th>@lang('english.employee_name')</th>
                        <th>Paid</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach ($expenses_payments as $pay_expenses)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ @format_date($pay_expenses->paid_on)}}</td>
                        <td>{{ $pay_expenses->payment_ref_no}}</td>
                        <td>{{ $pay_expenses->expense_transaction->expenseCategory->name}}</td>
                        <td>{{ $pay_expenses->expense_transaction->additional_notes}}</td>
                        @if(!empty($pay_expenses->employee))
                        <td>{{ $pay_expenses->employee->employeeID}}</td>
                        <td>{{ $pay_expenses->employee->first_name.' '.$pay_expenses->employee->last_name}}</td>
                        @else
                        <td></td>
                        <td></td>
                        @endif
                        <td>{{ @num_format($pay_expenses->amount)}}</td>
                    </tr>
                    @php
                    $total_expenses_paid += $pay_expenses->amount;

                    @endphp
                    @endforeach

                     <tr>
                        <td colspan='7'>Total</td>
                        <td>{{@num_format($total_expenses_paid)}}</td>
                    </tr>

                </tbody>
            </table>
        </div>


    </div>
    <hr>
  <table class="table mb-0" width="100%" style="margin-top:20px;">
        <thead class="table-light" width="100%">
            <tr>
              <th>@lang('english.total') Fee Payment</th>
              <td> {{ @num_format($total_paid) }}</td>
              
            </tr>
        </thead>
        <table>
    <table class="table mb-0" width="100%">
        <thead class="table-light" width="100%">
            <tr>
                <th>@lang('english.total')  Hrm Payment</th>
                <td> {{ @num_format($total_hrm_paid) }}</td>
              
            </tr>
        </thead>
        <table>
            <table class="table mb-0" width="100%">
                <thead class="table-light" width="100%">
                    <tr>
                        <th>@lang('english.total') Expenses Payment</th>
                        <td> {{ @num_format($total_expenses_paid) }}</td>
                      
                    </tr>
                </thead>
                <table>
    <table class="table mb-0" width="100%">
        <thead class="table-light" width="100%">
            <tr>
            @php
                $total_income=$total_paid-($total_expenses_paid+$total_hrm_paid);

            @endphp
                <th>@lang('english.total') Income</th>
                <td> {{ @num_format($total_income) }}</td>
              
            </tr>
        </thead>
        <table>
    </html>
