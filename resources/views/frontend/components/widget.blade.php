  <section id="page-banner" class="pt-105 pb-130 bg_cover" data-overlay="8" style="background-image: url({{url('uploads/front_image/'.session()->get("front_details.page_banner"))}})">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-banner-cont">
                        <h2 >{{$title }}</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                
								<li class="breadcrumb-item"><a href="{{ url('/') }} ">Home</a></li>
                           <li class="breadcrumb-item active" aria-current="page">{{$title }}</li>
                            </ol>
                        </nav>
                    </div> <!-- page banner cont -->
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </section>