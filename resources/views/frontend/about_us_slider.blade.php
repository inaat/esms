 <section class="about-section">
     <div class="anim-icon">
         <div class="icon icon-1 float-bob-x"></div>
         <div class="icon icon-2 wow zoomIn animated" data-wow-delay="00ms" data-wow-duration="1500ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 0ms; animation-name: zoomIn;"></div>
     </div>
     <section class="massage">
         <div class="container">
             <div class="row blog">
                 <div class="col-md-12">
                     <div id="newblogCarousel" class="carousel slide" data-ride="carousel">
                         <div class="carousel-inner">
                      
                             @foreach ($about_us as  $about)
                             <div class="carousel-item  @if($loop->iteration==1) active @endif">
                                 <div class="row">
                                     <div class="col-lg-4 col-md-12 col-sm-12 image-column">
                                         <div class="image-box wow fadeInLeft animated" data-wow-delay="0ms" data-wow-duration="1500ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 0ms; animation-name: fadeInLeft;">
                                             <figure class="image image-2">
                                                 <img src="{{url('uploads/front_image/'.$about->image)}}" alt="about">
                                             </figure>
                                         </div>
                                     </div>
                                     <div class="col-lg-8 col-md-12 col-sm-12 content-column">
                                         <div class="content-box">
                                             <div class="text">
                                                 <div class="title-box">
                                                     <div class="sec-title">
                                                         <h5>{{ $about->home_title }}</h5>
                                                     </div>
                                                     <div class="text">
                                                      <p style="text-align: justify;">{!! $about->description !!}</p>
                                                     </div>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                                @endforeach
                          
                             <div class="tab-con">
                               @foreach ($about_us as  $about)
                                 <div data-target="#newblogCarousel" data-slide-to="{{ $loop->iteration-1 }}" class="msg-tab">
                                     <a href="#">{{  $about->title }}</a>
                                 </div>
                                 @endforeach

                             </div>

                         </div>
                         <!--.carousel-inner-->
                     </div>
                 </div>
             </div>
         </div>
     </section>
 </section>