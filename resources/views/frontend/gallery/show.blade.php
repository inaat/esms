@extends("frontend.layouts.app")
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
      @component('frontend.components.widget', ['title'=>$query->title ])
  @endcomponent
    <div class="container">
        <section>
             @if(!empty($data))
            <div id="image-gallery">
                <div class="row" id="gallery">
                @foreach ($data as $pic)
                 @if($pic->type==1)
                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 image">
                        <div class="img-wrapper">
                            <a href="{{url('uploads/front_image/'.$pic->image)}}"><img src="{{url('uploads/front_image/'.$pic->image)}}" class="img-responsive"></a>
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
                            <img src="{{url('uploads/front_image/'.$pic->image)}}"  class="img-responsive">
                         <div class="video-overlay">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </div>
                            
                        </div>
                        </a>
                    </div>
                     @endif
                    @endforeach
                </div><!-- End row -->
            </div><!-- End image gallery -->
            @endif
        </section>
    </div>

</div>
<script>
</script>
<!-- ======= Contact Section ======= --
        @endsection

