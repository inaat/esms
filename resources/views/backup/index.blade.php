@extends("admin_layouts.app")
@section('title', __('english.backup'))
@section('wrapper')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">@lang('english.backups')</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ url('/home') }}"><i class="bx bx-home-alt"></i></a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
<!-- Main content -->
<section class="content">
    
  @if (session('notification') || !empty($notification))
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                @if(!empty($notification['msg']))
                    {{$notification['msg']}}
                @elseif(session('notification.msg'))
                    {{ session('notification.msg') }}
                @endif
              </div>
          </div>  
      </div>     
  @endif

  <div class="row">
    <div class="col-sm-12">
    
          <div class="box-tools">
            @can('backup.create')
              <div class="d-lg-flex align-items-center mb-4 gap-3">
                     <div class="ms-auto"><a  id="create-new-backup-button" class="btn btn-primary radius-30 mt-2 mt-lg-0" href="{{ url('backup/create') }}">
                             <i class="bx bxs-plus-square"></i>@lang('english.create_new_backup')</a></div>
                 </div>
            @endcan
          </div>
        @if (count($backups))
                <table class="table table-striped table-bordered">
                  <thead>
                  <tr>
                      <th>@lang('english.file')</th>
                      <th>@lang('english.size')</th>
                      <th>@lang('english.date')</th>
                      <th>@lang('english.age')</th>
                      <th>@lang('english.actions')</th>
                  </tr>
                  </thead>
                    <tbody>
                    @foreach($backups as $backup)
                        <tr>
                            <td>{{ $backup['file_name'] }}</td>
                            <td>{{ humanFilesize($backup['file_size']) }}</td>
                            <td>
                                {{ Carbon::createFromTimestamp($backup['last_modified'])->toDateTimeString() }}
                            </td>
                            <td>
                                {{ Carbon::createFromTimestamp($backup['last_modified'])->diffForHumans(Carbon::now()) }}
                            </td>
                            <td>
                             @can('backup.download')

                              <a class="btn btn-xs btn-success"
                                   href="{{action('BackUpController@download', [$backup['file_name']])}}"><i
                                        class="fa fa-cloud-download"></i> @lang('english.download')</a>
                                @endcan
                                            @can('backup.delete')

                                <a class="btn btn-xs btn-danger link_confirmation" data-button-type="delete"
                                   href="{{action('BackUpController@delete', [$backup['file_name']])}}"><i class="fa fa-trash-o"></i>
                                    @lang('english.delete')</a>
                                    @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
              </table>
            @else
                <div class="well">
                    <h4>There are no backups</h4>
                </div>
            @endif
            <br>
            <strong>@lang('english.auto_backup_instruction'):</strong><br>
            <code>{{$cron_job_command}}</code> <br>
            <strong>@lang('english.backup_clean_command_instruction'):</strong><br>
            <code>{{$backup_clean_cron_job_command}}</code>
    </div>
  </div>
</section>
@endsection