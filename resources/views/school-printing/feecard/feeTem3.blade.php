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
            width: 75%;
            padding: 5px;
            overflow: hidden;
            border-right: 2px solid #000;


        }

        .flex_item_right {
            width: 25%;
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
                    <div class="paided" style=" border:1px solid black; margin-top:10px;  ">

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
                             <strong>@lang('english.challan_no'):</strong>
                                <strong>{{ ucwords($std['current_transaction']->voucher_no) }}</strong>
                                <span style="margin-left:4px"><strong>@lang('english.due_date'):</strong>
                                    <strong>{{ @format_date($std['current_transaction']->due_date ?$std['current_transaction']->due_date :$std['current_transaction']->transaction_date ) }}</strong></span><br>
                                <strong>@lang('english.roll_no'):</strong>
                                <strong>{{ ucwords($std['current_transaction']->student->roll_no) }}</strong>
                                <span style="margin-left:4px"><strong>@lang('english.class'):{{ $std['current_transaction']->student_class->title .'  ' .$std['current_transaction']->student_class_section->section_name }}
                                    </strong></span><br>
                                <span style="margin-left:4px"><strong>@lang('english.student_name'):
                                        <strong>{{ ucwords($std['current_transaction']->student->first_name . ' ' . $std['current_transaction']->student->last_name) }}</strong>
                                    </strong></span><br>

                                <span><strong>@lang('english.father_name'):{{ ucwords($std['current_transaction']->student->father_name) }}
                                    </strong></span>
                                <span style="margin-left:4px"><strong>@lang('english.cell'):{{ ucwords($std['current_transaction']->student->mobile_no) }}
                                    </strong></span>
                                <br>
                                <span><strong>@lang('english.address'):{{ ucwords($std['current_transaction']->student->std_permanent_address) }}
                                    </strong></span>

                         
                        </div>
                        <div class="info_right">
                            @if (file_exists(public_path($std['student_image'])))
                            <img width="100" height="80" src="{{ url($std['student_image']) }}" />
                            @else
                            <img width="100%" height="100" src="{{ url('uploads/student_image/default.png') }}" />
    @endif
    
                        </div>
                    </div>

                    <table style="zoom:70%; line-height:1.5">

                        <tr>
                            <th style=""></th>
                            @foreach (__('english.short_months') as $month)
                                <th>{{ $month }}</th>
                            @endforeach
                        </tr>

                        <tr>
                            @php
                                $c_count = 0;
                                foreach ($std['transaction_formatted'] as $c) {
                                    if ($c != 0) {
                                        $c_count++;
                                    }
                                }
                            @endphp
                            <td><strong>@lang('english.b/f')</strong></td>
                            @foreach ($std['balance']['bf'] as $b)
                                @if ($loop->iteration == $std['fee_month'] + 1)
                                    @for ($i = 1; $i <= 11 - $std['fee_month']; $i++)
                                        <td>&nbsp;</td>
                                    @endfor
                                @break
                            @endif
                            <td><strong>{{ number_format($b, 0) }}</strong></td>
                        @endforeach
                    </tr>
                    <tr>
                        <td><strong>@lang('english.current_fee')</strong></td>
                        @foreach ($std['transaction_formatted'] as $t)
                            @if ($t != 0)
                                <td><strong>{{ number_format($t, 0) }}</strong></td>
                            @else
                                <td><strong>{{ ' ' }}</strong></td>
                            @endif
                        @endforeach
                    </tr>
                    <tr>
                        <td><strong>@lang('english.total')</strong></td>
                        @foreach ($std['balance']['total'] as $t)
                            @if ($loop->iteration == $std['fee_month'] + 1)
                                @for ($i = 1; $i <= 12 - $std['fee_month']; $i++)
                                    <td>&nbsp;</td>
                                @endfor
                            @break
                        @endif
                        <td><strong>{{ number_format($t, 0) }}</strong></td>
                    @endforeach
                </tr>
                <tr>
                    <td><strong>@lang('english.paid')</strong></td>
                    @if (count($std['payment_formatted']) == 0)
                        @for ($i = 1; $i <= 12; $i++)
                            <td>&nbsp;</td>
                        @endfor
                    @else
                        @foreach ($std['payment_formatted'] as $p)
                            @if ($p != 0)
                                <td><strong>{{ number_format($p, 0) }}</strong></td>
                            @else
                                <td><strong>{{ ' ' }}</strong></td>
                            @endif
                        @endforeach
                    @endif
                </tr>
                <tr>

                    <td><strong>@lang('english.discount')</strong></td>
                    @if (count($std['discount_payment_formatted']) == 0)
                        @for ($i = 1; $i <= 12; $i++)
                            <td>&nbsp;</td>
                        @endfor
                    @else
                        @foreach ($std['discount_payment_formatted'] as $d)
                            @if ($d != 0)
                                <td><strong>{{ number_format($d, 0) }}</strong></td>
                            @else
                                <td><strong>{{ ' ' }}</strong></td>
                            @endif
                        @endforeach
                    @endif

                </tr>
                <tr>
                    <td><strong>@lang('english.balance')</strong></td>
                    @foreach ($std['balance']['balance'] as $b)
                        @if ($loop->iteration == $std['fee_month'] + 1)
                            @for ($i = 1; $i <= 12 - $std['fee_month']; $i++)
                                <td>&nbsp;</td>
                            @endfor
                        @break
                    @endif
                    <td><strong>{{ number_format($b, 0) }}</strong></td>
                @endforeach
            </tr>

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
                        <th>{{ $feeHead->feeHead->description }}</th>
                    @endforeach
                    @endforeach
                    <th>@lang('english.total_current_fee')</th>
                </tr>
                <tr>
                    @foreach ($std['current_headings'] as $feeheading)
                    @foreach ($feeheading->fee_lines as $feeHead)
                        <td>{{ number_format($feeHead->amount, 0) }}</td>
                    @endforeach
                    @endforeach
                    <td>{{ number_format($total_final_fee, 0) }}</td>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <table style="width:50% ; float:right;margin-top:1px; ">
            <thead class="table-light">
                <tr>
                 
                    <th><strong>@lang('english.net_total')</strong></th>
                     @if($std['total_due']!=$remain_total && $remain_total>-1)
                    <td style="background-color: rgb(238, 22, 22);">{{ number_format($std['total_due'], 0) }}</td>
                    @else 
                    <td >{{ number_format($std['total_due'], 0) }}</td>
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
        <p>
            <strong>@lang('english.account_officer')</strong>
            <strong>_____________________</strong>
        </p>


    </div>
    <div class="flex_item_right">
        <div id="head1">
            <h6>Fee Upto @lang('english.months.' . $std['current_transaction']->month ) Session ({{ $std['current_transaction']->session->title }})
            </h6>
        </div>
        <div class="space " style="width:100%;  height:15px;">
        </div>


        <p style="zoom: 80%"> <strong>@lang('english.roll_no'):</strong>
            <span
                class=""><strong>{{ ucwords($std['current_transaction']->student->roll_no) }}</strong></span>
            <br>
            <br>
            <strong>@lang('english.old_roll_no'):</strong>
            <span
                class=""><strong>{{ ucwords($std['current_transaction']->student->old_roll_no) }}</strong></span>



            <br>
            <br>
            <strong>@lang('english.name'):</strong>
            <strong>{{ ucwords($std['current_transaction']->student->first_name . ' ' . $std['current_transaction']->student->last_name) }}</strong>



            <br>
            <br>
            <strong>@lang('english.f_name'):</strong>

            <strong>{{ ucwords($std['current_transaction']->student->father_name) }}</strong>


            <br>
            <br>
            <strong>@lang('english.class')</strong>
            {{ $std['current_transaction']->student_class->title .'  ' .$std['current_transaction']->student_class_section->section_name }}
        </p>


        <div style="margin:0px auto;width:100%;  text-align:center;">
            <table>
                <thead class="table-light" width="100%">
                    <tr>
                        <th>@lang('english.net_total')</th>
                        <td>{{ number_format($std['total_due'], 0) }}</td>
                    </tr>
                    <tr>
                        <th>@lang('english.paid')</th>
                        @if($std['current_transaction_paid']->total_paid==0)
                        <td></td>
                        @else
                        <td>{{ number_format($std['current_transaction_paid']->total_paid, 0) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <th>@lang('english.balance')</th>
                        <td></td>
                    </tr>

                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="space " style="width:100%;  height:30px;">
        </div>
        <p> <strong>@lang('english.adjustment')</strong>
            <strong>__________</strong>
            <br>
            <br>

            <strong>@lang('english.account_officer')</strong>
            <strong>______</strong>
        </p>
    </div>
</div>

</div>

</div>
{{-- @endif --}}
@endforeach

</body>

</html>
