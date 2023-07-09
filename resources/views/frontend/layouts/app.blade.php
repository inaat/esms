<!doctype html>
<html lang="en">

<!-- Added by HTTrack -->

<!-- Mirrored from npskedu.in/ by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 01 Jan 2023 14:35:10 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<head>
    <!-- Required meta tags -->
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ session()->get("front_details.school_name") }}</title>
    <meta name="keywords" content="{{ session()->get("front_details.school_name") }}">
    <meta name="description" content="{{ session()->get("front_details.school_name") }}">
    <!-- Favicon -->
    <style type="text/css">
        :root {
            --blue: #007bff;
            --indigo: #6610f2;
            --purple: #6f42c1;
            --pink: #e83e8c;
            --red: #dc3545;
            --orange: #fd7e14;
            --yellow: #ffc107;
            --green: #28a745;
            --teal: #20c997;
            --cyan: #17a2b8;
            --white: #fff;
            --gray: #6c757d;
            --gray-dark: #343a40;
            --primary: #007bff;
            --secondary: #6c757d;
            --success: #28a745;
            --info: #17a2b8;
            --warning: #ffc107;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #343a40;
            /* --green: #157D4C;
   --orange:#FF9F29;
  --greengredient:linear_gradient linear-gradient(125deg, #144B2F, #34495e);*/
            --green: @php echo session()->get("front_details.main_color")@endphp;
            --orange:@php echo session()->get("front_details.hover_color")@endphp;
            --greengredient:@php echo session()->get("front_details.linear_gradient")@endphp;
           --breakpoint-xs: 0;
            --breakpoint-sm: 576px;
            --breakpoint-md: 768px;
            --breakpoint-lg: 992px;
            --breakpoint-xl: 1200px;
            --font-family-sans-serif: -apple-system,
            BlinkMacSystemFont,
            "Segoe UI",
            Roboto,
            "Helvetica Neue",
            Arial,
            sans-serif,
            "Apple Color Emoji",
            "Segoe UI Emoji",
            "Segoe UI Symbol";
            --font-family-monospace: SFMono-Regular,
            Menlo,
            Monaco,
            Consolas,
            "Liberation Mono",
            "Courier New",
            monospace;


        }

    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css ">
    <link href="https://www.flawlessindia.co.in/static/NPS/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link rel="icon" href="{{url('uploads/front_image/'.session()->get("front_details.logo_image"))}}" type="image/ico">
    <link href="{{ url('front/front_assets/css/style.css')}} " rel="stylesheet">
    <link href="{{ url('front/front_assets/css/responsive.css')}}" rel="stylesheet">
    <link href="{{ url('front/swiper/swiper-bundle.min.css')}}" rel="stylesheet">
    <link href='{{ url('front/front_assets/css/boxicons.min.css')}}' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body class="boxed_wrapper">
    @include('frontend.layouts.partials.header')
    @yield("wrapper")
    @include('frontend.layouts.partials.footer')



    <!--Scroll to top-->
    <button class="scroll-top scroll-to-target" data-target="html">
        <i class="bx bx-chevron-up"></i>
    </button>
    <script src="{{ url('front/front_assets/js/jquery.js')}}"></script>
    <script src="{{ url('front/front_assets/js/popper.min.js')}}"></script>
    <script src="{{ url('front/front_assets/js/bootstrap.min.js')}}"></script>
    <script src="{{ url('front/front_assets/js/owl.js')}}"></script>
    <script src="{{ url('front/front_assets/js/wow.js')}}"></script>
    <script src="{{ url('front/front_assets/js/validation.js')}}"></script>
    <script src="{{ url('front/swiper/swiper-bundle.min.js')}}"></script>
    <script src="{{ url('front/front_assets/js/jquery.fancybox.js')}}"></script>
    <script src="{{ url('front/front_assets/js/appear.js')}}"></script>
    <script src="{{ url('front/front_assets/js/parallax.min.js')}}"></script>
    <script src="{{ url('front/front_assets/js/isotope.js')}}"></script>
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
    <!-- main-js -->
    <script src="{{ url('front/front_assets/js/script.js')}}"></script>
</body>

<!-- Mirrored from npskedu.in/ by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 01 Jan 2023 14:35:10 GMT -->
</html>

