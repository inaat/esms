  <style type="text/css">
        @media print {
            body {
                background-color: #fff;
                color: #000;
            }

            @page {

                size: A4;
                -webkit-print-color-adjust: exact;
                page-break-inside: avoid;

            }

            * {
                margin: 0px;
                padding: 0px;
                font-family: sans serif;

            }

            .main-box {
                width: 250px;
                height: 420px;
                box-shadow: 2px 4px 2px 4px rgba(70, 224, 151, 0.65);
                border-radius: 10px;
                text-align: center;
                padding: 10px 0;
                page-break-inside: avoid;
                color: #000;


            }

            .big-circle {
                width: 160px;
                height: 160px;
                border: 10px solid #46E097;
                border-radius: 50%;
                margin: auto;
                position: relative;
            }

            .small-circle {
                width: 150px;
                height: 150px;
                border: 10px solid #DAF9F6;
                border-radius: 50%;
                position: absolute;
                top: -5px;
                left: -5px;

                background-position: center;
                background-size: cover;
            }

            .heading-name {
                margin: 3px 0;
            }

            .heading-work {
                color: #000;

                font-weight: 300;
                margin: 3px 0;
            }

            p {
                padding: 0 20px;
                margin-bottom: 25px;
                color: #000;

            }


            .grid {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr 1fr;
                grid-gap: 10px;
                align-items: stretch;
                color: #000;

            }

            .grid>.main-box {
                border: 1px solid #ccc;
                box-shadow: 2px 2px 6px 0px rgba(0, 0, 0, 0.3);
            }

            .grid>.main-box img {
                max-width: 100%;
            }
        }

    </style>
<body>
    @include('common.logo')
    <h1 style="text-align: center">@lang('english.top') {{ $limit }} @lang('english.position') @lang('english.holder') ({{ $class_level_name->title }})</h1>
    <div class="grid">
        @foreach ($students as $student)

        <div class="main-box">
            <div class="big-circle">
                <div class="small-circle" style="background-image: url('{{ url('uploads/student_image/' . $student['data']->student->student_image) }}') ">

                </div>
            </div>
            <h3 class="heading-work card-title text-primary text-center">@lang('english.top') {{ $student['rank'] }}</h3>
           
            <div class="clearfix mt-2" style="border-top:1px solid black; padding:5px zoom:50% color:#000;">
                <p class="mb-0 float-start">@lang('english.name')</p>
                <p class="mb-0 float-end "><span class="me-2 ">{{ ucwords($student['data']->student->first_name . ' ' . $student['data']->student->last_name) }}</span></p>
            </div>

            <div class="clearfix" style="border-top:1px solid black; padding:5px; zoom:50% color:#000;">
                <p class="mb-0 float-start">@lang('english.f_name')</p>
                <p class="mb-0 float-end "><span class="me-2 " style=>{{ ucwords($student['data']->student->father_name) }}</span></p>
            </div>
            <div class="clearfix" style="border-top:1px solid black; padding:5px">
                <p class="mb-0 float-start">@lang('english.class')</p>
                <p class="mb-0 float-end "><span class="me-2  text-center">{{ ucwords($student['data']->current_class->title .'  '. $student['data']->current_class_section->section_name) }}</span></p>
            </div>
            <div class="clearfix" style="border-top:1px solid black; padding:5px; zoom:50% color:#000;">
                <p class="mb-0 float-start">@lang('english.marks')</p>
                <p class="mb-0 float-end "><span class="me-2  text-center">{{ @num_format($student['data']->obtain_mark).'/'.@num_format($student['data']->total_mark) }}</span></p>
            </div>
            <div class="clearfix" style="border-top:1px solid black; padding:5px">
                <p class="mb-0 float-start">@lang('english.percentage')</p>
                <p class="mb-0 float-end "><span class="me-2  text-center">{{ @num_format($student['data']->final_percentage).'%' }}</span></p>
            </div>
        </div>
        @endforeach
    </div>
</body>
