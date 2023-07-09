@extends('admin_layouts.app')
@section('title', __('english.top_defaulters'))
@section('wrapper')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">@lang('english.top_defaulters')</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('english.top_defaulters')</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary">@lang('english.flitters')</h5>

                    <div class="row align-items-center">

                        <div class="col-sm-3">
                            {!! Form::label('english.top_defaulters', __('english.top_defaulters') . ':') !!}
                           
                             {!! Form::text('limit', 10, ['class' => 'form-control input_number','id'=>'limit','placeholder' => __('english.limit'), 'required']) !!}

                        </div>
                        <div class="col-sm-3">
                            {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                            {!! Form::select('campus_id', $campuses,null,['class' => 'form-select select2 global-campuses','id'=>'filter_campus','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                        </div>
                        <div class="col-sm-3">
                            {!! Form::label('english.classes', __('english.classes') . ':') !!}
                            {!! Form::select('class_id',[],null, ['class' => 'form-select select2 global-classes ','id'=>'filter_class','style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                        </div>
                        <div class="col-sm-3">
                            {!! Form::label('english.sections', __('english.sections') . ':') !!}
                            {!! Form::select('class_section_id', [],null, ['class' => 'form-select select2 global-class_sections', 'id'=>'filter_section','style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                        </div>
                    </div>



                    <hr>
                    <h5 class="card-title text-primary">@lang('english.top_defaulter_list')</h5>

                    <div class="table-responsive">
                        <table class="mb-0 table" width="100%" id="class_section_table">
                            <thead class="table-light" width="100%">
                                <tr>
                                    <th>@lang('english.campus_name')</th>
                                    <th>@lang('english.student_name')</th>
                                    <th>@lang('english.father_name')</th>
                                 <th>@lang('english.status')</th>
                                 <th>@lang('english.roll_no')</th>
                                    <th>@lang('english.class_name')</th>
                                    <th>@lang('Transport Due')</th>
                                    <th>@lang('english.balance')</th>
                                                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>
    <div class="modal fade class_section_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
@endsection
@section('javascript')
    <script>
        $(document).ready(function() {
         
            //class_section_table
            var class_section_table = $("#class_section_table").DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
                    "url": "/report/top-defaulter",
                    "data": function(d) {


                        if ($('#limit').length) {
                            d.limit = $('#limit').val();
                        }
                        if ($('#filter_campus').length) {
                            d.campus_id = $('#filter_campus').val();
                        }
                        if ($('#filter_class').length) {
                            d.class_id = $('#filter_class').val();
                        }
                        if ($('#filter_section').length) {
                            d.class_section_id = $('#filter_section').val();
                        }

                        d = __datatable_ajax_callback(d);
                    }
                },
                columns: [{
                        data: "campus_name",
                        name: "campus_name",
                        orderable: false,
                        searchable: false

                    }, {
                        data: "student_name",
                        name: "student_name",
                        orderable: false
                    },
                    
                    {
                data: "father_name",
                name: "father_name",
            },
            {
                data: "status",
                name: "status",
                orderable: false,
                searchable: false,
            },
            {
                data: "roll_no",
                name: "roll_no",
            },
                    {
                        data: "current_class",
                        name: "current_class",
                        orderable: false,
                        searchable: false

                    },
                    {
                data: "total_due_transport_fee",
                name: "total_due_transport_fee",
                orderable: false,
                searchable: false,
            },
            {
                data: "total_due",
                name: "total_due",
                orderable: false,
                searchable: false,
            },
                ],
            });
            $(document).on('change', '#filter_campus,#filter_class,#filter_section,#limit', function() {
                class_section_table.ajax.reload();
            });
         
          
        });
    </script>
@endsection
