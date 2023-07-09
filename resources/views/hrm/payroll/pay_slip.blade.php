<div class="row">
    <!-- left column -->
    <div class="col-md-12">

        <div class="">
            <table width="100%">
                <tbody>
                    <tr>


                        <td style="height: 100px;width: 850px;">
                            <div>@include('common.logo')</div>

                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <h3 style="margin: 10px 0 20px;">{{ $transaction->payroll_group_name }}</h3>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table width="100%" class="">
                <tbody>
                    <tr>
                        <th>Payslip #{{ $transaction->ref_no  }}</th>
                        <td></td>
                        <th class="text-right"></th>
                        <th class="text-right"></th>

                    </tr>
                </tbody>
            </table>
            <hr>

            <table width="100%" class="">

                <tbody>
                    <tr>
                        <th width="25%">Staff ID</th>
                        <td width="25%">{{ ucwords($transaction->employee->employeeID) }}</td>
                        <th width="25%">@lang('english.name')</th>
                        <td width="25%">{{ ucwords($transaction->employee->first_name . ' ' . $transaction->employee->last_name) }}</td>
                    </tr>
                    <tr>
                        <th>Department</th>
                        <td>Academic</td>

                        <th>Designation</th>
                        <td>{{ $transaction->employee->designations->designation }}</td>
                    </tr>
                </tbody>
            </table>


        </div>
    </div>
    <!--/.col (left) -->

</div>




<div class="row">
    <div class="col-6 ">
        <table class="earntable table table-striped table-responsive">
            <tbody>
                <tr>
                    <th width="19%">Earning</th>
                    <th width="16%" class="pttright reborder">Amount</th>

                </tr>
                @foreach ($transaction->allowance as $allowance)


                <tr>

                    <td>{{ $allowance->hrm_allowance->allowance}}</td>
                    <td class="pttright reborder">@format_currency($allowance->amount) </td>

                </tr>
                @endforeach

                <th>@lang('english.total') Earning</th>
                <th class="pttright reborder">@format_currency($transaction->allowances_amount) </th>

                </tr>
            </tbody>
        </table>

    </div>
    <div class="col-6 ">

        <table class="earntable table table-striped table-responsive">
            <tbody>
                <tr>

                    <th width="20%" class="pttleft">Deduction</th>
                    <th width="16%" class="text-right">Amount</th>
                </tr>
                @foreach ($transaction->deduction as $deduction)


                <tr>
                    <td>{{ $deduction->hrm_deduction->deduction}}</td>
                    <td class="pttright reborder">@format_currency($deduction->amount) </td>
                </tr>
                @endforeach

                <tr>

                    <th class="pttleft">Total Deduction</th>
                    <th class="text-right">@format_currency($transaction->deductions_amount) </th>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<div class="row">
    <div class="col-6 ">
        <h3>Payments</h3>
        <table class="table table-slim">
            @if(!empty($transaction->payment_lines))
            @foreach($transaction->payment_lines as $payment)
            <tr>
                <td>{{$payment->method}}</td>
                <td>@format_currency($payment->amount)</td>
                <td>{{$payment->paid_on}}</td>
            </tr>
            @endforeach
            @endif
        </table>

    </div>
    <div class="col-6 ">

        <table class="totaltable table table-striped table-responsive">
            <tbody>
                <tr>
                    <th width="20%">Basic Salary</th>
                    <td class="text-right">@format_currency($transaction->basic_salary) </td>
                </tr>

                <tr>
                    <th width="20%">Gross Salary</th>
                    <td class="text-right">@format_currency($transaction->basic_salary+$transaction->allowances_amount) </td>
                </tr>
                <tr>
                    <th width="20%">Net Salary</th>
                    <td class="text-right">@format_currency(($transaction->basic_salary+$transaction->allowances_amount)-$transaction->deductions_amount)  </td>
                </tr>
                <tr>
                    <th width="20%">Total Paid</th>
                    <td class="text-right">@format_currency($transaction->total_paid)  </td>
                </tr>
                <tr>
                    <th width="20%">Total Current Due</th>
                    <td class="text-right">@format_currency($transaction->final_total-$transaction->total_paid)  </td>
                </tr>
                </tr>
                <tr>
                    <th width="20%">Previous Dues</th>
                    <td class="text-right">@format_currency($employee_details->total_due-($transaction->final_total-$transaction->total_paid))  </td>
                </tr>
                </tr>
                <tr>
                    <th width="20%">Total Balance</th>
                    <td class="text-right">@format_currency($employee_details->total_due) </td>
                </tr>
                <tr>



                </tr>

            </tbody>
        </table>

    </div>

</div>






<style type="text/css">
    body {
        color: #000000;
    }

    @page {
        margin: 0px;
        padding: 3px;
        font-size: 16px;
        font-weight: 700;
    }

    @media print {

        .pace-progress {
            display: none;
        }

        * {
            margin: 0px;
            padding: 0px;
            color: #000;
            page-break-inside: avoid;
            font-family: sans serif;

        }

        @page {

            size: A4;
            -webkit-print-color-adjust: exact;
            page-break-inside: avoid;

            margin: 15px !important;
            padding: 15px !important;
            width: 100%;
            height: 100%;
        }
    }

</style>
