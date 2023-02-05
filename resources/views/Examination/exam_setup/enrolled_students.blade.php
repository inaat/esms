@extends("admin_layouts.app")
@section('title', __('english.enrolled_students'))
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.manage_your_enrolled_students')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.enrolled_students')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">@lang('english.enrolled_students')
                </h5>
                <hr>

                <div class="table-responsive">
                    <table class="table mb-0" width="100%" id="enrolled_students">
                        <thead class="table-light" width="100%">
                            <tr>
                                <th>@lang('english.action')</th>
                                <th>@lang('english.student_name')</th>
                                <th>@lang('english.father_name')</th>
                                <th>@lang('english.roll_no')</th>
                                <th>@lang('english.class')</th>
                                <th>@lang('english.section')</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ( $students as $student )
                        <tr>
                        <td> <div class="d-flex order-actions">
                 
                      <button data-href="{{ action('Examination\ExamSetupController@destroy', [$student->id]) }}" class="btn btn-sm btn-danger delete_enrolled_button"><i class="bx bxs-trash f-16 text-white"></i> @lang("english.delete")</button>
                  </div></td>
                        <td>{{ $student->student_name }}</td>
                        <td>{{ $student->father_name }}</td>
                        <td>{{ $student->roll_no }}</td>
                        <td>{{ $student->class }}</td>
                        <td>{{ $student->section_name }}</td>
                        </tr>
                            
                        @endforeach
                        </tbody>

                    </table>
                    
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
 
  var enrolled_table=$('#enrolled_students').DataTable({
    "scrollX": true,
    dom: 'T<"clear"><"button">lfrtip',
   
  });
    $(document).on("click", "button.delete_enrolled_button", function() {
                swal({
                    title: LANG.sure,
                    text: LANG.confirm_delete_designation,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        var href = $(this).data("href");
                        var data = $(this).serialize();

                        $.ajax({
                            method: "DELETE",
                            url: href,
                            dataType: "json",
                            data: data,
                            success: function(result) {
                                if (result.success == true) {
                                    toastr.success(result.msg);
            location.reload(true);
                                } else {
                                    toastr.error(result.msg);
                                }
                            },
                        });
                    }
                });
            });
        });
    </script>
@endsection
