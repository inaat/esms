@if(!empty($for_pdf))

<link rel="stylesheet" href="{{ asset('assets/css/app.css?v='.$asset_v) }}">
@endif

<div class="row" style="@if(!empty($for_pdf))  margin:20px; @endif">



    <div class="col-md-12 col-sm-12 @if(!empty($for_pdf)) width-100 align-right @endif">
        <p class="text-right float-md-end"><strong>{{ session()->get("system_details.org_name") }}</strong>
            <br>
            {{ session()->get("system_details.org_address") }}<br>{{ session()->get("system_details.org_contact_number") }}
        </p>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4 @if(!empty($for_pdf)) width-20 f-left @endif">
        <p class="blue-heading p-4 text-center width-50">@lang('english.to'):</p>
        <p><strong>{{ ucwords($employee->first_name . ' '. $employee->last_name) }}</strong><br>@lang('english.current_address'):{{ $employee->current_address}}<br> @lang('english.mobile_no'):{{ $employee->mobile_no }}
        </p>
    </div>
    <div class="col-md-8 col-sm-7   ">
        <h5 class="mb-0 blue-heading text-center p-4">@lang('english.payroll_summary')</h5>
        <small>{{$ledger_details['start_date']}} @lang('english.to') {{$ledger_details['end_date']}}</small>
        <hr>
        <table class="table table-condensed  ">
            <tbody>
                <tr>
                    <td>@lang('english.opening_balance')</td>
                    <td class="align-right">@format_currency($ledger_details['beginning_balance'])</td>
                </tr>
                <tr>
                    <td>@lang('english.total') @lang('english.pay_roll')</td>
                    <td class="align-right">@format_currency($ledger_details['total_payroll'])</td>
                </tr>
                <tr>
                    <td>@lang('english.total_paid')</td>
                    <td class="align-right">@format_currency($ledger_details['total_paid'])</td>
                </tr>

                <tr>
                    <td><strong>@lang('english.balance_due')</strong></td>
                    <td class="align-right">@format_currency($ledger_details['balance_due'])</td>
                </tr>
            </tbody>
        </table>
    </div>


    <div class="col-md-12 col-sm-12 @if(!empty($for_pdf)) width-100 @endif">
        <p class="text-center" style="text-align: center;"><strong>@lang('english.ledger_table_heading', ['start_date' => $ledger_details['start_date'], 'end_date' => $ledger_details['end_date']])</strong></p>
        <div class="table-responsive">
            <table class="table table-striped @if(!empty($for_pdf)) table-pdf td-border @endif" id="ledger_table">
                <thead>
                    <tr class="row-border blue-heading">
                        <th width="18%" class="text-center">@lang('english.date')</th>
                        <th width="9%" class="text-center">@lang('english.ref_no')</th>
                        <th width="8%" class="text-center">@lang('english.type')</th>
                        <th width="5%" class="text-center">@lang('english.payment_status')</th>
                        <th width="10%" class="text-center">@lang('english.debit')</th>
                        <th width="10%" class="text-center">@lang('english.credit')</th>
                        <th width="10%" class="text-center">@lang('english.balance')</th>
                        <th width="5%" class="text-center">@lang('english.payment_method')</th>
                        <th width="15%" class="text-center">@lang('english.others')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ledger_details['ledger'] as $data)
                    <tr @if(!empty($for_pdf) && $loop->iteration % 2 == 0) class="odd" @endif>
                        <td class="row-border">{{@format_date($data['date'])}}</td>
                        <td>{{$data['ref_no']}}</td>
                        <td>{{$data['type']}}</td>
                        <td>{{$data['payment_status']}}</td>
                        <td class="ws-nowrap align-right">@if($data['debit'] != '') @format_currency($data['debit']) @endif</td>
                        <td class="ws-nowrap align-right">@if($data['credit'] != '') @format_currency($data['credit']) @endif</td>
                        <td class="ws-nowrap align-right">{{$data['balance']}}</td>
                        <td>{{$data['payment_method']}}</td>
                        <td>{!! $data['others'] !!}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
