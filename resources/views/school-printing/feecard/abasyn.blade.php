<!DOCTYPE html >
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.fee_card')</title>
<style>
    @page {
        margin: 0px;
        padding: 3px;
        font-size: 12px;
        font-weight: 700;
    }
@import url('https://fonts.googleapis.com/css?family=Roboto'); /* Example URL for Google Fonts */

    @media print {

        .pace-progress {
            display: none;
        }

        * {
            margin: 0px;
            padding: 0px;
            color: #000;
            page-break-inside: avoid;
            font-family: 'Roboto', sans-serif; 

        }
        td>strong{
            font-size:18px;color:black;
        }

        @page {

            size: A4;
            -webkit-print-color-adjust: exact;
            page-break-inside: avoid;

            margin: 15px !important;
            padding: 10px !important;
            width: 100%;
            height: 100%;
        }


        .flex_container {
            display: flex;
            width: 100%;
            zoom: 98%;
            border-bottom: 1px solid #000;
            page-break-inside: avoid;



        }

        .flex_item_left {
            width: 50%;
            padding: 5px;
            overflow: hidden;
            border-right: 2px solid #000;


        }

        .flex_item_right {
            width: 50%;
            overflow: hidden;
            padding: 5px;


        }

        .flex_item_right>img {
            overflow: hidden;

        }
     
        #head {
            width: 70%;
            /* 70% of the parent*/
            background: {{  config('constants.head_bg_color') }};
            text-align: center;
            padding: 3px;
            margin: 0px auto;
            border-radius: 5px;
            height: 25px;
            border-bottom: 1px solid #000;



        }

        #head>h6 {
            color: #fff;
            -webkit-print-color-adjust: exact;
        }

        #head1 {
            width: 95%;
            /* 70% of the parent*/
            background: {{  config('constants.head_bg_color') }};
            text-align: center;
            padding: 3px;
            margin: 0px auto;
            border-radius: 5px;
            height: 20px;
            border-bottom: 1px solid #000;



        }

        #head1>h6 {
            color: #fff;
            font-size: 8px;
            -webkit-print-color-adjust: exact;
        }


        .info {
            display: flex;
            width: 100%;
            padding: 0px;
            margin: 0px;
            gap: 0px;


        }

        .info_left {
            width: 80%;
            overflow: hidden;
            padding: 0px;
            margin: 0px;

        }

        .info_right {
            width: 20%;
            overflow: hidden;
            padding: 0px;
            margin: 0px;


        }


        table {
            width: 100%;

            border-collapse: collapse;
            border-bottom: 2px solid black;

            color: #000;


        }

        /* Zebra striping */
        tr:nth-of-type(odd) {}

        td,
        th {
            padding: 5px;
            border: 1px solid black;
            text-align: center;
            font-size: 12px;
            color: black;
        }
    }

</style>
</head>

<body style="color:#000;background-color:#ffff;" >
    @foreach ($student as $std)
    @php
    $remain_total= $std['balance']['balance'] [count($std['balance']['balance'] )-1];
 @endphp

      {{-- @if($std['total_due']!=$remain_total && $remain_total>-1)  --}}
    
   
        {{-- <div style=" border:1px solid black; margin:5px; page-break-after: always;"> --}}
