
@extends("frontend.layouts.app")
@section('wrapper')
    <!--====== SEARCH BOX PART START ======-->

    <!--====== SEARCH BOX PART ENDS ======-->

    <!--====== SLIDER PART START ======-->

    <section id="about-page">
        <div class="container">

            <div class="row">

                <div class="col-lg-8">

                    <div class="about-image mt-0">
                        <section id="slider-part" class="slider-active">
                                @foreach ( $slider as  $slide)
         <div class="single-slider bg_cover pt-165" style="{{'background-image:url(../uploads/front_image/'.$slide->slider_image.')'}}">

                 <div class="content-box" style="position: absolute;  bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        padding: 20px;
        background-color: rgba(255, 255, 255, 0.8);
        text-align: center;
        width: 80%; /* Adjust the width as needed */
        border-radius: 8px;">
                     <h1>{{ $slide->title }}</h1>
                      <h3>{{ $slide->description }}</h3>
                     @if(!empty($slide->btn_name))
                     <div class="btn-box"><a href="{{$slide->btn_url  }}" target="_blank" class="theme-btn">{{ $slide->btn_name }}</a></div>
                     @endif 
                 </div> 
         </div>
          @endforeach
                           

                        </section>

                    </div>


                </div>
                <div class="col-lg-4" align="center">
                    <div class="section-title">
						  <div class="card" align="center">
					    <div class="about-event mt-0">

                           @include('frontend.facebookpage')
						  </div> </div></div>

                </div> <!-- section title -->

            </div> <!-- about cont -->
        </div> <!-- row -->

    </section>


    <!--====== SLIDER PART ENDS ======-->

    <!--====== CATEGORY PART START ======-->

    <!--====== CATEGORY PART ENDS ======-->

    <!--====== ABOUT PART START ======-->

    <section id="about-part1" class="pt-45">

        {{-- <div class="container">
            <div class="row">

                <div class="col-lg-12">
                  

                    <div class="section-title mt-10">

                        <h5>About us</h5>
                        <h2 style="font-size: 30px">Welcome to {{ session()->get("front_details.school_name") }} </h2>
                    </div>
                    <div class="about-cont">
                        <p align="justify">{{ session()->get("front_details.school_name") }} (INC) is located in the country’s capital,
                            Islamabad at the foothills of the scenic Margalla hills. The INC provides a nurturing and an
                            innovative learning environment that promotes caring, competence, and professionalism. Our
                            highly qualified and energetic faculty members form the academic life line of this College
                            and you will get the full support and facilitation by our faculty members and support staff.
                            The huge infrastructure and well-equipped academic facilities make a suitable environment
                            for you to learn and grow professionally. <br>
                            INC is recognized by Pakistan Nursing Council (No. PNC F-7-181-Admin/2016-2946) and
                            affiliated with Shaheed Zulfiqar Ali Bhutto Medical University (SZABMU).<br>
                            <b>Serving with care and compassion</b> is the slogan of the college, which is reflected in
                            the services of the college.
                        </p>




                    </div>
                </div>


            </div>
        </div> --}}
        
