<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.class')WISE TIME TABLE</title>

<body>

 
         @include('common.mpdfheaderfooter')
    <div  class="head text-primary">
        <h3>@lang('english.class') @lang('english.wise') @lang('english.time_table')</h3>
    </div>
    <div class="space" style="width:100%;  height:1px;">
    </div>
    <table class="table mb-0" width="100%" id="employees_table">

        <thead class="table-light" width="100%">

            <tr style="background:#eee">
                <th>#</th>
                <th>Clsases</th>
                @php
                foreach ($data['class_time_table_title'] as $t) {
                echo '<th>'.$t.'</th>';
                }
                @endphp

            <tr>
        </thead>
        <tbody>
            @foreach ( $data['sections'] as $section)

            <tr>
                <td> {{$loop->iteration}}
                </td>
               <td>{{ $section['section_name']}}</td>
                @foreach ($section['timetables'] as $time_table)
                @if(!empty($time_table->subjects))
                <td> {{ $time_table->subjects->name }}  {{ $time_table->other ? '('.__('english.'.$time_table->other).')' : null }} <br>@if(!empty($time_table->teacher)) <strong>({{ ucwords($time_table->teacher->first_name . ' ' . $time_table->teacher->last_name) }})@endif</strong></td>
                @elseif(!empty($time_table->periods))
                @if($time_table->periods->type=='lunch_break' || $time_table->periods->type=='paryer_time')
                <td ><span class="vertical-text">@lang('english.'.$time_table->periods->type)</span></td>
                @else
                <td >
                    @if(!empty($time_table->other))
                                @lang('english.'.$time_table->other) 
                    @endif
                       @if(!empty($time_table->multi_subject_ids))
                                @foreach ($time_table->multi_subject_ids as $multi_subject )
                                    {{ $data['all_subjects'][$multi_subject] }} +
                                    
                                @endforeach
                                <br>
                                @foreach ($time_table->multi_teacher as $multi_teach )
                                   <strong>( {{ $data['teachers'][$multi_teach] }})</strong>
                                    
                                @endforeach
                                
                                @endif
                    <br>@if(!empty($time_table->note)) <strong>({{ ucwords($time_table->note ) }})@endif</strong>
                </td>
               @endif

                                   @else
                                   <td></td>
                                @endif
                                
                                @endforeach 
            </tr>
            
            @endforeach
        </tbody>
    </table>

</body>

</html>
                    