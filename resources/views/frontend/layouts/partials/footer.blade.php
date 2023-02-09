 
 
<!-- ======= Contact Section ======= -->

<section id="contact" class="contact">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          
          <h3><span>Contact Us</span></h3>
          <p>Give us a shout-out and feel free to ask anything that interests</p>
        </div>

        <div class="row" data-aos="fade-up" data-aos-delay="100">
          <div class="col-lg-6">
            <div class="info-box mb-4">
              <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAbBJREFUSEu1le01REEQRO9GgAgQASJABIgAESASRIAIEAEiIANkQASc63Q7s+PNm7c/zJ+3Zz6qqqt6Zmf885j9Mz5TCNaAE2AH2AxBL8AjcAm8jYnsEVwE+BiGe85aG8YIVLkRB28AgZxzWMkpcFhUtDVE0iJI5Z9hTQLXGBJp1VLYJencGCLQ89fYpSrBnTuvMtAW/ZfkOfav15kMEaR6A1SR4AIsV+I+AAVIch125ZnfrUME6X2qvwP2gCfgKE4KuA3cA/tFFZ6dy2KI4CtAck2lelyWnzaq3nlHfe5nchGCFUAyRxK8x++FCOwKy68tcv44CK6iu9IiL+FD2Ojv0QwyMLvEwFWrt9pUDlvYDtImm8Eu875kTk2LDO02Dqa/kkhWPhWC5jPhdxU4AGyK0QpczANaYkVjQ8VaVubRJcgqDNUqMtyayLvhpfT7R32rixIkw/a72ygh74x3ZC7c3D/22KlKqwxXm7KD8mw2g2Gb0WCVvee6fMxKkhJc5a3HcNIfTk1iZWbUe2mbbTpkd0ni+iTwXsg1kSTZsrZm05Yp96DT+tOXeyFPR2rs/AZz92gZw5u8WQAAAABJRU5ErkJggg==">
              <h3>Our Address</h3>
              <p>{{ session()->get("front_details.address") }}</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="info-box  mb-4">
              <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAARRJREFUSEvdlNFxwjAQRB8VJB0AFZAS0gmkg1BJ6ADSSUoIHUAHpAKYzdxlNLLkk834g+jHY/u073ZPoxkTr9nE+vxPwCuwBxYj4zsBb8CX9pciUsF8pLhvk8ayBrha1QXYAodG2Ab4AJ6t/rf5kgMHuK4AAglYWhKUsADpCgHKcQc8Ad+Wq57perF56fkDvNv7X/N9DvRPG+VgZQ7SyNJIjuZADXgCoQOHKwI5WVvrPhOP5NM69whDQG2mEvTIVOOR5IegCsiH2xlYEpn+CZjPRN/vArSc2NERtYj3OnCBWlStF2PoYHJAFEXfYfC9Z78sI9u5mOojgMR1wqq3aeqgBIgcdi+kQTsGFkcRDZTrlj8+4AaNzzgZuJBekwAAAABJRU5ErkJggg==">
              <h3>Email Us</h3>
              <p>{{ session()->get("front_details.email") }}</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="info-box  mb-4">
             <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAY5JREFUSEvVle0xQ1EQht9UgApIBagAFZAKUAEqQAWogFSAClABOlCCDpgns3tnrbM3cmfyw85kksnds89+vHvuSEu20ZLjayjgS9KbfR4kPVaJVoBrSeuSjiV9Ng4DiAYMX75/WAasSrqXtGtez5L2iuy2zO/UkiGRM0l30T8DyGAzBeQA2fUZFZ9YtSTUVZIBuXQPmiFk+yqJCi/NySEE3/aDfwXgT/kEwWIiMeCHtWsiieH/UhEZ7RS9QCkH4Rm/L6ylDmceV5Kmko5aAFpx2AC820CzooAgihd7zuBpXVdVbpEfiIwquPvQLsBrqX2z2C2Z0seVQBhL4r9B1lq03CZUQq8HWQuwYT30Kigf2eUqWMpbk+pNRa+uCjI+D4cYGtJzCMGfJDFUrFzGvssOHe8HCJWwB0gQ5Xhwd2lC+gBkyV7kq6NvFvhTaSfnedc1ECqplq8F65asJdMqO2bClkb5Vr4MHN+ZzasgBkFdgFjGCuQb3Z1bBOCHaBsQ3hlAvX3NjR8CWGjh/j/gG+gKUhl2keNfAAAAAElFTkSuQmCC">
              <h3>Call Us</h3>
              <p>{{ session()->get("front_details.phone_no") }}</p>
            </div>
          </div>

        </div>

        <div class="row" data-aos="fade-up" data-aos-delay="100">

          <div class="col-lg-6 ">
              {{-- <iframe src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=Khwaza%20khela%20Swat+(Swat%20Collegiate%20School%20&amp;%20college)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed" style="border:0; width: 100%; height: 384px;" allowfullscreen="" loading="lazy"></iframe> --}}
              <iframe src="{{ session()->get("front_details.map_url") }}" style="border:0; width: 100%; height: 384px;" allowfullscreen="" loading="lazy"></iframe>
          </div>

          <div class="col-lg-6">
            <form action="https://npskedu.in/Contact/sendenquiry" method="post" role="form" class="php-email-form">
              <div class="row">
                <div class="col form-group">
                  <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required="">
                </div>
                <div class="col form-group">
                  <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required="">
                </div>
              </div>
              <div class="form-group">
                <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile" required="">
              </div>
              <div class="form-group">
                <textarea class="form-control" name="message" rows="5" placeholder="Message" required=""></textarea>
              </div>
              <div class="my-3">
                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message">Your message has been sent. Thank you!</div>
              </div>
              <div class="text-center"><button type="submit">Send Message</button></div>
            </form>
          </div>

        </div>

      </div>
    </section>


<!-- End Contact Section -->


 
 <!-- ======= Footer ======= -->
 <footer id="footer">
     <div class="footer-newsletter">
         <div class="container">
             <div class="row justify-content-center">
                 <div class="col-lg-6">
                     <h4>Sign up for our newsletter</h4>
                     <!--<p>Tamen quem nulla quae legam multos aute sint culpa legam noster magna</p>-->
                     <form action="#" method="post">
                         <input type="email" name="email" placeholder="Email Address">
                         <input type="submit" value="Sign up" style="background: #f5a3b2;">
                     </form>
                 </div>
             </div>
         </div>
     </div>

     <div class="footer-top">
         <div class="container">
             <div class="row">

                 <div class="col-lg-3 col-6 footer-contact">
                 
                     <h1 class="logo footerlogo"><a href="{{ url('/') }}"><img src="{{url('uploads/front_image/'.session()->get("front_details.logo_image"))}}" alt="logo"><span></span></a></h1>
                     <p>{{ session()->get("front_details.address") }}<br><br>
                         <strong>Phone:</strong> {{ session()->get("front_details.phone_no") }}<br>
                         <strong>Email:</strong>{{ session()->get("front_details.email") }}<br>
                     </p>
                 </div>

                 <div class="col-lg-3 col-6 footer-links">
                     <h4>Quick Links</h4>
                     <ul>
                         <li><i class="bx bx-chevron-right"></i> <a href="{{ url('/') }}">Home</a></li>
                         <li><i class="bx bx-chevron-right"></i> <a href="school-profile.html">About School</a></li>
                         <!--<li><i class="bx bx-chevron-right"></i> <a href="#">Certificates</a></li>-->
                         <li><i class="bx bx-chevron-right"></i> <a href="tc.html">TC</a></li>
                         <li><i class="bx bx-chevron-right"></i> <a href="events.html">Event</a></li>
                         <li><i class="bx bx-chevron-right"></i> <a href="gallery.html">Gallery</a></li>
                         <li><i class="bx bx-chevron-right"></i> <a href="contact.html">Contact</a></li>
                     </ul>
                 </div>

                 <div class="col-lg-3 col-6 footer-links">
                     <h4>School Activities</h4>
                     <ul>
                         <li><i class="bx bx-chevron-right"></i> <a href="safety-and-wellness.html">Safety & Wellness</a></li>
                         <li><i class="bx bx-chevron-right"></i> <a href="house-system.html">House System</a></li>
                         <li><i class="bx bx-chevron-right"></i> <a href="talent-club.html">Talent Club</a></li>
                     </ul>
                 </div>

                 <div class="col-lg-3 col-6 footer-links">
                     <h4>Social Networks</h4>
                     <p></p>
                     <div class="social-links mt-3">
                         <a href="{{ session()->get("front_details.twiter") }}" target="_blank" class="twitter"><i class="bx bxl-twitter"></i></a>
                         <a href="{{ session()->get("front_details.facebook") }}" target="_blank" class="facebook"><i class="bx bxl-facebook"></i></a>
                         <a href="{{ session()->get("front_details.instagran") }}" target="_blank" class="instagram"><i class="bx bxl-instagram"></i></a>
                         <a href="{{ session()->get("front_details.skype") }}" target="_blank" class="skype"><i class="bx bxl-skype"></i></a>
                         <a href="{{ session()->get("front_details.linkedin") }}" target="_blank" class="linkedin"><i class="bx bxl-linkedin"></i></a>
                         <a href="{{ session()->get("front_details.youtube") }}" target="_blank" class="youtube"><i class="bx bxl-youtube"></i></a>
                     </div>
                 </div>

             </div>
         </div>
     </div>

     <div class="container py-4">
         <div class="copyright">
             &copy; Copyright <strong><span>{{ session()->get("front_details.school_name") }}</span></strong>. All Rights Reserved
         </div>
         <div class="credits">
             <a href="https://illuminatetech.net" target="_blank">Developed By Illuminate Technologies </a>
         </div>
     </div>
 </footer><!-- End Footer -->

 

 <div class="modal fade" id="popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
     <div class="modal-dialog modelset">
         <div class="modal-content">

             <div class="modal-body modelform">
                 <button type="button" class="close closebtn" data-dismiss="modal" onclick="popupclose();"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                 <div class="orh-form orh-home-text">
                     <div id="form-collapse" style="display: none;">-</div>
                     <form action="https://npskedu.in/Contact/sendenquiry" method="POST">
                         <p class="text-center form-head">Admission Enquiry
                             <!--<span id="acdyr" value="2022-23">2022-23</span>-->
                             <select id="acadamicYear" name="year" required="">
                                 <option value="">Select Academic Year</option>
                                 <option value="2022-23">2022-23</option>
                                 <option value="2023-24" selected="">2023-24</option>
                             </select>
                         </p>
                         <p class="text-center" style="font-size: 12px; margin-bottom: 0px; line-height: 16px;">A Journey To A Better Future Begins With Us</p>
                         <div class="caldera-grid" style="">
                             <label id="errormsg" class="text-danger"></label>
                             <div class="form-group">
                                 <input placeholder="Student Name *" required="" type="text" class=" form-control" id="name_lead" name="name" value="" data-type="text" aria-r="" equired="true" aria-labelledby="fld_8768091Label">
                             </div>
                             <div class="form-group">
                                 <input placeholder="Parent Name *" required="" type="text" class=" form-control" id="parent_name" name="parent_name" value="" data-type="text" aria-required="true" aria-labelledby="fld_8768091Label">
                             </div>
                             <div class="form-group">
                                 <div class="phone-wrapper" style="width: 100%; display: flex;">
                                     <div class="country-code" style="width: 25%;">
                                         <select class="form-control" id="ddlcountrycode" name="phonecode">
                                             <!--<option value="" disabled selected>Code </option>-->
                                             <option value="91" selected="" id="india">(+91)</option>
                                             <option value="65" id="Singapore">(+65)</option>
                                             <option value="971" id="UAE">(+971)</option>
                                             <option value="44" id="UK">(+44)</option>
                                             <option value="1" id="USA">(+1)</option>
                                             <option value="84" id="Vietnam">(+84)</option>
                                             <option value="49" id="Germany">(+49)</option>
                                             <option value="60" id="Malaysia">(+60)</option>
                                             <option value="7" id="others">Others</option>
                                         </select>
                                     </div>
                                     <div class="phone-num" style="width: 75%;">
                                         <input placeholder="Phone Number*" maxlength="10" required="" type="number" oninput="numberOnly('phone_lead');javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" class="form-control" id="phone_lead" name="mobile" value="" data-type="text" aria-required="true" aria-labelledby="fld_8768091Label">
                                     </div>
                                 </div>
                             </div>
                             <div class="form-group">
                                 <input placeholder="Email ID" type="text" class="form-control" id="emailid" name="email" value="" data-type="text">
                             </div>
                             <div class="form-group">
                                 <input placeholder="City*" type="text" class="form-control" id="emailid" name="city" value="" data-type="text">
                             </div>

                             <div class="form-group">
                                 <input placeholder="Grade" type="text" class="form-control" id="emailid" name="grade" value="" data-type="text">
                             </div>
                             <div class="form-group">
                                 <div class="submittbtn">
                                     <button type="submit" class="btn btn-primary" id="orchidsform" value="Submit">SUBMIT</button>
                                 </div>
                             </div>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
     </div>
 </div>
