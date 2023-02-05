
@extends("admin_layouts.app")
@section('title', __('english.categories'))
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.manage_your_categories')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.categories')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">@lang('english.categories_list')
                    
                </h5>


                <div class="d-lg-flex align-items-center mb-4 gap-3">

                    <div class="ms-auto">
                    @can('expense_category.create')
                        
                   
                    <button type="button" class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-modal" data-href="{{ action('ExpenseCategoryController@create') }}" data-container=".expense_category_modal">
                            <i class="bx bxs-plus-square"></i>@lang('english.add_new_category')</button></div>
 @endcan
                </div>


                <hr>

                <div class="table-responsive">
                    <table class="table mb-0" width="100%" id="expense_category_table">
                        <thead class="table-light" width="100%">
                            <tr>
                                <th>@lang('english.cat_name')</th>
                                <th>@lang('english.code')</th>
                                <th>@lang('english.action')</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<div class="modal fade expense_category_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            //designations table
            var expense_category_table = $("#expense_category_table").DataTable({
                processing: true,
                serverSide: true,
                ajax: "/expense-categories",
                columns: [{
                    data: "name",
                    name: "name"
                },
                {
                    data: "code",
                    name: "code"
                },
                {
                    data: "action",
                    name: "action"
                },
                ],
            });

            $(document).on("submit", "form#expense_category_add_form", function(e) {
                e.preventDefault();
                var form = $(this);
                var data = form.serialize();

                $.ajax({
                    method: "POST",
                    url: $(this).attr("action"),
                    dataType: "json",
                    data: data,
                    beforeSend: function(xhr) {
                        __disable_submit_button(form.find('button[type="submit"]'));
                    },
                    success: function(result) {
                        if (result.success == true) {
                            $("div.expense_category_modal").modal("hide");
                            toastr.success(result.msg);
                            expense_category_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });


          
            $(document).on("click", "button.delete_expense_category", function() {
                swal({
                    title: LANG.sure,
                    text: LANG.confirm_delete_categories,
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
                                    expense_category_table.ajax.reload();
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
