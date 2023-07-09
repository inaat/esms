<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.syllabus')</title>

</head>

<body>
 @include('common.mpdfheaderfooter')

    @foreach ($data['class_syllabus_list'] as $syllabus)

    @if(!empty($syllabus['data']))
    <div class="card">

        <div class=' '><b>@lang('english.printed') {{ @format_date('now') }} <b></div>
        <h5 class="head text-primary">@lang('english.syllabus') @lang('english.of') @lang('english.class') {{$syllabus['classes']->title }} @lang('english.exam') {{ $data['term']->name }}</h5>


        <div class="table-responsive" >
            <hr>

            <table class="table mb-0" width="100%">
                <thead class="table-light" width="100%">
                    <tr>
                        <th  style="width:20%">@lang('english.subject') </th>
                        <th  style="width:80%">@lang('english.details')</th>

                    </tr>
                </thead>
                <tbody>

                    @foreach ($syllabus['data'] as $subject)
                    <tr>
                        <td>{{ $subject['subject']->name }}</td>
                        @if(!$subject['syllabus']->isEmpty())
                        <td>
                           <table >
                           <tr>
                           <th>@lang('english.chapters')</th>
                           <th>@lang('english.description')</th>
                           <th>@lang('english.lesson')</th>
                           </tr>
                            @foreach($subject['syllabus'] as $sub)
                           
                            
                                <tr>
                                    <td style="width:60%">
                                       {{ $sub->chapter->chapter_name }}
                                    </td>
                                    <td style="width:10%">
                                      {{ $sub->description }}
                                    </td>
                                     <td style="width:30%">
                                    @foreach ($sub->chapter->lesson as  $lesson)
                                       
                                      {{ $lesson->name }},
                                    
                                    @endforeach
                                    </td>
                                </tr>
                            
                            @endforeach
                            </table>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p style="page-break-after: always;"></p>


    </div>
    @endif
    @endforeach





    </html>

