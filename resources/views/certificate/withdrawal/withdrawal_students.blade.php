@extends("admin_layouts.app")
@section('title', __('english.roles'))
@section("wrapper")
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.manage_your_withdrawal_register')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.withdrawal_register')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="card">
             <div class="card-body">
                 <div class="accordion" id="student-fillter">
                     <div class="accordion-item">
                         <h2 class="accordion-header" id="student-fillter">
                             <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                 <h5 class="card-title text-primary">@lang('english.students_flitters')</h5>
                             </button>
                         </h2>
                         <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="student-fillter" data-bs-parent="#student-fillter" style="">
                             <div class="accordion-body">
                                 <div class="row">
                                     <div class="col-md-4 p-1">
                                         {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                                         {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select select2 global-campuses', 'required', 'id'=>'students_list_filter_campus_id','style' => 'width:100%', 'required', 'placeholder' => __('english.all')]) !!}
                                     </div>
                                     <div class="col-md-4 p-1">
                                         {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                                         {!! Form::select('adm_class_id', [], null, ['class' => 'form-select select2 global-classes', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all'), 'id' => 'students_list_filter_class_id']) !!}
                                     </div>
                                       <div class="col-md-4 p-1">
                                         {!! Form::label('english.class_level', __('english.class_level') . ':*') !!}
                                         {!! Form::select('class_level_id', $class_level, null, ['class' => 'form-select select2', 'required', 'id'=>'class_level_id','style' => 'width:100%', 'required', 'placeholder' => __('english.all')]) !!}
                                     </div>        
                                    <div class="clear-fix"></div>
                                     <div class="col-md-3 p-1">
                                         {!! Form::label('admission_no', __('english.admission_no'), ['classs' => 'form-lable']) !!}
                                         {!! Form::text('admission_no', null, ['class' => 'form-control', 'id'=>'students_list_filter_admission_no','placeholder' => __('english.admission_no')]) !!}
                                     </div>
                                     <div class="col-md-3 p-1">
                                         {!! Form::label('roll_no', __('english.roll_no')) !!}
                                         {!! Form::text('roll_no', null, ['class' => 'form-control', 'placeholder' => __('english.roll_no'), 'id' => 'students_list_filter_roll_no']) !!}
                                     </div>
                                    
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>

             </div>
         </div>


        <div class="card">
            <div class="card-body">
                            <h5 class="card-title text-primary">@lang('english.student_list')</h5>
                <hr>

                <div class="table-responsive">
                    <table style="" class="table table-striped table-bordered " cellspacing="0" width="100%" id="withdraw_students">
                        <thead class="table-light" width="100%">
                            <tr>
                             <th>@lang('english.action')</th>
                                <th>@lang('english.sr_no')</th>
                                <th>@lang('english.name_of_student')</th>
                                <th>@lang('english.date_of_birth_in_words_and_figures')</th>
                                <th>@lang('english.father_name')</th>
                                {{-- <th>@lang('english.cast')</th> --}}
                                <th>@lang('english.roll_no')</th>
                                <th>@lang('english.slc_no')</th>
                                <th>@lang('english.class_of_which_admitted')</th>
                                <th>@lang('english.class_from_which_withdrawn')</th>
                                <th>@lang('english.date_of_withdrawal')</th>
                                <th>@lang('english.remarks')</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
@endsection
@section('javascript')
<script src="{{ asset('/js/withdrawal.js?v=' . $asset_v) }}"></script>

@endsection
