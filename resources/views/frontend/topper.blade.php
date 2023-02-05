
@if(!empty($topers))
 <section class="classes-section style-two style-three">
     <div class="parallax-scene parallax-scene-1 parallax-icon">
         <span data-depth="0.40" class="parallax-layer icon icon-1"></span>
         <span data-depth="0.50" class="parallax-layer icon icon-2"></span>
         <span data-depth="0.30" class="parallax-layer icon icon-3"></span>
         <span data-depth="0.40" class="parallax-layer icon icon-4"></span>
         <span data-depth="0.50" class="parallax-layer icon icon-5"></span>
         <span data-depth="0.30" class="parallax-layer icon icon-6"></span>
         <span data-depth="0.40" class="parallax-layer icon icon-7"></span>
     </div>
     <div class="anim-icon">
         <div class="icon icon-2 float-bob-y"></div>
     </div>
     <div class="container">
         <div class="row">
             <div class="col-lg-5 col-md-12 col-sm-12 inner-column">
                 <div class="inner-content">
                     <div class="sec-title style-two">
                         <h5>{{ $topers[0]['data']->session->title }}</h5>
                         <h1>Our Toppers</h1>
                     </div>
                     <div class="text">We have a history of Meritorious Students who bring honor to all of us. Here are some of our young achievers...</div>
                     <br>
                 </div>
             </div>
             <div class="col-lg-7 col-md-12 col-sm-12 carousel-column">
                 <div class="carousel-content">
                     <div class="classes-carousel owl-carousel owl-theme">

                         @foreach ($topers as $top)
                         <div class="inner-block wow fadeInUp" data-wow-delay="0ms" data-wow-duration="1500ms">
                             <figure class="image-box" style="width:170px; height:200px;    object-fit: fill;"><a href="#">
                                     <img src="{{url('uploads/student_image/' . $top['data']->student->student_image) }}" alt="" ></a>
                             </figure>
                             <div class="lower-content" style="zoom:60%">
                                 <h3><a href="#">{{ ucwords($top['data']->student->first_name . ' ' . $top['data']->student->last_name) }} </a></h3>
                                <h6>@lang('english.father_name') - {{ ucwords($top['data']->student->father_name)}}</h6>
                                 <div class="price">{{ ucwords($top['data']->campuses->campus_name) }}</div>
                                 <div class="price">Class - {{ ucwords($top['data']->current_class->title .'  '. $top['data']->current_class_section->section_name) }}</div>
                                 <ul class="info-box">
                                     <li>Rank: <span>{{ $top['rank'] }} </span></li>
                                     <li>Score: <span>{{ number_format($top['data']->final_percentage,2).'%' }}</span></li>
                                     <li>Marks: <span>{{ number_format($top['data']->obtain_mark,2).'/'.number_format($top['data']->total_mark,2) }}</span></li>
                                     <li>Session: <span>{{ $top['data']->session->title }}</span></li>
                                 </ul>
                             </div>
                         </div>
                        @endforeach
                     
                
                      
                   
                       

                     </div>
                 </div>
             </div>
         </div>
     </div>
 </section>
 @endif