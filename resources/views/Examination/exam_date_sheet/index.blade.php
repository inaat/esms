@extends("admin_layouts.app")
@section('title', __('english.date_sheet'))
@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.all_your_exam_date_sheets')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.exam_date_sheet')</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->
        <!--breadcrumb-->
        <div class="card">
            <div class="card-body">
                <div class="accordion" id="student-fillter">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="student-fillter">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                <h5 class="card-title text-primary">@lang('english.flitters')</h5>
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="student-fillter" data-bs-parent="#student-fillter" style="">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-3 p-2 ">
                                        {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                                        {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select select2 global-campuses', 'required', 'id' => 'filter_campus_id', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all')]) !!}
                                    </div>
                                    <div class="col-md-3 p-2">
                                        {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                                        {!! Form::select('class_id', [], null, ['class' => 'form-select select2 global-classes', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all'), 'id' => 'filter_class_id']) !!}
                                    </div>
                                    {{-- <div class="col-md-3 p-2">
                                        {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                                        {!! Form::select('class_section_id', [], null, ['class' => 'form-select select2 global-class_sections', 'id' => 'filter_class_section_id', 'style' => 'width:100%', 'placeholder' => __('english.all')]) !!}
                                    </div> --}}
                                    <div class="col-md-3 p-1">
                                        {!! Form::label('english.sessions', __('english.sessions') . ':*') !!}
                                        {!! Form::select('session_id',$sessions,null, ['class' => 'form-select select2 exam-session ','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'),'id'=>'filter_session_id']) !!}
                                    </div>
                                    <div class="col-md-3 ">
                                        {!! Form::label('term', __( 'english.term' ) . ':*') !!}
                                        {!! Form::select('exam_create_id',[],null, ['class' => 'form-select select2 exam_create_id exam_term_id', 'style' => 'width:100%','id' => 'filter_exam_create_id', 'placeholder' => __('english.please_select')]) !!}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">@lang('english.exam_date_sheet_list') </h5>


                <div class="d-lg-flex align-items-center mb-4 gap-3">

                    <div class="ms-auto"><button type="button" class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-modal" data-href="{{ action('Examination\ExamDateSheetController@create') }}" data-container=".exam_date_sheet_modal">
                            <i class="bx bxs-plus-square"></i>@lang('english.add_new_exam_date_sheet')</button></div>

                </div>


                <hr>

                <div class="table-responsive">
                    <table class="table mb-0" width="100%" id="date_sheet">
                        <thead class="table-light" width="100%">
                            <tr>
                                <th>@lang('english.action')</th>
                                <th>@lang('english.date')</th>
                                <th>@lang('english.day')</th>
                                <th>@lang('english.exam_term')</th>
                                <th>@lang('english.session')</th>
                                <th>@lang('english.subject_name')</th>
                                <th>@lang('english.campus_name')</th>
                                <th>@lang('english.class')</th>
                                {{-- <th>@lang('english.section')</th> --}}
                                <th>@lang('english.start_time')</th>
                                <th>@lang('english.end_time')</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<div class="modal fade exam_date_sheet_modal contains_select2" id="exam_date_sheet_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

@endsection


@section('javascript')
<script src="{{ asset('js/exam.js?v=' . $asset_v) }}"></script>

@endsection

