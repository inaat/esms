{{-- <div class="card">
            <div class="card-body">
                <h6 class="card-title text-primary">@lang('english.classes_time_table')</h6>
                <hr>
                <div class="row m-0">
                    @foreach ($timetables as $timetable)
                    <div class="col-md-3  ">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title text-center text-primary">@if(!empty($timetable->subject_id )){{$timetable->subjects->name }} @endif
                                    @if(!empty($timetable->other))
                                    @lang('english.'.$timetable->other)
                                    @endif
                                    @if(!empty($time_table->multi_subject_ids))
                                    @foreach ($time_table->multi_subject_ids as $multi_subject )
                                    {{ $all_subjects[$multi_subject] }} +

                                    @endforeach
                                    <br>
                                    @endif
                                    @if(!empty($timetable->note)) <strong>({{ ucwords($timetable->note ) }}) </strong> <br> @endif

                                    @if($timetable->periods->type=='lunch_break' || $timetable->periods->type=='paryer_time')
                                    @lang('english.'.$timetable->periods->type)
                                    @endif

                                </h6>
                                <h6 class="card-title text-center text-primary"> @if(!empty($timetable->teacher)) ({{ ucwords($timetable->teacher->first_name . ' ' . $timetable->teacher->last_name) }})@endif
                                </h6>
                                <h6 class="card-title text-center text-primary">{{ @format_time($timetable->periods->start_time). ' To '. @format_time($timetable->periods->end_time)}}</h6>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div> --}}