<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.application_for_admission')</title>
        <link rel="icon" href="{{ url('/uploads/business_logos/'.session()->get('system_details.org_favicon', ''))}}" type="image/png" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap');

        @media print {
            @page {
                size: A4;
                /* DIN A4 standard, Europe */
                margin: 10px;
                padding: 0;

            }
/* Hide scrollbar for Chrome, Safari and Opera */
.page::-webkit-scrollbar {
  display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */

            .page {

                margin: 0px;
                -webkit-box-shadow: none;
                -moz-box-shadow: none;
                box-shadow: none;
                  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;

            }

            .page[size="A4"] {

                margin: 0px;
                -webkit-box-shadow: none;
                -moz-box-shadow: none;
                box-shadow: none;

            }
  .logo {
            margin-top: 10px;
            margin-left: 10px;
            margin-right: 10px;
            
            border-bottom: 1px solid black;

        }
            .print_data {
                display: none;
            }
        }

        * {
            margin: 0;
            padding: 0;
        }


        body {
            margin: 0;
            padding: 0;

            width: 100%;
            background-color: #fff;

            font-family: 'Roboto Condensed', sans-serif;

        }

        h2,
        h4,
        p {
            margin: 0;


        }

        button {
            background-color: #4CAF50;
            /* Green */
            border: none;
            color: white;
            margin:20px;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
        }

        .page {
            background: #ffffff;
            display: block;
            margin: 3rem auto 3rem auto;
            position: relative;
            box-shadow: 0 0 0.5cm rgba(0, 0, 0.1);

        }

        .page[size="A4"] {
            width: 21cm;
            height: 29.7cm;
            overflow-x: hidden;
        }

        .logo {
            margin-top: 10px;
            margin-left: 10px;
            margin-right: 10px;
            
            border-bottom: 1px solid black;

        }

        /* border: solid black 1px; */
        /* }.content{
            margin-top: 1px;
            padding: 0;
             margin-left: 10px;
             margin-right: 10px;


        }
        .student-info{
            margin-top: 7px;
            border: 1px solid  black;

        }
        .row{
            display: flex;

        }
        .input-data{

            width: 100%;
  height: 40px;
      border-bottom: 1px solid  black;
      border-left: 1px solid  black;


        }
        .inside{
            font-size:12px;
            font-weight: 900;

        } */
        #head {
            width: 30%;
            /* 70% of the parent*/
            margin: 1px auto;
            background: {{  config('constants.head_bg_color') }};
            text-align: center;
            color: white;
            padding: 3px;
            border-radius: 5px;
        }

        .photo {
            width: 30%;
            /* 70% of the parent*/
            margin: 1px auto;
            border: 1px solid {{  config('constants.head_bg_color') }};
            ;
            /* background:rgb(4,101,49); */
            text-align: center;
            /* color: white; */
            padding: 3px;
            border-radius: 5px;
            width: 2in;
            height: 2in;
            text-align: center;
            position: absolute;
            left: 73%;
        }

        .text {
            width: 50%;
            /* 70% of the parent*/
            margin: 1px auto;
            /* background:rgb(4,101,49); */
            text-align: center;
            /* color: white; */
            padding: 3px;
            border-radius: 5px;
            margin-top: 50px;
        }

        .form-head {
            display: flex;
        }

        .adm-no {
            position: absolute;
            left: 70%;
            top: 135px;
        }

        .form-no {
            position: absolute;
            left: 2%;
            top: 135px;
        }

        .session-no {
            position: absolute;
            left: 40%;
            top: 155px;
        }

        #student-header {
            width: 70%;
            /* 70% of the parent*/
            margin-top: 30px;
            margin-left: 12px;
            background: {{  config('constants.head_bg_color') }};
            text-align: center;
            color: white;
            padding: 3px;
            border-radius: 5px;
        }

        .student-info {
            width: 70%;

            margin: 10px;

        }

        .check {
            border: 1px solid black;
            width: 20px;
            height: 20px;
        }

        .row {
            display: flex;
            padding: 10px;

        }

        .underline {
            flex-grow: 1;
            border-bottom: 1px solid black;
            margin-left: 5px;
            text-align: center;
        }

        .mg-left {
            margin-left: 10px;
        }

        .student-info-full {
            margin: 10px;

        }

        .student-full-header {
            margin: 10px;
            background: {{  config('constants.head_bg_color') }};
            text-align: center;
            color: white;
            padding: 3px;
            border-radius: 5px;
            text-transform: uppercase;

        }

    </style>
</head>

