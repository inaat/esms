<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.top_position_list') @if($data['type']=='class_wise')@lang('english.class') @lang('english.wise') @else @lang('english.section') @lang('english.wise') @endif</title>

</head>

<body>
      @include('common.mpdfheaderfooter')

    <div class="card">

        <div class=' '><b>@lang('english.printed') {{ @format_date('now') }} <b></div>
        <h5 class="head text-primary">{{ $data['students'][0]->campuses->campus_name.' ' }} {{ ucwords($data['students'][0]->exam_create->term->name).'  (' }}{{ $data['students'][0]->session->title.')  ' }} @lang('english.top_position_list')</h5>
        <h5 class="head text-primary">@lang('english.top_position_list') @if($data['type']=='class_wise')@lang('english.class') @lang('english.wise') @else @lang('english.section') @lang('english.wise') @endif</h5>


        <div class="table-responsive" >
            <hr>

            <table class="table mb-0" width="100%">
                <thead class="table-light" width="100%">
                    <tr>
                        <th>#</th>
                        <th>@lang('english.roll_no')</th>
                        <th>@lang('english.student_name')</th>
                        <th>@lang('english.father_name')</th>
                        <th>@lang('english.class')</th>
                        <th>@lang('english.section')</th>
                        <th>@lang('english.position_in_class')</th>
                        <th>@lang('english.position_in_section')</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach ($data['students'] as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                         <td>{{ $student->student->roll_no}}</td>
                        <td>{{ $student->student->first_name.' '.$student->student->last_name}}</td>
                        <td>{{ $student->student->father_name}}</td>
                        <td>{{ $student->current_class->title}}</td>
                        <td>{{ $student->current_class_section->section_name}}</td>
                        {{-- <td>{{ $student->class_position}}</td> --}}
                        <td>{!! str_ordinal($student->class_position,true) !!}</td>
                        <td>{!! str_ordinal($student->class_section_position,true)!!}</td>
                    </tr>
                 
                    @endforeach

                </tbody>
            </table>
        </div>


    </div>
    
     

    </html>
