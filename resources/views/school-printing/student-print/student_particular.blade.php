<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.student_particular')</title>
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
        zoom:80%;

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
    thead { display: table-header-group }
tfoot { display: table-row-group }
tr { page-break-inside: avoid }

</style>
</head>

<body>
    @php
        $total_tuition_fee=0;
        $total_transport_fee=0;
        $total_reamining_balance=0;
        $total_paid=0;
        $total_fee_due=0;
        $total_transport_fee_due=0;
    @endphp
    @foreach ($student_list as  $section)
    <div class="card" >
        
        <div class=' '><b>@lang('english.printed') {{ @format_date('now') }} <b></div>

        <h5 class="head text-primary">{{ $section[0]['campus_name']}} @lang('english.student_particular') @lang('english.of') @lang('english.class') :<span class="mg-left">{{ $section[0]['class_name'] }}</span><span class="mg-left">{{ $section[0]['section_name']}}</span></h5>
   
   
        <div class="table-responsive" style="margin-top:20px;">
            <hr>

            <table class="table mb-0" width="100%">
                <thead class="table-light" width="100%">
                    <tr>
                        <th>#</th>
                        <th>@lang('english.roll_no')</th>
                        <th>@lang('english.student_name')</th>
                        <th>@lang('english.father_name')</th>
                        <th>@lang('english.date_of_birth')</th>
                        <th>@lang('english.mobile_no')</th>
                        <th>@lang('english.father_cnic_no')</th>
                        <th>@lang('english.address')</th>
                        {{-- <th>@lang('english.current_class')</th> --}}
                        <th >@lang('english.tuition')</th>
                        <th>@lang('english.transport')</th>
                        <th>@lang('english.paid')</th>
                        <th>@lang('english.transport_fee_due')</th>
                        <th>@lang('english.fee_due')</th>
                        <th>@lang('english.balance')</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $balance = 0;
                    $tuition = 0;
                    $transport = 0;
                    $total_students=0;
                    $transport_due=0;
                    $fee_due=0;
                    $paid=0;
                @endphp
                  @foreach ($section as  $student)
                    <tr @if($student['paid']>0)style="	background-color: #fde9d9;" @endif>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $student['roll_no'] }}</td>
                        <td>{{ ucwords($student['student_name']) }}</td>
                        <td>{{ ucwords($student['father_name']) }}</td>
                        <td>{{ @format_date($student['birth_date']) }}</td>
                        <td>{{ $student['mobile_no'] }}</td>
                        <td>{{ $student['father_cnic_no']}}</td>
                        <td>{{ $student['std_permanent_address']}}</td>
                        <td>{{ @num_format($student['student_tuition_fee']) }}</td>
                        <td>{{ @num_format($student['student_transport_fee']) }}</td>
                        <td>{{ @num_format($student['paid']) }}</td>
                        <td>{{ @num_format($student['total_fee_due']) }}</td>
                        <td>{{ @num_format($student['total_transport_fee_due']) }}</td>
                        <td>{{ @num_format($student['total_due'])}}</td>
                    </tr>
                    @php
                    $total_students +=1;
                    $balance += $student['total_due'];
                    $tuition += $student['student_tuition_fee'];
                    $transport += $student['student_transport_fee'];
                    $paid += $student['paid'];
                    $fee_due+= $student['total_fee_due'];
                    $transport_due+-$student['total_transport_fee_due'];

                    @endphp
                    @endforeach

                <tr>
                    <td colspan="6" class="text-right"><b>Total Students</b></td>
                    <td>{{ $total_students }}</td>
                    <td colspan="1" class="text-right"><b>Total</b></td>
                    <td>{{ @num_format( $tuition) }}</td>
                    
                    <td>{{ @num_format($transport) }}</td>
                    <td>{{ @num_format($paid) }}</td>
                    <td>{{ @num_format($fee_due) }}</td>
                    <td>{{ @num_format($transport_due) }}</td>
                    <td>{{ @num_format($balance) }}</td>
                    @php
                         $total_tuition_fee += $tuition;
                         $total_transport_fee += $transport;
                         $total_reamining_balance += $balance;
                         $total_paid += $paid;
                    @endphp
                </tr>

                </tbody>
            </table>
        </div>
         <p style="page-break-after: always;"></p>
   
   
    </div>
   
    @endforeach
    <table class="table mb-0" width="100%">
        <thead class="table-light" width="100%">
            <tr>
              <th>@lang('english.total') Tuition Fee</th>
              <td> {{ @num_format($total_tuition_fee) }}</td>
              
            </tr>
        </thead>
        <table>
    <table class="table mb-0" width="100%">
        <thead class="table-light" width="100%">
            <tr>
                <th>@lang('english.total') Transport Fee</th>
                <td> {{ @num_format($total_transport_fee) }}</td>
              
            </tr>
        </thead>
        <table>
            <table class="table mb-0" width="100%">
                <thead class="table-light" width="100%">
                    <tr>
                        <th>Recovery</th>
                        <td> {{ @num_format($total_paid) }}</td>
                      
                    </tr>
                </thead>
                <table>
    <table class="table mb-0" width="100%">
        <thead class="table-light" width="100%">
            <tr>
                <th>@lang('english.total') Dues</th>
                <td> {{ $total_reamining_balance }}</td>
              
            </tr>
        </thead>
        <table>
    
   
</body>
</html>

