<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.application_for_admission')</title>
<style>
    * {
        margin: 0px;
        padding: px;
    }


    body {
        margin: 0;
        padding: 0;

        width: 100%;
        background-color: rgba(204, 204, 204);

        font-family: 'Roboto Condensed', sans-serif;

    }

    h2,
    h4,
    p {
        margin: 0;


    }

    #head {
        width: 30%;
        /* 70% of the parent*/
        background: {{  config('constants.head_bg_color') }};
        text-align: center;
        color: white;
        padding: 3px;
        margin: 1px auto;
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
        top: 50px;
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
        left: 71%;
        top: 12px;
    }

    .form-no {
        position: absolute;
        left: 2%;
        top: 8px;
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
        display: -webkit-box;
        display: -webkit-flex;
        display: flex;
        padding: 10px;

    }

    .underline {
        -webkit-box-flex: 1;
        -webkit-flex: 1;
        flex: 1;

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

    }



    table {
        width: 100%;
        border-right-color: #ffffff;
        font-size: 18px;
        /* /* border: 1px solid #343a40; */
        border-collapse: collapse;
    }

    th,
    td {
        /* border: 1px solid #343a40; */
        padding: 16px 24px;
        text-align: left;
    }

    th {
        background-color: #087f5b;
        color: #fff;
        width: 25%;
    }

    tbody tr:nth-child(odd) {
        background-color: #f8f9fa;
    }


    tbody tr:nth-child(even) {
        background-color: #e9ecef;
    }
    span.textunderline {
        padding-left:2px; 
                text-decoration: underline;
            }
