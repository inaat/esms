 
    @php
               $front_counters =session()->get("front_counters");
            @endphp
            @if(!empty($front_counters->count()>0))
 <section id="counter-stats" class="wow fadeInRight" data-wow-duration="1.4s">
     <div class="container">
         <div class="row">
            @foreach($front_counters as $data)
            @if($data->status=='publish')
            <div class="col-lg-3 col-6 stats">
                 <div class="counting" data-count="{{ $data->number }}">0</div>
                 <a  href="{{ $data->link }}"><h4> {{ $data->title }}</h5></a>
             </div>
             @endif
             @endforeach

             {{-- <div class="col-lg-3 col-6 stats">
                 <div class="counting" data-count="50">0</div><span class="ptwo">+</span>
                 <h5>Awards</h5>
             </div>
             <div class="col-lg-3 col-6 stats">
                 <div class="counting" data-count="75">0</div><span class="pthree">+</span>
                 <h5>Faculty</h5>
             </div>
             <div class="col-lg-3 col-6 stats">
                 <div class="counting" data-count="15000">0</div><span class="pfour">+</span>
                 <h5>Success Students</h5>
             </div> --}}

         </div>
         <!-- end row -->
     </div>
     <!-- end container -->

 </section>
 @endif