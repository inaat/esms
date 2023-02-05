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
            width:100%;
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

            <div class="flex_container">
                <div class="flex_item_left">
                   @include('common.logo')
                     <div id="head">
                     @if(!empty($std['current_transaction']))
                        <h6>@lang('english.months.' . $std['current_transaction']->month ) Session ({{ $std['current_transaction']->session->title }}) Student copy
                        </h6>
                        @else
                           <h6>@lang('english.months.' . $std['other_transaction'][0]->month ) Session ({{ $std['other_transaction'][0]->session->title }}) Student copy
                        </h6>
                    @endif
                    </div>
                    <div class="flex_container">
                    <div class="flex_item_left">
                    <table>
                     <tr>
                    <th>Roll No</th>
                    <td>{{ $std['current_transaction'] ? $std['current_transaction']->student->roll_no :$std['other_transaction'][0]->student->roll_no  }}</td>
                      </tr>
                    <tr>
                    <th>Name</th>
                    <td>{{ ucwords( $std['current_transaction'] ? $std['current_transaction']->student->first_name . ' ' . $std['current_transaction']->student->last_name :$std['other_transaction'][0]->student->first_name . ' ' . $std['other_transaction'][0]->student->last_name) }}</td>
                      </tr>
                    <tr>
                    <th>Class</th>
                    <td>{{ $std['current_transaction'] ?$std['current_transaction']->student_class->title .'  ' .$std['current_transaction']->student_class_section->section_name:$std['other_transaction'][0]->student_class->title .'  ' .$std['other_transaction'][0]->student_class_section->section_name }}</td>
                      </tr>
                    <tr>
                    <th>Father's Name</th>
                    <td>{{ ucwords($std['current_transaction'] ?$std['current_transaction']->student->father_name:$std['other_transaction'][0]->student->father_name) }}</td>
                      </tr>
                   
                    </table>
                     <div id="head">
                                          @if(!empty($std['current_transaction']))

                        <h6>@lang('english.months.' . $std['current_transaction']->month ) 
                        </h6>
                        @else
                        <h6>@lang('english.months.' . $std['other_transaction'][0]->month ) 
                        </h6>
                                            @endif

                    </div>
                     <table>
                     <tr>
                    <th>Particulars</th>
                    <th>Amount</th>
                      </tr>
                       @php
                        $paid=0;
                        $final_total=0;
                        @endphp
                    @if(!empty($std['current_transaction']))

                       @foreach ($std['current_transaction']->fee_lines as $feeHead)
                       <tr>
                        <th>{{ $feeHead->feeHead->description }}</th>
                        <td>{{ number_format($feeHead->amount, 0) }}</td>

                            </tr>
                    @endforeach
                   
                  
                     <tr>
                    <th>Final Total</th>
                    <td>{{ number_format($std['current_transaction']->final_total, 0) }}</td>
                      </tr>
                    @php
                    $final_total=$std['current_transaction']->final_total;
                        foreach($std['current_transaction']->payment_lines as $line){
                            $paid += $line->amount;
                        }
                    @endphp
                                         <tr>
               
                    <th>Paid Amount</th>

                    <td>{{ number_format($paid, 0) }}</td>
                      </tr>
                
                                         <tr>
                    <th>Previous Arrears</th>

                    <td>{{ number_format($std['total_fee_due']-$std['current_transaction']->final_total + $paid, 0) }}</td>
                      </tr>
                          @endif
                                         <tr>
                    <th>Total Arrears</th>

                    <td>{{ number_format($std['total_fee_due'], 0) }}</td>
                      </tr>
                   
                   
                    </table>
                    </div>
                    <div class="flex_item_right">
                    <div id="head">
                        @if(!empty($std['current_transaction']))

                        <h6>Due Date {{ @format_date($std['current_transaction']->due_date ?$std['current_transaction']->due_date :$std['current_transaction']->transaction_date ) }}
                        </h6>
                        @else
                        <h6>Due Date {{ @format_date($std['other_transaction'][0]->due_date ?$std['other_transaction'][0]->due_date :$std['other_transaction'][0]->transaction_date ) }}
                        </h6>

                        @endif
                    </div>
                    <table>  
                      @php
                        $other_paid=0;
                        $other_final_total=0;
                        $transport_paid=0;
                        $transport_final_total=0;
                      @endphp 
                         <tr>
                    <th>Other Particulars</th>
                    <th>Amount</th>
                      </tr>   
                       @forelse($std['other_transaction'] as $other)
                       @php
                         $other_final_total+=$other->final_total;
                          foreach($other->payment_lines as $line){
                            $other_paid += $line->amount;
                        }
                       @endphp
                        @foreach ($other->fee_lines as $feeHead)
                       <tr>
                        <th>{{ $feeHead->feeHead->description }}</th>
                        <td>{{ number_format($feeHead->amount, 0) }}</td>

                            </tr>
                    @endforeach
                        
                       @empty
                    @endforelse
                       @forelse($std['transport_transaction'] as $transport)
                       @php
                         $transport_final_total+=$transport->final_total;
                          foreach($transport->payment_lines as $line){
                            $transport_paid += $line->amount;
                        }
                       @endphp
                        @foreach ($transport->fee_lines as $feeHead)
                       <tr>
                        <th>{{ $feeHead->feeHead->description }}</th>
                        <td>{{ number_format($feeHead->amount, 0) }}</td>

                            </tr>
                    @endforeach
                        
                       @empty
                    @endforelse
                     <tr>
                    <th>Other Final Total</th>
                    <td>{{ number_format($other_final_total+$transport_final_total, 0) }}</td>
                      </tr>
                      <tr>
                    <th>Other Paid Amount</th>

                    <td>{{ number_format($other_paid+$transport_paid, 0) }}</td>
                      </tr>
                                         <tr>
                    <th>Other Previous Arrears</th>

                    <td>{{ number_format(($std['total_other_fee_due']+$std['total_transport_fee_due'])-($other_final_total - $other_paid) -($transport_final_total-$transport_paid), 0) }}</td>
                      </tr>
                                         <tr>
                    <th>Other Total Arrears</th>

                    <td>{{ number_format($std['total_other_fee_due']+$std['total_transport_fee_due'], 0) }}</td>
                      </tr>
                   
                    </table>
                    </div>
                    </div>
                    <div>
                    <table>
                    <tr>
                    <th>Total</th>

                    <td>{{ number_format($final_total+$other_final_total+$transport_final_total, 0) }}</td>
                    <th>Total Arrears</th>

                    <td>{{ number_format($std['total_fee_due']+$std['total_other_fee_due']+$std['total_transport_fee_due'], 0) }}</td>
                      </tr>
                   
                    </table>
                    *Rs. 1500 as Others are charged "only once" at start of the year and spent back on exam papers outsourcing, preparation of work sheets, papers for worksheets, home assignments, sports/labs consumables. During rest of the year, no other/extra amount under any other head is charged except Monthly Fee.

                    </div>
                </div>
               
            </div>
@endforeach

</body>

</html>
