 @extends("admin_layouts.app")
@section('title', __('english.import_students'))
 @section('wrapper')
 <div class="page-wrapper">
     <div class="page-content">
      <!--breadcrumb-->
         <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
             <div class="breadcrumb-title pe-3">@lang('english.student_file_to_import')</div>
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
             @if (session('notification') || !empty($notification))
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    @if(!empty($notification['msg']))
                        {{$notification['msg']}}
                    @elseif(session('notification.msg'))
                        {{ session('notification.msg') }}
                    @endif
                </div>
            </div>
        </div>
    @endif
       <div class="row">
        <div class="col-sm-12">
                {!! Form::open(['url' => action('ImportStudentsController@store'), 'method' => 'post', 'enctype' => 'multipart/form-data' ]) !!}
                {{-- {!! Form::open(['url' => action('ImportStudentsController@StudentImage'), 'method' => 'post', 'enctype' => 'multipart/form-data' ]) !!} --}}
                {{-- {!! Form::open(['url' => action('ImportStudentsController@employeeImport'), 'method' => 'post', 'enctype' => 'multipart/form-data' ]) !!} --}}
                    <div class="row">
                        <div class="col-sm-6">
                        <div class="col-sm-8">
                            <div class="form-group">
                                {!! Form::label('name', __( 'english.student_file_to_import' ) . ':') !!}
                                {!! Form::file('products_csv', ['accept'=> '.xls, .xlsx, .csv', 'required' => 'required']); !!}
                              </div>
                        </div>
                        <div class="col-sm-4">
                        <br>
                            <button type="submit" class="btn btn-primary">@lang('english.submit')</button>
                        </div>
                        </div>
                    </div>

                {!! Form::close() !!}
                <br><br>
                <div class="row">
                    <div class="col-sm-4">
                        <a href="{{ asset('files/import_products_csv_template.xls') }}" class="btn btn-success" download><i class="fa fa-download"></i> @lang('english.download_template_file')</a>
                    </div>
                </div>
        </div>
    </div>
     </div>
 </div>

 @endsection

 @section('javascript')

 <script type="text/javascript">
     $(document).ready(function() {


     });

 </script>
 @endsection
