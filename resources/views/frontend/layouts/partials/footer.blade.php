
    <!--====== FOOTER PART START ======-->

    <!--  <footer id="footer-part">
        <div class="footer-top pb-70 pt-40">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-about mt-40">
                            <div class="logo">
                                <a href="#"><img src="images/logo-2.png" alt="Logo"></a>
                            </div>
                            <p>Gravida nibh vel velit auctor aliquetn quibibendum auci elit cons equat ipsutis sem nibh id elit. Duis sed odio sit amet nibh vulputate.</p>
                           <ul class="mt-20">
                   <li><a href="https://www.facebook.com/imdcollegeofficial" target="_blank"><i class="fa fa-facebook-f"></i></a></li>
                   <li><a href="https://twitter.com/IslamabadAnd" target="_blank"><i class="fa fa-twitter"></i></a></li>
                   <li><a href="https://www.instagram.com/imdcofficial/" target="_blank"><i class="fa fa-instagram"></i></a></li>
    <li><a href="https://www.linkedin.com/company/imdc-official/" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="footer-link mt-40">
                            <div class="footer-title pb-25">
                                <h6>Sitemap</h6>
                            </div>
                            <ul>
                                <li><a href="index-2.html"><i class="fa fa-angle-right"></i>Home</a></li>
                                <li><a href="about.html"><i class="fa fa-angle-right"></i>About us</a></li>
                                <li><a href="courses.html"><i class="fa fa-angle-right"></i>Courses</a></li>
                                <li><a href="blog.html"><i class="fa fa-angle-right"></i>News</a></li>
                                <li><a href="events.html"><i class="fa fa-angle-right"></i>Event</a></li>
                            </ul>
                            <ul>
                                <li><a href="#"><i class="fa fa-angle-right"></i>Gallery</a></li>
                                <li><a href="shop.html"><i class="fa fa-angle-right"></i>Shop</a></li>
                                <li><a href="teachers.html"><i class="fa fa-angle-right"></i>Teachers</a></li>
                                <li><a href="#"><i class="fa fa-angle-right"></i>Support</a></li>
                                <li><a href="contact.html"><i class="fa fa-angle-right"></i>Contact</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <div class="footer-link support mt-40">
                            <div class="footer-title pb-25">
                                <h6>Support</h6>
                            </div>
                            <ul>
                                <li><a href="#"><i class="fa fa-angle-right"></i>FAQS</a></li>
                                <li><a href="#"><i class="fa fa-angle-right"></i>Privacy</a></li>
                                <li><a href="#"><i class="fa fa-angle-right"></i>Policy</a></li>
                                <li><a href="#"><i class="fa fa-angle-right"></i>Support</a></li>
                                <li><a href="#"><i class="fa fa-angle-right"></i>Documentation</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-address mt-40">
                            <div class="footer-title pb-25">
                                <h6>Contact Us</h6>
                            </div>
                            <ul>
                                <li>
                                    <div class="icon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <div class="cont">
                                        <p>143 castle road 517 district, kiyev port south Canada</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <div class="cont">
                                        <p>+3 123 456 789</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <i class="fa fa-envelope-o"></i>
                                    </div>
                                    <div class="cont">
                                        <p>info@yourmail.com</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-copyright pb-25 pt-10">
            <div class="container">
                <div class="row">
                   
     <div class="col-md-8">
                        <div class="copyright text-md-left pt-15 text-center">
                            <p style="color: #791632; font-weight: 700;">Copyright (R) 2015, Islamabad Medical & Dental College </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="copyright text-md-right pt-15 text-center">
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>-->

    <footer id="footer-part">
        <div class="footer-top pt-75 pb-80">
            <div class="container">
                <div class="row">

                    <div class="col-lg-8 col-md-6 pl-25">
                    
                        <div class="footer-address mt-40  ">
                        
                            <div class="footer-title pb-25">
                                <h6>Contact Us</h6>
                            </div>
                            <ul>
                                <li>
                                    <div class="icon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <div class="cont">
                                        <p>{{ session()->get("front_details.address") }}</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <div class="cont">
                                        <p>+ {{ session()->get("front_details.phone_no") }}</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <i class="fa fa-envelope-o"></i>
                                    </div>
                                    <div class="cont">
                                        <p>{{ session()->get("front_details.email") }}</p>
                                    </div>
                                </li>
                            </ul>
                            <div class="footer-about mt-40" align="right">


                                <ul class="mt-20">
                                  @if (!empty(session()->get('front_details.facebook')))
                                <li><a href="{{ session()->get('front_details.facebook') }}" target="_blank"><i
                                            class="fa fa-facebook-f"></i></a></li>
                            @endif
                            @if (!empty(session()->get('front_details.twitter')))
                                <li><a href="{{ session()->get('front_details.twitter') }}" target="_blank"><i
                                            class="fa fa-twitter"></i></a></li>
                            @endif
                            @if (!empty(session()->get('front_details.instagram')))
                                <li><a href="{{ session()->get('front_details.instagram') }}" target="_blank"><i
                                            class="fa fa-instagram"></i></a></li>
                            @endif
                            @if (!empty(session()->get('front_details.linkedin')))
                                <li><a href="{{ session()->get('front_details.linkedin') }}" target="_blank"><i
                                            class="fa fa-linkedin"></i></a></li>
                            @endif
                            @if (!empty(session()->get('front_details.youTube')))
                                <li><a href="{{ session()->get('front_details.youTube') }}" target="_blank"><i
                                            class="fa fa-youtube"></i></a></li>
                            @endif
                            @if (!empty(session()->get('front_details.skype')))
                                <li><a href="{{ session()->get('front_details.skype') }}" target="_blank"><i
                                            class="fa fa-skype"></i></a></li>
                            @endif


                                </ul>
                            </div>
                        </div> <!-- footer address -->
                    </div>

                  
                 
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="footer-link mt-40">
                            <div class="footer-title pb-25">
                                <h6>Quick Navigation</h6>
                            </div>
                            <ul style="padding-left: 10px">
                                <li><a href="{{ url('/') }}"><i class="fa fa-angle-right"></i>HOME</a></li>
                                <li><a href="{{ action('Frontend\FrontHomeController@about_index') }}"><i class="fa fa-angle-right"></i>ABOUT
                                        US</a></li>
                                {{-- <li><a href="program.html"><i class="fa fa-angle-right"></i>PROGRAMS</a></li> --}}
                                {{-- <li><a href="anth.html"><i class="fa fa-angle-right"></i>ANTH</a></li>
                                <li><a href="students-affairs.html"><i
                                            class="fa fa-angle-right"></i>STUDENTS-AFFAIRS</a></li>
                            </ul>
                            <ul>
                                <li><a href="students-affairs.html"><i class="fa fa-angle-right"></i>LIFE @ INC</a>
                                </li>
                                <li><a href="fee.html"><i class="fa fa-angle-right"></i>FEE STRUCTURE</a></li>
                                <li><a href="eligibility.html"><i class="fa fa-angle-right"></i>ELIGIBILITY</a></li> --}}

                                {{-- <li><a href="contact.html"><i class="fa fa-angle-right"></i>CONTACT US</a></li> --}}
                            </ul>

                        </div> <!-- footer link -->
                    </div>
                    <!--      <div class="col-lg-3 col-md-6">
                        <div class="footer-address mt-40">
                            <div class="footer-title pb-25">
                                <h6>Contact Us</h6>
                            </div>
                            <ul>
                                <li>
                                    <div class="icon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <div class="cont">
                                        <p>143 castle road 517 district, kiyev port south Canada</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <div class="cont">
                                        <p>+3 123 456 789</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <i class="fa fa-envelope-o"></i>
                                    </div>
                                    <div class="cont">
                                        <p>info@yourmail.com</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>  -->
                </div> <!-- row -->
            </div> <!-- container -->
        </div> <!-- footer top -->

        <div class="footer-copyright pb-25 pt-10">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <div class="copyright text-md-left pt-15 text-center">
                            <p style="color: var(--primaryColor); font-weight: 700;">Copyright © 2015,{{ session()->get("front_details.school_name") }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="copyright text-md-right pt-15 text-center">

                        </div>
                    </div>
                </div> <!-- row -->
            </div> <!-- container -->
        </div> <!-- footer copyright -->
    </footer>

    <!--====== FOOTER PART ENDS ======-->

    <!--====== BACK TO TP PART START ======-->

    <a href="#" class="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!--====== BACK TO TP PART ENDS ======-->








    <!--====== jquery js ======-->
    <script src="{{ url('front/js/vendor/modernizr-3.6.0.min.js') }}"></script>
    <script src="{{ url('front/js/vendor/jquery-1.12.4.min.js') }}"></script>

    <!--====== Bootstrap js ======-->
    <script src="{{ url('front/js/bootstrap.min.js') }}"></script>

    <!--====== Slick js ======-->
    <script src="{{ url('front/js/slick.min.js') }}"></script>

    <!--====== Magnific Popup js ======-->
    <script src="{{ url('front/js/jquery.magnific-popup.min.js') }}"></script>

    <!--====== Counter Up js ======-->
    <script src="{{ url('front/js/waypoints.min.js') }}"></script>
    <script src="{{ url('front/js/jquery.counterup.min.js') }}"></script>

    <!--====== Nice Select js ======-->
    <script src="{{ url('front/js/jquery.nice-select.min.js') }}"></script>

    <!--====== Nice Number js ======-->
    <script src="{{ url('front/js/jquery.nice-number.min.js') }}"></script>

    <!--====== Count Down js ======-->
    <script src="{{ url('front/js/jquery.countdown.min.js') }}"></script>

    <!--====== Validator js ======-->
    <script src="{{ url('front/js/validator.min.js') }}"></script>

    <!--====== Ajax Contact js ======-->
    <script src="{{ url('front/js/ajax-contact.js') }}"></script>

    <!--====== Main js ======-->
    <script src="{{ url('front/js/main.js') }}"></script>

    <!--====== Map js ======-->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDC3Ip9iVC0nIxC6V14CKLQ1HZNF_65qEQ"></script>
    <script src="{{ url('front/js/map-script.js') }}"></script>
    <script >
    		$(window).on('load', function() {
			setTimeout(function() {
				$("#popup").modal('show');
			}, 10000);
		}); 
   
		$(window).on('load', function() {
			setTimeout(function() {
				$("#popup").modal('hide');
			}, 300000);
		});
   
		function popupclose() {
			$("#popup").hide();
			$("#popup").removeClass('show');
			$(".modal-backdrop").hide();
			$(".modal-backdrop").removeClass('show');
		}
    </script>
    <div class="icon-bar">

          @if(!empty(session()->get("front_details.facebook")))
                                <a class="facebook" href="{{ session()->get("front_details.facebook") }}" target="_blank"><i
                                            class="fa fa-facebook-f"></i></a>
                            @endif
                            @if(!empty(session()->get("front_details.twitter")))
                                <a  class="twitter" href="{{ session()->get("front_details.twitter") }}" target="_blank"><i
                                            class="fa fa-twitter"></i></a>
                             @endif
                            @if(!empty(session()->get("front_details.instagram")))
                                <a class="instagram" href="{{ session()->get("front_details.instagram") }}" target="_blank"><i
                                            class="fa fa-instagram"></i></a>
                             @endif
                            @if(!empty(session()->get("front_details.linkedin")))
                                <a class="linkedin" href="{{ session()->get("front_details.linkedin") }}" target="_blank"><i
                                            class="fa fa-linkedin"></i></a>
                             @endif
                            @if(!empty(session()->get("front_details.youTube")))
                                <a class="youtube" href="{{ session()->get("front_details.youTube") }}" target="_blank"><i
                                            class="fa fa-youtube"></i></a>
                             @endif
                            @if(!empty(session()->get("front_details.skype")))
                                <a class="skype" href="{{ session()->get("front_details.skype") }}" target="_blank"><i
                                            class="fa fa-skype"></i></a>
                             @endif
    </div>

@if(session()->get('front_details.admission_open')== 'yes')
 <div id="popup" class="modal fade">

    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title"> </h2>
                <button type="button" class="close" data-dismiss="modal">&times;</button>   </div>
           
   <div class="modal-body">
   <a href="{{ url('online-apply') }}" target="_blank"><img src="{{ url('uploads/front_image/' . session()->get('front_details.admission_banner')) }}" alt=""/></a>       </div>
        </div>
 </div></div>
 @endif
