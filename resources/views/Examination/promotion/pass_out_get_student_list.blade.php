@extends("admin_layouts.app")
@section('title', __('english.roles'))
@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.students_pass_out')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        {!! Form::open(['url' => action('Examination\PromotionController@passOutPost'), 'method' => 'post', 'id' =>'promotion-list-form']) !!}

        <div class="card">

            <div class="card-body">
                <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                <hr>
                <div class="row m-0">
                    <div class="col-md-3 p-2 ">
                        {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                        {!! Form::select('campus_id', $campuses, $campus_id, ['class' => 'form-select select2 global-campuses', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-3 p-2">
                        {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                        {!! Form::select('class_id',$classes,$class_id, ['class' => 'form-select select2 global-classes', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-3 p-2">
                        {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                        {!! Form::select('class_section_id',$sections, $class_section_id, ['class' => 'form-select select2 global-class_sections' ,'required', 'id' => 'students_list_filter_class_section_id', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>

                </div>
                <div class="d-lg-flex align-items-center mt-4 gap-3">
                    <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                            <i class="fas fa-filter"></i>@lang('english.filter')</button></div>
                </div>
            </div>
        </div>

        {{ Form::close() }}

        <div class="row">
            <div class="col-lg-12">
                <div class="card bg-warning bg-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="font-35 text-dark"><i class="bx bx-info-circle"></i>
                            </div>
                            <div class="ms-3">
                                 <h6 class="mb-0 text-dark">@lang('english.warning') / @lang('english.disclaimer')</h6>
                                <div class="text-dark">@lang('english.backup_alert')</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (isset($students))
            {!! Form::open(['url' => action('Examination\PromotionController@passOut'), 'method' => 'post', 'class' => '', '' . 'id' => 'promote-students']) !!}
            <div class="row">

                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">

                            <h6 class="card-title text-primary">Pass Out</h6>
                            <hr>

                            <div class="table-responsive">
                                <div class="row m-0">
                                    <div class="col-md-3 p-1">
                                        {!! Form::label('status', __('english.student_status') . ':*') !!}
                                        {!! Form::select('status', __('english.std_status'), null, ['class' => 'form-control select2', 'placeholder' => __('english.please_select'), 'required']); !!}
                                    </div>


                                </div>
                                <table class="table mb-0" width="100%" id="students_table">
                                    <thead class="table-light" width="100%">
                                        <tr>
                                            {{-- <th>#</th> --}}
                                            <th>@lang('english.roll_no')</th>
                                            <th>@lang('english.student_name')</th>
                                            <th>@lang('english.father_name')</th>
                                            <th>@lang('english.date_of_birth')</th>
                                            <th>@lang('english.next_session_status')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="">
                                        @foreach ($students as $student)
                                        <tr>
                                            <td>{{ $student->roll_no }}</td>
                                            <td>{{ ucwords($student->first_name .' '.$student->last_name) }}</td>
                                            <input type="hidden" name="promotion[{{$loop->iteration}}][student_id]" value="{{ $student->id }}">
                                            </td>
                                            <td>{{ ucwords($student->father_name) }}</td>
                                            <td>{{ @format_date($student->birth_date) }}</td>

                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" name="promotion[{{$loop->iteration}}][promote]" type="radio" id="" @if($student->final_percentage>=40)checked @endif value="continue">
                                                    <label class="form-check-label" for="{{ $student->id }}">Continue</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" name="promotion[{{$loop->iteration}}][promote]" type="radio" id="{{ $student->id }}" @if($student->final_percentage<40)checked @endif value="leave">
                                                        <label class="form-check-label" for="{{ $student->id }}">Leave</label>
                                                </div>


                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    @if ($students->count() > 0)
                                    <tr>
                                        <td colspan="7">
                                            <div class="text-center">
                                                <button type="submit" id="btn-assign-fees-group" class="btn btn-primary radius-30 mt-2 mt-lg-0 fix-gr-bg mb-0 submit" id="btn-assign-fees-group" data-loading-text="<i class='fas fa-spinner'></i> Processing Data">
                                                    <span class="ti-save pr"></span>
                                                    @lang('english.pass_out')
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{ Form::close() }}
            @endif


        </div>
    </div>

    @endsection

    @section('javascript')

    <script type="text/javascript">
        $(document).ready(function() {
            $('#promote-students').validate();
            $('#promotion-list-form').validate({
                rules: {
                    'campus_id': {
                        required: true
                    }
                    , 'class_id': {
                        required: true
                    }
                    , 'class_section_id': {
                        required: true
                    }

                }
            });


        });
        $(document).on('change', '.promote-campuses', function() {
            var doc = $(this);
            var campus_id = doc.closest(".row").find(".promote-campuses").val();
            $.ajax({
                method: "GET"
                , url: "/classes/get_campus_classes"
                , dataType: "html"
                , data: {
                    campus_id: campus_id
                , }
                , success: function(result) {
                    if (result) {
                        doc.closest(".row").find(".promote-classes").html(result);
                    }
                }
            , });
            $(document).on('change', '.promote-classes', function() {
                var doc = $(this);
                var class_id = doc.closest(".row").find(".promote-classes").val();
                $.ajax({
                    method: "GET"
                    , url: "/classes/get_class_section"
                    , dataType: "html"
                    , data: {
                        class_id: class_id
                    , }
                    , success: function(result) {
                        if (result) {
                            doc.closest(".row").find(".promote-class_sections").html(result);
                        }
                    }
                , });
            });

        });

    </script>
    @endsection

