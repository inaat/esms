<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.account_report_from') :{{ @format_date($data['start_date']) }} @lang('english.to') {{ @format_date($data['end_date']) }}</title>
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

</style>
</head>

<body>
    
    <div class="space" style="width:100%;  height:1px;">
    </div>
    <div id="head">
        <h4>@lang('english.account_report_from') : {{ @format_date($data['start_date']) }} @lang('english.to') {{ @format_date($data['end_date']) }} <b></h4>
    </div>
    <div class="space" style="width:100%;  height:1px;">
    </div>
    <div style="float:right">
     <table class="table">
                                      <tr>
                                          <th>@lang('english.account_name'): </th>
                                          <td>{{ $account->name }}</td>
                                      </tr>
                                      <tr>
                                          <th>@lang('english.account_type'):</th>
                                          <td>@if (!empty($account->account_type->parent_account)) {{ $account->account_type->parent_account->name }} - @endif {{ $account->account_type->name ?? '' }}</td>
                                           {!! Form::hidden('account_id', $account->id )!!}
                                      </tr>
                                      <tr>
                                          <th>@lang('english.account_number'):</th>
                                          <td>{{ $account->account_number }}</td>
                                      </tr>
                                      <tr>
                                          <th>@lang('english.balance'):</th>
                                          <td><span id="account_balance" class="display_currency" data-currency_symbol="true">{{ @num_format($balance) }}</span></td>
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
           @foreach ($ledger_transaction as  $data)
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
