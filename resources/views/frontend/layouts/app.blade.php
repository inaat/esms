<!doctype html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<!-- Mirrored from incollege.edu.pk/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 14 Jan 2024 18:29:11 GMT -->

<head>


    <style type="text/css">
        :root {
            --primaryColor: @php echo session()->get("front_details.main_color")
        @endphp
        ;
        --hoverColor:@php echo session()->get("front_details.hover_color")
        @endphp
        ;
        --greengredient:@php echo session()->get("front_details.linear_gradient")
        @endphp
        ;
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
    <link href="{{ url('frontold/front_assets/css/style.css') }} " rel="stylesheet">
    <link href="{{ url('frontold/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href='{{ url('frontold/front_assets/css/boxicons.min.css') }}' rel='stylesheet'>
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!--====== Required meta tags ======-->
    <meta charset="utf-8">
    <meta https-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--====== Title ======-->
    <title>{{ session()->get('front_details.school_name') }}</title>

    <!--====== Favicon Icon ======-->
    <link rel="shortcut icon" href="{{ url('uploads/front_image/' . session()->get('front_details.logo_image')) }}"
        type="image/png">

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
    @if (session('status'))
        <input type="hidden" id="status_span" data-status="{{ session('status.success') }}"
            data-msg="{{ session('status.msg') }}">
    @endif
    <div class="container">

    @yield('wrapper')

    <!-- ======= Contact Section ======= -->

    <section id="contact" class="contact">
        <div class="container" data-aos="fade-up">


            @include('frontend.layouts.contact_us')



        </div>
    </section>


    <!-- End Contact Section -->



</div>
    @include('frontend.layouts.partials.footer')

    <script src="{{ url('frontold/front_assets/js/popper.min.js') }}"></script>
    <script src="{{ url('frontold/front_assets/js/owl.js') }}"></script>
    <script src="{{ url('frontold/front_assets/js/wow.js') }}"></script>
    <script src="{{ url('frontold/front_assets/js/validation.js') }}"></script>
    <script src="{{ url('frontold/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ url('frontold/front_assets/js/jquery.fancybox.js') }}"></script>
    <script src="{{ url('frontold/front_assets/js/appear.js') }}"></script>
    <script src="{{ url('frontold/front_assets/js/parallax.min.js') }}"></script>
    <script src="{{ url('frontold/front_assets/js/isotope.js') }}"></script>
    <script src="{{ url('frontold/front_assets/js/script.js') }}"></script>
    <script type="text/javascript">
        base_path = "{{ url('/') }}";
        if ($('#status_span').length) {
            var status = $('#status_span').attr('data-status');
            if (status === '1') {
                toastr.success($('#status_span').attr('data-msg'));
            } else if (status == '' || status === '0') {
                toastr.error($('#status_span').attr('data-msg'));
            }
        }
        //used for push notification
        $.ajaxSetup({
            beforeSend: function(jqXHR, settings) {
                if (settings.url.indexOf('http') === -1) {
                    settings.url = base_path + settings.url;
                }
            },
        });
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            @if (config('app.debug') == false)
                $.fn.dataTable.ext.errMode = 'throw';
            @endif
        });

        // Gallery image hover
        $(".img-wrapper").hover(
            function() {
                $(this).find(".img-overlay").animate({
                    opacity: 1
                }, 600);
            },
            function() {
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
            },
            function() {
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
    <script>
        $(document).ready(function() {
            $('#contact_add_form').submit(function(e) {
                e.preventDefault(); // Prevent the default form submission

                // Get the form data
                var formData = new FormData($(this)[0]);

                // Hide submit button
                $('button[type="submit"]').hide();

                // Display loading message
                $('.loading').show();
                $('.error-message').empty();
                $('.sent-message').empty();

                // Reference to the form for resetting after success
                var form = $(this);

                // Make the AJAX request
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Hide loading message
                        $('.loading').hide();

                        // Check the JSON response
                        if (response && response.message) {
                            // Display success message from the server
                            $('.sent-message').text(response.message).show();

                            // Clear form fields
                            form[0].reset();
                        } else {
                            // Display a generic success message if the response is unexpected
                            $('.sent-message').text('Your message has been sent. Thank you!')
                                .show();
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        // Hide loading message
                        $('.loading').hide();

                        // Check if the server returned a JSON error response
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            // Display the error message from the server
                            $('.error-message').text('Error: ' + xhr.responseJSON.error).show();
                        } else {
                            // Display a generic error message if the response is unexpected
                            $('.error-message').text('Error: ' + errorThrown).show();
                        }
                    },
                    complete: function() {
                        // Show submit button after AJAX request is complete
                        $('button[type="submit"]').show();
                    }
                });
            });
        });
    </script>

</body>

</html>
