@extends("admin_layouts.app")
@section('title', __('english.mark_entry'))
@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.mark_entry')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.mark_entry')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        {{-- {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'fees-assign-search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_studentA']) }} --}}
        {!! Form::open(['url' => action('TeacherLayout\TeacherDashboardController@store'), 'method' => 'post', 'class'=>'needs-validation was-validated','novalidate'.'id' =>'search_student_fee' ,'files' => true]) !!}

        <div class="card">

            <div class="card-body">
                <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                <hr>
                <div class="row ">

                    <div class="col-md-4 p-2 ">
                        {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                        {!! Form::select('campus_id', $campuses, $campus_id,['class' => 'form-select select2 teacher-campuses','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-4 p-2">
                        {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                        {!! Form::select('class_id',$classes,$class_id, ['class' => 'form-select select2 teacher-classes ','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-4 p-2">
                        {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                        {!! Form::select('class_section_id', $sections, $class_section_id, ['class' => 'form-select select2 teacher-class_sections', 'required', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-4 p-1">
                        {!! Form::label('english.sessions', __('english.sessions') . ':*') !!}
                        {!! Form::select('session_id',$sessions,$session_id, ['class' => 'form-select select2 teacher-exam-session ','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'),'id'=>'session_id']) !!}
                    </div>
                    <div class="col-md-4 ">
                        {!! Form::label('term', __( 'english.term' ) . ':*') !!}
                        {!! Form::select('exam_create_id',$terms,$exam_create_id, ['class' => 'form-select select2 exam_term_id','required', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('subjects', __('english.subjects') . ':') !!}
                        {!! Form::select('subject_id',$classSubject, $subject_id, ['class' => 'form-select select2 teacher-section-subjects', 'required', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                </div>
                <div class="d-lg-flex align-items-center mt-4 gap-3">
                    <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                            <i class="fas fa-filter"></i>@lang('english.filter')</button></div>
                </div>
            </div>
        </div>


        {{ Form::close() }}


        @if (isset($subs))
        {!! Form::open(['url' => action('Examination\ExamMarkController@postSubjectResult'), 'method' => 'post', 'class' => '', '' . 'id' => 'mark-entry-form', 'files' => true]) !!}


        <div class="card">

            <div class="card-body">
                <h6 class="card-title text-primary">@lang('english.mark_entry')({{ $subs[0]->subject_name->name }})({{ ucwords($subs[0]->teacher->first_name.'  ').$subs[0]->teacher->last_name }})</h6>
                <hr>
                <div class="table-responsive">
                    {!! Form::hidden("campus_id", $campus_id) !!}
                    {!! Form::hidden("class_id", $class_id) !!}
                    {!! Form::hidden("class_section_id", $class_section_id) !!}
                    {!! Form::hidden("session_id", $session_id) !!}
                    {!! Form::hidden("exam_create_id", $exam_create_id) !!}
                    {!! Form::hidden("subject_id", $subject_id) !!}

                    <table style="zoom:80%" id="pos_table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th rowspan="2">#</th>
                                <th rowspan="2">@lang('english.roll_no')</th>
                                <th rowspan="2">@lang('english.name')</th>
                                <th rowspan="2">@lang('english.photo')</th>
                                <th rowspan="2">@lang('english.absent')</th>
                                @if($subs[0]->theory_mark>0)
                                <th colspan="2">@lang('english.written')</th>
                                @endif
                                @if($subs[0]->parc_mark>0)
                                <th colspan="2">@lang('english.viva')</th>
                                @endif
                                <th colspan="2">@lang('english.total')</th>
                                <th rowspan="2">@lang('english.exam_grade')</th>
                                <th rowspan="2">@lang('english.remark')</th>
                            </tr>
                            <tr>
                                @if($subs[0]->theory_mark>0)

                                <th>@lang('english.mark')</th>
                                <th>@lang('english.obtain')</th>
                                @endif
                                @if($subs[0]->parc_mark>0)

                                <th>@lang('english.mark')</th>
                                <th>@lang('english.obtain')</th>
                                @endif
                                <th>@lang('english.mark')</th>
                                <th>@lang('english.obtain')</th>

                            </tr>
                        </thead>
                        <tbody id="fn_mark">

                            @foreach ($subs as $data)

                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->student->roll_no }}</td>
                                <td>{{ ucwords($data->student->first_name.'  ').$data->student->last_name }}</td>

                                <td>
                                    <img src="{{ url('uploads/student_image/' . $data->student->student_image) }}" alt="" width="40" height="40">
                                </td>
                                <td>
                                    {!! Form::checkbox('marks[' . $loop->iteration . '][is_absent]', 1, $data->is_attend, ['class' => 'form-check-input mt-2 fee-head-check']) !!} </td>
                                {!! Form::hidden("marks[$loop->iteration][subject_result_id]", $data->id); !!}

                                </td>
                                @if($data->theory_mark>0)

                                <td>
                                    <input type="number" value="{{$data->theory_mark}}" readonly name="marks[{{$loop->iteration}}][theory_mark]" class="form-control  col-md-7 col-xs-12 fn_mark_total">
                                </td>
                                <td>
                                    <input type="text" name="marks[{{$loop->iteration}}][obtain_theory_mark]" class="form-control obtain_theory_mark tabkey addtabkey input_number" value="{{@num_format($data->obtain_theory_mark)}}" data-rule-max-value="{{$data->theory_mark}}" data-msg-max-value="{{__('english.minimum_selling_price_error_msg', ['price' => @num_format($data->theory_mark)])}}">
                                </td>
                                @endif
                                @if($data->parc_mark>0)

                                <td>
                                    <input type="number" value="{{$data->parc_mark}}" readonly name="marks[{{$loop->iteration}}][parc_mark]" class="form-control  col-md-7 col-xs-12 fn_mark_total">
                                </td>
                                <td>
                                    <input type="text" name="marks[{{$loop->iteration}}][obtain_parc_mark]" class="form-control obtain_parc_mark tabkey addtabkey input_number" value="{{@num_format($data->obtain_parc_mark)}}" data-rule-max-value="{{$data->parc_mark}}" data-msg-max-value="{{__('english.minimum_selling_price_error_msg', ['price' => @num_format($data->parc_mark)])}}">
                                </td>
                                @endif
                                <td>
                                    <input type="number" value="{{$data->total_mark}}" readonly name="marks[{{$loop->iteration}}][total_mark]" class="form-control total_mark  col-md-7 col-xs-12">
                                </td>
                                <td>
                                    <input type="number" value="{{$data->total_obtain_mark}}" readonly name="marks[{{$loop->iteration}}][obtain_total_mark]" class="form-control  total_obtain_mark col-md-7 col-xs-12">
                                </td>

                                <td>
                                    <select name="marks[{{$loop->iteration}}][grade_id]" class="form-control input-sm gsms-nice-select_">
                                        <option value="">--Select--</option>

                                        @foreach($grades as $key => $value)

                                        <option value="{{$key}}" @if(!empty($data->grade_id) && $data->grade_id == $key) selected @endif>
                                            {{$value}}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" value="{{  $data->remark ?? null }}" name="marks[{{$loop->iteration}}][remark]" readonly class="form-control  col-md-7 col-xs-12 remark">
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    @if ($subs->count() > 0)
                    <div class="d-lg-flex align-items-center mt-4 gap-3">
                        <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0 tabkey" type="submit">
                                @lang('english.submit')</button></div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="instructions"><strong>@lang('english.instruction'): </strong>@lang('english.mark_entry_warning')</div>
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
        $(document).on('keyup', '.obtain_theory_mark,.obtain_parc_mark', function() {
            var row = $(this).closest('tr');
            var obtain_theory_mark = __read_number(row.find('input.obtain_theory_mark'));
            var obtain_parc_mark = __read_number(row.find('input.obtain_parc_mark'));
            var total_mark = __read_number(row.find('input.total_mark'));

            var line_total = obtain_theory_mark + obtain_parc_mark;
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
                    $(this).find('input.addtabkey ').attr('disabled', 'disabled');
                    $(this).find('input').removeClass("tabkey");
                } else {
                    $(this).find('input.addtabkey ').removeAttr('disabled');
                    $(this).find('input.addtabkey').addClass("tabkey");
                }
            });


        });

        $(document).on('change', '.teacher-campuses', function() {
            var campus_id = $(this).closest(".row").find(".teacher-campuses").val();
            $.ajax({
                method: "GET"
                , url: "/get_teacher_campus_classes"
                , dataType: "html"
                , data: {
                    campus_id: campus_id
                , }
                , success: function(result) {
                    if (result) {
                        $(".teacher-classes").html(result);
                    }
                }
            , });

        });

        $(document).on('change', '.teacher-classes', function() {
            var class_id = $(this).closest(".row").find(".teacher-classes").val();
            var campus_id = $(this).closest(".row").find(".teacher-campuses").val();

            $.ajax({
                method: "GET"
                , url: "/get_teacher_class_section"
                , dataType: "html"
                , data: {
                    class_id: class_id
                    , campus_id: campus_id,

                }
                , success: function(result) {
                    if (result) {
                        $(".teacher-class_sections").html(result);
                    }
                }
            , });
        });
        $(document).on('change', '.teacher-exam-session', function() {
            var campus_id = $(this).closest(".row").find(".teacher-campuses").val();
            var session_id = $(this).closest(".row").find(".teacher-exam-session").val();
            $.ajax({
                method: "GET"
                , url: "/exam/get_term"
                , dataType: "html"
                , data: {
                    campus_id: campus_id
                    , session_id: session_id
                }
                , success: function(result) {
                    if (result) {
                        $(".teacher-exam_term_id").html(result);
                    }
                }
            , });
        });
        $(document).on('change', '.teacher-class_sections', function() {
            var class_id = $(".teacher-classes").val();
            var campus_id = $(".teacher-campuses").val();
            var class_section_id = $(".teacher-class_sections").val();

            $.ajax({
                method: "GET"
                , url: "/get-teacher-subjects"
                , dataType: "html"
                , data: {
                    class_id: class_id
                    , class_section_id: class_section_id
                    , campus_id: campus_id
                , }
                , success: function(result) {
                    if (result) {
                        $(".teacher-section-subjects").html(result);
                    }
                }
            , });
        });

    });

</script>
@endsection

