@extends("admin_layouts.app")
@section('title', __('english.bulk_edit'))
@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.bulk_edit')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.bulk_edit')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        {!! Form::open(['url' => action('StudentController@getBulkEdit'), 'method' => 'post', 'class'=>'needs-validation was-validated','novalidate'.'id' =>'search_student_fee' ,'files' => true]) !!}

        <div class="card">

            <div class="card-body">
                <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                <hr>
                <div class="row ">

                    <div class="col-md-4 p-2 ">
                        {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                        {!! Form::select('campus_id', $campuses, $campus_id,['class' => 'form-select select2 global-campuses','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-4 p-2">
                        {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                        {!! Form::select('class_id',$classes,$class_id, ['class' => 'form-select select2 global-classes ','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    {{-- <div class="col-md-4 p-2">
                        {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                        {!! Form::select('class_section_id', $sections, $class_section_id, ['class' => 'form-select select2 global-class_sections', 'required', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                  --}}
                   
                    
                </div>
                <div class="d-lg-flex align-items-center mt-4 gap-3">
                    <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                            <i class="fas fa-filter"></i>@lang('english.filter')</button></div>
                </div>
            </div>
        </div>


        {{ Form::close() }}
    

    {!! Form::open(['url' => action('StudentController@postBulkEdit'), 'method' => 'post', 'class' => '', '' . 'id' => 'mark-entry-form', 'files' => true]) !!}


    <div class="card">

        <div class="card-body">
            {{-- <h6 class="card-title text-primary">@lang('english.bulk_edit')({{ $subs[0]->subject_name->name }})({{ ucwords($subs[0]->teacher->first_name.'  ').$subs[0]->teacher->last_name }})</h6> --}}
            <hr>
            <div class="table-responsive">
                <table style="zoom:80%"id="pos_table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th >@lang('english.roll_no')</th>
                            <th >@lang('english.first_name')</th>
                            <th >@lang('english.last_name')</th>
                                                        <th >@lang('english.admission_date')</th>

                            <th >@lang('english.father_name')</th>
                            <th >@lang('english.date_of_brith')</th>
                            <th >@lang('english.tuition_fee')</th>
                            <th >@lang('english.transport_fee')</th>
                            <th >@lang('english.mobile_no')</th>
                            <th >@lang('english.address')</th>
                        </tr>
                        
                    </thead>
                    <tbody id="fn_mark">
                        @foreach ($students as $std)
                        <tr>
                            
                            <td>{{ $std->roll_no }}</td>
                            <td><input type="text" value="{{ $std->first_name}}" name="marks[{{$std->id}}][first_name]"  required class="form-control  tabkey addtabkey "> </td>
                            <td><input type="text" value="{{ $std->last_name}}" name="marks[{{$std->id}}][last_name]"   class="form-control  tabkey addtabkey "> 
                                <input type="hidden" value="{{ $std->id }}" name="marks[{{$std->id}}][student_id]"/>
                            </td>
                            
                                                 <td> 
                            <div style=""class="input-group flex-nowrap input-group-append  input-group date"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                                                 <input class="form-control datetimepicker-input date-timepicker" data-target-input="nearest" data-toggle="datetimepicker" required name="marks[{{$std->id}}][admission_date]" type="text" value="{{ @format_date($std->admission_date)}}">
                                             </div>
                                             </td>
                            <td><input type="text" value="{{ $std->father_name}}" name="marks[{{$std->id}}][father_name]"   class="form-control tabkey addtabkey "> </td>
                               <td> 
                            <div style=""class="input-group flex-nowrap input-group-append  input-group date"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                                                 <input class="form-control datetimepicker-input date-timepicker" data-target-input="nearest" data-toggle="datetimepicker" required name="marks[{{$std->id}}][birth_date]" type="text" value="{{ @format_date($std->birth_date)}}">
                                             </div>
                                             </td>
                                             
                                              <td><input type="number" value="{{ $std->student_tuition_fee}}" name="marks[{{$std->id}}][student_tuition_fee]"  required class="form-control input_number tabkey addtabkey input_number"> </td>
                            <td><input type="number" value="{{ $std->student_transport_fee}}" name="marks[{{$std->id}}][student_transport_fee]"  required class="form-control input_number tabkey addtabkey input_number"> </td>

                           
                            <td><input type="text" value="{{ $std->mobile_no}}" name="marks[{{$std->id}}][mobile_no]"   class="form-control  tabkey addtabkey "> </td>
                            <td><input type="text" value="{{ $std->std_permanent_address}}" name="marks[{{$std->id}}][std_permanent_address]"   class="form-control  tabkey addtabkey "> </td>

                        </tr>
                        @endforeach
                        
                       
                    </tbody>
                </table>
                @if ( $students->count() > 0)
                <div class="d-lg-flex align-items-center mt-4 gap-3">
                    <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0 tabkey" type="submit">
                            @lang('english.submit')</button></div>
                </div>

                @endif
            </div>


        </div>
    </div>

    {{ Form::close() }}

</div>
</div>
@endsection

@section('javascript')

<script type="text/javascript">
    $(document).ready(function() {
       
        $('body').on('keydown', 'input, select ,.select2-input', function(e) {
            if (e.key === "Enter") {
                var self = $(this)
                    , form = self.parents('form:eq(0)')
                    , focusable, next;
                focusable = form.find('.tabkey').filter(':visible');
                next = focusable.eq(focusable.index(this) + 1);
                if (next.length) {
                    next.focus();
                    next.select();

                } else {
                    mark_form_obj.submit();
                }


                return false;
            }

        });

      

    });



</script>
@endsection

