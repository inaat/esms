 <section class="main-slider style-three">
     <div class="main-slider-carousel owl-carousel owl-theme nav-style-two">
        @foreach ( $slider as  $slide)
         <div class="slide" style="{{'background-image:url(../uploads/front_image/'.$slide->slider_image.')'}}">
             <div class="container">
                 <div class="content-box">
                     <h1>{{ $slide->title }}</h1>
                     <h3>{{ $slide->description }}</h3>
                     @if(!empty($slide->btn_name))
                     <div class="btn-box"><a href="{{$slide->btn_url  }}" target="_blank" class="theme-btn">{{ $slide->btn_name }}</a></div>
                     @endif
                 </div>
             </div>
         </div>
          @endforeach
     </div>
 </section>