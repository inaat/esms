<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.roll_no_slip')</title>
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
            font-family: sans serif;

        }


        @page {

            size: A4 landscape;
            -webkit-print-color-adjust: exact;
            margin: 15px !important;
            padding: 10px !important;
            
            width: 100%;
            height: 100%;
        }


        h3 {
            text-align: center;
        }


        body {
            background-color: #fff font-family: Calibri, Myriad;
             
        }

        #main {
            width: 100%;
            padding: 20px;
            margin: auto;
        }

        .timecard {
            margin: auto;
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
            /*for older IE*/
            border-style: hidden;
           
            font-weight: bold;


        }

        .timecard caption {
            background-color: #f79646;
            color: #fff;
            font-size: x-large;
            font-weight: bold;
            letter-spacing: .3em;
        }

        .timecard thead th {
            padding: 8px;
            background-color: #fde9d9;
            font-size: large;
        }





        .timecard th,
        .timecard td {
            padding: 0px;
            border-width: 1px;
            border-style: solid;
            border-color: #f79646 #ccc;
            color: #000;
        }

        .timecard td {
            text-align: center;
        }

        .timecard tbody th {
            text-align: left;
            font-weight: normal;
        }



        .timecard tr.even {
            background-color: #fde9d9;
        }
    }

</style>
</head>

<body style="color:#000;background-color:#ffff;">
 
    <div id="head">
         <img src="{{ url('/uploads/business_logos/'.session()->get("system_details.page_header_logo")) }}" width="100%"   style="height:100px">


        <h3><b>Exam Date Sheet<b></h3>
        <hr>
    </div>
<div class="table-responsive">
    <table class="mb-0 table timecard" width="100%">
        
            <thead>

            <tr style="background: #ccc">
                <td>Class
                </td>
                @foreach ($details as $key => $sub)
                    <td> {{ $key }}
                    </td>
                @endforeach
            </tr>
        </thead>
        <tbody>
      
            @foreach ($classes as $cl)
                <tr class="@if($loop->iteration%2==0) even @else odd @endif">

                    <td> {{ $cl->title }}
                    </td>
                    @foreach ($details as $key => $sub)
                    @foreach ($sub as $subject)
                        @if ($cl->id == $subject->class_id)
                            <td> {{$subject->subject->name }}
                                <br>
                                {{$subject->type}}
                            </td>
                        @endif
                    @endforeach
                    @endforeach
                <tr>
            @endforeach
			
                <tfoot>
                    <tr>
                        <td colspan="{{ $details->count()+1 }}">
                            <p style="float:right; margin-top:10px;">@lang('english.controller_of_examination')<br>Mr Ihsan Ullah Khan
</p>
                        </td>
                    <tr>
                </tfoot>
        </tbody>


    </table>
</div>
</body>

</html>