<!--====== HEADER PART START ======-->

<div class="top-header">
    <div class="topscrollBox">
        <marquee onmouseover="stop();" onmouseout="start();" scrollamount="3">
            @php
                $front_notices = session()->get('front_notices');
            @endphp
            @if (!empty($front_notices->count() > 0))
                @foreach ($front_notices as $data)
                    @if ($data->status == 'publish')
                        <a href="{{ url($data->link) }}"><span><strong>Notice :</strong> {{ $data->title }}</span> </a>
                    @endif
                @endforeach
            @endif
            @php
                $front_news = session()->get('front_news');
            @endphp
            @if (!empty($front_news->count() > 0))
                @foreach ($front_news as $data)
                    @if ($data->status == 'publish')
                        <a href="{{ action('Frontend\FrontHomeController@news_show', [$data->slug, $data->id]) }}"><span><strong>News
                                    :</strong> {{ $data->title }}</span> </a>
                    @endif
                @endforeach
            @endif
        </marquee>
    </div>
</div>
<header id="header-part ">
    <div class="header-top d-none d-lg-block">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="header-contact text-lg-left text-center" style="padding-top: 0px">
                        <ul>
                            <li style="padding-left: 7px"><img src="{{ url('front/images/all-icon/map.png') }}"
                                    alt="icon"><span
                                    style="padding-left: 6px">{{ session()->get('front_details.address') }}</span></li>
                            <li><img src="{{ url('front/images/all-icon/email.png') }}" alt="icon"><span><a
                                        href="mailto:{{ session()->get('front_details.email') }}"
                                        style="color: #d7e2da">{{ session()->get('front_details.email') }}</a></span>
                            </li>

                        </ul>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="header-about text-lg-right text-center">


                        <ul>
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
                </div>
                <!--   <div class="col-lg-6">
                        <div class="header-opening-time text-lg-right text-center">
                            <p>Opening Hours : Monday to Saturay - 8 Am to 5 Pm</p>
                        </div>
                    </div>-->
            </div> <!-- row -->
        </div> <!-- container -->
    </div> <!-- header top -->

    <div class="header-logo-support pb-1 pt-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-5">
                    <div class="logo">
                        <a href="{{ url('/') }}">
                            <img src="{{ url('uploads/front_image/' . session()->get('front_details.logo_image')) }}"
                                alt="Logo">
                        </a>
                    </div>
                </div>
                <div class="col-lg-7 col-md-7 pt-25">
                    <div class="support-button d-none d-md-block float-right">
                        <div class="support float-left">
                            <div class="icon">
                                <img src="{{ url('front/images/all-icon/support.png') }}" alt="icon">
                            </div>
                            <div class="cont">
                                <p>Need Help? call us.</p>
                                <span> {{ session()->get('front_details.phone_no') }}</span>

                            </div>
                        </div>
                        <div class="button float-left">
                        @if(session()->get('front_details.admission_open')== 'yes')

                            <a href="{{ url('online-apply') }}" target="_blank" class="main-btn">Apply
                                Now</a>
                        @else
                         <a href="{{ url('about-us') }}" target="_blank" class="main-btn">About US</a>
                        @endif
                        </div>
                    </div>
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </div> <!-- header logo support -->
<div class="container">

    <div class="navigation">
        <div class="container">
            <div class="row">
                <div class="col-lg-11 col-md-10 col-sm-9 col-8">
                    <nav class="navbar navbar-expand-lg">
                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                        <div class="navbar-collapse sub-menu-bar collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav mr-auto">
                                <li class="nav-item">
                                    <a class="active" href="{{ url('/') }}">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ action('Frontend\FrontHomeController@about_index') }}">About US</a>
                                    @php
                                        $about_nav = frontMenu();
                                    @endphp
                                    <ul class="sub-menu">
                                        @foreach ($about_nav as $nav)
                                            <li><a
                                                    href="{{ action('Frontend\FrontHomeController@about_show', [$nav->slug, $nav->id]) }}">{{ $nav->title }}</a>

                                            </li>
                                        @endforeach
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ url('gallery') }}">Gallery</a>
                                </li>


                                <li class="nav-item">
                                    <a href="{{ action('Frontend\FrontHomeController@event_index') }}">Event</a>
                                    @php
                                        $event_nav = frontEventMenu();
                                    @endphp
                                    <ul class="sub-menu">
                                        @foreach ($event_nav as $nav)
                                            <li><a
                                                    href="{{ action('Frontend\FrontHomeController@event_show', [$nav->slug, $nav->id]) }}">{{ $nav->title }}</a>

                                            </li>
                                        @endforeach


                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ action('Frontend\FrontHomeController@news_index') }}">News</a>
                                    @php
                                        $news_nav = frontNewsMenu();
                                    @endphp
                                    <ul class="sub-menu">
                                        @foreach ($news_nav as $nav)
                                            <li><a
                                                    href="{{ action('Frontend\FrontHomeController@news_show', [$nav->slug, $nav->id]) }}">{{ $nav->title }}</a>

                                            </li>
                                        @endforeach


                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ action('Frontend\FrontHomeController@faculty') }}">Faculty</a>

                                </li>
                                  @php
                                    $front_navbars = session()->get('front_navbars');
                                @endphp
                                @foreach ($front_navbars as $nav)
                                    <li class="nav-item colorChange">
                                        <a @if ($nav->type == 'download_and_blink') class="colorChange" @endif
                                            href="{{ action('Frontend\FrontHomeController@show_page_index', [$nav->slug, $nav->id]) }}">{{ $nav->title }}</a>

                                        <ul class="sub-menu">
                                            @foreach ($nav->custom_pages as $page)
                                                <li><a
                                                        href="{{ action('Frontend\FrontHomeController@show_page', [$page->slug, $page->id]) }}">{{ $page->title }}</a>

                                                </li>
                                            @endforeach


                                        </ul>
                                    </li>
                                @endforeach
                                <li class="nav-item">
                                    @if (Auth::check())
                                        <a href="{{ url('/home') }}">Dashboard</a>
                                    @else
                                        <a href="{{ url('/login') }}">Login</a>
                                    @endif
                                </li>

                              
                            </ul>
                        </div>
                    </nav> <!-- nav -->
                </div>
                <!--   <div class="col-lg-2 col-md-2 col-sm-3 col-4">
                        <div class="right-icon text-right">
                            <ul>
                                <li><a href="#" id="search"><i class="fa fa-search"></i></a></li>
                                <li><a href="#"><i class="fa fa-shopping-bag"></i><span>0</span></a></li>
                            </ul>
                        </div>
                    </div>-->
            </div>
        </div> <!-- container -->
    </div>
</div>
</header>

<!--====== HEADER PART ENDS ======-->
