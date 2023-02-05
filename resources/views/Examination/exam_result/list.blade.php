@extends("admin_layouts.app")
@section('title', __('english.manage_exam_term_results'))
@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.manage_exam_term_results')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.manage_exam_term_results')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        {{-- {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'fees-assign-search', 'method' => 'POST', 'enctype' => 'multipart/form-student', 'id' => 'search_studentA']) }} --}}
        {!! Form::open(['url' => action('Examination\ExamResultController@store'), 'method' => 'post', 'class'=>'needs-validation was-validated','novalidate'.'id' =>'search_student_fee' ,'files' => true]) !!}

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
                        {!! Form::label('term', __( 'english.term' ) . ':*') !!}
                        {!! Form::select('exam_create_id',$terms,$exam_create_id, ['class' => 'form-select select2 exam_term_id', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>

                </div>
                <div class="d-lg-flex align-items-center mt-4 gap-3">
                    <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                            <i class="fas fa-filter"></i>@lang('english.filter')</button></div>
                </div>
            </div>
        </div>


        {{ Form::close() }}


        @if (isset($students))


        <div class="card">

            <div class="card-body">
                <h6 class="card-title text-primary">@lang('english.manage_exam_term_results')</h6>
                <hr>
                <div class="table-responsive">


                    <table style="zoom:90%" id="pos_table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('english.action')</th>
                                <th>@lang('english.roll_no')</th>
                                <th>@lang('english.name')</th>
                                <th>@lang('english.photo')</th>
                                <th>@lang('english.total') @lang('english.exam_mark')</th>
                                <th>@lang('english.total') @lang('english.obtained_mark')</th>
                                <th>@lang('english.percentage')</th>
                                <th>@lang('english.grade')</th>
                                <th>@lang('english.remarks')</th>
                                <th>@lang('english.position_in_class')</th>
                                <th>@lang('english.position_in_section')</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ( $students as $student )
                            <tr>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                                        <ul class="dropdown-menu" style="">
                                            <li><a class="dropdown-item  print-invoice" data-href="{{ action('Examination\ExamResultController@show', [$student->id]) }}><i class="bx bx-print "></i>Print Mark Sheet</a></li>

                                        </ul>
                                    </div>
                                </td>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $student->student->roll_no }}</td>
                                <td>{{ ucwords($student->student->first_name.'  ').$student->student->last_name }}</td>

                                <td>
                                    <img src="{{ url('uploads/student_image/' . $student->student->student_image) }}" alt="" width="40" height="40">
                                </td>
                                <td>
                                    {{ @num_format($student->total_mark) }}
                                </td>
                                <td>
                                    {{ @num_format($student->obtain_mark) }}
                                </td>

                                <td>
                                    {{ @num_format($student->final_percentage) }}%
                                </td>
                                <td>
                                    @if(!empty($student->grade))
                                    {{ ucwords($student->grade->name) }}
                                    @endif
                                </td>
                                <td>
                                    {{ ucwords($student->remark) }}
                                </td>
                                <td>
                                    {{ ucwords($student->class_position) }}
                                </td>
                                <td>
                                    {{ ucwords($student->class_section_position) }}
                                </td>
                               
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                    @if ($students->count() > 0)
                    @endif
                </div>


            </div>
        </div>

        @endif

    </div>
</div>
@endsection

@section('javascript')

<script type="text/javascript">
    $(document).ready(function() {


    });

</script>
@endsection

