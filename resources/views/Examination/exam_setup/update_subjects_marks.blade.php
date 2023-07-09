@extends("admin_layouts.app")
@section('title', __('english.update_subjects_marks'))
@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.update_subjects_marks')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.update_subjects_marks')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">

            {!! Form::open(['url' => action('Examination\ExamSetupController@updateSubjectsMarkPost'), 'method' => 'post', 'id' => 'store_student_fee' ]) !!}
            <div class="row">
                {!! Form::hidden("exam_create_id", $exam_create->id) !!}

                @foreach ( $class_wise_subjects as $class)

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title text-primary">{{ $class['class']->title }}</h6>
                            <hr>
                            <div class="table-responsive">

                                <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>@lang('english.subject_name')</th>
                                            <th>@lang('english.theory_mark')</th>
                                            <th>@lang('english.practical_mark')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($class['subjects'] as $sub)
                                        <tr>
                                            <td>
                                                {{ $sub->name }}
                                            </td>
                                            <td>
                                                <input type="hidden" value="{{ $class['class']->id }}" required name="subjects[{{$sub->id}}][class_id]" class="form-control ">
                                                <input type="hidden" value="{{ $sub->id }}" required name="subjects[{{$sub->id}}][subject_id]" class="form-control ">

                                                <input type="number" value="{{ $sub->theory_mark }}" required name="subjects[{{$sub->id}}][theory_mark]" class="form-control ">
                                            </td>
                                            <td>
                                                <input type="number" value="{{ $sub->parc_mark}}" required name="subjects[{{$sub->id}}][parc_mark]" class="form-control ">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                                <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <div class="d-lg-flex align-items-center mt-4 gap-3">
                            <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0 tabkey" type="submit">
                                    @lang('english.submit')</button></div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="instructions"><strong>@lang('english.instruction'): </strong>@lang('english.mark_entry_warning')</div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
    @endsection

