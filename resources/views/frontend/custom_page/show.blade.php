@extends('frontend.layouts.app')
@section('title', __('english.leave_applications_for_employee'))
@section('wrapper')
<style>
    #gallery {
        padding-top: 40px;
    }

    @media screen and (min-width: 991px) {
        #gallery {
            padding: 60px 30px 0 30px;
        }
    }

    .video-wrapper {
        position: relative;
        margin-top: 15px;
    }
    .img-wrapper {
        position: relative;
        margin-top: 15px;
    }

    .img-wrapper img {
        width: 100%;
    }
    .video-wrapper img {
        width: 100%;
    }

    .img-overlay  {
        background: rgba(0, 0, 0, 0.7);
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
    }
.video-overlay {
        background: rgba(0, 0, 0, 0.7);
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
    }
    .img-overlay i {
        color: #fff;
        font-size: 3em;
    }
    .video-overlay i {
        color: #fff;
        font-size: 3em;
    }

    #overlay {
        background: rgba(0, 0, 0, 0.7);
        width: 100%;
        height: 100%;
        position: fixed;
        top: 0;
        left: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 999;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    #overlay img {
        margin: 0;
        width: 80%;
        height: auto;
        -o-object-fit: contain;
        object-fit: contain;
        padding: 5%;
    }

    @media screen and (min-width: 768px) {
        #overlay img {
            width: 60%;
        }
    }

    @media screen and (min-width: 1200px) {
        #overlay img {
            width: 50%;
        }
    }

    #nextButton {
        color: #fff;
        font-size: 2em;
        transition: opacity 0.8s;
    }

    #nextButton:hover {
        opacity: 0.7;
    }

    @media screen and (min-width: 768px) {
        #nextButton {
            font-size: 3em;
        }
    }

    #prevButton {
        color: #fff;
        font-size: 2em;
        transition: opacity 0.8s;
    }

    #prevButton:hover {
        opacity: 0.7;
    }

    @media screen and (min-width: 768px) {
        #prevButton {
            font-size: 3em;
        }
    }

    #exitButton {
        color: #fff;
        font-size: 2em;
        transition: opacity 0.8s;
        position: absolute;
        top: 15px;
        right: 15px;
    }

    #exitButton:hover {
        opacity: 0.7;
    }

    @media screen and (min-width: 768px) {
        #exitButton {
            font-size: 3em;
        }
    }

</style>

    <div class="aboutUs">

        @component('frontend.components.widget', ['title' => $data->title])
        @endcomponent
        <div class="container abooutContent">
            <div class="row">
                <div class="col-md-4  d-flex">
                    <div class="card sidebar radius-10 p-0 w-100 p-3">
                        <div class="card-body">
                            <ul>
                                @foreach ($nav->custom_pages as $bar)
                                    <li class=" {{ $data->id == $bar->id ? 'active' : '' }}   mx-auto"><a
                                            href="{{ action('Frontend\FrontHomeController@show_page', [$bar->slug, $bar->id]) }}">{{ $bar->title }}</a>
                                    </li>
                                @endforeach

                            </ul>

                        </div>
                    </div>
                </div>
                <div class="col-md-8 mt-5">
                    <div class="row">

                        <div class=" col-12 d-flex">
                            <div class="card radius-10 p-0 w-100 p-3">
                                <h5 class="card-title ">{{ $data->title }}</h5>
                                <div class="card-body">
                                    <p style="text-align: justify;">{!! $data->description !!}</p>
                                    @php
                                        $elements = json_decode($data->elements);
                                    @endphp
                                    @if (!empty($elements))
                                        @foreach ($elements as $pic)
                                            @if ($pic->type == 3)
                                                <div class="row">
                                                    <div class="col-sm-12 caseletter">
                                                        <div class="table" style="padding-top: 15px;">
                                                            <table border="1" width="100%">
                                                                <tbody>
                                                                    <tr>
                                                                        <td><a href="{{ url('uploads/front_image/' . $pic->image) }}"
                                                                                target="_blank">{{ $data->title }}<img
                                                                                    style="width: 15px;margin-left: 3px;"
                                                                                    src="{{ url('uploads/front_image/pdficon.png') }}"></a>
                                                                        </td>
                                                                        <td style="text-align:center !important;"><a
                                                                                href="{{ url('uploads/front_image/' . $pic->image) }}"
                                                                                target="_blank">Open</a></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                </div>
                                            @else
                                            @if($pic->type == 1)
                                            <div id="image-gallery">
                <div class="row" id="gallery">
                                                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 image">
                                                    <div class="img-wrapper">
                                                        <a href="{{ url('uploads/front_image/' . $pic->image) }}"><img
                                                                src="{{ url('uploads/front_image/' . $pic->image) }}"
                                                                class="img-responsive"></a>
                                                        {{-- <a data-fancybox="video-gallery" href="https://www.youtube.com/watch?v=v9aVd6qnEjA&amp;autoplay=1"><img src="http://i3.ytimg.com/vi/v9aVd6qnEjA/maxresdefault.jpg"></a> --}}
                                                        <div class="img-overlay">
                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                 
                                            @else
                                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 image">
                                                    <a data-fancybox="video-gallery" href="{{ $pic->video_url }}">
                                                        <div class="video-wrapper">
                                                            <img src="{{ url('uploads/front_image/' . $pic->image) }}"
                                                                class="img-responsive">
                                                            <div class="video-overlay">
                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                            </div>

                                                        </div>
                                                    </a>
                                                </div>
                                                </div>
                                                 </div> 
                                            @endif
                                            @endif
                                        @endforeach

                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <style>


    </style><!-- ======= Contact Section ======= -->

@endsection
