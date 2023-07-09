<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.note_book_status_report')</title>

</head>

<body>
       @include('common.mpdfheaderfooter')


    <div class="card">

        <div style="float:left"><b>@lang('english.printed') {{ @format_date('now') }} <b>
    </div>

    <h3 class="head text-primary">@lang('english.note_book_status_of') {{ $data['sections']->campuses->campus_name }} @lang('english.class') {{ $data['sections']->classes->title}}   @lang('english.section'): {{   $data['sections']->section_name }}</span></h3> 

    <div class="table-responsive">
      

        <table class="table mb-0" width="100%">
            <thead class="table-light" width="100%">
                <tr>
                    <th>#</th>
                    <th>@lang('english.roll_no')</th>
                    <th>@lang('english.student_name')</th>
                    @foreach ($data['class_subjects'] as $subject)
                    <th>{{ $subject->name }}</th>
                    @endforeach

                </tr>
            </thead>
            <tbody>

                @foreach ($data['note_books'] as $note)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ ucwords($note['roll_no']) }}
                    <td>{{ ucwords($note['student_name']) }}
                    </td>
                    @foreach ($note['subjects_list'] as $sub)
                    <td>
                        {{ $sub['status'] }}
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>



</body>
</html>
