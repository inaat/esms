<div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary">@lang('english.document_list')
                        <small class="text-info font-13"></small>
                    </h5>
                             {!! Form::hidden('employee_id',$employee->id ,['class' => 'form-control ', 'id' => 'employee_id']); !!}

               <div class="d-lg-flex align-items-center mb-4 gap-3">
               @can('employee_document.create')
                    <div class="ms-auto"><button type="button"
                                class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-modal"
                                data-href="{{ action('Hrm\HrmEmployeeController@document_create',[$employee->id] )}}"
                                data-container=".document_modal">
                                <i class="bx bxs-plus-square"></i>@lang('english.add_new_document')</button></div>
                                @endcan
                </div>

                    
                    <hr>

                    <div class="table-responsive">
                        <table class="table mb-0" width="100%" id="documents_table">
                            <thead class="table-light" width="100%">
                                <tr>
                                    <th>@lang('english.type')</th>
                                    <th>@lang('english.action')</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>



 