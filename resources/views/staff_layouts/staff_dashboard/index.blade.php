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
    @if($students != false)
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-primary">@lang('english.enrolled_students_in_your_bus')</h4>
                <hr>
                <div class="table-responsive">

                    <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th rowspan="2">#</th>
                                <th rowspan="2">@lang('english.roll_no')</th>
                                <th rowspan="2">@lang('english.name')</th>
                                <th rowspan="2">@lang('english.photo')</th>
                                <th rowspan="2">@lang('english.mobile_no')</th>
                              
                            </tr>
                           
                        </thead>
                        <tbody>
                        @foreach ($students as  $student)
                        <tr>
                        <td>{{ $loop->iteration }}</td>
                                <td>{{ $student->roll_no }}</td>
                                <td>{{ ucwords($student->first_name.'  ').$student->last_name }}</td>

                                <td>
                                    <img src="{{ url('uploads/student_image/' . $student->student_image) }}" alt="" width="40" height="40">
                                </td>
                                <td>
                                {{ $student->mobile_no }}
                                </td>
                                </tr>
                                  @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        @endif
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
                url: "/get_teacher_attendance?grade=1"
                , modal: true
            }
        });
    });

</script>
@endsection

