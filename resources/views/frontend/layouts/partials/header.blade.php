 <div class=""></div>

 <div class="top-header">
     <div class="topscrollBox">
         <marquee onmouseover="stop();" onmouseout="start();" scrollamount="3">
             <a href="#"><span><strong>Notice :</strong> Admission for class XI 2022-23</span> </a>
         </marquee>
     </div>
 </div>
 <header class="main-header" style="background-color:#000; height: 152px;">
     <div class="header-bottom">
         <div class="container">
             <div class="clearfix mobile-tab">
                 <div class="logo-box pull-left">
                     <figure class="logo"><a href="url('/')">
                             <img  src="{{url('uploads/front_image/'.session()->get("front_details.logo_image"))}}"   alt="image"><br>
                             <p class="hjghjg centred">{{ session()->get("front_details.reg_no") }}</p>
                         </a></figure>
                 </div>
                 
                 <div class="topheading">
                     <h2>{{ session()->get("front_details.school_name") }}</h2>
                 </div>
                 <ul class="topcontact">
                     <li><a href="tel:0946-745746"><i class="fa fa-phone" aria-hidden="true"></i> : {{ session()->get("front_details.phone_no") }}</a></li>
                     <li><a href="mailto:info@swatcollegiate.edu.pk"><i class="fa fa-envelope" aria-hidden="true"></i> : {{ session()->get("front_details.email") }}</a></li>
                 </ul>
                 <div class="nav-outer pull-right clearfix">
                     <ul class="top-tab">
                         <li class="btn-box"><a class="theme-btn" href="#"></a></li>
                       
                     </ul>

                 </div>
             </div>
             <div class="menu-area">
                 <nav class="main-menu navbar-expand-lg">
                     <div class="navbar-header">
                         <!-- Toggle Button -->
                         <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                             <span class="icon-bar"></span>
                             <span class="icon-bar"></span>
                             <span class="icon-bar"></span>
                         </button>
                     </div>
                     <div class="navbar-collapse collapse clearfix">
                         <ul class="navigation clearfix">
                             <li>
                                 <a href="{{ url('/') }}">Home</a>
                                 <ul>
                                 </ul>
                             </li>
                              <li class="dropdown">
                                 <a href="{{ action('Frontend\FrontHomeController@about_index') }}">About</a>
                                 <ul>
                                  @php
                                    $about_nav =frontMenu();
                                  @endphp
                                  @foreach ($about_nav as  $nav)
                                     <li class="sub-dropdown"><a href="{{ action('Frontend\FrontHomeController@about_show', [$nav->slug,$nav->id]) }}">{{ $nav->title }}</a>
                                       
                                     </li>
                                       @endforeach
                                 </ul>
                             </li>
                             <li>
                                 <a href="{{ url('gallery') }}">Gallery</a>
                                 <ul>
                                 </ul>
                             </li>
                             <li class="dropdown">
                                <a href="{{ action('Frontend\FrontHomeController@event_index') }}">Event</a>
                                <ul>
                                 @php
                                   $event_nav =frontEventMenu();
                                 @endphp
                                 @foreach ($event_nav as  $nav)
                                    <li class="sub-dropdown"><a href="{{ action('Frontend\FrontHomeController@event_show', [$nav->slug,$nav->id]) }}">{{ $nav->title }}</a>
                                      
                                    </li>
                                      @endforeach
                                </ul>
                            </li>
                             <li>
                              @if (Auth::check())
                                 <a href="{{ url('/home') }}">Dashboard</a>
                                @else
                                 <a href="{{ url('/login') }}">Login</a>
                                @endif
                              
                                 
                             </li> 
                             {{-- <li class="dropdown">
                                 <a href="admissions.html">Admissions</a>
                                 <ul>
                                     <li class="sub-dropdown"><a href="admission-criteria.html">Admission Criteria</a>
                                     </li>
                                 </ul>
                             </li> --}}
                             {{-- <li>
                                 <a href="alumni.html">Alumni</a>
                                 <ul>
                                 </ul>
                             </li> --}}
                             {{-- <li>
                                 <a href="gallery.html">Gallery</a>
                                 <ul>
                                 </ul>
                             </li> --}}
                             {{-- <li>
                                 <a href="contact.html">Contact</a>
                                 <ul>
                                 </ul>
                             </li> --}}
                             {{-- <li>
                                 <a class="colorChange" href="../front/front_assets/images/admissionlist.pdf" target="_blank">Admissions List</a>
                             </li>  --}}
                         </ul>
                     </div>
                 </nav>
             </div>
         </div>
     </div>
     <!--Sticky Header-->
     <div class="sticky-header">
         <div class="container clearfix">
             <div class="menu-area">
                 <nav class="main-menu navbar-expand-lg">
                     <div class="navbar-header">
                         <!-- Toggle Button -->
                         <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                             <span class="icon-bar"></span>
                             <span class="icon-bar"></span>
                             <span class="icon-bar"></span>
                         </button>
                     </div>
                     <div class="navbar-collapse collapse clearfix">
                         <ul class="navigation clearfix">
                             <li>
                                 <a href="Home.html">Home</a>
                                 <ul>
                                 </ul>
                             </li>
                             <li class="dropdown">
                                 <a href="about.html">About</a>
                                 <ul>
                                     <li><a href="infrastructure.html">Infrastructure</a></li>
                                     <li><a href="../front/from-the-principal-s-desk.html">From The Principal's Desk</a></li>
                                     <li><a href="aim.html">Aim</a></li>
                                     <li><a href="vision.html">Vision</a></li>
                                     <li><a href="rules-and-regulations.html">Rules and Regulations</a></li>
                                     <li><a href="manager-s-desk.html">Manager's Desk</a></li>
                                     <li><a href="chairperson-s-message.html">Chairperson's Message</a></li>
                                     <li><a href="profile.html">Profile</a></li>
                                     <li><a href="other-committees.html">Other Committees</a></li>
                                     <li><a href="managing-committee.html">Managing Committee</a></li>
                                     <li><a href="mandatory-public-disclosure.html">Mandatory Public Disclosure</a></li>
                                 </ul>
                             </li>
                             <li>
                                 <a href="academic.html">Academic</a>
                                 <ul>
                                 </ul>
                             </li>
                             <li>
                                 <a href="events.html">Events</a>
                                 <ul>
                                 </ul>
                             </li>
                             <li>
                                 <a href="achievements.html">Achievements</a>
                                 <ul>
                                 </ul>
                             </li>
                             <li class="dropdown">
                                 <a href="admissions.html">Admissions</a>
                                 <ul>
                                     <li><a href="admission-criteria.html">Admission Criteria</a></li>
                                 </ul>
                             </li>
                             <li>
                                 <a href="alumni.html">Alumni</a>
                                 <ul>
                                 </ul>
                             </li>
                             <li>
                                 <a href="{{ url('gallery') }}">Gallery</a>
                                 <ul>
                                 </ul>
                             </li>
                             <li>
                                 <a href="contact.html">Contact</a>
                                 <ul>
                                 </ul>
                             </li>
                         </ul>
                     </div>
                 </nav>
             </div>
         </div>
     </div>
     <nav class="social">
         <ul>
             <li><a href="{{ session()->get("front_details.facebook") }}" target="_blank">Facebook <i style="color: #1880a7;" class="social-icon social-facebook icon-facebook bx bxl-facebook"></i></a></li>
             <li><a href="{{ session()->get("front_details.instagram") }}" target="_blank">Instagram <i style="color: #e01d6e;" class="social-icon social-instagram bx bxl-instagram"></i></a></li>
             <li><a href="{{ session()->get("front_details.twiter") }}" target="_blank">Twitter <i style="color: #36c1f5;" class="social-icon social-twitter icon-twitter bx bxl-twitter"></i></a></li>
         </ul>
     </nav>
     <div class="sticky-style-2">
         <a target="_blank" href="enquiry.html">
             <b class="font-weight-5" style="margin-right: 17px;"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAASRJREFUSEvdldFNxDAQRGcqgA6ACqAEOoHrACqBDoBOKOHo4K4DqGDQWLsR8SWxddEJ6fxjRVnP7L51NsSJF0+sjzM0kHQP4A3A9ZH4dgA2JD99/gCRJAdcHSmex3Ykb+YMFFHfAJ5JvveYSXoE8ALgsgiTJfmpCtIgdW1gIxseLEkWtLANhtVjsAHwCuACwDa4eh+WpLvol/cfAE/x3K7AGYSAK7gFMEJWIflyBSS3kgqBZgUZEAhcyUOknj1JJB/OPBE2DeYaGhknMocVJPUlmDXIF1MGf6ox66ECI6njVxl0XtflHvSILMU0ezCHKjG1Evh/g94MG3F7kmVYLv4Palzx8dWjpPbax0c3PU2rUTAS6+3DaCa1kKx9f4a/zLVI6vO/GgaVGZzCF9UAAAAASUVORK5CYII=" /></b>Enquiry</a>

     </div>
 </header>


