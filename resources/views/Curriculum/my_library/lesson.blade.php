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
                                <li class="breadcrumb-title btn pe-3 bg-warning text-white" aria-current="page"><a href="{{ action('Curriculum\MyLibraryController@getSubjectsByClass',[$subject->classes->id]) }}"> Subjects</a></li>
                            </ol>
                        </nav> 
                    </div> 
                </div>
                <div class="col-md-9 ">
                    <div class="row m-0">
                        <div class="flex title uppercase"><h3 class="css-1e7ec0x-Subject">SELECT CHAPTER
                            ({{ $subject->classes->title }} > {{ $subject->name }} ) > {{$chapter->chapter_name}}</h3></div>
                            <div class=" btn pe-3 bg-primary text-white book-view">Chapter View</div>
                            <div class="hide bookdiv-view">
                                <iframe src="{{ url('uploads/subjects/' . $chapter->chapter_pdf)}}"  frameborder="0" scrolling="no"width="100%" height="500px" >
                                </iframe>
                            </div>
                            <div class="" style="margin-top:20px;">
                                <h5 class=" btn pe-3 bg-primary text-white btn pe-3  text-white">@lang('english.all_lessons')</h5>
        
                            </div>
                     @foreach ($lessons as $lesson) 
                    <div class="col-lg-3 col-md-3 col-xs-6 ">
                         <a class=" " >
                                     <h3 class="card-title text-center bg-primary  text-white">
                                        {{ $lesson->name}}</h3>
                        </a>
                    </div>
                    @endforeach

                    <div class="" style="margin-top:70px;">
                        <h5 class=" btn pe-3 bg-primary text-white btn pe-3  text-white">@lang('english.all_videos')</h5>

                    </div>
                    
                    <!--Grid column-->
                    @foreach ($chapter->video_link as $v)  
                    <div class="col-lg-6 col-md-6 col-xs-6 ">
                        <iframe class="embed-responsive-item" src="{{'https://www.youtube.com/embed/'. $v.'?enablejsapi=1&amp;'}}"  allowfullscreen="" ></iframe>

                    </div>
                    @endforeach
                   
             
                    </div>
                </div>
                  <div class="col-md-3  ">
                    <img height="40%" class="css-hzx1rw-Grades" src="https://gradewise.pustakalaya.org/assets/graphics/character/Subject.svg" alt="">
                  </div>
                
                </div>
            </div>
            
        
        
        
        </div>
           
    </div>
</div>
@endsection

@section('javascript')
<script>
    $(".book-view").click(function(){
        var check=$(".bookdiv-view").is(':hidden') ? true : false;
        if(check==true){
            $(".bookdiv-view").removeClass("hide");


        }else{
            $(".bookdiv-view").addClass("hide");

        }

  });
</script>
@endsection
