 <section class="news-letter">
     <div class="container">
         <div class="sec-title centred">
             <h5>Notifications</h5>
             <h1>Latest Updates</h1>
         </div>
         <div class="news-carousel owl-carousel owl-theme">
         @foreach($news as $n)
             <div class="testimonial-block">
                 <div class="news-inner-box">
                     <div class="content-box">
                         <div class="date">{{ $n->date }}</div>
                         <h3><a href="{{ action('Frontend\FrontHomeController@news_show', [$n->slug,$n->id]) }}">
                                 {{ $n->title }} </a>
                         </h3>
                         <div class="location"><a href="{{ action('Frontend\FrontHomeController@news_show', [$n->slug,$n->id]) }}">Read More</a></div>
                     </div>
                 </div>
             </div>
             @endforeach
            
         
         </div>
     </div>
 </section>