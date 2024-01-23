<!doctype html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<!-- Mirrored from incollege.edu.pk/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 14 Jan 2024 18:29:11 GMT -->

<head>


<style type="text/css">
        :root {
            --primaryColor:@php echo session()->get("front_details.main_color")@endphp;
            --hoverColor:@php echo session()->get("front_details.hover_color")@endphp;
            --greengredient:@php echo session()->get("front_details.linear_gradient")@endphp;
            }
                    .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table td,
        .table th {
            padding: 1px 15px;
            border: 0px solid #ddd;
            font-size: 14px;
        }

        @media(max-width:500px) {
            .table thead {
                display: none;
            }

            .table,
            .table tbody,
            .table tr,
            .table td {
                display: block;
                width: 100%;
            }

            .table tr {
                margin-bottom: 15px;
            }

            .table td {
                text-align: right;
                padding-left: 50%;
                text-align: right;
                position: relative;
            }

            .table td:: before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 15px;
                font-size: 16px;
                font-weight: bold;
                text-align: left;
            }
        }

    </style>
    <link href="{{ url('frontold/front_assets/css/style.css')}} " rel="stylesheet">
    <link href="{{ url('frontold/swiper/swiper-bundle.min.css')}}" rel="stylesheet">
    <link href='{{ url('frontold/front_assets/css/boxicons.min.css')}}' rel='stylesheet'>
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!--====== Required meta tags ======-->
    <meta charset="utf-8">
    <meta https-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--====== Title ======-->
    <title>{{ session()->get("front_details.school_name") }}</title>

    <!--====== Favicon Icon ======-->
    <link rel="shortcut icon" href="{{url('uploads/front_image/'.session()->get("front_details.logo_image"))}}" type="image/png">

    <!--====== Slick css ======-->
    <link rel="stylesheet" href="{{ url('front/css/slick.css') }}">

    <!--====== Animate css ======-->
    <link rel="stylesheet" href="{{ url('front/css/animate.css') }}">

    <!--====== Nice Select css ======-->
    <link rel="stylesheet" href="{{ url('front/css/nice-select.css') }}">

    <!--====== Nice Number css ======-->
    <link rel="stylesheet" href="{{ url('front/css/jquery.nice-number.min.css') }}">

    <!--====== Magnific Popup css ======-->
    <link rel="stylesheet" href="{{ url('front/css/magnific-popup.css') }}">

    <!--====== Bootstrap css ======-->
    <link rel="stylesheet" href="{{ url('front/css/bootstrap.min.css') }}">

    <!--====== Fontawesome css ======-->
    <link rel="stylesheet" href="{{ url('front/css/font-awesome.min.css') }}">

    <!--====== Default css ======-->
    <link rel="stylesheet" href="{{ url('front/css/default.css') }}">

    <!--====== Style css ======-->
    <link rel="stylesheet" href="{{ url('front/css/style.css') }}">

    <!--====== Responsive css ======-->
    <link rel="stylesheet" href="{{ url('front/css/responsive.css') }}">
    <!--====== w3schools =============-->

    <!--====== Style Sticky Social Icon Bar ======-->


    <script type="text/javascript" src="{{ url('front/code.jquery.com/jquery-1.8.2.js') }}"></script>

    <script src="{{ url('front/ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js') }}"></script>
    <script src="{{ url('front/preloader/src/jquery.countdown360.js') }}" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
        $(window).on('load', function() {
            $('#myModal').modal('show');
        });
    </script>
</head>

