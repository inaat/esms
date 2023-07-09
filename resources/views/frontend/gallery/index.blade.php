@extends("frontend.layouts.app")
@section('title', __('english.galleries'))
@section('wrapper')


<div class="site-content" data-color="">

    <nav aria-label="breadcrumb" class="breadjhj">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Gallery</li>
        </ol>
    </nav>
    <div class="container">
        <div class="row txtsearch">
            <div class="col-md-6">
                <div class="input-group">
                    <input style="width:100%;" type="search" name="title" onkeypress="getbytitle(this.value)" class="form-control rounded" placeholder="Search By Title" aria-label="Search" aria-describedby="search-addon">
                </div>
            </div>
        
        </div>

    </div>
    <div class="container galleryalbum">
        <div class="row">
            <div class="col-md-12" style="margin-bottom: 15px;">
                <h2 style="text-align:center;">PREVIOUS ALBUM(S)</h2>
                <hr>
            </div>
        </div>
        <div class="row detaillist">
            @foreach ($albums as $album)
            <div class="col-md-3">
                <div class="card" style="width: 18rem;">
                    <img class="card-img-top" src="{{url('uploads/front_image/'.$album->thumb_image)}}" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">{{ $album->title }}</h5>
                        <p class="card-text">{{$album->description}}</p>
                    </div>
                    <div class="col-12">
                        <div class="d-grid">
                            <a href="{{ action('Frontend\FrontHomeController@gallery_show', [$album->slug,$album->id]) }}" class="btn btn-primary text-white">VIEW ALL PHOTOS</a>
                        </div>
                    </div>

                </div>
            </div>
            @endforeach
        </div>
    </div>



</div>
<script>
    function getbytitle(val) {
        $.ajax({
            type: "POST"
            , url: "/gallery-search"
            , data: 'title=' + val
            , success: function(data) {
                console.log(data.html_content);
                $(".detaillist").html(data.html_content);

            }
        });
    }

</script>
<!-- ======= Contact Section ======= --
        @endsection

