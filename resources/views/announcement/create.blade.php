@extends('admin_layouts.app')
@section('title', __('english.announcement'))
@section('wrapper')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary">@lang('english.all_announcements')</h5>
                    {!! Form::open(['url' => action('AnnouncementController@store'), 'method' => 'post',  'id' => 'announcement_form', 'files' => true]) !!}
                    <hr>
                    <div class="row">
                        

                        <div class="col-md-6 p-2">
                            {!! Form::label('title *', __('english.class_title') . ':*') !!}
                            {!! Form::text('title', null, [
                                'class' => 'form-control',
                                'placeholder' => __('english.class_title'),
                            ]) !!}
                        </div>
                        <div class="col-md-6 p-2">
                            {!! Form::label('descriptions', __('english.descriptions') . ':') !!}
                            {!! Form::textarea('description', null, ['rows' => '2', 'placeholder' => __('description'), 'class' => 'form-control']) !!}

                        </div>

                        
            
                        <div class="col-md-12 p-1">
                            {!! Form::label('files', __('english.files') . ':') !!}
                            <input type="file" name="file[]" class="form-control" multiple />        
                        </div>
                    
                        
                            <div class="form-group col-sm-4 col-md-3">
                                <label>{{ __('assign_to') }} </label>
                                <select name="set_data" id="set_data" class="form-control select2">
                                    <option value="">{{ __('select') . ' ' . __('assign_to') }}</option>
                                    @if(Auth::user()->hasRole('Teacher#1'))
                                    <option value="class_section">Class Section</option>

                                    @else
                                        {{--<option value="class">{{ __('class') }}</option>--}}
                                        <option value="general">{{ __('english.noticeboard') }}</option>
                                    @endif
                                </select>
                            </div>
                    <div class="row teacher-data ">
                        <div class="col-md-4 p-2">
                            {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                            {!! Form::select('campus_id', $campuses, null, [
                                'class' => 'form-select select2 global-campuses',
                                'style' => 'width:100%',
                                'placeholder' => __('english.please_select'),
                            ]) !!}
                        </div>
                        <div class="col-md-4 p-2">
                            {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                            {!! Form::select('class_id', [], null, [
                                'class' => 'form-select  select2 global-classes ',
                                'style' => 'width:100%',
                                'placeholder' => __('english.please_select'),
                            ]) !!}
                        </div>
                        <div class="col-md-4 p-2">
                            {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                            {!! Form::select('class_section_id', [], null, [
                                'class' => 'form-select select2 global-class_sections',
                                'style' => 'width:100%',
                                'placeholder' => __('english.please_select'),
                            ]) !!}
                        </div>
                        <div class="col-md-4 p-2">
                            {!! Form::label('subjects', __('english.subjects') . ':') !!}
                            {!! Form::select('get_data[]', [], null, [
                                'class' => 'form-select select2 global-section-subjects',
                                'id' => 'global-section-subjects',
                                'style' => 'width:100%',
                                'placeholder' => __('english.please_select'),
                            ]) !!}
                        </div>
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
        

    </script>

@endsection