</style>
</head>
<body >
    <div>
    <div class="space" style="margin-top:5px; width:100%;  height:5px;">
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
        @php
    $path = public_path('uploads/student_image/'.$student->student_image);
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    @endphp
        {{-- <div class="text">
            Attach 2 Passport Size Photographs
        </div> --}}
        <img src="{{ $base64 }}" style="width:100%" height="192px">
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
            <div class='label'>@lang('english.class')of Admission:</div>
            <div class='underline'>{{ strtoupper($student->admission_class->title) }}</div>
            <div class='label mg-left'>CNIC #:</div>
            <div class='underline'>{{ strtoupper($student->cnic_no) }}</div>
        </div>
    </div>
    <div class="student-info-full">
        <div class='row'>
            <div class='label'>Place of Birth:</div>
            <div class='underline'>{{ strtoupper($student->BirthPlace) }}</div>
            <div class='label'>Nationality:</div>
            <div class='underline'>{{ strtoupper($student->nationality) }}</div>
            <div class='label mg-left'>Religion:</div>
            <div class='underline'>{{ strtoupper($student->religion) }}</div>
            <div class='label mg-left'>Blood Group:</div>
            <div class='underline'>{{ strtoupper($student->blood_group) }}</div>
        </div>
        <div class='row'>
            <div class='label'>Father's Ocupation:</div>
            <div class='underline'>{{ strtoupper($student->father_occupation) }}</div>
            <div class='label'>Father's CNIC #:</div>
            <div class='underline'>{{ strtoupper($student->father_cnic_no) }}</div>
            <div class='label mg-left'>Transport:</small></div>
            <div class="check mg-left">@if($student->is_transport==1)<span style="padding: 1px">&#10004;<span>@endif</div>
            <div class='mg-left'>Yes</div>
            <div class="check mg-left">@if($student->is_transport==0)<span style="padding: 1px">&#10004;<span>@endif</div>
            <div class='mg-left'>No</div>
        </div>
        <div class='row'>
            <div class='label'>Address:</div>
            <div class='underline'>{{ strtoupper($student->std_permanent_address) }}</div>
        </div>
        <div class='row'>
            <div class='label'>City/Town:</div>
            <div class='underline'>@if(!empty($student->city)){{ strtoupper($student->city->name) }}@endif</div>
            <div class='label  mg-left'>Tehsil:</div>
            <div class='underline'></div>
            <div class='label mg-left'>District:</div>
            <div class='underline'>@if(!empty($student->city)){{ strtoupper($student->district->name) }}@endif</div>
        </div>
        <div class='row'>
            <div class='label'>Contact Numbers (Cell/PTCL):</div>
            <div class='underline'>{{ $student->mobile_no }}</div>
            <div class='label  mg-left'>Guardian’s Contact Number:</div>
            <div class='underline'>{{ $student->father_phone }}</div>
        </div>
        <div class='row'>
            <div class='label'>Email:</div>
            <div class='underline'>{{ $student->email }}</div>

        </div>
        <div class="student-full-header">
            <h6>PARENT/GUARDIAN INFORMATION</h6>
        </div>

        <div class='row'>
            <div class='label'>Parent/Guardian:</div>
            <div class='underline'>@if(!empty($student->guardian)){{ strtoupper($student->guardian->student_guardian->guardian_name) }}@endif</div>
            <div class='label  mg-left'>Relationship:</div>
            <div class='underline'>@if(!empty($student->guardian)){{ strtoupper($student->guardian->student_guardian->guardian_relation) }}@endif</div>
            <div class='label mg-left'>Guardian CNIC #:</div>
            <div class='underline'>@if(!empty($student->guardian)){{ strtoupper($student->guardian->student_guardian->guardian_cnic) }}@endif</div>
        </div>
        <div class="student-full-header">
            <h6>ACADEMIC INFORMATION</h6>
        </div>

        <div class='row'>
            <div class='label'>Previous School Name:</div>
            <div class='underline'>{{ strtoupper($student->previous_school_name) }}</div>
            <div class='label  mg-left'>Last @lang('english.grade'):</div>
            <div class='underline'>{{ strtoupper($student->last_grade) }}</div>
        </div>
        @if(!empty($siblings[0]->students))
        <div class="student-full-header">
            <h6>SBLING INFORMATION</h6>
        </div>
        @foreach ($siblings as $sibling)
             <div class='row'>
            <div class='label'>@lang('english.name'):</div>
            <div class='underline'>{{ strtoupper($sibling->students->first_name . ' '. $sibling->students->last_name) }}</div>
            <div class='label  mg-left'>Roll #:</div>
            <div class='underline'>{{ strtoupper($sibling->students->roll_no) }}</div>
            <div class='label mg-left'>Current Class:</div>
            <div class='underline'>{{ $sibling->students->current_class->title .' '.$sibling->students->current_class_section->section_name }}</div>
        </div>
        @endforeach
        @endif
       
          <div class="student-full-header">
            <h6 style="  text-transform: uppercase;">How did you come to know about Swat Collegiate School</h6>
        </div>
        <div class='row'>
            <div class='label mg-left'>Please check your <input type="checkbox" checked="checked" class="myinput" />
                source of information about Swat Collegiate School.</div>
        </div>
        <div class='row' >
            <div class="check mg-left"></div>

            <div class='label mg-left'> Swat Collegiate Prospectus / Brochures</div>
            <div class="check mg-left"></div>

            <div class='label mg-left'> Swat Collegiate Website</div>
            <div class="check mg-left"></div>

            <div class='label mg-left'> Friends / Relatives</div>
            <div class="check mg-left"></div>

            <div class='label mg-left'>Others</div>
            
        </div>
        <div style="border-bottom:1px solid #000 ;border-style: dashed;  "></div>
        <div class="student-full-header" >
            <h6>FOR OFFICE USE ONLY</h6>
        </div>

        <div class='row'>
            <div class='label'>Admission #:</div>
            <div class='underline'>{{ $student->admission_no }}</div>
            <div class='label  mg-left'>Admission Date</div>
            <div class='underline'>{{ @format_date($student->admission_date) }}</div>
            <div class='label  mg-left'>Roll #:</div>
            <div class='underline'>{{ strtoupper($student->roll_no) }}</div>
            <div class='label  mg-left'>Session #:</div>
            <div class='underline'>{{ $student->adm_session->title }}</div>
        </div>
        <div class='row'>
            <div class='label'>Remark:</div>
            <div class='underline'>{{ $student->remark }}</div>
        </div>
      
    </div>

</body>
</html>
