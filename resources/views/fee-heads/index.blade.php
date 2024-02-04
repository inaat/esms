@extends("admin_layouts.app")
@section('title', __('english.fee_heads'))
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.manage_your_heads')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.fee_heads')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
  <div class="card">

            <div class="card-body">
                <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                <hr>
                <div class="row ">

                    <div class="col-md-4 p-2 ">
                        {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                        {!! Form::select('campus_id', $campuses, null,['class' => 'form-select select2 global-campuses','id'=>'filter_campus_id','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-4 p-2">
                        {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                        {!! Form::select('class_id',[],null, ['class' => 'form-select select2 global-classes ','id'=>'filter_class_id','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                </div>

            </div>
        </div>
        <div class="card">
            <div class="card-body">
                            <h5 class="card-title text-primary">@lang('english.fee_head_list')</h5>

               <div class="d-lg-flex align-items-center mb-4 gap-3">
               @can('fee_head.create')
                    <div class="ms-auto"><button type="button"
                                class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-modal"
                                data-href="{{ action('FeeHeadController@create') }}"
                                data-container=".fee-head_modal">
                                <i class="bx bxs-plus-square"></i>@lang('english.add_new_fee_head')</button></div>
               @endcan
                </div>



                <hr>

                <div class="table-responsive">
                    <table class="table mb-0" width="100%" id="fee_heead_table">
                        <thead class="table-light" width="100%">
                            <tr>
                                <th>@lang('english.action')</th>
                                <th>@lang('english.campus_name')</th>
                                <th>@lang('english.class_title')</th>
                                <th>@lang('english.fee_head')</th>
                                <th>@lang('english.fee_amount')</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
 <div class="modal fade fee-head_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  </div>
@endsection
@section('javascript')
<script>
    $(document).ready(function() {
  
        //fee_heead_table
        var fee_heead_table = $("#fee_heead_table").DataTable({
            processing: true
            , serverSide: true,
            "ajax": {
          "url": "/fee-heads",
          "data": function ( d ) {
                if ($('#filter_campus_id').length) {
                    d.campus_id = $('#filter_campus_id').val();
                }
                if ($('#filter_class_id').length) {
                    d.class_id = $('#filter_class_id').val();
                }
               
                 d = __datatable_ajax_callback(d);
            }
            }
            , columns: [{
                    data: "action"
                    , name: "action"
                }
                , {
                    data: "campus_name"
                    , name: "campus_name"
                }
                 , {
                    data: "class_name"
                    , name: "class_name"
                }
                , {
                    data: "description"
                    , name: "description"
                }
                , {
                    data: "amount"
                    , name: "amount"
                }
            , ]
        , });
 $(document).on('change', '#filter_campus_id,#filter_class_id',  function() {
          fee_heead_table.ajax.reload();
      });
   $(document).on("submit", "form#fee_head_add_form", function (e) {
        e.preventDefault();
        var form = $(this);
        var data = form.serialize();

        $.ajax({
            method: "POST",
            url: $(this).attr("action"),
            dataType: "json",
            data: data,
            beforeSend: function (xhr) {
                __disable_submit_button(form.find('button[type="submit"]'));
            },
            success: function (result) {
                if (result.success == true) {
                    $("div.fee-head_modal").modal("hide");
                    toastr.success(result.msg);
                    fee_heead_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });
 $(document).on("click", ".edit_fee_head_button", function () {
        $("div.fee-head_modal").load($(this).data("href"), function () {
            $(this).modal("show");

            $("form#fee_head_edit_form").submit(function (e) {
                e.preventDefault();
                var form = $(this);
                var data = form.serialize();

                $.ajax({
                    method: "POST",
                    url: $(this).attr("action"),
                    dataType: "json",
                    data: data,
                    beforeSend: function (xhr) {
                        __disable_submit_button(
                            form.find('button[type="submit"]')
                        );
                    },
                    success: function (result) {
                        if (result.success == true) {
                            $("div.fee-head_modal").modal("hide");
                            toastr.success(result.msg);
                           fee_heead_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });
        });
    });  $(document).on("click", "button.delete_fee_head_button", function() {
                swal({
                    title: LANG.sure,
                    text: LANG.confirm_delete_district,
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
                                   fee_heead_table.ajax.reload();
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