@include('frontend.notification')
@include('frontend.about_us_slider')
@include('frontend.topper')
<!-- portfolio-section -->
@include('frontend.event.news_event')
<!-- start count stats -->
@include('frontend.count_home')
<!-- end cont stats -->
@include('frontend.gallery') 

    </section>
    <section style="background-color: var(--primaryColor);" id="page-banner" class="bg_cover pb-20 pt-20">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-banner-cont">
                        <h2 style="font-size: 32px; padding-top:30px;" align="center">Affiliated with</h2>
                        <p align="center" style="color: var(--hoverColor); font-size: 25px;">Shaheed Zulfiqar Ali Bhutto Medical
                            University</p><br>
                        <div align="center" class="button">
                            <a href="http://www.szabmu.edu.pk/" target="_blank" class="main-btn">SZABMU</a><br><br>
                        </div>
                        <!-- <nav aria-label="breadcrumb">
                            <ol  class="breadcrumb">
                               
                                <li style="font-size: 25px" class="breadcrumb-item active" aria-current="page">Shaheed Zulfiqar Ali Bhutto Medical University</li>
                            </ol>
                        </nav>-->
                    </div> <!-- page banner cont -->
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </section>
    <!--====== ABOUT PART ENDS ======-->

    <!--====== APPLY PART START ======-->

    <!-- <section id="apply-aprt" class="pb-120">
        <div class="container">
            <div class="apply">
                <div class="row no-gutters">
                    <div class="col-lg-6">
                        <div class="apply-cont apply-color-1">
                            <h3>Apply for fall 2019</h3>
                            <p>Gravida nibh vel velit auctor aliquetn sollicitudirem sem quibibendum auci elit cons equat ipsutis sem nibh id elituis sed odio sit amet nibh vulputate cursus equat ipsutis.</p>
                            <a href="#" class="main-btn">Apply Now</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="apply-cont apply-color-2">
                            <h3>Apply for scholarship</h3>
                            <p>Gravida nibh vel velit auctor aliquetn sollicitudirem sem quibibendum auci elit cons equat ipsutis sem nibh id elituis sed odio sit amet nibh vulputate cursus equat ipsutis.</p>
                            <a href="#" class="main-btn">Apply Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <!--====== APPLY PART ENDS ======-->
  
    <hr>
 


    <!--====== COURSE PART ENDS ======-->

    <!--====== VIDEO FEATURE PART START ======-->


    <!--====== VIDEO FEATURE PART ENDS ======-->

    <!--====== TEACHERS PART START ======-->

    <!--  <section id="teachers-part" class="pt-70 pb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="section-title mt-50">
                        <h5>Featured Teachers</h5>
                        <h2>Meet Our teachers</h2>
                    </div>
                    <div class="teachers-cont">
                        <p>Lorem ipsum gravida nibh vel velit auctor aliquetn sollicitudirem quibibendum auci elit cons equat ipsutis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet . Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt  mauris. <br> <br> auci elit cons equat ipsutis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet . Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt  mauris</p>
                        <a href="#" class="main-btn mt-55">Career with us</a>
                    </div>
                </div>
                <div class="col-lg-6 offset-lg-1">
                    <div class="teachers mt-20">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="singel-teachers mt-30 text-center">
                                    <div class="image">
                                        <img src="images/teachers/t-1.jpg" alt="Teachers">
                                    </div>
                                    <div class="cont">
                                        <a href="teachers-singel.html"><h6>Mark alen</h6></a>
                                        <span>Vice chencelor</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="singel-teachers mt-30 text-center">
                                    <div class="image">
                                        <img src="images/teachers/t-2.jpg" alt="Teachers">
                                    </div>
                                    <div class="cont">
                                        <a href="teachers-singel.html"><h6>David card</h6></a>
                                        <span>Pro chencelor</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="singel-teachers mt-30 text-center">
                                    <div class="image">
                                        <img src="images/teachers/t-3.jpg" alt="Teachers">
                                    </div>
                                    <div class="cont">
                                        <a href="teachers-singel.html"><h6>Rebeka alig</h6></a>
                                        <span>Pro chencelor</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="singel-teachers mt-30 text-center">
                                    <div class="image">
                                        <img src="images/teachers/t-4.jpg" alt="Teachers">
                                    </div>
                                    <div class="cont">
                                        <a href="teachers-singel.html"><h6>Hanna bein</h6></a>
                                        <span>Aerobics head</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>-->

    <!--====== TEACHERS PART ENDS ======-->

    <div id="patnar-logo" class="pb-40 pt-40">
        <div class="container">
            <div class="row">
                <div class="col-lg-12" align="center">
                    <a href="index.html" target="_blank"> <img src="{{ url('front/images/patnar-logo/p-8.png') }}"
                            alt="Teachers" style="padding-right: 25px"></a>
                    <a href="https://www.anth.pk/" target="_blank"> <img
                            src="{{ url('front/images/patnar-logo/p-1.png') }}" alt="Teachers"
                            style="padding-right: 25px"></a>
                    <a href="https://iideas.edu.pk/main/" target="_blank"><img
                            src="{{ url('front/images/patnar-logo/p-2.png') }}" alt="Teachers"
                            style="padding-right: 25px"></a>

                    <img src="{{ url('front/images/patnar-logo/p-4.png') }}" alt="Teachers"
                        style="padding-right: 25px">
                    <img src="{{ url('front/images/patnar-logo/p-5.png') }}" alt="Teachers"
                        style="padding-right: 25px">


                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </div>




   

    <!--====== NEWS PART START ======-->

    <!--  <section id="news-part" class="pt-115 pb-110">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="section-title pb-50">
                        <h5>Latest News</h5>
                        <h2>From the news</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="singel-news mt-30">
                        <div class="news-thum pb-25">
                            <img src="images/news/n-1.jpg" alt="News">
                        </div>
                        <div class="news-cont">
                            <ul>
                                <li><a href="#"><i class="fa fa-calendar"></i>2 December 2018 </a></li>
                                <li><a href="#"> <span>By</span> Adam linn</a></li>
                            </ul>
                            <a href="blog-singel.html"><h3>Tips to grade high cgpa in university life</h3></a>
                            <p>Lorem ipsum gravida nibh vel velit auctor aliquetn sollicitudirem quibibendum auci elit cons equat ipsutis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt .</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="singel-news news-list">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="news-thum mt-30">
                                    <img src="images/news/ns-1.jpg" alt="News">
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="news-cont mt-30">
                                    <ul>
                                        <li><a href="#"><i class="fa fa-calendar"></i>2 December 2018 </a></li>
                                        <li><a href="#"> <span>By</span> Adam linn</a></li>
                                    </ul>
                                    <a href="blog-singel.html"><h3>Intellectual communication</h3></a>
                                    <p>Gravida nibh vel velit auctor aliquetn sollicitudirem quibibendum auci elit cons  vel.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="singel-news news-list">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="news-thum mt-30">
                                    <img src="images/news/ns-2.jpg" alt="News">
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="news-cont mt-30">
                                    <ul>
                                        <li><a href="#"><i class="fa fa-calendar"></i>2 December 2018 </a></li>
                                        <li><a href="#"> <span>By</span> Adam linn</a></li>
                                    </ul>
                                    <a href="blog-singel.html"><h3>Study makes you perfect</h3></a>
                                    <p>Gravida nibh vel velit auctor aliquetn sollicitudirem quibibendum auci elit cons  vel.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="singel-news news-list">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="news-thum mt-30">
                                    <img src="images/news/ns-3.jpg" alt="News">
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="news-cont mt-30">
                                    <ul>
                                        <li><a href="#"><i class="fa fa-calendar"></i>2 December 2018 </a></li>
                                        <li><a href="#"> <span>By</span> Adam linn</a></li>
                                    </ul>
                                    <a href="blog-singel.html"><h3>Technology edcution is now....</h3></a>
                                    <p>Gravida nibh vel velit auctor aliquetn sollicitudirem quibibendum auci elit cons  vel.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>->
    
    <!--====== NEWS PART ENDS ======-->

@endsection
