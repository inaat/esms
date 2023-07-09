@extends("admin_layouts.app")
@section('title', __('english.routine_test'))
@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.routine_test')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.routine_test')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        {!! Form::open(['url' => action('Examination\RoutineTestController@store'), 'method' => 'post', 'class'=>'needs-validation was-validated','novalidate'.'id' =>'search_student_fee' ,'files' => true]) !!}

        <div class="card">

            <div class="card-body">
                <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                <hr>
                <div class="row ">

                    <div class="col-md-4 p-2 ">
                        {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                        {!! Form::select('campus_id', $campuses, $campus_id,['class' => 'form-select select2 global-campuses','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-4 p-2">
                        {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                        {!! Form::select('class_id',$classes,$class_id, ['class' => 'form-select select2 global-classes ','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-4 p-2">
                        {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                        {!! Form::select('class_section_id', $sections, $class_section_id, ['class' => 'form-select select2 global-class_sections', 'required', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-4 p-1">
                        {!! Form::label('english.sessions', __('english.sessions') . ':*') !!}
                        {!! Form::select('session_id',$sessions,$session_id, ['class' => 'form-select select2 exam-session ','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'),'id'=>'session_id']) !!}
                    </div>
                    <div class="col-md-4 ">
                        {!! Form::label('date', __( 'english.date' ) . ':*') !!}
                        {!! Form::text('date',@format_date($date), ['class' => 'form-control date-picker', 'readonly', 'placeholder' => __('english.date')]) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('subjects', __('english.subjects') . ':') !!}
                        {!! Form::select('subject_id',$classSubject, $subject_id, ['class' => 'form-select select2 global-section-subjects', 'id' => 'global-section-subjects', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-4 p-2">
                        {!! Form::label('type', __('english.type') . ':') !!}
                        {!! Form::select('type',['morning'=>'Morning','evening'=>'Evening'],$type, ['class' => 'form-select select2 global-section-type', 'id' => 'global-section-type', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                </div>
                <div class="d-lg-flex align-items-center mt-4 gap-3">
                    <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                            <i class="fas fa-filter"></i>@lang('english.filter')</button></div>
                </div>
            </div>
        </div>


        {{ Form::close() }}
    

    @if (isset($subs) || isset($studennts))
    {!! Form::open(['url' => action('Examination\RoutineTestController@postSubjectResult'), 'method' => 'post', 'class' => '', '' . 'id' => 'mark-entry-form', 'files' => true]) !!}


    <div class="card">

        <div class="card-body">
            {{-- <h6 class="card-title text-primary">@lang('english.routine_test')({{ $subs[0]->subject_name->name }})({{ ucwords($subs[0]->teacher->first_name.'  ').$subs[0]->teacher->last_name }})</h6> --}}
            <hr>
            <div class="table-responsive">
                {!! Form::hidden("campus_id", $campus_id) !!}
                {!! Form::hidden("class_id", $class_id) !!}
                {!! Form::hidden("class_section_id", $class_section_id) !!}
                {!! Form::hidden("session_id", $session_id) !!}
                {!! Form::hidden("date", @format_date($date)) !!}
                {!! Form::hidden("type", $type) !!}
                {!! Form::hidden("subject_id", $subject_id) !!}
               
                <table style="zoom:80%"id="pos_table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th >@lang('english.roll_no')</th>
                            <th >@lang('english.name')</th>
                            <th >@lang('english.photo')</th>
                            <th >@lang('english.absent')</th>
                            <th>@lang('english.obtain')</th>
                          
                            <th >@lang('english.total')</th>
                            <th >@lang('english.exam_grade')</th>
                            <th >@lang('english.remark')</th>
                        </tr>
                        
                    </thead>
                    <tbody id="fn_mark">
                        @foreach ($students as $std)
                        <tr>
                            <td>{{ $std->roll_no }}</td>
                            <td>{{ ucwords($std->first_name.'  ').$std->last_name }}
                                <input type="hidden" value="{{ $std->id }}" name="marks[{{$std->id}}][student_id]"/>
                            </td>
                            <td>
                                <img src="{{ url('uploads/student_image/' . $std->student_image) }}" alt="" width="40" height="40">
                            </td>
                            <td>
                                {!! Form::checkbox('marks[' . $std->id. '][is_absent]', 1, false, ['class' => 'form-check-input mt-2 fee-head-check']) !!} </td>
                            </td>
                            <td>
                                <input type="number" value="" name="marks[{{$std->id}}][obtain_total_mark]"  required class="form-control obtain_total_mark tabkey addtabkey input_number">
                            </td>
                            <td>
                                <input type="number" value="" name="marks[{{$std->id}}][total_mark]" required class="form-control total_mark  col-md-7 col-xs-12">
                            </td>
                            <td>
                                <select name="marks[{{$std->id}}][grade_id]" class="form-control input-sm gsms-nice-select_">
                                    <option value="">--Select--</option>

                                    @foreach($grades as $key => $value)

                                    <option value="{{$key}}">
                                        {{$value}}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" value="" name="marks[{{$std->id}}][remark]" readonly class="form-control  col-md-7 col-xs-12 remark">
                            </td>
                        </tr>
                        @endforeach
                        
                        @foreach ($subs as $sub)
                        <tr>
                            <td>{{ $sub->student->roll_no }}</td>
                            <td>{{ ucwords($sub->student->first_name.'  ').$sub->student->last_name }}
                                <input type="hidden" value="{{ $sub->student->id }}" name="marks[{{$sub->student->id}}][student_id]"/>
                            </td>
                            <td>
                                <img src="{{ url('uploads/student_image/' . $sub->student->student_image) }}" alt="" width="40" height="40">
                            </td>
                            <td>
                                {!! Form::checkbox('marks[' . $sub->student->id. '][is_absent]', 1, $sub->is_attend, ['class' => 'form-check-input mt-2 fee-head-check']) !!} </td>
                            </td>
                            <td>
                                <input type="number" value="{{ $sub->obtain_mark }}" name="marks[{{$sub->student->id}}][obtain_total_mark]"  required class="form-control obtain_total_mark tabkey addtabkey input_number">
                            </td>
                            <td>
                                <input type="number" value="{{ $sub->total_mark }}" name="marks[{{$sub->student->id}}][total_mark]" required class="form-control total_mark  col-md-7 col-xs-12">
                            </td>
                            <td>
                                <select name="marks[{{$sub->student->id}}][grade_id]" class="form-control input-sm gsms-nice-select_">
                                    <option value="">--Select--</option>

                                    @foreach($grades as $key => $value)

                                    <option value="{{$key}}" @if(!empty($sub->grade_id) && $sub->grade_id == $key) selected @endif>
                                        {{$value}}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" value="{{ $sub->remark }}" name="marks[{{$sub->student->id}}][remark]" readonly class="form-control  col-md-7 col-xs-12 remark">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if ($subs->count() > 0 || $students->count() > 0)
                <div class="d-lg-flex align-items-center mt-4 gap-3">
                    <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0 tabkey" type="submit">
                            @lang('english.submit')</button></div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="instructions"><strong>@lang('english.instruction'): </strong>@lang('english.routine_test_warning')</div>
                </div>
                @endif
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
        var json_grades = @json($json_grades);
        //Validate minimum selling price if hidden
        mark_form_obj = $('form#mark-entry-form');

        mark_form_validator = mark_form_obj.validate({
            submitHandler: function(form) {
                form.submit();
            }
        , });
        //On Change of quantity
        $(document).on('keyup', '.obtain_total_mark,.total_mark', function() {
            var row = $(this).closest('tr');
            var obtain_total_mark = __read_number(row.find('input.obtain_total_mark'));
            var total_mark = __read_number(row.find('input.total_mark'));
            row.find('input.obtain_total_mark').attr('data-rule-max-value', total_mark);
            msg = __translate('minimum_value_error_msg', { value: total_mark});
            row.find('input.obtain_total_mark').attr('data-msg-max-value', msg);
            $('.total_mark').val(total_mark);
            var line_total = obtain_total_mark;
            __write_number(
                row.find('input.total_obtain_mark')
                , line_total
            );
            percentage = (line_total * 100) / total_mark;
            json_grades.filter(function(value) {
                if (percentage >= value.percentage_from && percentage <= value.percentage_to) {
                    row.find('select.gsms-nice-select_').val(value.id);
                    row.find('input.remark').val(value.remark);
                }
            });


        });

        $('body').on('keydown', 'input, select ,.select2-input', function(e) {
            if (e.key === "Enter") {
                var self = $(this)
                    , form = self.parents('form:eq(0)')
                    , focusable, next;
                focusable = form.find('.tabkey').filter(':visible');
                next = focusable.eq(focusable.index(this) + 1);
                if (next.length) {
                    next.focus();
                    next.select();

                } else {
                    mark_form_obj.submit();
                }


                return false;
            }

        });

        $(document).on('change', 'input.amount,input.fee-head-check', function() {
                     var table = $(this).closest('table');
                     table.find('tbody tr').each(function() {
                         if ($(this).find('input.fee-head-check').is(':checked')) {
                            $(this).find('input.addtabkey ').attr('readonly',true);
                            $(this).find('input.addtabkey ').val(0);
                            $(this).find('input').removeClass("tabkey");
                            var row = $(this).closest('tr');
            var obtain_total_mark = __read_number(row.find('input.obtain_total_mark'));
            var total_mark = __read_number(row.find('input.total_mark'));
            $('.total_mark').val(total_mark);
            var line_total = obtain_total_mark;
            __write_number(
                row.find('input.total_obtain_mark')
                , line_total
            );
            percentage = (line_total * 100) / total_mark;
            json_grades.filter(function(value) {
                if (percentage >= value.percentage_from && percentage <= value.percentage_to) {
                    row.find('select.gsms-nice-select_').val(value.id);
                    row.find('input.remark').val(value.remark);
                }
            });

                         }else{
                            $(this).find('input.addtabkey ').removeAttr('readonly'); 
                            $(this).find('input.addtabkey').addClass("tabkey");
                         }
                     });
                     

                 });

    });

</script>
@endsection

