<!DOCTYPE html>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 
<title>Students  Particulars</title>
<style>
@page {
    margin:15px;
  header: page-header;
  footer: page-footer;
        padding: 0;
        margin-top:130px;
        margin-bottom:10px;

}
    * {
        margin: 0 ;
        padding: 0;
    }


    body {
        margin: 0;
        padding: 0px;
        width: 100%;
font-family: 'lato-black';  
  font-size: 10px;

    }
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
             font-size: 7px;

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
    font-size: 7.5px;
        word-wrap: break-word;
        font-weight: bold;
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

  

.adm-no{
float:right;
}
</style>
</head>

<body>
     @php
        $total_tuition_fee=0;
        $total_transport_fee=0;
        $total_reamining_balance=0;
        $total_paid=0;
    @endphp
    <htmlpageheader name="page-header">
  @include('common.logo')
</htmlpageheader>
<htmlpagefooter name="page-footer">
<footer style=" text-align: center;">
        <h6>Develope By Explainer Technology :Contact Us:0342-8927305  &nbsp; &nbsp; &nbsp; <b>Printed {{ @format_date('now') }} &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;Page: &nbsp; {PAGENO}<b></h6>
    </footer> 
</htmlpagefooter>
<div class="myfixed2" >
 <div class="form-no">@lang('english.form_no'):<span class="underline"></span></div>
        <!-- <div class="session-no">@lang('english.session'):(2021-2022)</div> -->
        <div class="adm-no">@lang('english.admission_no'):<span class="underline"></span></div>
</div>
 @foreach ($student_list as  $section)
    <div class="card"  >
        <h5 class=" head text-primary">{{ $section[0]['campus_name']}} Students Particulars of Class :<span class="mg-left">{{ $section[0]['class_name'] }}</span><span class="mg-left">{{ $section[0]['section_name']}}</span></h5>
         
   
        <div class="table-responsive" >
            <table class="table mb-0" width="100%">
                <thead class="table-light" width="100%">
                    <tr>
                        <th class="red">#</th>
                        <th>@lang('english.roll_no')</th>
                        <th>@lang('english.student_name')</th>
                        <th>@lang('english.father_name')</th>
                        <th>@lang('english.date') of Birth</th>
                        <th>Mobile No</th>
                        <th>Father CNIC</th>
                        <th>Address</th>
                        {{-- <th>@lang('english.current_class')</th> --}}
                        <th >Tuition</th>
                        <th>Transport</th>
                        <th>Paid</th>
                        <th>@lang('english.balance')</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $balance = 0;
                    $tuition = 0;
                    $transport = 0;
                    $total_students=0;
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
                        <td>{{ @num_format($student['total_due'])}}</td>
                    </tr>
                    @php
                    $total_students +=1;
                    $balance += $student['total_due'];
                    $tuition += $student['student_tuition_fee'];
                    $transport += $student['student_transport_fee'];
                    $paid += $student['paid'];

                    @endphp
                    @endforeach

                <tr>
                    <td colspan="6" class="text-right"><b>Total Students</b></td>
                    <td>{{ $total_students }}</td>
                    <td colspan="1" class="text-right"><b>Total</b></td>
                    <td>{{ @num_format( $tuition) }}</td>
                    
                    <td>{{ @num_format($transport) }}</td>
                    <td>{{ @num_format($paid) }}</td>

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
     <table class="table table-bordered" width="100%">
      
        <tbody>
       <tr>
                <th>@lang('english.total') Transport Fee</th>
                <td> {{ @num_format($total_transport_fee) }}</td>
              
            </tr>
             <tr>
                        <th>Recovery</th>
                        <td> {{ @num_format($total_paid) }}</td>
                      
                    </tr>
                     <tr>
                <th>@lang('english.total') Dues</th>
                <td> {{ $total_reamining_balance }}</td>
              
            </tr>
        </tbody>
        </table>
    
              
         
</body>    
   

</html>

