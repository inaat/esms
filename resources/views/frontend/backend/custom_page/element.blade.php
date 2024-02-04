
@extends("admin_layouts.app")
@section('title', __('english.upload'))
@section('title', __('english.upload'))

@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.upload')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.upload')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">@lang('english.upload_list')</h5>
                @can('gallery.create')
                <div class="d-lg-flex align-items-center mb-4 gap-3">
                    <div class="ms-auto"><a class="btn btn-primary radius-30 mt-2 mt-lg-0 " href="{{ action('Frontend\FrontCustomPageController@addImage',[$gallery->id]) }}" >
                            <i class="bx bxs-plus-square"></i>@lang('english.upload')</a></div>
                </div>
                @endcan

                <hr>
                @can('gallery.view')
                <div class="table-responsive">
                    <table class="table mb-0" width="100%" id="gallery_table">
                        <thead class="table-light" width="100%">
                            <tr>
                               <th>#</th>
							<th>@lang('english.thumb_image')</th>
							<th>@lang('english.type')</th>
							<th>@lang('english.video_url')</th>
							<th>@lang('english.action')</th>
                            </tr>
                        </thead>
                          <tbody>
					       @if(!empty($getJson))
                            @foreach ($getJson as $key => $row )
                            <tr>
                                <td>{{ $loop->iteration }} </td>
                            @if($row['type'] ==3)
							<td><img class="img-border" src="{{ url('uploads/front_image/pdficon.png') }}" width="50" height="50"/></td>
                           @else
							<td><img class="img-border" src="{{url('uploads/front_image/'.$row['image']) }}" width="50" height="50"/></td>

                           @endif
                            <td><?php echo ($row['type'] == 1 ? "Photo" : ($row['type'] == 2 ? "Video" : "File")); ?></td>
							<td><?php echo $row['video_url']; ?></td>
							<td><a href="{{ action('Frontend\FrontCustomPageController@upload_delete',[$gallery->id,$key]) }}" class="btn btn-sm btn-danger delete_element_button"><i class="bx bxs-trash f-16 mr-15 text-white"></i> @lang("english.delete")</a></td>
                            
                            </tr>
                            @endforeach
                           @endif
					
					</tbody>
                    </table>
                </div>
                @endcan
            </div>
        </div>
        <!--end row-->
    </div>
</div>

@endsection
@section('javascript')
<script type="text/javascript">

</script>
@endsection

