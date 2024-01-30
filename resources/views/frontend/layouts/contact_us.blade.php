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
              <iframe src="{{ session()->get("front_details.map_url") }}" style="border:0; width: 100%; height: 384px;" allowfullscreen="" loading="lazy"></iframe>
          </div>

          <div class="col-lg-6">
            {!! Form::open(['url' => action('\App\Http\Controllers\Frontend\FrontHomeController@submitForm'), 'method' => 'post','class'=>'php-email-form','id' =>'contact_add_form' ,'files' => true]) !!}
              <div class="row">
                <div class="col form-group">
                  <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
                </div>
                <div class="col form-group">
                  <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required>
                </div>
              </div>
                            <div class="row">

              <div class="col form-group">
                <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" required>
              </div>
              <div class="col form-group">
                <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile" required>
              </div>
              </div>
              <div class="form-group">
                <textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea>
              </div>
              <div class="my-3">
                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message">Your message has been sent. Thank you!</div>
              </div>
              <div class="text-center"><button type="submit">Send Message</button></div>
 {!! Form::close() !!}
          </div>

        </div>

      </div>
    </section>