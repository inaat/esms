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

.css-pxgg1r {
    height: 100%;
    width: 100%;
    object-fit: scale-down;
    margin: 0 auto;
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
                    <div class="breadcrumb-title btn pe-3 bg-white text-black">< Grade</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-title btn pe-3 bg-warning text-white" aria-current="page"> Subjects</li>
                            </ol>
                        </nav> 
                    </div> 
                </div>
                <div class="col-md-9 ">
                    <div class="row m-0">
                        <div class="flex title uppercase"><h3 class="css-1e7ec0x-Subject">ME AND MY FAMILY</h3></div>
                     @foreach ($lessons as $lessson) 
                    <div class="col-lg-3 col-md-3 col-xs-6 ">
                         <a class="w-24 h-24 xl:w-32 xl:h-32 flex items-center justify-center " href="">
                                    <img class="css-pxgg1r box d-flex align-items-center justify-content-center"  style="background: #EBA9A5; margin:0 auto;" src="{{ url('uploads/subjects/' . $chap->chapter_icon)}}" alt="Maths ">

                                     <h3 class="card-title text-center ">
                                        <br>{{ $chap->chapter_name}}</h3>
                        </a>
                    </div>
                    @endforeach
                    </div>
                </div>
                  <div class="col-md-3  ">
                    <img height="60%" class="css-hzx1rw-Grades" src="https://gradewise.pustakalaya.org/assets/graphics/character/Subject.svg" alt="">
                  </div>
               
                </div>
            </div>
        </div>
           
    </div>
</div>
@endsection

@section('javascript')

@endsection
