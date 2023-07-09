<section class="gallery-page-section">
     <div class="container">
         <div class="section-title">
             <h3>Events</h3>
         </div>
         @if(!@empty($events))
             
         <div class="gal-carousel owl-carousel owl-theme">
          
             @foreach ($events as $event )
                
             <div class="gallery-block">
                 <div class="image-box">
                     <figure class="image"><img src="{{url('uploads/front_image/'.$event->images)}}" alt="photos"></figure>
                     <div class="overlay-box">
                         <a href="{{ action('Frontend\FrontHomeController@event_show', [$event->slug,$event->id]) }}" class="photos" data-fancybox="gallery">
                             <i class="bx bx-show"></i></a>
                         <div class="">
                             <a href="{{ action('Frontend\FrontHomeController@event_show', [$event->slug,$event->id]) }}">{{ $event->title }}</a>
                         </div>
                     </div>
                 </div>
             </div>
             @endforeach
        
         </div>
         @endif
     </div>
 </section>