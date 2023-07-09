<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.student_identity_card')</title>
<head>
    <style type="text/css">
        @media print {
            @page {
                size: A4;
                /* DIN A4 standard, Europe */
                margin: 0;
                padding: 0;
            }
        }

       body {
    font-family: Georgia, serif;

}

        .padding {
            padding: 10px;
        }

        .container {

  font-family: Georgia, serif;

            width: 100%;
            /* justify-content: center; */
            background-color: #e6ebe0;

            display: -webkit-box;
            /* wkhtmltopdf uses this one */
            display: flex;
            -webkit-box-pack: center;
            /* wkhtmltopdf uses this one */
            justify-content: center;
            -webkit-flex-direction: row;
            -webkit-flex-flow: wrap;
            page-break-inside: avoid;


        }



        .back {
            margin-left: 5px;

            height: 106mm;
            width: 66mm;
            position: relative;
            padding: 4px;
            border: 2px solid #08622E;
            border-radius: 10px;
            background: #fff;
            page-break-inside: avoid;


        }

        .front {
            margin-left: 5px;

            height: 106mm;
            width: 66mm;
            position: relative;
            border-radius: 10px;
            background: #fff;
            padding: 4px;
            border: 2px solid #08622E;
            page-break-inside: avoid;

            /* background:rgb(215, 230, 219) */
        }




        .row {
            display: -webkit-box;
            display: -webkit-flex;
            display: flex;
            padding: 8px;
            font-size: 16px;
            color: #08622E;

        }

        .underline {
            -webkit-box-flex: 1;
            -webkit-flex: 1;
            flex: 1;

            flex-grow: 1;
            border-bottom: 1px solid #08622E;
            margin-left: 5px;
        }

        .mg-left {
            margin-left: 10px;
        }

        .bottom {
            text-align: center;
        }

        .vertical-text {
            writing-mode: vertical-lr;
            -webkit-writing-mode: vertical-lr;
            -ms-writing-mode: tb-rl;
            background-color: #08622E;
            position: absolute;
            bottom: 0px;
            height: 230px;
            left: 92%;
            border-radius: 4px;
        }

        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }

    </style>
</head>

<body>

    @foreach($students as $key =>$student)

    <div class="container">

        <div class="padding">
            <div class="front">
                <div class="header" >
                    @php
                    $path = public_path('uploads/business_logos/'.session()->get('system_details.id_card_logo'));
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    @endphp
                    <img src="{{ $base64 }}" style="width:90%" height="100px;">
                </div>

                <hr style="background: #08622E;">
                <div class="center" style="border:2px solid #08622E;border-radius: 5%;">

                     @php
                       if(file_exists(public_path('uploads/student_image/'.$student->student_image))){
                         $path1 = public_path('uploads/student_image/'.$student->student_image);
                        $type1 = pathinfo($path, PATHINFO_EXTENSION);
                        $data1 = file_get_contents($path1);
                        $student_image = 'data:image/' . $type1 . ';base64,' . base64_encode($data1);
                        }else{
                       
                        $path1 = public_path('uploads/student_image/default.png');
                        $type1 = pathinfo($path, PATHINFO_EXTENSION);
                        $data1 = file_get_contents($path1);
                        $student_image = 'data:image/' . $type1 . ';base64,' . base64_encode($data1);
                        }
                        @endphp
                    <img class="center" src="{{ $student_image }}" alt="" style="width:120px;height:120px;border-radius: 5%;">


                </div>
               
                <p class="center" style="text-align: center; margin-top:10px;"> <strong>{{ucwords(strtolower($student->first_name . ' ' . $student->last_name)) }} </strong>

                </p>
                <p style="margin-top:2px;text-align: center;">
                    <strong>Class:{{ ucwords(strtolower($student->current_class->title))}}
                    {{ ucwords(strtolower($student->current_class_section->section_name))}}</strong>
                     <br>
                     <strong>@lang('english.roll_no'):{{ $student->roll_no }}</strong>
                      <br>
                     <strong>@lang('english.date_of_birth'):{{ @format_date($student->birth_date) }}</strong>
                     <br>
                 

                   
                </p>
            </div>

        </div>
        <div class="padding">
            <div class="back">
                <div class='row'>
                    <div class='label'>@lang('english.father_name') :</div>
                    <div class='underline mg-left'>
                        <p style="text-align: center;">{{ ucwords(strtolower($student->father_name)) }}</p>
                    </div>
                </div>
                <div class='row'>
                    <div class='label'>@lang('english.date_of_birth'):</div>
                    <div class='underline mg-left'>
                        <p style="text-align: center;">{{ @format_date($student->birth_date) }}</p>
                    </div>
                </div>

                <div class='row'>
                    <div class='label'>@lang('english.address'):</div>
                    <div class='underline mg-left'>
                        <p style="text-align: center;">{{ ucwords(strtolower($student->std_permanent_address)) }}</p>
                    </div>
                </div>
                <div class='row'>
                    <div class='label'>@lang('english.cell'):</div>
                    <div class='underline mg-left'>
                        <p style="text-align: center;">{{ $student->mobile_no }}</p>
                    </div>
                </div>
                <img class="center" style="width:150px;height:150px;"  src="data:image/png;base64,{{DNS2D::getBarcodePNG($student->roll_no , 'QRCODE')}}">

            </div>

        </div>
     </div>
    @endforeach
   
</body>

</html>

