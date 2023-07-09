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
            <h3 class="card-title text-primary">@lang('english.your_children')</h3>

                <div class="row">
                @foreach ($child as $data)
                     <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="box-body box-profile text-center">
                                    <img src="{{ !empty($data->students->student_image)  ? url('uploads/student_image/', $data->students->student_image) : url('uploads/student_image/default.png') }}" alt="Admin" class="rounded-circle p-1 bg-primary" width="100" height="100">
                                    {{-- <h6 class="profile-username text-center">{{ ucwords($data->students->first_name . ' '. $data->students->last_name) }}</h6> --}}
                                  <a href="{{ action('StudentController@studentProfile',$data->students->id) }}">
                                        <h6 class="profile-username text-center text-primary">{{ ucwords($data->students->first_name . ' '. $data->students->last_name) }}</h6>
                                    </a>
                                    <ul class="list-group list-group-unbordered list-group list-group-flush">

                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0">@lang('english.adm_no')</h6>
                                            <span class="text-secondary">{{ ucwords($data->students->admission_no ) }}</span>

                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0">@lang('english.roll_no')</h6>
                                            <span class="text-secondary">{{ ucwords($data->students->roll_no ) }}</span>

                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0">@lang('english.class')</h6>
                                            <span class="text-secondary">{{ ucwords($data->students->current_class->title ) }}</span>

                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0">@lang('english.section')</h6>
                                            <span class="text-secondary">{{ ucwords($data->students->current_class_section->section_name) }}</span>

                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0">@lang('english.gender')</h6>
                                            <span class="text-secondary">{{ ucwords($data->students->gender) }}</span>
                                        </li>

                                    </ul>
                                </div>

                            </div>
                        </div>
                    
                    </div> 
                    @endforeach
                  
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src="{{ asset('/js/calender/zabuto_calendar.min.js?v=' . $asset_v) }}"></script>

<script type="text/javascript">
    $(document).ready(function() {

    });

</script>
@endsection

