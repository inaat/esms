<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.account_report_from') :{{ @format_date($data['start_date']) }} @lang('english.to') {{ @format_date($data['end_date']) }}</title>

<body>
    
     @include('common.mpdfheaderfooter')
    <div  class="head text-primary">
        <h4>@lang('english.account_report_from') : {{ @format_date($data['start_date']) }} @lang('english.to') {{ @format_date($data['end_date']) }} <b></h4>
    </div>
    <div class="space" style="width:100%;  height:1px;">
    </div>
    <div style="float: right; width: 28%;">
     <table class="table" >
                                      <tr>
                                          <th>@lang('english.account_name'): </th>
                                          <td>{{ $data['account']->name }}</td>
                                      </tr>
                                      <tr>
                                          <th>@lang('english.account_type'):</th>
                                          <td>@if (!empty($data['account']->account_type->parent_account)) {{ $data['account']->account_type->parent_account->name }} - @endif {{ $data['account']->account_type->name ?? '' }}</td>
                                           {!! Form::hidden('account_id', $data['account']->id )!!}
                                      </tr>
                                      <tr>
                                          <th>@lang('english.account_number'):</th>
                                          <td>{{ $data['account']->account_number }}</td>
                                      </tr>
                                      <tr>
                                          <th>@lang('english.balance'):</th>
                                          <td><span id="account_balance" class="display_currency" data-currency_symbol="true">{{ @num_format($data['balance']) }}</span></td>
                                      </tr>
                                  </table>
                                  </div>
                                    <div class="space" style="width:100%;  height:1px;">
    </div>
    <table class="table mb-0" width="100%" id="employees_table">
        <thead class="table-light" width="100%">
            <tr style="background:#eee">
                <th>#</th>
                          <th>@lang( 'messages.date' )</th>
                                  <th>@lang( 'english.description' )</th>
                                  <th >@lang( 'account.note' )</th>
                                  <th>@lang( 'english.added_by' )</th>
                                  <th>@lang('english.debit')</th>
                                  <th>@lang('english.credit')</th>
                                  <th>@lang( 'english.balance' )</th>


            </tr>
        </thead>
        <tbody>
        @php
            $total_debit=0;
            $total_credit=0;
            $balance = 0;
        @endphp
           @foreach ($data['ledger_transaction'] as  $data)
                          <tr>
                             <td>{{$loop->iteration}}</td>
                              <td>{{@format_datetime($data['date'])}}</td>
                              <td>{!! $data['description'] !!}</td>
                              <td>{{ $data['note'] }}</td>
                              <td>{!! $data['added_by'] !!}</td>
                              <td>{{ @num_format($data['debit']) }}</td>
                              <td>{{ @num_format($data['credit']) }}</td>
                              <td>{{ @num_format($data['balance']) }}</td>
                                @php
                                    $total_debit += $data['debit'];
                                    $total_credit += $data['credit'];
                                    $balance += $data['balance'];
                                @endphp
                          </tr>
                              
                          @endforeach

                             <tr>
                             <td  colspan="5">@lang('english.total') </td>
                              <td>{{ @num_format($total_debit) }}</td>
                              <td>{{ @num_format($total_credit) }}</td>
                              <td></td>
                               
                          </tr>
            </tbody>

    </table>

</body>

</html>
