@extends('admin_layouts.app')
@section('title', __('english.assignment'))
@section('wrapper')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary">@lang('english.all_assignments')</h5>
                    {!! Form::open(['url' => action('AssignmentController@store'), 'method' => 'post',  'id' => 'assignment_form', 'files' => true]) !!}
                    <hr>
                    <div class="row">
                        <div class="col-md-4 p-2">
                            {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                            {!! Form::select('campus_id', $campuses, null, [
                                'class' => 'form-select select2 global-campuses',
                                'style' => 'width:100%',
                                'required',
                                'placeholder' => __('english.please_select'),
                            ]) !!}
                        </div>
                        <div class="col-md-4 p-2">
                            {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                            {!! Form::select('class_id', [], null, [
                                'class' => 'form-select  select2 global-classes ',
                                'style' => 'width:100%',
                                'required',
                                'placeholder' => __('english.please_select'),
                            ]) !!}
                        </div>
                        <div class="col-md-4 p-2">
                            {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                            {!! Form::select('class_section_id', [], null, [
                                'class' => 'form-select select2 global-class_sections',
                                'required',
                                'style' => 'width:100%',
                                'placeholder' => __('english.please_select'),
                            ]) !!}
                        </div>
                        <div class="col-md-4 p-2">
                            {!! Form::label('subjects', __('english.subjects') . ':') !!}
                            {!! Form::select('subject_id', [], null, [
                                'class' => 'form-select select2 global-section-subjects',
                                'id' => 'global-section-subjects',
                                'style' => 'width:100%',
                                'placeholder' => __('english.please_select'),
                            ]) !!}
                        </div>

                        <div class="col-md-4 p-2">
                            {!! Form::label('assignment_name *', __('english.assignment_name') . ':*') !!}
                            {!! Form::text('name', null, [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('english.assignment_name'),
                            ]) !!}
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-4 p-2">
                            {!! Form::label('assignment_instructions', __('english.assignment_instructions') . ':') !!}
                            {!! Form::textarea('instructions', null, [
                                'class' => 'form-control',
                                'placeholder' => __('english.assignment_instructions'),
                            ]) !!}
                        </div>

                        <div class="col-md-4 p-2" id="datetimepicker" data-target-input="nearest"
                            data-target="#datetimepicker" data-toggle="datetimepicker">
                            {!! Form::label('english.last_submission_date', __('english.last_submission_date') . ':*') !!}
                            <div class="input-group input-group-append input-group date flex-nowrap"> <span
                                    class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                                {!! Form::text('due_date', @format_datetime('now'), [
                                    'class' => 'form-control datetimepicker-input',
                                    'data-target' => '#datetimepicker',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
            
                        <div class="col-md-4 p-1">
                            {!! Form::label('files', __('english.files') . ':') !!}
                            <input type="file" name="file[]" class="form-control" multiple />        
                        </div>
                        <div class="col-md-4 p-1">
                            {!! Form::label('points', __('english.points') . ':') !!}
                            {!! Form::text("points",null, ['class' => 'form-control input_number', 'placeholder' => 'english.points', 'id'=>'points']); !!}
        
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="resubmission"
                                        id="resubmission_allowed" value="">{{ __('english.resubmission_allowed') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group" id="extra_days_for_resubmission_div" style="display: none;">
                            <label>{{ __('extra_days_for_resubmission') }} <span class="text-danger">*</span></label>
                            <input type="text" id="extra_days_for_resubmission" name="extra_days_for_resubmission"
                                placeholder="{{ __('english.extra_days_for_resubmission') }}" class="form-control" />
                        </div>

                        <div class="col-sm-12 text-center">
                            <button type="submit" class="btn btn-primary btn-big">@lang('english.save')</button>
                        </div>
                    </div>
                    
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')
    <script >
        
$('#resubmission_allowed').on('change', function () {
    if ($(this).is(':checked')) {
        $(this).val(1);
        $('#extra_days_for_resubmission_div').show();
    } else {
        $(this).val(0);
        $('#extra_days_for_resubmission_div').hide();
    }
})

$('#edit_resubmission_allowed').on('change', function () {
    if ($(this).is(':checked')) {
        $(this).val(1);
        $('#edit_extra_days_for_resubmission_div').show();
    } else {
        $(this).val(0);
        $('#edit_extra_days_for_resubmission_div').hide();
    }
})

    </script>

@endsection
