 @extends("admin_layouts.app")
@section('title', __('english.roles'))
 @section('wrapper')
     <div class="page-wrapper">
         <div class="page-content">
             <!--breadcrumb-->
             <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                 <div class="breadcrumb-title pe-3">@lang('english.employee_identity_card')</div>
                 <div class="ps-3">
                     <nav aria-label="breadcrumb">
                         <ol class="breadcrumb mb-0 p-0">
                             <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                             </li>
                         </ol>
                     </nav>
                 </div>
             </div>
             <!--end breadcrumb-->
             {!! Form::open(['url' => action('SchoolPrinting\IdCardPrintController@employeeIdPrintPost'), 'method' => 'post', 'class' => '', 'novalidate' . 'id' => 'search_student_fee', 'files' => true]) !!}

             <div class="card">

                 <div class="card-body">
                     <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                     <hr>
                     <div class="row m-0">
                         <div class="col-md-3 p-2 ">
                             {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                             {!! Form::select('campus_id', $campuses, $campus_id, ['class' => 'form-select select2 global-campuses', 'required', 'id' => 'employees_list_filter_campus_id', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all')]) !!}
                         </div>
                         <div class="col-md-3 p-2">
                            {!! Form::label('english.designations', __('english.designations') . ':') !!}
                            {!! Form::select('designation_id', $designations,$designation_id, ['class' => 'form-select select2', 'style' => 'width:100%', 'placeholder' => __('english.all'), 'id' => 'designation_id']) !!}
                         </div>
                       
                     </div>
                     <div class="d-lg-flex align-items-center mt-4 gap-3">
                         <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                                 <i class="fas fa-filter"></i>@lang('english.filter')</button></div>
                     </div>
                 </div>
             </div>


             {{ Form::close() }}
             
                 @if (isset($employees))
                     {!! Form::open(['url' => action('SchoolPrinting\IdCardPrintController@employeePrintIdCard'), 'method' => 'post', 'class' => '', '' . 'id' => 'employee-form', 'files' => true]) !!}
                     <div class="row">
                        
                         <div class="col-lg-12">

                             <div class="card">
                                 <div class="card-body">
                                     <div class="table-responsive">
                                         <table class="table mb-0" width="100%" id="employees_table">
                                             <thead class="table-light" width="100%">
                                                 <tr>
                                                     {{-- <th>#</th> --}}

                                                     <th> <input type="checkbox" id="checkAll"
                                                             class="common-checkbox form-check-input mt-2" name="checkAll">
                                                         <label for="checkAll">@lang('english.all')</label>
                                                     </th>
                                                     <th>@lang('english.employee_name')</th>
                                                     <th>@lang('english.father_name')</th>
                                                     <th>@lang('english.employeeID')</th>
                                                     <th>@lang('english.gender')</th>
                                                 </tr>
                                             </thead>
                                             <tbody class="">
                                                 @foreach ($employees as $employee)
                                                     <tr>
                                                         <td>
                                                             <input type="checkbox" id="employee.{{ $employee->id }}"
                                                                 class="common-checkbox form-check-input mt-2"
                                                                 name="employee_checked[]" value="{{ $employee->id }}" }}>
                                                             <label for="employee.{{ $employee->id }}"></label>
                                                         </td>
                                                         <td>{{ ucwords($employee->first_name . ' ' . $employee->last_name) }}
                                                             <input type="hidden" name="id[]" value="{{ $employee->id }}">
                                                         </td>
                                                         <td>{{ ucwords($employee->father_name) }}</td>
                                                         <td>{{ $employee->employeeID }}</td>
                                                         <td>{{ ucwords($employee->gender) }}</td>
                                                     </tr>
                                                 @endforeach
                                             </tbody>
                                             @if ($employees->count() > 0)
                                                 <tr>
                                                     <td colspan="7">
                                                         <div class="text-center">
                                                             <button type="submit" 
                                                                 class="btn btn-primary radius-30 mt-2 mt-lg-0 fix-gr-bg mb-0 submit"
                                                                 
                                                                 data-loading-text="<i class='fas fa-spinner'></i> Processing Data">
                                                                 <span class="ti-save pr"></span>
                                                                 @lang('english.print')
                                                             </button>
                                                         </div>
                                                     </td>
                                                 </tr>
                                             @endif
                                         </table>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>

                     {{ Form::close() }}
                 @endif
             </div>
         </div>
     @endsection

     @section('javascript')

         <script type="text/javascript">
             $(document).ready(function() {


                 if ($("#employees_table").length) {
                     $("#employees_table").DataTable({
                         dom: 'T<"clear"><"button">lfrtip',
                         bFilter: true,
                         bLengthChange: true,
                         bPaginate: false,
                     });
                 }




                 // Card Assign
                 $("#checkAll").on("click", function() {
                     $(".common-checkbox").prop("checked", this.checked);
                 });

                 $(".common-checkbox").on("click", function() {
                     if (!$(this).is(":checked")) {
                         $("#checkAll").prop("checked", false);
                     }
                     var numberOfChecked = $(".common-checkbox:checked").length;
                     var totalCheckboxes = $(".common-checkbox").length;
                     var totalCheckboxes = totalCheckboxes - 1;

                     if (numberOfChecked == totalCheckboxes) {
                         $("#checkAll").prop("checked", true);
                     }
                 });


                 // fees group assign

                 $("form#employee-form").submit(function(event) {
                     var url = $("#url").val();
                     var employee_checked = $("input[name='employee_checked[]']:checked")
                         .map(function() {
                             return $(this).val();
                         })
                         .get();
                     if (employee_checked.length < 1) {
                         event.preventDefault();
                         toastr.error("Please select at least one Employee");
                         return false;
                     }
                 });

             });
         </script>
     @endsection
