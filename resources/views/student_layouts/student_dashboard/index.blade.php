@extends("admin_layouts.app")
@section('title', __('english.dashboard'))
@section("wrapper")
@section("style")
<link href="{{ asset('/js/calender/zabuto_calendar.min.css')}}" rel="stylesheet" />
<style>
    .holiday {
        background-color: #420E5F !important;
        color: #fff !important;
    }

    .weekend {
        background-color: #3A565C !important;
        color: #fff !important;
    }

</style>
@endsection
<div class="page-wrapper">
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-primary">@lang('english.classes_time_table')</h6>
                <hr>
                <div class="row m-0">
                    @foreach ($timetables as $timetable)
                    <div class="col-md-3  ">
                        {{-- <a class="" href="{{ action('TeacherLayout\TeacherDashboardController@show',[$timetable->subjects->id]) }}"> --}}
                        <div class="card">
                            <div class="card-body">
                                {{-- <h6 class="card-title text-center text-primary">{{ $timetable->campuses->campus_name}}</h6> --}}
                                {{-- <h6 class="card-title text-center text-primary">{{ $timetable->classes->title.' '. $timetable->section->section_name }}</h6> --}}
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
                        {{-- </a> --}}
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-primary">@lang('english.attendance')</h4>
                <hr>

                <div id="my-calendar"></div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src="{{ asset('/js/calender/zabuto_calendar.min.js?v=' . $asset_v) }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#my-calendar").zabuto_calendar({
            legend: [{
                    type: "block"
                    , label: "@lang('english.absent')"
                    , classname: 'icon-badge bg-danger'
                }
                , {
                    type: "block"
                    , label: "@lang('english.present')"
                    , classname: 'icon-badge bg-success'
                }
                , {
                    type: "block"
                    , label: "@lang('english.late')"
                    , classname: 'icon-badge  bg-warning'
                }
                , {
                    type: "block"
                    , label: "@lang('english.half_day')"
                    , classname: 'icon-badge bg-dark'
                }
                , {
                    type: "block"
                    , label: "@lang('english.holiday')"
                    , classname: 'icon-badge holiday'
                }
                , {
                    type: "block"
                    , label: "@lang('english.weekend')"
                    , classname: 'icon-badge weekend'
                }
                , {
                    type: "block"
                    , label: "@lang('english.leave')"
                    , classname: 'icon-badge bg-info'
                }
            , ]
            , ajax: {
                url: "/get_student_attendance?student_id={{$student->id}}&grade=1"
                , modal: true
            }
        });
    });

</script>
@endsection

