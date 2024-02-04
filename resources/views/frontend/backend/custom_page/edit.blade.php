
 @extends("admin_layouts.app")
 @section('title', __('english.custom_page'))
 @section('title', __('english.custom_page'))
 
 @section("wrapper")
 <div class="page-wrapper">
     <div class="page-content">
         <!--breadcrumb-->
         <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
             <div class="breadcrumb-title pe-3">@lang('english.custom_page')</div>
             <div class="ps-3">
                 <nav aria-label="breadcrumb">
                     <ol class="breadcrumb mb-0 p-0">
                         <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                         </li>
                         <li class="breadcrumb-item active" aria-current="page">@lang('english.custom_page')</li>
                     </ol>
                 </nav>
             </div>
         </div>
         <!--end breadcrumb-->
 
         <div class="card">
             <div class="card-body">
                 {!! Form::open(['url' => action('Frontend\FrontCustomPageController@update',[$custom_page->id]), 'method' => 'PUT', 'id' =>'weekend_holiday_add_form' ]) !!}
 
                 <div class="row">
                    <div class="col-md-4 p-3">
                        {!! Form::label('name', __('english.title' ) . ':*') !!}
                        {!! Form::text('title',$custom_page->title, ['class' => 'form-control', 'required', 'placeholder' => __('english.title' )]) !!}
                    </div>
                      <div class="col-md-4 p-3">
				      {!! Form::label('front_page_navbar', __('english.front_page_navbar') . ':*') !!} 
				      {!! Form::select('front_page_navbar_id', $custom_navbars, $custom_page->front_page_navbar_id, ['class' => 'form-select', 'placeholder' => __('english.please_select'), 'required']) !!}

                     </div>
                         <div class="col-md-12 p-3">
                        {!! Form::label('description', __('english.description' ) . ':') !!}
                        {!! Form::textarea('description',$custom_page->description, ['class' => 'form-control', 'id'=>'description','placeholder' => __('english.description' )]) !!}
                    </div>
                   
                    
                 </div>
                 <div class="d-lg-flex align-items-center mt-4 gap-3">
                     <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                             @lang('english.update')</button></div>
                 </div>
                 {!! Form::close() !!}
             </div>
             <!--end row-->
         </div>
     </div>
 
     @endsection
     @section('javascript')
     <script type="text/javascript">
         //event table
         $(document).ready(function() {
             if ($('textarea#description').length > 0) {
             tinymce.init({
                 selector: 'textarea#description'
                 , height: "50vh"
             });
         }
 
 
         });
 
     </script>
     @endsection
 