<div class="paided" style=" border:1px solid black; margin-top:10px; zoom:70% ">

            <div class="flex_container">
                <div class="flex_item_left" >
                    {{-- <img src="{{ url('/uploads/invoice_logo/logo.jpeg') }}" width="100% " > --}}
                   @include('common.logo')

                </div>
                <div class="flex_item_right" style="">
                    {{-- <img src="{{ url('/uploads/invoice_logo/logo.jpeg') }}" width="100%" height="100%"> --}}
                    @include('common.logo_with_height')

                </div>
            </div>
            <div class="flex_container">
                <div class="flex_item_left ">
                    <div id="head">
                        <h6>Fee Upto @lang('english.months.' . $std['current_transaction']->month ) Session ({{ $std['current_transaction']->session->title }})
                        </h6>
                    </div>
                    <div class="info" style="border: 1px solid #000; padding:1px; zoom:85%">
                  
                            <div class="info_left">
                                 {{-- <strong>@lang('english.challan_no'):</strong>
                                <strong>{{ ucwords($std['current_transaction']->voucher_no) }}</strong>
                                <span style="margin-left:30px"><strong>@lang('english.due_date'):</strong>
                                    <strong>{{ @format_date($std['current_transaction']->due_date ?$std['current_transaction']->due_date :$std['current_transaction']->transaction_date ) }}</strong></span><br>--}}
                                <strong style="margin-left:30px">@lang('english.roll_no'):</strong>
                                <strong>{{ ucwords($std['current_transaction']->student->roll_no) }}</strong><br>
                                <span style="margin-left:30px"><strong>@lang('english.class'):{{ $std['current_transaction']->student_class->title .'  ' .$std['current_transaction']->student_class_section->section_name }}
                                    </strong></span><br>
                                <span style="margin-left:30px"><strong>@lang('english.student_name'):
                                        <strong>{{ ucwords($std['current_transaction']->student->first_name . ' ' . $std['current_transaction']->student->last_name) }}</strong>
                                    </strong></span><br>

                                <span style="margin-left:30px"><strong>@lang('english.father_name'):{{ ucwords($std['current_transaction']->student->father_name) }}
                                    </strong></span>
                                {{--  <span style="margin-left:30px"><strong>@lang('english.cell'):{{ ucwords($std['current_transaction']->student->mobile_no) }}
                                    </strong></span>
                                <br>
                                <span><strong>@lang('english.address'):{{ ucwords($std['current_transaction']->student->std_permanent_address) }}
                                    </strong></span>
                                    <strong>@lang('english.challan_no'):</strong>
                                <strong>{{ ucwords($std['current_transaction']->voucher_no) }}</strong>--}}
                            </div>
                                <div class="info_right">
                                        @if (file_exists(public_path($std['student_image'])))
                                        <img width="100" height="80" src="{{ url($std['student_image']) }}" />
                                        @else
                                        <img width="100%" height="100" src="{{ url('uploads/student_image/default.png') }}" />
                                        @endif
                                </div>
                    </div>
                                            
                        <table style="zoom:70%;">
                            <tr style="background:#0070C0;color:white">
                            <td ><strong style="background:#0070C0;color:white">Months</strong></td>
                            <td><strong style="background:#0070C0;color:white">@lang('english.b/f')</strong></td>
                            <td><strong style="background:#0070C0;color:white">@lang('english.current_fee')</strong></td>
                            <td><strong style="background:#0070C0;color:white">@lang('english.total')</strong></td>
                            <td><strong style="background:#0070C0;color:white">@lang('english.paid')</strong></td>
                            <td><strong style="background:#0070C0;color:white">@lang('english.discount')</strong></td>
                            <td><strong style="background:#0070C0;color:white">@lang('english.balance')</strong></td>
                        
                            
                            </tr>              
                            @foreach (__('english.short_months') as $month)
                            <tr>
                                
                                <td>
                            
                                <strong> {{ $month }}</strong>
                            
                                </td>
                                @if($std['current_transaction']->month+1 <=$loop->iteration  )
                                <td></td>
                                  <td></td>
                                    <td></td>
                                      <td></td>
                                        <td></td>
                                          <td></td>
                                @else
                                {{-- B/F --}}
                                <td>
                                    @if ($std['fee_month']+1 <=$loop->iteration  )
                                    <strong>{{ ' ' }}</strong>
                            @else
                            <strong>{{ number_format($std['balance']['bf'][$loop->iteration] , 0) }}</strong>
                            @endif  
                        </td>
                        
                        <td>
                                    
                            @if ($std['transaction_formatted'][$loop->iteration] != 0)
                            <strong>{{ number_format($std['transaction_formatted'][$loop->iteration], 0) }}</strong>
                        @else
                        <strong>{{ ' ' }}</strong>
                        @endif
                        </td>
                        
                        
                        <td style="background:#0070C0;color:white">
                        @if ($std['fee_month']+1 <=$loop->iteration )
                        
                        <strong>{{ ' ' }}</strong>
                        @else
                        <strong style="background:#0070C0;color:white">{{ number_format($std['balance']['total'][$loop->iteration], 0) }}</strong>
                        @endif
                        </td>
                        
                        
                        <td>
                        
                            @if (count($std['payment_formatted']) == 0)
                        
                            @else
                                @if ($std['payment_formatted'][$loop->iteration] != 0)
                                    <strong>{{ number_format($std['payment_formatted'][$loop->iteration], 0) }}</strong>
                                @else
                                    <strong>{{ ' '}}</strong>
                                @endif
                            @endif
                            </td>
                            <td>
                                @if (count($std['discount_payment_formatted']) == 0)
                            
                                @else
                                    @if ($std['discount_payment_formatted'][$loop->iteration] != 0)
                                        <strong>{{ number_format($std['discount_payment_formatted'][$loop->iteration], 0) }}</strong>
                                    @else
                                        <strong>{{ ' ' }}</strong>
                                    @endif
                                @endif
                                </td>
                        
                                <td >
                        
                                    @if ($std['fee_month']+1 <=$loop->iteration )
                                    
                                    <strong>{{ ' ' }}</strong>
                                    @else
                                    <strong >{{ number_format($std['balance']['balance'][$loop->iteration], 0) }}</strong>
                                    @endif    
                                </td>
                                 @endif 
                            </tr>
                            
                            @endforeach
                        
                        
                        
                        </table>
                        
        <h6 style="text-align:center;color:#000;display:inline">@lang('english.current_fee')</h6>
        <table style="zoom:70%; line-height:1.5">
            <thead class="" width="100%">
                <tr style="background:#0070C0;color:white">
                    @php
                        $total_final_fee=0;
                    @endphp
                    @foreach ($std['current_headings'] as $feeheading)
                    @php
                        $total_final_fee+=$feeheading->final_total;
                    @endphp
                    @foreach ($feeheading->fee_lines as $feeHead)
                        <th style="background:#0070C0;color:white"> {{ $feeHead->feeHead->description }}</th>
                    @endforeach
                    @endforeach
                    <th style="background:#0070C0;color:white">@lang('english.total_current_fee')</th>
                </tr>
                <tr>
                    @foreach ($std['current_headings'] as $feeheading)
                    @foreach ($feeheading->fee_lines as $feeHead)
                        <td><strong>{{ number_format($feeHead->amount, 0) }}</strong></td>
                    @endforeach
                    @endforeach
                    <td><strong>{{ number_format($total_final_fee, 0) }}</strong></td>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <table style="width:50% ; float:right;margin-top:1px; ">
            <thead class="table-light">
                <tr style="background:#0070C0;color:white">
                 
                    <th><strong style="background:#0070C0;color:white">@lang('english.net_total')</strong></th>
                     @if($std['total_due']!=$remain_total && $remain_total>-1)
                    <td style="background-color: rgb(238, 22, 22);">{{ number_format($std['balance']['balance'][$std['current_transaction']->month], 0) }}</td>
                    @else 
                    <td style="background:#0070C0;color:white" ><strong style="background:#0070C0;color:white">{{number_format($std['balance']['balance'][$std['current_transaction']->month], 0) }}</strong></td>
                    @endif 
                </tr>
                <tr>
                    <th><strong>@lang('english.paid')</strong></th>
                    @if($std['current_transaction_paid']->total_paid==0)
                    <td></td>
                    @else
                    <td>{{ number_format($std['current_transaction_paid']->total_paid, 0) }}</td>
                    @endif
                </tr>
                <tr>
                    <th><strong>@lang('english.balance')</strong></th>
                    <td></td>
                </tr>

            </thead>
            <tbody>
            </tbody>
        </table>
        <p> <br><strong>@lang('english.adjustment')</strong>
            <strong>____________________</strong>
        </p>
        {{-- <p>
            <strong>@lang('english.account_officer')</strong>
            <strong>_____________________</strong>
        </p> --}}

                </div>
                <div class="flex_item_right">
                
                    <div id="head">
                        <h6>Fee Upto @lang('english.months.' . $std['current_transaction']->month ) Session ({{ $std['current_transaction']->session->title }})
                        </h6>
                    </div>
                    <div class="info" style="border: 1px solid #000; padding:1px; zoom:85%">
                  
                            <div class="info_left">
                                   {{-- <strong>@lang('english.challan_no'):</strong>
                                <strong>{{ ucwords($std['current_transaction']->voucher_no) }}</strong>
                                <span style="margin-left:30px"><strong>@lang('english.due_date'):</strong>
                                    <strong>{{ @format_date($std['current_transaction']->due_date ?$std['current_transaction']->due_date :$std['current_transaction']->transaction_date ) }}</strong></span><br>--}}
                                <strong style="margin-left:30px">@lang('english.roll_no'):</strong>
                                <strong>{{ ucwords($std['current_transaction']->student->roll_no) }}</strong><br>
                                <span style="margin-left:30px"><strong>@lang('english.class'):{{ $std['current_transaction']->student_class->title .'  ' .$std['current_transaction']->student_class_section->section_name }}
                                    </strong></span><br>
                                <span style="margin-left:30px"><strong>@lang('english.student_name'):
                                        <strong>{{ ucwords($std['current_transaction']->student->first_name . ' ' . $std['current_transaction']->student->last_name) }}</strong>
                                    </strong></span><br>

                                <span style="margin-left:30px"><strong>@lang('english.father_name'):{{ ucwords($std['current_transaction']->student->father_name) }}
                                    </strong></span>
                                {{--  <span style="margin-left:30px"><strong>@lang('english.cell'):{{ ucwords($std['current_transaction']->student->mobile_no) }}
                                    </strong></span>
                                <br>
                                <span><strong>@lang('english.address'):{{ ucwords($std['current_transaction']->student->std_permanent_address) }}
                                    </strong></span>
                                    <strong>@lang('english.challan_no'):</strong>
                                <strong>{{ ucwords($std['current_transaction']->voucher_no) }}</strong>--}}
                            </div>
                                <div class="info_right">
                                        @if (file_exists(public_path($std['student_image'])))
                                        <img width="100" height="80" src="{{ url($std['student_image']) }}" />
                                        @else
                                        <img width="100%" height="100" src="{{ url('uploads/student_image/default.png') }}" />
                                        @endif
                                </div>
                    </div>
                                            
                        <table style="zoom:70%;">
                            <tr style="background:#0070C0;color:white">
                            <td ><strong style="background:#0070C0;color:white">Months</strong></td>
                            <td><strong style="background:#0070C0;color:white">@lang('english.b/f')</strong></td>
                            <td><strong style="background:#0070C0;color:white">@lang('english.current_fee')</strong></td>
                            <td><strong style="background:#0070C0;color:white">@lang('english.total')</strong></td>
                            <td><strong style="background:#0070C0;color:white">@lang('english.paid')</strong></td>
                            <td><strong style="background:#0070C0;color:white">@lang('english.discount')</strong></td>
                            <td><strong style="background:#0070C0;color:white">@lang('english.balance')</strong></td>
                        
                            
                            </tr>              
                            @foreach (__('english.short_months') as $month)
                            <tr>
                                
                                <td>
                            
                                <strong> {{ $month }}</strong>
                            
                                </td>
                                {{-- B/F --}}
                                  @if($std['current_transaction']->month+1 <=$loop->iteration  )
                                  <td></td>
                                  <td></td>
                                    <td></td>
                                      <td></td>
                                        <td></td>
                                          <td></td>
                                @else
                                <td>
                                    @if ($std['fee_month']+1 <=$loop->iteration  )
                                    <strong>{{ ' ' }}</strong>
                            @else
                            <strong>{{ number_format($std['balance']['bf'][$loop->iteration] , 0) }}</strong>
                            @endif  
                        </td>
                        
                        <td>
                                    
                            @if ($std['transaction_formatted'][$loop->iteration] != 0)
                            <strong>{{ number_format($std['transaction_formatted'][$loop->iteration], 0) }}</strong>
                        @else
                        <strong>{{ ' ' }}</strong>
                        @endif
                        </td>
                        
                        
                        <td style="background:#0070C0;color:white">
                        @if ($std['fee_month']+1 <=$loop->iteration )
                        
                        <strong>{{ ' ' }}</strong>
                        @else
                        <strong style="background:#0070C0;color:white">{{ number_format($std['balance']['total'][$loop->iteration], 0) }}</strong>
                        @endif
                        </td>
                        
                        
                        <td>
                        
                            @if (count($std['payment_formatted']) == 0)
                        
                            @else
                                @if ($std['payment_formatted'][$loop->iteration] != 0)
                                    <strong>{{ number_format($std['payment_formatted'][$loop->iteration], 0) }}</strong>
                                @else
                                    <strong>{{ ' '}}</strong>
                                @endif
                            @endif
                            </td>
                            <td>
                                @if (count($std['discount_payment_formatted']) == 0)
                            
                                @else
                                    @if ($std['discount_payment_formatted'][$loop->iteration] != 0)
                                        <strong>{{ number_format($std['discount_payment_formatted'][$loop->iteration], 0) }}</strong>
                                    @else
                                        <strong>{{ ' ' }}</strong>
                                    @endif
                                @endif
                                </td>
                        
                                <td >
                        
                                    @if ($std['fee_month']+1 <=$loop->iteration )
                                    
                                    <strong>{{ ' ' }}</strong>
                                    @else
                                    <strong >{{ number_format($std['balance']['balance'][$loop->iteration], 0) }}</strong>
                                    @endif    
                                </td>
                                   @endif
                            </tr>
                            @endforeach
                        
                        
                        
                        </table>
                        
        <h6 style="text-align:center;color:#000;display:inline">@lang('english.current_fee')</h6>
        <table style="zoom:70%; line-height:1.5">
            <thead class="" width="100%">
                <tr>
                    @php
                        $total_final_fee=0;
                    @endphp
                    @foreach ($std['current_headings'] as $feeheading)
                    @php
                        $total_final_fee+=$feeheading->final_total;
                    @endphp
                    @foreach ($feeheading->fee_lines as $feeHead)
                        <th style="background:#0070C0;color:white">{{ $feeHead->feeHead->description }}</th>
                    @endforeach
                    @endforeach
                    <th style="background:#0070C0;color:white">@lang('english.total_current_fee')</th>
                </tr>
                <tr>
                    @foreach ($std['current_headings'] as $feeheading)
                    @foreach ($feeheading->fee_lines as $feeHead)
                        <td><strong>{{ number_format($feeHead->amount, 0) }}</strong></td>
                    @endforeach
                    @endforeach
                    <td><strong>{{ number_format($total_final_fee, 0) }}</strong></td>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <table style="width:50% ; float:right;margin-top:1px; ">
            <thead class="table-light">
                <tr style="background:#0070C0;color:white">
                 
                    <th><strong style="background:#0070C0;color:white">@lang('english.net_total')</strong></th>
                     @if($std['total_due']!=$remain_total && $remain_total>-1)
                    <td style="background-color: rgb(238, 22, 22);">{{ number_format($std['balance']['balance'][$std['current_transaction']->month], 0) }}</td>
                    @else 
                    <td  ><strong style="background:#0070C0;color:white">{{ number_format($std['balance']['balance'][$std['current_transaction']->month], 0) }}</strong>
                    
                    
                    
                    </td>
                    @endif 
                </tr>
                <tr>
                    <th><strong>@lang('english.paid')</strong></th>
                    @if($std['current_transaction_paid']->total_paid==0)
                    <td></td>
                    @else
                    <td>{{ number_format($std['current_transaction_paid']->total_paid, 0) }}</td>
                    @endif
                </tr>
                <tr>
                    <th><strong>@lang('english.balance')</strong></th>
                    <td></td>
                </tr>

            </thead>
            <tbody>
            </tbody>
        </table>
        <p> <br><strong>@lang('english.adjustment')</strong>
            <strong>____________________</strong>
        </p>
        {{-- <p>
            <strong>@lang('english.account_officer')</strong>
            <strong>_____________________</strong>
        </p> --}}

            
                </div>

            </div>

</div>
{{-- @endif --}}
@endforeach

</body>

</html>