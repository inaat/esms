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
                margin: 10px;
                padding: 0;

            }

        .print_data {
            display: none;
        }
            .container {
                page-break-after: avoid;

                zoom: 85%;
                margin-bottom: 50px;

            }
        }

        * {
            margin: 0px;
            padding: 00px;
            box-sizing: content-box;
            page-break-inside: avoid;

        }

        
    button {
        background-color: #4CAF50;
        /* Green */
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
    }.padding {
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



        }



        .back {
            margin-left: 5px;

            height: 66mm;
            width: 106mm;
            position: relative;
            padding: 4px;
            border: 2px solid #08622E;
            border-radius: 10px;
            background: #fff;


        }

        .front {
            margin-left: 5px;

            height: 66mm;
            width: 106mm;
            position: relative;
            border-radius: 10px;
            background: #fff;
            padding: 4px;
            border: 2px solid #08622E;


            /* background:rgb(215, 230, 219) */
        }


        .name {
            margin-top: 0px;
            padding-left: 0px;
        }

        .std-img {

            position: absolute;
            top: 45%;
            left: 65%;
        }

        .row {
            display: -webkit-box;
            display: -webkit-flex;
            display: flex;
            padding: 8px;
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

    </style>
</head>

<body>
    <button class="print_data" onclick="printData()">Print me</button>

    @foreach($students as $key =>$student)


    <div class="container">
        <div class="padding">
            <div class="front">
                <div class="header">
                    @php
                    $path = public_path('uploads/business_logos/'.session()->get('system_details.id_card_logo') );
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    @endphp
                    <img src="{{ $base64 }}" style="width:90%;height:80px">
                </div>
                <div style="background-color: #08622E;   border-radius: 4px;">
                    <h3 style="color: white ; text-align: center">@lang('english.student_card')</h3>
                </div>
                <div class="mid-area">
                    <div class="name">
                        <p><strong>{{ ucwords(strtolower($student->first_name . ' ' . $student->last_name)) }}<br>
                                <strong>Class:</strong> <strong>{{ ucwords(strtolower($student->current_class->title))}}</strong>
                                <strong>{{ ucwords(strtolower($student->current_class_section->section_name))}}</strong>

                        </p>
                        <img style="margin-left:50px;width:150px;height:80px;" src="data:image/png;base64,{{DNS2D::getBarcodePNG($student->roll_no, 'QRCODE')}}">

                    </div>
                    <div class="std-img">

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
                        <img src="{{ $student_image }}" alt="" style="width:100px;height:100px;border-radius: 5%;">


                    </div>

                    <div class="vertical-text">

                        <h3 style="padding:1px;color: white ; text-align: center">{{ ucwords($student->roll_no) }}</h3>
                    </div>

                    <div style="background-color: #08622E; margin-top: 0px;   border-radius: 4px;">

                        <h3 style="color: white ; text-align: center">{{ session()->get('system_details.tag_line') }}</h3>
                    </div>
                </div>

            </div>

        </div>
        <div class="padding">
            <div class="back">
                <div class='row'>
                    <div class='label'>@lang('english.father_name'):</div>
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
                @if(!empty($student->blood_group))
                <div class='row'>
                    <div class='label'>@lang('english.blood_group'):</div>
                    <div class='underline mg-left'>
                        <p style="text-align: center;">{{ $student->blood_group }}</p>
                    </div>
                </div>
                @else
                <div style="margin-top:0px"></div>
                @endif
                <div class="bottom">
                    <p style="">@lang('english.if_found')<br>
                        <span style="color: #08622E;font-size:10px;"><strong>{{ session()->get('system_details.org_name')}} {{ session()->get('system_details.org_address') }}<strong></span></p>
                </div>
                <div class='row' style="font-size: 8px; color:#000">
                    <div class='underline '>
                        <p style="text-align: center;"><strong>{{ session()->get('system_details.org_contact_number') }}</strong></p>
                    </div>
                    <div class='underline '>
                        <p style="text-align: center; "><strong>{{ session()->get('system_details.org_email') }}</strong></p>
                    </div>
                    <div class='underline '>
                        <p style="text-align: center;"><strong>{{ session()->get('system_details.org_website') }}</strong></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    {{-- @if($key==3 || $key==7 || $key==11 || $key==15 || $key==19)
    <p style="page-break-after: always;"></p>

    @endif --}}
    @endforeach
        <script>
        function printData() {
          

            window.print();
        }

       

    </script>
</body>

</html>

