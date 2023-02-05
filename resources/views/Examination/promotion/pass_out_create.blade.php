@extends("admin_layouts.app")
@section('title', __('english.roles'))
@section('wrapper')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">@lang('english.students_pass_out')</div>
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
            {!! Form::open(['url' => action('Examination\PromotionController@passOutPost'), 'method' => 'post', 'id' =>'promotion-list-form']) !!}

            <div class="card">

                <div class="card-body">
                    <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                      <hr>
                    <div class="row m-0">
                        <div class="col-md-3 p-2 ">
                            {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                            {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select select2 global-campuses', 'required',  'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                        </div>
                        <div class="col-md-3 p-2">
                            {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                            {!! Form::select('class_id', [], null, ['class' => 'form-select select2 global-classes', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                        </div>
                        <div class="col-md-3 p-2">
                            {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                            {!! Form::select('class_section_id', [], null, ['class' => 'form-select select2 global-class_sections' ,'required', 'id' => 'students_list_filter_class_section_id', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                        </div>
                        
                  
                    </div>
                    <div class="d-lg-flex align-items-center mt-4 gap-3">
                         <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0"
                                 type="submit">
                                 <i class="fas fa-filter"></i>@lang('english.filter')</button></div>
                    </div>
                </div>
            </div>

            {{ Form::close() }}




        </div>
    </div>
  
@endsection

@section('javascript')

    <script type="text/javascript">
        $(document).ready(function() {
        $('#promotion-list-form').validate({
            rules: {
                'campus_id': {
                    required: true
                },
                'class_id': {
                    required: true
                },
                'class_section_id': {
                    required: true
                }
            }
        });


        });
    </script>
@endsection
