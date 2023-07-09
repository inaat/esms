@extends("admin_layouts.app")
@section('title', __('english.roles'))
@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.certificate_issue')</div>
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
        {!! Form::open(['url' => action('Certificate\CertificateController@issuePost'), 'method' => 'post', 'class'=>'needs-validation was-validated','novalidate'.'id' =>'search_student_fee' ,'files' => true]) !!}

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">@lang('english.select_ground')</h5>
                    <small class="text-info font-13"></small>
                </h5>

                <div class="row">
                    <div class="col-md-3 p-1">
                        {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                        {!! Form::select('campus_id', $campuses, $campus_id ?? null, ['class' => 'form-select select2 global-campuses','required', 'id'=>'students_list_filter_campus_id','style' => 'width:100%', 'placeholder' => __('english.all')]) !!}
                    </div>

                    <div class="col-md-3 p-1">
                        {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                        {!! Form::select('adm_class_id', $classes ?? [], $class_id?? null, ['class' => 'form-select select2 global-classes', 'style' => 'width:100%', 'placeholder' => __('english.all'), 'id' => 'students_list_filter_class_id']) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('roll_no', __('english.roll_no')) !!}
                        {!! Form::text('roll_no', $roll_no??null, ['class' => 'form-control', 'placeholder' => __('english.roll_no'), 'id' => 'students_list_filter_roll_no']) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('certificate_type', __('english.certificate_types') . ':*') !!}
                        {!! Form::select('certificate_type_id', $certificate_type,$certificate_type_id??null, ['class' => 'form-select select2 ', 'required', 'id'=>'','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-4 p-1">
                       {!! Form::label('status', __('english.student_status') . ':*') !!}
                       {!! Form::select('status', __('english.std_status'),'active', ['class' => 'form-control','id'=>'students_list_filter_status','placeholder' => __('english.please_select'), 'required']); !!}
                   </div>
                    <div class="clear-fix"></div>
                    <div class="d-lg-flex align-items-center mt-4 gap-3">
                        <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                                <i class="fas fa-filter"></i>@lang('english.filter')</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->
    {{ Form::close() }}
    @if(isset($withdrawal_students))
    <div class="card">
        <div class="card-body">
            <h5 class="card-title text-primary">@lang('english.student_list')</h5>
            <hr>

            <div class="table-responsive">
                <table style="" class="table table-striped table-bordered " cellspacing="0" width="100%" id="withdrawal_register_table">
                    <thead class="table-light" width="100%">
                        <tr>
                            <th>@lang('english.name_of_student')</th>
                            <th>@lang('english.father_name')</th>
                            <th>@lang('english.roll_no')</th>
                            <th>@lang('english.class_from_which_withdrawn')</th>

                            <th>@lang('english.issue_date')</th>
                            <th>@lang('english.action')</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($withdrawal_students as $key => $dt)
                        @if(!empty($dt->student))
                        <tr>
                            <td>{{ ucwords($dt->student->first_name) .'  '.ucwords($dt->student->last_name) }}</td>
                            <td>{{ ucwords($dt->student->father_name) }}</td>
                            <td>{{ ucwords($dt->student->roll_no) }}</td>
                            <td>{{ ucwords($dt->leaving_class->title) }}</td>
                            <td>
                                {!! Form::open(['url' => action('Certificate\CertificateController@store'), 'method' => 'post','id' =>'issued' ,'files' => true]) !!}
                                <div class="col-md-12 p-1">
                                    <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                                        {!! Form::hidden("campus_id", $campus_id) !!}
                                        {!! Form::hidden("student_id", $dt->student_id) !!}
                                        {!! Form::hidden("certificate_type_id", $certificate_type_id) !!}
                                        {!! Form::hidden("class_id", $class_id?? null) !!}
                                        {!! Form::text('issue_date',@format_date($dt->slc_issue_date??'now'), ['class' => 'form-control date-picker', 'readonly', 'placeholder' => __('english.issue_date')]) !!}

                                    </div>
                                </div>
                            </td>
                            <td>
                               <button class="btn- btn btn-primary radius-30 mt-2 mt-lg-0">@if(!empty($dt->slc_issue_date))@lang('english.update')@else @lang('english.save')@endif</button>

                                {{ Form::close() }}
                            </td>
                        </tr>
                         @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @endif
    @if(isset($students))
    <div class="card">
        <div class="card-body">
            <h5 class="card-title text-primary">@lang('english.student_list')</h5>
            <hr>

            <div class="table-responsive">
                <table style="" class="table table-striped table-bordered " cellspacing="0" width="100%" id="withdrawal_register_table">
                    <thead class="table-light" width="100%">
                        <tr>
                            <th>@lang('english.name_of_student')</th>
                            <th>@lang('english.father_name')</th>
                            <th>@lang('english.roll_no')</th>
                            <th>@lang('english.class')</th>

                            <th>@lang('english.issue_date')</th>
                            <th>@lang('english.action')</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $key => $dt)
                        <tr>
                            <td>{{ ucwords($dt->first_name) .'  '.ucwords($dt->last_name) }}</td>
                            <td>{{ ucwords($dt->father_name) }}</td>
                            <td>{{ ucwords($dt->roll_no) }}</td>
                            <td>{{ ucwords($dt->current_class->title) }}</td>
                            <td>
                                {!! Form::open(['url' => action('Certificate\CertificateController@store'), 'method' => 'post','id' =>'issued' ,'files' => true]) !!}
                                <div class="col-md-12 p-1">
                                    <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                                        {!! Form::hidden("campus_id", $campus_id) !!}
                                        {!! Form::hidden("student_id", $dt->id) !!}
                                        {!! Form::hidden("certificate_type_id", $certificate_type_id) !!}
                                        {!! Form::hidden("class_id", $class_id?? null) !!}
                                        {!! Form::text('issue_date',@format_date('now'), ['class' => 'form-control date-picker', 'readonly', 'placeholder' => __('english.issue_date')]) !!}

                                    </div>
                                </div>
                            </td>
                            <td>
                               <button class="btn- btn btn-primary radius-30 mt-2 mt-lg-0">@lang('english.save')</button>

                                {{ Form::close() }}
                            </td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>
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

