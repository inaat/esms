 @foreach ($albums as $album)
 <div class="col-md-3">
     <div class="card" style="width: 18rem;">
         <img class="card-img-top" src="{{url('uploads/front_image/'.$album->thumb_image)}}" alt="Card image cap">
         <div class="card-body">
             <h5 class="card-title">{{ $album->title }}</h5>
             <p class="card-text">{{ $album->description}}</p>
         </div>
         <div class="col-12">
             <div class="d-grid">
                 <a href="{{ action('Frontend\FrontHomeController@gallery_show', [$album->slug,$album->id]) }}" class="btn btn-primary text-white">VIEW ALL PHOTOS</a>
             </div>
         </div>

     </div>
 </div>
 @endforeach
