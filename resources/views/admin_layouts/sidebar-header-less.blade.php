<!doctype html>
<html  lang="{{ app()->getLocale() }}" dir="{{in_array(session()->get('user.language', config('app.locale')), config('constants.langs_rtl')) ? 'rtl' : 'ltr'}}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ url('/uploads/business_logos/'.session()->get('system_details.org_favicon', ''))}}" type="image/png" />
    <link rel="stylesheet" href="{{asset('css/fontawesome-free-6.0.0-web/css/all.min.css')}}">
    @include('common.color')

    <!--plugins-->
    @yield("style")
    <link href="{{ asset('css/vendor.css')}}"  rel="stylesheet" />
    <link href="{{ asset('css/icon.css')}}" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-extended.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.css')}}" rel="stylesheet">
    
    <link href="{{ asset('/js/tinymce/matheditor/html/css/math.css')}}" rel="stylesheet" />

    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/semi-dark.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/header-colors.css')}}" />

    <title>{{ session()->get('system_details.org_name')}}</title>
</head>

<body>

    <!--wrapper-->
    <div class="wrapper" style="padding-left:10px; padding-right:10px ;">
     
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
        

        <!--start page wrapper -->
        @yield("wrapper")
        <!--end page wrapper -->

       
        {{-- <footer class="sidebar-head-less-page-footer">
            <p class="mb-0">Copyright © 2021. All right reserved.</p>
        </footer> --}}
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
 
    <div class="modal fade view_modal" tabindex="-1" role="dialog" 
        aria-labelledby="gridSystemModalLabel"></div>
         <!-- This will be printed -->
    <section class="invoice print_section" id="receipt_section">
    </section>
             <script src="{{ asset('/js/socket.io.js?v=' . $asset_v) }}"></script>

    @include("common.javascripts")
    @yield("script")
</body>

</html>
