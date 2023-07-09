<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.employee_identity_card')</title>
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

        * {
            margin: 0px;
            padding: 00px;
            box-sizing: content-box;
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

            height: 66mm;
            width: 106mm;
            position: relative;
            padding: 4px;
            border: 2px solid #08622E;
            border-radius: 10px;
            background:#fff;
            {{-- background: rgb(20, 125, 69);
            background: linear-gradient(90deg, rgba(20, 125, 69, 0.9920343137254902) 29%, rgba(117, 208, 154, 1) 100%); --}}
            page-break-inside: avoid;


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
            page-break-inside: avoid;

            /* background:rgb(215, 230, 219) */
        }


        .name {
            margin-top: 20px;
            padding-left: 20px;
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

    @foreach($employees as $key =>$employee)


    <div class="container">
        <div class="padding">
            <div class="front">
                <div class="header">
                    @php
                    $path = public_path('uploads/business_logos/'.session()->get('system_details.id_card_logo'));
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    @endphp
                    <img src="{{ $base64 }}" style="width:90%; height:80px">
                </div>
                <div style="background-color: #08622E;   border-radius: 4px;">
                    <h3 style="color: white ; text-align: center">@lang('english.employee_card')</h3>
                </div>
                <div class="mid-area">
                    <div class="name">
                        <p><strong>{{ ucwords(strtolower($employee->first_name . ' ' . $employee->last_name)) }}<br>
                                <span style="text-align: center; margin-left: 20px;">
                                    <br>
                                    <strong>@lang('english.designation'):</strong> <strong>{{ ucwords(strtolower($employee->designations->designation))}}</strong>

                        </p>
                        <img style="margin-left:140px;width:60px;height:40px;"  src="data:image/png;base64,{{DNS2D::getBarcodePNG(ucwords(strtolower($employee->first_name . ' ' . $employee->last_name)). ucwords(strtolower($employee->designations->designation)), 'QRCODE')}}">

                    </div>
                    <div class="std-img">
                       @php
                      if(file_exists(public_path('uploads/employee_image/'.$employee->employee_image))){

                        $path1 = public_path('uploads/employee_image/'.$employee->employee_image);
                        $type1 = pathinfo($path, PATHINFO_EXTENSION);
                        $data1 = file_get_contents($path1);
                        $employee_image = 'data:image/' . $type1 . ';base64,' . base64_encode($data1);
                        }else{
                        $path1 = public_path('uploads/employee_image/default.jpg');
                        $type1 = pathinfo($path, PATHINFO_EXTENSION);
                        $data1 = file_get_contents($path1);
                        $employee_image = 'data:image/' . $type1 . ';base64,' . base64_encode($data1);
                        }
                        @endphp
                       
                        <img src="{{ $employee_image }}" alt="" style="width:100px;height:100px;border-radius: 5%;">


                         </div>

                    <div class="vertical-text">

                        <h3 style="padding:1px;color: white ; text-align: center">{{ ucwords($employee->employeeID) }}</h3>
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
                    <div class='label'>@lang('english.father_name') :</div>
                    <div class='underline mg-left'>
                        <p style="text-align: center;">{{ ucwords(strtolower($employee->father_name)) }}</p>
                    </div>
                </div>
                {{-- <div class='row'>
                    <div class='label'>Date Of Brith:</div>
                    <div class='underline mg-left'>
                        <p style="text-align: center;">{{ @format_date($employee->birth_date) }}</p>
                    </div>
                </div>
                <div class='row'>
                    <div class='label'>Joining Date</div>
                    <div class='underline mg-left'>
                        <p style="text-align: center;">{{ @format_date($employee->joining_date) }}</p>
                    </div>
                </div> --}}
                {{-- <div class='row'>
                    <div class='label'>Valid From:</div>
                    <div class='underline mg-left'>
                        <p style="text-align: center;">01/2022</p>
                    </div>
                </div>
                <div class='row'>
                    <div class='label'>Valid Through:</div>
                    <div class='underline mg-left'>
                        <p style="text-align: center;">12/2024</p>
                    </div>
                </div> --}}

                <div class='row'>
                    <div class='label'>@lang('english.address'):</div>
                    <div class='underline mg-left'>
                        <p style="text-align: center;">{{ ucwords(strtolower($employee->permanent_address)) }}</p>
                    </div>
                </div>
                <div class='row'>
                    <div class='label'>@lang('english.cell'):</div>
                    <div class='underline mg-left'>
                        <p style="text-align: center;">{{ $employee->mobile_no }}</p>
                    </div>
                </div>
                @if(!empty($employee->blood_group))
                {{-- <div class='row'>
                    <div class='label'>Blood Group:</div>
                    <div class='underline mg-left'>
                        <p style="text-align: center;">{{ $employee->blood_group }}</p>
                    </div>
                </div> --}}
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
    @endforeach
</body>

</html>