<body>



    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous"
        src="{{ url('front/connect.facebook.net/en_US/sdk.js#xfbml=1&version=v9.0') }}" nonce="6Nr31DjU"></script>

    <!--====== PRELOADER PART START ======-->
    <div class="preloader">
        <div class="loader rubix-cube">
            <div class="layer layer-1"></div>
            <div class="layer layer-2"></div>
            <div class="layer layer-3 color-1"></div>
            <div class="layer layer-4"></div>
            <div class="layer layer-5"></div>
            <div class="layer layer-6"></div>
            <div class="layer layer-7"></div>
            <div class="layer layer-8"></div>
        </div>
    </div>

    <!--====== PRELOADER PART START ======-->

    @include('frontend.layouts.partials.header')
    @yield("wrapper")
     
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


 
    @include('frontend.layouts.partials.footer')

    <script src="{{ url('frontold/front_assets/js/popper.min.js')}}"></script>
    <script src="{{ url('frontold/front_assets/js/owl.js')}}"></script>
     <script src="{{ url('frontold/front_assets/js/wow.js')}}"></script>
    <script src="{{ url('frontold/front_assets/js/validation.js')}}"></script>
    <script src="{{ url('frontold/swiper/swiper-bundle.min.js')}}"></script>
    <script src="{{ url('frontold/front_assets/js/jquery.fancybox.js')}}"></script>
    <script src="{{ url('frontold/front_assets/js/appear.js')}}"></script>
    <script src="{{ url('frontold/front_assets/js/parallax.min.js')}}"></script>
    <script src="{{ url('frontold/front_assets/js/isotope.js')}}"></script>
        <script src="{{ url('frontold/front_assets/js/script.js')}}"></script>
  <script type="text/javascript">
        base_path = "{{url('/')}}";
        //used for push notification
        $.ajaxSetup({
            beforeSend: function(jqXHR, settings) {
                if (settings.url.indexOf('http') === -1) {
                    settings.url = base_path + settings.url;
                }
            }
        , });
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            @if(config('app.debug') == false)
            $.fn.dataTable.ext.errMode = 'throw';
            @endif
        });

        // Gallery image hover
        $(".img-wrapper").hover(
            function() {
                $(this).find(".img-overlay").animate({
                    opacity: 1
                }, 600);
            }
            , function() {
                $(this).find(".img-overlay").animate({
                    opacity: 0
                }, 600);
            }
        );
        // Gallery image hover
        $(".video-wrapper").hover(
            function() {
                $(this).find(".video-overlay").animate({
                    opacity: 1
                }, 600);
            }
            , function() {
                $(this).find(".video-overlay").animate({
                    opacity: 0
                }, 600);
            }
        );

        // Lightbox
        var $overlay = $('<div id="overlay"></div>');
        var $image = $("<img>");
        var $prevButton = $('<div id="prevButton"><i class="fa fa-chevron-left"></i></div>');
        var $nextButton = $('<div id="nextButton"><i class="fa fa-chevron-right"></i></div>');
        var $exitButton = $('<div id="exitButton"><i class="fa fa-times"></i></div>');

        // Add overlay
        $overlay.append($image).prepend($prevButton).append($nextButton).append($exitButton);
        $("#gallery").append($overlay);

        // Hide overlay on default
        $overlay.hide();

        // When an image is clicked
        $(".img-overlay").click(function(event) {
            // Prevents default behavior
            event.preventDefault();
            // Adds href attribute to variable
            var imageLocation = $(this).prev().attr("href");
            // Add the image src to $image
            $image.attr("src", imageLocation);
            // Fade in the overlay
            $overlay.fadeIn("slow");
        });

        // When the overlay is clicked
        $overlay.click(function() {
            // Fade out the overlay
            $(this).fadeOut("slow");
        });

        // When next button is clicked
        $nextButton.click(function(event) {
            // Hide the current image
            $("#overlay img").hide();
            // Overlay image location
            var $currentImgSrc = $("#overlay img").attr("src");
            // Image with matching location of the overlay image
            var $currentImg = $('#image-gallery img[src="' + $currentImgSrc + '"]');
            // Finds the next image
            var $nextImg = $($currentImg.closest(".image").next().find("img"));
            // All of the images in the gallery
            var $images = $("#image-gallery img");
            // If there is a next image
            if ($nextImg.length > 0) {
                // Fade in the next image
                $("#overlay img").attr("src", $nextImg.attr("src")).fadeIn(800);
            } else {
                // Otherwise fade in the first image
                $("#overlay img").attr("src", $($images[0]).attr("src")).fadeIn(800);
            }
            // Prevents overlay from being hidden
            event.stopPropagation();
        });

        // When previous button is clicked
        $prevButton.click(function(event) {
            // Hide the current image
            $("#overlay img").hide();
            // Overlay image location
            var $currentImgSrc = $("#overlay img").attr("src");
            // Image with matching location of the overlay image
            var $currentImg = $('#image-gallery img[src="' + $currentImgSrc + '"]');
            // Finds the next image
            var $nextImg = $($currentImg.closest(".image").prev().find("img"));
            // Fade in the next image
            $("#overlay img").attr("src", $nextImg.attr("src")).fadeIn(800);
            // Prevents overlay from being hidden
            event.stopPropagation();
        });

        // When the exit button is clicked
        $exitButton.click(function() {
            // Fade out the overlay
            $("#overlay").fadeOut("slow");
        });

    </script>
</body>

</html>
