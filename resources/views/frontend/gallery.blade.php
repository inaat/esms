<section id="portfolio" class="portfolio">
    <div class="container" data-aos="fade-up">

        <div class="section-title">
            <h3>Check our <span>Gallery</span></h3>

        </div>



        <div class="row portfolio-container" data-aos="fade-up" data-aos-delay="200">
             @foreach ($galleries as $gallery )
                 
            <div class="col-lg-4 col-md-6 portfolio-item filter-app homegallery">
                <img style="width: 100%;" src="{{ url('uploads/front_image/'.$gallery->thumb_image) }}" class="img-fluid" alt="">
                <div class="portfolio-info">
                    <!--<h4>App 1</h4>
              <p>App</p>-->
                    <a href="{{ action('Frontend\FrontHomeController@gallery_show', [$gallery->slug,$gallery->id]) }}" data-gallery="portfolioGallery" class="portfolio-lightbox preview-link" title="2">{{ $gallery->title }} <i class="bx bx-eye"></i></a>
                    <!--<a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>-->
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section><!-- End Portfolio Section -->
