@extends('admin_layouts.app')
@section('title', __('english.assignment'))
@section('wrapper')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->


            <div class="card">
                <div class="card-body">
                    <div class="accordion" id="assignments-filter">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="assignments-filter">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    <h5 class="card-title text-primary">@lang('english.assignments_filter')</h5>
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="assignments-filter"
                                data-bs-parent="#assignments-filter" style="">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-4 p-2">
                                            {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                                            {!! Form::select('campus_id', $campuses, null, [
                                                'class' => 'form-select select2 global-campuses',
                                                'style' => 'width:100%',
                                                'required',
                                                'placeholder' => __('english.please_select'),
                                            ]) !!}
                                        </div>
                                        <div class="col-md-4 p-2">
                                            {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                                            {!! Form::select('class_id', [], null, [
                                                'class' => 'form-select  select2 global-classes ',
                                                'style' => 'width:100%',
                                                'required',
                                                'placeholder' => __('english.please_select'),
                                            ]) !!}
                                        </div>
                                        <div class="col-md-4 p-2">
                                            {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                                            {!! Form::select('class_section_id', [], null, [
                                                'class' => 'form-select select2 global-class_sections',
                                                'required',
                                                'style' => 'width:100%',
                                                'placeholder' => __('english.please_select'),
                                            ]) !!}
                                        </div>
                                        <div class="col-md-4 p-2">
                                            {!! Form::label('subjects', __('english.subjects') . ':') !!}
                                            {!! Form::select('subject_id', [], null, [
                                                'class' => 'form-select select2 global-section-subjects',
                                                'id' => 'global-section-subjects',
                                                'style' => 'width:100%',
                                                'placeholder' => __('english.please_select'),
                                            ]) !!}
                                        </div>

                                    </div>
                                    <div class="clearfix"></div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">@lang('english.all_assignments')</h5>

                <div class="d-lg-flex align-items-center mb-4 gap-3">
                    <div class="ms-auto"><a class="btn btn-primary radius-30 mt-lg-0 mt-2"
                            href="{{ action('AssignmentController@create') }}">
                            <i class="bx bxs-plus-square"></i>@lang('english.add_new_assignment')</a></div>

                </div>


                <hr>

                <div class="table-responsive">
                    <table class="mb-0 table" width="100%" id="assignments_table">
                        <thead class="table-light" width="100%">
                                <tr>
                                    <th style="" data-field="operate">
                                        <div class="th-inner">Action</div>
                                        <div class="fht-cell"></div>
                                    </th>
                                    <th style="" data-field="name">
                                        <div class="th-inner sortable both">Name</div>
                                        <div class="fht-cell"></div>
                                    </th>
                                    <th style="" data-field="instructions">
                                        <div class="th-inner sortable both">
                                            Instructions</div>
                                        <div class="fht-cell"></div>
                                    </th>
                                    {{-- <th style="" data-field="file">
                                        <div class="th-inner sortable both">file</div>
                                        <div class="fht-cell"></div>
                                    </th> --}}
                                    <th >
                                            @lang('english.campus_name')
                                    </th>
                                    <th style="" data-field="class_section_name">
                                        <div class="th-inner sortable both">
                                            Class Section</div>
                                        <div class="fht-cell"></div>
                                    </th>
                                    <th style="" data-field="subject_name">
                                        <div class="th-inner sortable both">
                                            Subject</div>
                                        <div class="fht-cell"></div>
                                    </th>
                                    <th style="" data-field="due_date">
                                        <div class="th-inner sortable both">Due Date
                                        </div>
                                        <div class="fht-cell"></div>
                                    </th>
                                    <th style="" data-field="points">
                                        <div class="th-inner sortable both">Points
                                        </div>
                                        <div class="fht-cell"></div>
                                    </th>
                                    <th style="" data-field="resubmission">
                                        <div class="th-inner sortable both">Resubmission</div>
                                        <div class="fht-cell"></div>
                                    </th>
                                    <th style="" data-field="extra_days_for_resubmission">
                                        <div class="th-inner sortable both">
                                            Extra Days for Resubmission</div>
                                        <div class="fht-cell"></div>
                                    </th>
                                 
                                </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection

@section('javascript')
    <script>
$(document).ready(function() {
            $(document).on('change', '#filter_campus_id,#filter_class_id,#filter_class_section_id',  function() {
          assignments_table.ajax.reload();
         });
            //class_subjects table
            var assignments_table = $("#assignments_table").DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
          "url": "/assignments",
          "data": function ( d ) {
                if ($('#filter_campus_id').length) {
                    d.campus_id = $('#filter_campus_id').val();
                }
                if ($('#filter_class_id').length) {
                    d.class_id = $('#filter_class_id').val();
                }
                if ($('#filter_class_section_id').length) {
                    d.class_section_id = $('#filter_class_section_id').val();
                }
               
                 d = __datatable_ajax_callback(d);
            }
            },
                columns: [
                    {
                    data: "action",
                    name: "action"
                },{
                    data: "name",
                    name: "name"
                },
                {
                    data: "instructions",
                    name: "instructions"
                },
                {
              
              data: "campus_name",
              name: "campus_name"
          }, 
           {
        
              data: "class_name",
              name: "class_name"
          },{
                    data: "subject_name",
                    name: "subject_name"
                },  
              
                 {
              
                    data: "due_date",
                    name: "due_date"
                },
                {
              
              data: "points",
              name: "points"
          },
          {
              
              data: "resubmission",
              name: "resubmission"
          },
          {
              
              data: "extra_days_for_resubmission",
              name: "extra_days_for_resubmission"
          },
              ],
            });
        });
    </script>

@endsection
