@extends("admin_layouts.app")
@section('title', __('english.dashboard'))
@section("wrapper")
@section("style")
<style>


.col-lg-2, .col-md-3, .col-xs-6{
    margin-top: 30px !important;
}
.box{
  background: #EBA9A5;
}
.grade {
    background: url(https://gradewise.pustakalaya.org/static/media/bg.5d24c4e2.svg);
    background-size: 113px;
    position: relative;
    min-height: 90vh;
}
.css-hzx1rw-Grades {
    height: 75%;
    margin: 0 auto;
    position: fixed;
}
</style>
@endsection
<div class="page-wrapper">
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title text-primary bold">@lang('english.my_library')</h3>
                <hr>
              
                <div class="row m-0 grade" style="zoom:90%">
                  <div class=" page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                    <a href="{{ action('Curriculum\MyLibraryController@index') }}" class="breadcrumb-title btn pe-3 bg-warning text-white">Grade</a>
                    {{-- <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="http://localhost/esms/public/home "><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Student Admission Form</li>
                            </ol>
                        </nav> -
                    </div> --}}
                </div>
                  <div class="col-md-3  ">
                    <img height="60%" class="css-hzx1rw-Grades" src="https://gradewise.pustakalaya.org/assets/graphics/character/Grade.svg" alt="">
                  </div>
                  <div class="col-md-9 ">
                    <div class="row m-0">

                     @foreach ($classes as $cl) 
                    <div class="col-lg-3 col-md-3 col-xs-6 ">
                         <a class="" href="{{ action('Curriculum\MyLibraryController@getSubjectsByClass',[$cl->id]) }}"> 

                            <div class="card  box"  style="background: #EBA9A5; width:150px; height:150px; margin:0 auto;" >
                                <div class="card-body d-flex align-items-center justify-content-center">
                                     <h3 class="card-title text-center text-white">{{ $cl->title}}</h3>
                                  
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                    </div>
                </div>
                </div>
            </div>
        </div>
           
    </div>
</div>
@endsection

@section('javascript')

@endsection
