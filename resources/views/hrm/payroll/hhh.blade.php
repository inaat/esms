<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.fee_card')</title>
<style>
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
            padding: 10px !important;
            width: 100%;
            height: 100%;
        }


        .flex_container {
            display: flex;
            width: 100%;
            
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

<body style="color:#000">


    <div style=" border:1px solid black; margin:5px; page-break-after: always;">
        <div class="flex_container">
            <div class="flex_item_left">
                <img src="{{ url('/uploads/invoice_logo/logo.jpeg') }}" width="100% ">
            </div>
            <div class="flex_item_right" style="">
                <img src="{{ url('/uploads/invoice_logo/logo.jpeg') }}" width="100%" height="100%">
            </div>
        </div>
        <div class="flex_container">
            <div class="flex_item_left ">
                <div id="head">
                    <h6>{{ $transaction->payroll_group_name }}
                    </h6>
                </div>
                <div class="info" style="border: 1px solid #000; padding:1px; ">
                    <div class="info_left">
                        <div style="border-bottom: 1px solid #000; line-height:1.8; margin-left:4px  ">
                            <strong>Pay Slip No:</strong>
                            <strong>{{ $transaction->ref_no  }}</strong>
                            <span style="margin-left:4px"><strong>@lang('english.date'):</strong>
                                <strong>{{ @format_datetime($transaction->transaction_date) }}</strong></span><br>

                            <strong>Employee ID:</strong>
                            <strong>{{ ucwords($transaction->employee->employeeID) }}</strong>
                            <span style="margin-left:4px"><strong>@lang('english.designation'): {{ $transaction->employee->designations->designation }}
                                </strong></span><br>
                            <strong>@lang('english.employee_name')</strong>
                            <strong>{{ ucwords($transaction->employee->first_name . ' ' . $transaction->employee->last_name) }}</strong>
                            <span style="margin-left:4px"><strong>@lang('english.father_name'): {{ $transaction->employee->father_name}}
                                </strong></span><br>
                            <strong>Mobile:</strong>
                            <strong>{{ ucwords($transaction->employee->mobile_no) }}</strong>
                            <span style="margin-left:4px"><strong>@lang('english.address'): {{ $transaction->employee->permanent_address}}
                                </strong></span>

                        </div>

                    </div>
                    <div class="info_right">
                        <img width="100" height="100" src="{{ url('uploads/employee_image/'.$transaction->employee->employee_image) }}" />

                    </div>
                </div>

                555555



            </div>
            <div class="flex_item_right">




            </div>
        </div>

    </div>

    </div>

</body>

</html>

