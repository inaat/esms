<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.note_book_status_empty_form')</title>
</head>

<body>
       @include('common.mpdfheaderfooter')

  @foreach ($data['subjects'] as $subject )
    <div class="card">

        <div><b>@lang('english.printed'): {{ @format_date('now') }} <b>
             @lang('english.subject') : {{ $subject->name}} 

        <h5 class="head text-primary">{{ $subject->campuses->campus_name . '   Class:' . $subject->classes->title }}</span></h5>
</div>
        <div class="table-responsive">
            <table class="table mb-0" width="100%">
                <thead class="table-light" width="100%">
                     <tr>
                            {{-- <th rowspan="2">Sr No</th> --}}
                            <th rowspan="2">#</th>
                             <th rowspan="2">@lang('english.roll_no')</th>
                            <th rowspan="2">@lang('english.student_name')</th>
                           <th rowspan="2">@lang('english.book_work')</th>
                        <th colspan="2">@lang('english.note_book_topic')</th>
                        <th rowspan="2">@lang('english.tutor')</th>
                        <th rowspan="2">@lang('english.remark')</th>

                        </tr>
                        <tr>
                            <th>@lang('english.date')</th>
                            <th>@lang('english.signature')</th>
                           
                        </tr>
                </thead>
                <tbody>
                @foreach ($data['students'] as $student )
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $student->roll_no}}</td>
                        <td>{{ ucwords($student->first_name .'  '.$student->last_name) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
                        <p style="page-break-after: always;"></p>

  @endforeach


</body>
</html>

