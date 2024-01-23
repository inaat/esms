@extends('frontend.layouts.app')
@section('wrapper')
    <section id="page-banner" class="pt-105 pb-200 bg_cover" data-overlay="8"
        style="background-image: url({{ url('uploads/front_image/' . session()->get('front_details.page_banner')) }})">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-banner-cont">
                        <h2>FACULTY</h2>

                    </div> <!-- page banner cont -->
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </section>

    <section id="teachers-page" class="pb-20 pt-20">
        <div class="col-lg-12">
            <div class="row">
                 @foreach ($faculty as $data)
                     
              
                <div class="col-lg-4 col-sm-8" style="padding-bottom: 50px">
                    <div class="card">
                        <div align="center" style="padding-top: 20px">
                         <img src="{{ $data['image'] }}" style="width: 374px; height: 254px; object-fit: cover;" alt="" />
</div>
                        <hr>
                        <h3 style="font-size: 25px; color: #713e7f; padding-left: 20px;">{{ $data['full_name'] }}</h3>
                        <h4
                            style="padding-left: 20px; font-size: 18px; font-family: Gotham, 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; padding-top: 10px;">
                           {{ $data['designation'] }}</h4>
                        <p style="padding-left: 20px;">{{ $data['educations'] }}</p>
                    </div>
                </div>
            

   @endforeach



            </div>
            <hr> <!-- row -->
            <!--      <div class="row">
                    <div class="col-lg-12">
                        <nav class="courses-pagination mt-50">
                            <ul class="pagination justify-content-center">
                                <li class="page-item">
                                    <a href="#" aria-label="Previous">
                                        <i class="fa fa-angle-left"></i>
                                    </a>
                                </li>
                                <li class="page-item"><a class="active" href="#">1</a></li>
                                <li class="page-item"><a href="#">2</a></li>
                                <li class="page-item"><a href="#">3</a></li>
                                <li class="page-item">
                                    <a href="#" aria-label="Next">
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>   -->
        </div> <!-- container -->
    </section>
@endsection