<body>
    <button class="print_data" onclick="printData()">Print me</button>
    <div class="page print_area" size="A4" id="page">
        <div class="logo">
        
                   <img src="https://explainerkhan.com/uploads/business_logos/1692769444_a01.jpg" width="100%"  style="height:130px;  width:100%;">
        </div>
          
        <div id="head">
            <h4> @lang('english.application_for_admission')</h4>
        </div>
        <div class="form-head">
            <div class="form-no">@lang('english.form_no'):<span class="underline">{{ $student->id }}</span></div>
            <!-- <div class="session-no">@lang('english.session'):(2021-2022)</div> -->
            <div class="adm-no">@lang('english.admission_no'):<span class="underline">{{ $student->admission_no }}</span></div>
        </div>
        <div class="photo">

            @if (file_exists(public_path('uploads/student_image/' . $student->student_image)))
            <img style="width:100%" height="192px" src="{{ url('uploads/student_image/' . $student->student_image) }}" />
            @else
            <img style="width:100%" height="192px" src="{{ url('uploads/student_image/default.png') }}" />
            @endif


        </div>
        <div id="student-header">
            <h6> @lang('english.student_information')</h6>
        </div>
        <div class="student-info">
            <div class='row'>
                <div class='label'>@lang('english.name') <small>(@lang('english.in_capital_letters_as_per_certificate'))</small>:</div>
                <div class='underline'>{{ strtoupper($student->first_name . ' '. $student->last_name) }}</div>
            </div>
            <div class='row'>
                <div class='label'>@lang('english.father_name') <small>(@lang('english.in_capital_letters_as_per_certificate'))</small></div>
                <div class='underline'>{{ strtoupper($student->father_name) }}</div>
            </div>
            <div class='row'>
                <div class='label'>@lang('english.date_of_birth')<small>({{ session('system_details.date_format', config('constants.default_date_format')) }})</small>:</small></div><span class="underline">{{ @format_date($student->birth_date) }}</span>
                <!-- <div class='underline'></div> -->
                <div class='label mg-left'>@lang('english.gender'):</small></div>
                <div class="check mg-left">@if($student->gender=='male')<span style="padding: 1px">&#10004;<span>@endif</div>
                <div class='mg-left'>@lang('english.male')</div>
                <div class="check mg-left">@if($student->gender=='female')<span style="padding: 1px">&#10004;<span>@endif</div>
                <div class='mg-left'>@lang('english.female')</div>
            </div>
            <div class='row'>
                <!-- <div class='label'>Date of Admission</div>
                <div class='underline'></div> -->
                <div class='label'>@lang('english.class') @lang('english.of') @lang('english.admission'):</div>
                <div class='underline'>{{ strtoupper($student->admission_class->title) }}</div>
                <div class='label mg-left'>@lang('english.cnic_no') #:</div>
                <div class='underline'>{{ strtoupper($student->cnic_no) }}</div>
            </div>
        </div>
        <div class="student-info-full">
            <div class='row'>
                <div class='label'>@lang('english.place_of_birth'):</div>
                <div class='underline'>{{ strtoupper($student->BirthPlace) }}</div>
                <div class='label'>@lang('english.nationality'):</div>
                <div class='underline'>{{ strtoupper($student->nationality) }}</div>
                <div class='label mg-left'>@lang('english.religion'):</div>
                <div class='underline'>{{ strtoupper($student->religion) }}</div>
                <div class='label mg-left'>@lang('english.blood_group'):</div>
                <div class='underline'>{{ strtoupper($student->blood_group) }}</div>
            </div>
            <div class='row'>
                <div class='label'>@lang('english.father_occupation'):</div>
                <div class='underline'>{{ strtoupper($student->father_occupation) }}</div>
                <div class='label'>@lang('english.father_cnic_no')</div>
                <div class='underline'>{{ strtoupper($student->father_cnic_no) }}</div>
                <div class='label mg-left'>@lang('english.transport'):</small></div>
                <div class="check mg-left">@if($student->is_transport==1)<span style="padding: 1px">&#10004;<span>@endif</div>
                <div class='mg-left'>@lang('english.yes')</div>
                <div class="check mg-left">@if($student->is_transport==0)<span style="padding: 1px">&#10004;<span>@endif</div>
                <div class='mg-left'>@lang('english.no')</div>
            </div>
            <div class='row'>
                <div class='label'>@lang('english.address'):</div>
                <div class='underline'>{{ strtoupper($student->std_permanent_address) }}</div>
            </div>
            <div class='row'>
                <div class='label'>@lang('english.city'):</div>
                <div class='underline'>@if(!empty($student->city)){{ strtoupper($student->city->name) }}@endif</div>
                <div class='label  mg-left'>@lang('english.tehsil'):</div>
                <div class='underline'></div>
                <div class='label mg-left'>@lang('english.district'):</div>
                <div class='underline'>@if(!empty($student->city)){{ strtoupper($student->district->name) }}@endif</div>
            </div>
            <div class='row'>
                <div class='label'>@lang('english.mobile_no'):</div>
                <div class='underline'>{{ $student->mobile_no }}</div>
                <div class='label  mg-left'>@lang('english.guardian') @lang('english.mobile_no'):</div>
                <div class='underline'>{{ $student->father_phone }}</div>
            </div>
            <div class='row'>
                <div class='label'>@lang('english.email'):</div>
                <div class='underline'>{{ $student->email }}</div>

            </div>
            <div class="student-full-header">
                <h6>@lang('english.parent')/@lang('english.guardian') @lang('english.information')</h6>
            </div>

            <div class='row'>
                <div class='label'>@lang('english.parent')/@lang('english.guardian'):</div>
                <div class='underline'>@if(!empty($student->guardian)){{ strtoupper($student->guardian->student_guardian->guardian_name) }}@endif</div>
                <div class='label  mg-left'>@lang('english.relationship'):</div>
                <div class='underline'>@if(!empty($student->guardian)){{ strtoupper($student->guardian->student_guardian->guardian_relation) }}@endif</div>
                <div class='label mg-left'>@lang('english.guardian') @lang('english.cnic_no') #:</div>
                <div class='underline'>@if(!empty($student->guardian)){{ strtoupper($student->guardian->student_guardian->guardian_cnic) }}@endif</div>
            </div>
            <div class="student-full-header">
                <h6>@lang('english.academic') @lang('english.information')</h6>
            </div>

            <div class='row'>
                <div class='label'>@lang('english.previous_school_name'):</div>
                <div class='underline'>{{ strtoupper($student->previous_school_name) }}</div>
                <div class='label  mg-left'>Last @lang('english.grade'):</div>
                <div class='underline'>{{ strtoupper($student->last_grade) }}</div>
            </div>
            @if(!empty($siblings[0]->students))
            <div class="student-full-header">
                <h6>@lang('english.sibling') @lang('english.information')</h6>
            </div>
            @foreach ($siblings as $sibling)
            <div class='row'>
                <div class='label'>@lang('english.name'):</div>
                <div class='underline'>{{ strtoupper($sibling->students->first_name . ' '. $sibling->students->last_name) }}</div>
                <div class='label  mg-left'>@lang('english.roll_no') #:</div>
                <div class='underline'>{{ strtoupper($sibling->students->roll_no) }}</div>
                <div class='label mg-left'>@lang('english.curent_class'):</div>
                <div class='underline'>{{ $sibling->students->current_class->title .' '.$sibling->students->current_class_section->section_name }}</div>
            </div>
            @endforeach
            @endif

            <div class="student-full-header">
                <h6 style="  text-transform: uppercase;">@lang('english.how_did_you_come_to_know_about') {{ session('system_details.org_name')}}</h6>
            </div>
            <div class='row'>
                <div class='label mg-left'>@lang('english.please_check_your') <input type="checkbox" checked="checked" class="myinput" />
                    @lang('english.source_of_information_about') {{ session('system_details.org_name')}}.</div>
            </div>
            <div class='row'>
                <div class="check mg-left"></div>

                <div class='label mg-left'> {{ session('system_details.org_name')}} @lang('english.prospectus') / @lang('english.brochures')</div>
                <div class="check mg-left"></div>

                <div class='label mg-left'> {{ session('system_details.org_name')}} @lang('english.website')</div>
                <div class="check mg-left"></div>

                <div class='label mg-left'> @lang('english.friends') / @lang('english.relatives')</div>
                <div class="check mg-left"></div>

                <div class='label mg-left'>@lang('english.others')</div>

            </div>
            <div style="border-bottom:1px solid #000 ;border-style: dashed !important;  "></div>
            <div class="student-full-header">
                <h6>@lang('english.for_office_use_only')</h6>
            </div>

            <div class='row'>
                <div class='label'>@lang('english.admission') #:</div>
                <div class='underline'>{{ $student->admission_no }}</div>
                <div class='label  mg-left'>@lang('english.admission_date')</div>
                <div class='underline'>{{ @format_date($student->admission_date) }}</div>
                <div class='label  mg-left'>@lang('english.roll_no') #:</div>
                <div class='underline'>{{ strtoupper($student->roll_no) }}</div>
                <div class='label  mg-left'>@lang('english.session') #:</div>
                <div class='underline'>{{ $student->adm_session->title }}</div>
            </div>
            <div class='row'>
                <div class='label'>@lang('english.remark'):</div>
                <div class='underline'>{{ $student->remark }}</div>
            </div>

        </div>
  <div class='row'>
                <div class='label'>@lang('english.student') @lang('english.signature'):</div>
                <div class='underline'></div>
                <div class='label  mg-left'>@lang('english.father') / @lang('english.guardian') @lang('english.signature'):</div>
                <div class='underline'></div>
                <div class='label  mg-left'>@lang('english.principal') @lang('english.signature'):</div>
                <div class='underline'></div>
            </div>    
    </div>

    <script type="text/javascript">
        function printData() {


            window.print();
        }
window.print();
    </script>
</body>

</html>

