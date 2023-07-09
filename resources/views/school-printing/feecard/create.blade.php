 @extends("admin_layouts.app")
@section('title', __('english.roles'))
 @section('wrapper')
     <div class="page-wrapper">
         <div class="page-content">
             <!--breadcrumb-->
             <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                 <div class="breadcrumb-title pe-3">@lang('english.fee_card_printing')</div>
                 <div class="ps-3">
                     <nav aria-label="breadcrumb">
                         <ol class="breadcrumb mb-0 p-0">
                             <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                             </li>
                             <li class="breadcrumb-item active" aria-current="page">@lang('english.fee_card_printing')</li>
                         </ol>
                     </nav>
                 </div>
             </div>
             <!--end breadcrumb-->
             <div class="card">

                 <div class="card-body">
                     <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                       <hr>
                     
                     <div class="row m-0">
                         <div class="col-md-3 p-2 ">
                             {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                             {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select select2 global-campuses', 'required', 'id' => 'students_list_filter_campus_id', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all')]) !!}
                         </div>
                         <div class="col-md-3 p-2">
                             {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                             {!! Form::select('class_id', [], null, ['class' => 'form-select select2 global-classes', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all'), 'id' => 'students_list_filter_class_id']) !!}
                         </div>
                         <div class="col-md-3 p-2">
                             {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                             {!! Form::select('class_section_id', [], null, ['class' => 'form-select select2 global-class_sections', 'id' => 'students_list_filter_class_section_id', 'style' => 'width:100%', 'placeholder' => __('english.all')]) !!}
                         </div>
                           <div class="col-md-3 p-2">
                             {!! Form::label('month_year', __('english.month_year') . ':*') !!}
                             <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i
                                         class="fa fa-calendar"></i></span>

                                 {!! Form::text('month_year', null, ['class' => 'form-control', 'placeholder' => __('english.month_year'), 'required', 'readonly']) !!}

                             </div>
                         </div>
                         <div class="col-md-4 mt-4 ">
                            {!! Form::checkbox('only_unpaid', 0, null ,[ 'class' => 'form-check-input big-checkbox' , 'id' => 'only_unpaid'] ); !!}
                         {{ Form::label('only_unpaid', __('english.only_unpaid') , ['class' => 'control-label mt-2 ']) }}
 
                     </div>
                          <div class="col-md-4 mt-4 ">
                            {!! Form::checkbox('only_transport', 0, null ,[ 'class' => 'form-check-input big-checkbox' , 'id' => 'only_transport'] ); !!}
                         {{ Form::label('only_transport', __('english.only_transport') , ['class' => 'control-label mt-2 ']) }}
 
                     </div>
                         {{-- <div class="col-md-3 p-2">
                             {!! Form::label('english.fee_month', __('english.fee_month') . ':*') !!}
                             {!! Form::select('month_id',__('english.months'),$now_month, ['class' => 'form-select select2 month_id', 'required', 'style' => 'width:100%']) !!}
                         </div> --}}
                     </div>
                      <div class="d-lg-flex align-items-center mt-4 gap-3">
                         <div class="ms-auto"><a class="btn btn-primary radius-30 mt-2 mt-lg-0 multi-print-invoice"
                                 type="button">
                                 <i class="lni lni-printer"></i>@lang('english.print')</a></div>
                     </div>
                 </div>
             </div>





         </div>
     </div>
   
 @endsection

 @section('javascript')

     <script type="text/javascript">
         $(document).ready(function() {

 $('#month_year').datepicker({
                 autoclose: true,
                 format: 'mm/yyyy',
                 minViewMode: "months"
             });


         });
     </script>
 @endsection
