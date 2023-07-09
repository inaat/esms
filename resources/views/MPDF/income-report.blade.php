<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<title>@lang('english.income_report')</title>
</head>

<body>
    @php
    $total_paid=0;
    $total_hrm_paid=0;
    $total_discount_amount=0;
    $total_expenses_paid=0;
    @endphp
  
       @include('common.mpdfheaderfooter')


    <div class="card">

        <h5 class="head text-primary">@if(empty($data['campus_id'])) @lang('english.all_your_campuses') @else @if(!empty($data['student_payments[0]']->campus->campus_name)){{ $data['student_payments[0]->campus->campus_name'] }}@endif @endif @lang('english.fee_payments')</h5>

        <div class="table-responsive">
            <table class="table mb-0" width="100%">
                <thead class="table-light" width="100%">
                    <tr>
                        <th>#</th>
                        <th>@lang('english.date')</th>
                        <th>@lang('english.method')</th>
                        <th>@lang('english.account_name')</th>
                        <th>@lang('english.roll_no')</th>
                        <th>@lang('english.student_name')</th>
                        <th>@lang('english.discount_amount')</th>
                        <th>@lang('english.paid')</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($data['student_payments'] as $pay)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ @format_date($pay->paid_on)}}</td>
                        <td>{{ $pay->method .' /'. $pay->note}}</td>
                        <td>{{ $pay->payment_account?$pay->payment_account->name:''}}</td>
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
                        <td colspan='5'>@lang('english.total')</td>
                        <td>{{@num_format($total_discount_amount)}}</td>
                        <td>{{@num_format($total_paid)}}</td>
                    </tr>

                </tbody>
            </table>
        </div>


    </div>
    <p style="page-break-after: always;"></p>
    <div class="card">

        <h5 class="head text-primary">@if(empty($data['campus_id'])) @lang('english.all_your_campuses') @else @if(!empty($data['hrm_payments[0]->campus->campus_name'])){{$data['hrm_payments[0]->campus->campus_name']}}@endif @endif @lang('english.hrm_payment')</h5>
        <div class="table-responsive">

            <table class="table mb-0" width="100%">
                <thead class="table-light" width="100%">
                    <tr>
                        <th>#</th>
                        <th>@lang('english.date')</th>
                         <th>@lang('english.method')</th>
                        <th>@lang('english.account_name')</th>
                        <th>@lang('english.employee_no')</th>
                        <th>@lang('english.employee_name')</th>
                        <th>@lang('english.paid')</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($data['hrm_payments'] as $pay_hrm)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ @format_date($pay_hrm->paid_on)}}</td>
                        <td>{{ $pay->method .' /'. $pay->note}}</td>
                        <td>{{ $pay->payment_account?$pay->payment_account->name:''}}</td>
                        <td>{{ $pay_hrm->employee->employeeID}}</td>
                        <td>{{ $pay_hrm->employee->first_name.' '.$pay_hrm->employee->last_name}}</td>
                        <td>{{ @num_format($pay_hrm->amount)}}</td>
                    </tr>
                    @php
                    $total_hrm_paid += $pay_hrm->amount;

                    @endphp
                    @endforeach

                    <tr>
                        <td colspan='5'>@lang('english.total')</td>
                        <td>{{@num_format($total_hrm_paid)}}</td>
                    </tr>

                </tbody>
            </table>
        </div>


    </div>
    <p style="page-break-after: always;"></p>

  <div class="card">

        <h5 class="head text-primary">@if(empty($data['campus_id'])) @lang('english.all_your_campuses') @else @if(!empty($data['expenses_payments[0]->campus->campus_name'] )){{$data['expenses_payments[0]->campus->campus_name'] }}@endif @endif  @lang('english.expenses_payment')</h5> </h5>


        <div class="table-responsive" >
            <hr>

            <table class="table mb-0" width="100%">
                <thead class="table-light" width="100%">
                    <tr>
                        <th>#</th>
                        <th>@lang('english.date')</th>
                        <th>@lang('english.method')</th>
                        <th>@lang('english.account_name')</th>
                        <th>@lang('english.category')</th>
                        <th>@lang('english.note')</th>
                        <th>@lang('english.employee_no')</th>
                        <th>@lang('english.employee_name')</th>
                        <th>@lang('english.paid')</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach ($data['expenses_payments'] as $pay_expenses)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ @format_date($pay_expenses->paid_on)}}</td>
                        <td>{{ $pay->method .' /'. $pay->note}}</td>
                        <td>{{ $pay->payment_account?$pay->payment_account->name:''}}</td>
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
                        <td colspan='7'>@lang('english.total')</td>
                        <td>{{@num_format($total_expenses_paid)}}</td>
                    </tr>

                </tbody>
            </table>
        </div>


    </div>

<table class="table table-bordered" width="100%">
      
        <tbody>
       <tr>
                <th>@lang('english.total')@lang('english.fee_payment')</th>
              <td> {{ @num_format($total_paid) }}</td>
              
              
            </tr>
             <tr>
                       <th>@lang('english.total') @lang('english.hrm_payment')</th>
                <td> {{ @num_format($total_hrm_paid) }}</td>
                      
                    </tr>
                     <tr>
                 <th>@lang('english.total') @lang('english.expenses_payment')</th>
                        <td> {{ @num_format($total_expenses_paid) }}</td>
                      
              
            </tr>
              <tr>
            @php
                $total_income=$total_paid-($total_expenses_paid+$total_hrm_paid);

            @endphp
                <th>@lang('english.total') @lang('english.income')</th>
                <td> {{ @num_format($total_income) }}</td>
              
            </tr>
        </tbody>
        </table>

   
</body>


</html>
