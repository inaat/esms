@extends("frontend.layouts.app")
@section('title', __('english.leave_applications_for_employee'))
@section('wrapper')


<div class="aboutUs">
  
    <nav aria-label="breadcrumb" class="breadjhj">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }} ">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$data->title }}</li>
        </ol>
    </nav>
    <div class="container abooutContent">
        <div class="row">
            <div class="col-md-4  d-flex">
                <div class="card sidebar radius-10 p-0 w-100 p-3">
                    <div class="card-body">
                            <ul>
                               @foreach ($nav as  $bar)
                             <li class=" {{ $data->id==$bar->id ? 'active'  : '' }}   mx-auto"><a href="{{ action('Frontend\FrontHomeController@news_show', [$bar->slug,$bar->id]) }}">{{ $bar->title }}</a></li>
                               @endforeach
                            
                            </ul>
                        
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">

                    <div class=" col-12 d-flex">
                        <div class="card radius-10 p-0 w-100 p-3">
                        <h5 class="card-title ">{{$data->title }}</h5>
                            <div class="card-body">
                  <p style="text-align: justify;">{!! $data->description !!}</p>
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

