


@extends('admin_layouts.app')
@section('title', __('english.bulk_date_sheet_creste'))
@section('wrapper')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">@lang('english.bulk_date_sheet_creste')</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('english.bulk_date_sheet_creste')</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card">
                {!! Form::open([
                    'url' => action('Examination\ExamDateSheetController@getSubjectDateSheet'),
                    'method' => 'post',
                    'id' => 'add_date_sheet_form',
                ]) !!}

                <div class="card-body">
                    <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 p-2">
                            {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                            {!! Form::select('campus_id', $campuses, $campus_id, [
                                'class' => 'form-select select2 global-campuses',
                                'style' => 'width:100%',
                                'required',
                                'placeholder' => __('english.please_select'),
                            ]) !!}
                        </div>
                        <div class="col-md-4 p-2">
                            {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                            {!! Form::select('class_id',  $classes, $class_id, [
                                'class' => 'form-select select2 global-classes ',
                                'style' => 'width:100%',
                                'required',
                                'placeholder' => __('english.please_select'),
                            ]) !!}
                        </div>
                        {{-- <div class="col-md-4 p-2">
                            {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                            {!! Form::select('class_section_id',$class_sections,$class_section_id, [
                                'class' => 'form-select select2 global-class_sections',
                                'required',
                                'style' => 'width:100%',
                                'placeholder' => __('english.please_select'),
                            ]) !!}
                        </div> --}}
                        <div class="col-md-4 p-1">
                            {!! Form::label('english.sessions', __('english.sessions') . ':*') !!}
                            {!! Form::select('session_id', $sessions, $session_id, [
                                'class' => 'form-select select2 exam-session',
                                'required',
                                'style' => 'width:100%',
                                'required',
                                'placeholder' => __('english.please_select'),
                                'id' => 'session_id',
                            ]) !!}
                        </div>
                        <div class="col-md-4">
                            {!! Form::label('term', __('english.term') . ':*') !!}
                            {!! Form::select('exam_create_id',$terms,$exam_create_id, [
                                'class' => 'form-select select2 exam_term_id',
                                'style' => 'width:100%',
                                'placeholder' => __('english.please_select'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="d-lg-flex align-items-center mt-4 gap-3">
                        <div class="ms-auto"><button class="btn btn-primary radius-30 mt-lg-0 mt-2" type="submit">
                                <i class="fas fa-filter"></i>@lang('english.filter')</button></div>
                    </div>
                </div>
                {{ Form::close() }}

            </div>
            @if ($subjects->count() > 0 || $date_sheet->count()>0)
            {!! Form::open([
                'url' => action('Examination\ExamDateSheetController@bulkPostDateSheet'),
                'method' => 'post',
                'id' => 'bulk_date_sheet_form',
            ]) !!}
            {!! Form::hidden("class_id", $class_id) !!}
            {{-- {!! Form::hidden("class_section_id", $class_section_id) !!} --}}
            {!! Form::hidden("session_id", $session_id) !!}
            {!! Form::hidden("exam_create_id", $exam_create_id) !!}
            {!! Form::hidden("campus_id", $campus_id) !!}
                <div class="card">

                    <div class="card-body">
                        <h6 class="card-title text-primary"></h6>
                        <hr>
                        


                            <table id="pos_table"
                                class="table table-condensed table-bordered text-center table-striped" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('english.subjects')</th>
                                        <th>@lang('english.type')</th>
                                        <th>@lang('english.date')</th>
                                        <th>@lang('english.start_time')</th>
                                        <th>@lang('english.end_time')</th>

                                    </tr>
                                </thead>
                                <tbody>
									@php
										$index=0;
									@endphp
									@foreach ($date_sheet as $key=>$dt)
									@php
										$index+=1;
									@endphp
									<tr>
										<td>{{ $index }}</td>
										<td>{{ $dt->subject->name }}</td>
										<td>
                                            {!! Form::hidden('data[' . $index . '][subject_id]', $dt->subject_id); !!}

											{!! Form::select('data[' . $index . '][type]', ['written' => 'Written', 'oral' => 'Oral', 'written_oral' => 'Written Oral'],$dt->type, [
												'class' => 'form-select select2 ','required',
												'id' => 'date_sheets',
												'style' => 'width:100%',
												'placeholder' => __('english.please_select'),
											]) !!}

										</td>
										<td>
											<div class=" flex-nowrap input-group-text input-group " data-target-input="nearest"> <span class="input-group-text "
													id="addon-wrapping"><i class="fa fa-calendar"></i></span>

												{!! Form::text('data[' . $index . '][date]', @format_date($dt->date), [
													'class' => 'form-control date-picker date-sheet-date',
													'placeholder' => __('english.date'),
												]) !!}

											</div>
											
										</td>
										<td>
										 
											<div class="timepicker1  input-group-text input-group" id="{{'start_timepicker'.$index}}" data-target-input="nearest" data-target="{{'#start_timepicker'.$index}}" data-toggle="datetimepicker">
												<div class="input-group flex-nowrap input-group-append  input-group date">
													{!! Form::text('data[' . $index . '][start_time]data[' . $index . '][start_time]', @format_time($dt->start_time), ['class' => 'form-control datetimepicker-input', 'data-target' => '#start_timepicker'.$index, 'required']) !!}
													<span class="input-group-text" id="addon-wrapping"><i class="fa fa-clock"></i></span>
												</div>
											</div>

											
										</td>
										<td>
										 
											<div class="timepicker1  input-group-text input-group" id="{{'end_timepicker'.$index}}" data-target-input="nearest" data-target="{{'#end_timepicker'.$index}}" data-toggle="datetimepicker">
												<div class="input-group flex-nowrap input-group-append  input-group date">
													{!! Form::text('data[' . $index . '][end_time]', @format_time($dt->end_time), ['class' => 'form-control datetimepicker-input', 'data-target' => '#end_timepicker'.$index, 'required']) !!}
													<span class="input-group-text" id="addon-wrapping"><i class="fa fa-clock"></i></span>
												</div>
											</div>

											
										</td>
										
									</tr>
									@endforeach
                                    @foreach ($subjects as $subject)
									@php
										$index+=1;
									@endphp
                                        <tr>
                                            <td>{{ $index }}</td>
                                            <td>{{ $subject->name }}</td>
                                            <td>
                                                {!! Form::hidden('data[' . $index . '][subject_id]', $subject->id); !!}

                                                {!! Form::select('data[' . $index . '][type]', ['written' => 'Written', 'oral' => 'Oral', 'written_oral' => 'Written Oral'], null, [
                                                    'class' => 'form-select select2 ',
                                                    'id' => 'date_sheets',
                                                    'style' => 'width:100%',
                                                    'placeholder' => __('english.please_select'),
                                                ]) !!}

                                            </td>
                                            <td>
                                                <div class=" flex-nowrap input-group-text input-group " data-target-input="nearest"> <span class="input-group-text "
                                                        id="addon-wrapping"><i class="fa fa-calendar"></i></span>

                                                    {!! Form::text('data[' . $index . '][date]', null, [
                                                        'class' => 'form-control date-picker date-sheet-date',
                                                        'placeholder' => __('english.date'),
                                                    ]) !!}

                                                </div>
												
                                            </td>
                                            <td>
                                             
												<div class="timepicker1  input-group-text input-group" id="{{'start_timepicker'.$index}}" data-target-input="nearest" data-target="{{'#start_timepicker'.$index}}" data-toggle="datetimepicker">
													<div class="input-group flex-nowrap input-group-append  input-group date">
														{!! Form::text('data[' . $index . '][start_time]', null, ['class' => 'form-control datetimepicker-input', 'data-target' => '#start_timepicker'.$index]) !!}
														<span class="input-group-text" id="addon-wrapping"><i class="fa fa-clock"></i></span>
													</div>
												</div>

												
                                            </td>
                                            <td>
                                             
												<div class="timepicker1  input-group-text input-group" id="{{'end_timepicker'.$index}}" data-target-input="nearest" data-target="{{'#end_timepicker'.$index}}" data-toggle="datetimepicker">
													<div class="input-group flex-nowrap input-group-append  input-group date">
														{!! Form::text('data[' . $index . '][end_time]', null, ['class' => 'form-control datetimepicker-input', 'data-target' => '#end_timepicker'.$index]) !!}
														<span class="input-group-text" id="addon-wrapping"><i class="fa fa-clock"></i></span>
													</div>
												</div>

												
                                            </td>
                                          
                                        </tr>
								
                                    @endforeach


                                </tbody>
                            </table>

                            <div class="d-lg-flex align-items-center mt-4 gap-3">
                                <div class="ms-auto"><button class="btn btn-primary radius-30 mt-lg-0 tabkey mt-2"
                                        type="submit">
                                        @lang('english.submit')</button></div>
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

            $(".timepicker1").datetimepicker({
                format: moment_time_format,
        ignoreReadonly: true,
		sideBySide: true,
        widgetPositioning: {
            horizontal: 'right',
            vertical: 'top'
        }
     
            });

        });
    </script>
@endsection
