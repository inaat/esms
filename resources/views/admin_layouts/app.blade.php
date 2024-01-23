<!doctype html>
<html  lang="{{ app()->getLocale() }}" dir="{{in_array(session()->get('user.language', config('app.locale')), config('constants.langs_rtl')) ? 'rtl' : 'ltr'}}">
{{-- class="color-sidebar sidebarcolor2 color-header headercolor4 --}}
<head>
  {{--  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1580489596045533"
     crossorigin="anonymous"></script>--}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ url('/uploads/business_logos/'.session()->get('system_details.org_favicon', ''))}}" type="image/png" />
    <link rel="stylesheet" href="{{asset('css/fontawesome-free-6.0.0-web/css/all.min.css')}}">
    <!--plugins-->

    @yield("style")
    <link href="{{ asset('css/vendor.css')}}" rel="stylesheet" />
    <link href="{{ asset('css/icon.css')}}" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-extended.css')}}" rel="stylesheet">
        @include('common.color')

    @if( in_array(session()->get('user.language', config('app.locale')), config('constants.langs_rtl')) )
	<link rel="stylesheet" href="{{ asset('assets/css/rtl/app.css') }}">
    @else
         <link href="{{ asset('assets/css/app.css')}}" rel="stylesheet"> 

@endif
    <link href="{{ asset('assets/css/icons.css')}}" rel="stylesheet">
    
    <link href="{{ asset('/js/tinymce/matheditor/html/css/math.css')}}" rel="stylesheet" />
     <link href="{{ asset('/js/intl-tel-input/intlTelInput.css')}}" rel="stylesheet" />
      <!-- Theme Style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/semi-dark.css')}}" />
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/header-colors.css')}}" /> --}}
    <style>
        @font-face {
		font-family: 'certificate';
		font-style: normal;
		font-weight: 400;
		src: url("{{  url('/fonts/Certificate.ttf')}}") format('embedded-opentype'),
                      url("{{  url('/fonts/Certificate.ttf')}}") format('opentype');
	  }
      
      @font-face {
        font-family: 'Jameel Noori Nastaleeq';
        font-style: normal;
        src: url("{{ url('/fonts/JameelNooriNastaleeqKasheeda.ttf') }}") format('truetype');
        /*src: url({{ url('/fonts/JameelNooriNastaleeqKasheeda.ttf') }}) format('truetype');*/
    } 
       .urdu,.urdu_input {
            font-family: Jameel Noori Nastaleeq !important;
            font-size: 16px !important;
            direction: rtl !important;
        }      
html.color-header .topbar .navbar-nav .nav-link {

color: #ffffff;

}

html.color-header .topbar .top-menu-left .nav-item .nav-link {

color: #ffffff;

}

html.color-header .search-bar input {

color: #413f3f;

background-color: #ffffff !important;

;

border: 1px solid rgb(241 241 241 / 15%) !important;

box-shadow: inset 0 1px 2px rgb(0 0 0 / 0%);

}

html.color-header .search-bar input:focus {

box-shadow: 0 0 0 .25rem rgba(255, 255, 255, 0.25)

}

html.color-header .search-bar input::placeholder {

color: #08090a !important;

opacity: .5 !important;

/* Firefox */

}

html.color-header::placeholder {

color: #08090a !important;

opacity: .5 !important;

/* Firefox */

}

html.color-header .search-show {

color: #221f1f;

}

html.color-header .user-info .user-name {

color: #ffffff;

}

html.color-header .user-info .designattion {

color: #ffffff;

}

html.color-header .user-box {

border-left: 1px solid rgb(255 255 255 / 0.35) !important;

}

html.color-header .mobile-toggle-menu {

color: #ffffff;

}
        html.headercolor4 .topbar {

background: #157d4c;

}
    </style>

 
 
    <title>@yield('title') - {{ Session::get('system_details.org_name') }}</title>

</head>

<body>

    <!--wrapper-->
    <div class="wrapper">
        <!--navigation-->
        @include("admin_layouts.nav")
        <!--end navigation-->
        <!-- Add currency related field-->
        <input type="hidden" id="__code" value="{{session('currency')['code']}}">
        <input type="hidden" id="__symbol" value="{{session('currency')['symbol']}}">
        <input type="hidden" id="__thousand" value="{{session('currency')['thousand_separator']}}">
        <input type="hidden" id="__decimal" value="{{session('currency')['decimal_separator']}}">
        <input type="hidden" id="__symbol_placement" value="{{session('business.currency_symbol_placement')}}">
        <input type="hidden" id="__precision" value="{{config('constants.currency_precision', 2)}}">
        <input type="hidden" id="__quantity_precision" value="{{config('constants.quantity_precision', 2)}}">
        <!-- End of currency related field-->

        @if (session('status'))
        <input type="hidden" id="status_span" data-status="{{ session('status.success') }}" data-msg="{{ session('status.msg') }}">
        @endif
        <!--start header -->
        @include("admin_layouts.header")
        <!--end header -->

        <!--start page wrapper -->
        @yield("wrapper")
        <!--end page wrapper -->
        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        
        <!--end overlay-->
        <!--Start Back To Top Button--> 
        <a href="{{ url('/home') }} " class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
       
        <footer class="page-footer">
            <p class="mb-0">Copyright © 2021. All right reserved.</p>
        </footer>
    </div>
    <audio id="success-audio">
        <source src="{{ asset('/audio/success.ogg?v=' . $asset_v) }}" type="audio/ogg">
        <source src="{{ asset('/audio/success.mp3?v=' . $asset_v) }}" type="audio/mpeg">
    </audio>
    <audio id="error-audio">
        <source src="{{ asset('/audio/error.ogg?v=' . $asset_v) }}" type="audio/ogg">
        <source src="{{ asset('/audio/error.mp3?v=' . $asset_v) }}" type="audio/mpeg">
    </audio>
    <audio id="warning-audio">
        <source src="{{ asset('/audio/warning.ogg?v=' . $asset_v) }}" type="audio/ogg">
        <source src="{{ asset('/audio/warning.mp3?v=' . $asset_v) }}" type="audio/mpeg">
    </audio>
    <!--end wrapper-->
    <!--start switcher-->
    <div class="switcher-wrapper">
        <div class="switcher-btn"> <i class='bx bx-cog bx-spin'></i>
        </div>
        <div class="switcher-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-uppercase">Theme Customizer</h5>
                <button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
            </div>
            <hr />
            <h6 class="mb-0">Theme Styles</h6>
            <hr />
            <div class="d-flex align-items-center justify-content-between">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="lightmode" >
                    <label class="form-check-label" for="lightmode">Light</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="darkmode">
                    <label class="form-check-label" for="darkmode">Dark</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="semidark">
                    <label class="form-check-label" for="semidark">Semi Dark</label>
                </div>
            </div>
            <hr />
            <div class="form-check">
                <input class="form-check-input" type="radio" id="minimaltheme" name="flexRadioDefault">
                <label class="form-check-label" for="minimaltheme">Minimal Theme</label>
            </div>
            <hr />
            <h6 class="mb-0">Header Colors</h6>
            <hr />
            <div class="header-colors-indigators">
                <div class="row row-cols-auto g-3">
                    <div class="col">
                        <div class="indigator headercolor1" id="headercolor1"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor2" id="headercolor2"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor3" id="headercolor3"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor4" id="headercolor4"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor5" id="headercolor5"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor6" id="headercolor6"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor7" id="headercolor7"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor8" id="headercolor8"></div>
                    </div>
                </div>
            </div>
            <hr />
            <h6 class="mb-0">Sidebar Colors</h6>
            <hr />
            <div class="header-colors-indigators">
                <div class="row row-cols-auto g-3">
                    <div class="col">
                        <div class="indigator sidebarcolor1" id="sidebarcolor1"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor2" id="sidebarcolor2"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor3" id="sidebarcolor3"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor4" id="sidebarcolor4"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor5" id="sidebarcolor5"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor6" id="sidebarcolor6"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor7" id="sidebarcolor7"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor8" id="sidebarcolor8"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade view_modal" tabindex="-1" role="dialog" 
        aria-labelledby="gridSystemModalLabel"></div>
         <!-- This will be printed -->
    <section class="invoice print_section" id="receipt_section">
    </section>
    {{-- <div class="hide print_table_part">
        <style type="text/css">
          @media print {
           body{
            zoom:70%;
           }

          }
        </style>
        </div> --}}
        {{-- whatsapp delete modal --}}
<div class="modal fade" id="whatsappDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        	<form action="{{route('gateway.whatsapp.delete')}}" method="POST">
        		@csrf
        		<input type="hidden" name="id" value="">
	            <div class="modal_body2">
	                <div class="modal_icon2">
	                    <i class="las la-trash-alt"></i>
	                </div>
	                <div class="modal_text2 mt-3">
	                    <h6>{{ __('Are you sure to delete')}}</h6>
	                </div>
	            </div>
	            <div class="modal_button2">
	                <button type="button" class="" data-bs-dismiss="modal">{{ __('Cancel')}}</button>
	                <button type="submit" class="bg--danger">{{ __('Delete')}}</button>
	            </div>
	        </form>
        </div>
    </div>
</div>

{{-- whatsapp qrQoute scan --}}
  <div class="modal fade" id="qrQuoteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">{{ __('Scan Device')}}</h5>
          <button type="button" class="btn-close" aria-label="Close" onclick="return deviceStatusUpdate('','','','','')"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="scan_id" id="scan_id" value="">
            <div>
                <h4 class="py-3">{{ __('To use WhatsApp')}}</h4>
                <ul>
                    <li>{{ __('1.Open WhatsApp on your phone')}}</li>
                    <li>{{ __('2.Tap Menu  or Settings  and select Linked Devices')}}</li>
                    <li>{{ __('3.Point your phone to this screen to capture the code')}}</li>
                </ul>
            </div>
          <div class="text-center">
                <img id="qrcode" class="w-50" src="" alt="">
          </div>
        </div>
      </div>
    </div>
  </div>
    @yield("css")
         <script src="{{ asset('/js/socket.io.js?v=' . $asset_v) }}"></script>

    @include("common.javascripts")
             <script src="{{ asset('/js/intl-tel-input/intlTelInput-jquery.min.js?v=' . $asset_v) }}"></script>

    @yield("script")
    @include("admin_layouts.theme-control")
  {{--  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1580489596045533"
     crossorigin="anonymous"></script>
<!-- Banner -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-1580489596045533"
     data-ad-slot="4324707098"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>--}}
</body>

</html>
