@extends("admin_layouts.app")
@section('title', __('english.exam_setup'))
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.add_new_exam')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.add_new_exam')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        {!! Form::open(['url' => action('Examination\ExamSetupController@store'), 'method' => 'post', 'id' =>'weekend_holiday_add_form' ]) !!}

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                        {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select select2 global-campuses', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                        {!! Form::select('class_ids[]', [], null, ['class' => 'form-select select2 global-classes', 'multiple','style' => 'width:100%']) !!}
                    </div>
                    <div class="col-md-4 ">
                        {!! Form::label('term', __( 'english.term' ) . ':*') !!}
                        {!! Form::select('exam_term_id',$exam_terms,null, ['class' => 'form-select select2 ', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="clearfix"></div>

                    <div class="col-md-4 ">
                        {!! Form::label('roll_no_type', __( 'english.roll_no_type' ) . ':*') !!}
                        {!! Form::select('roll_no_type',['default_roll_no'=>'Default Roll No','custom_roll_no'=>'Custom Roll No'],'default_roll_no', ['class' => 'form-select select2 ', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>

                    <div class="col-md-4 ">
                        {!! Form::label('order_type', __( 'english.order_type' ) . ':*') !!}
                        {!! Form::select('order_type',['descending'=>'Descending', 'ascending'=>'Ascending'],null, ['class' => 'form-select select2 ', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-4 ">
                        {!! Form::label('english.start_from', __('english.start_from') . ':*') !!}
                        {!! Form::text('start_from', null, ['class' => 'form-control input_number',  'placeholder' => __('english.start_from')]) !!}
                    </div>
                    <div class="clearfix"></div>

                    <div class="col-md-4 ">
                        {!! Form::label('english.date_range', __('english.date_range') . ':') !!}
                        <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                            {!! Form::text('list_filter_date_range', null, ['placeholder' => __('english.select_a_date_range'), 'id' => 'list_filter_date_range', 'class' => 'form-control']) !!}

                        </div>
                    </div>
                    <div class="clearfix"></div>



                </div>
                 <div class="d-lg-flex align-items-center mt-4 gap-3">
        <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
               @lang('english.save')</button></div>
    </div>
            </div>


        </div>
    </div>
   
    {!! Form::close() !!}

</div>
</div>

@endsection


@section('javascript')
<script type="text/javascript">
    $(document).ready(function() {
     
    });

</script>
@endsection

