@extends("admin_layouts.app")
@section('title', __('english.withdrawal_register'))
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
                 <div class="accordion" id="fillter">
                     <div class="accordion-item">
                         <h2 class="accordion-header" id="fillter">
                             <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                 <h5 class="card-title text-primary">@lang('english.flitters')</h5>
                             </button>
                         </h2>
                         <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="fillter" data-bs-parent="#fillter" style="">
                             <div class="accordion-body">
                                 <div class="row">
                                     <div class="col-md-4 p-1">
                                         {!! Form::label('english.campus', __('english.campus') . ':*') !!}
                                         {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select select2', 'required', 'id'=>'campus_id','style' => 'width:100%', 'required', 'placeholder' => __('english.all')]) !!}
                                     </div>
                                     <div class="col-md-4 p-1">
                                         {!! Form::label('english.class_level', __('english.class_level') . ':*') !!}
                                         {!! Form::select('class_level_id', $class_level, null, ['class' => 'form-select select2', 'required', 'id'=>'class_level_id','style' => 'width:100%', 'required', 'placeholder' => __('english.all')]) !!}
                                     </div>
                                    
                                 </div>
                                 <div class="d-lg-flex align-items-center mt-4 gap-3">
                                 @can('withdrawal.print_withdrawal')
                         <div class="ms-auto">
                                 <a class="btn btn-primary radius-30 mt-2 mt-lg-0 withdrawal_register_print"
                                 type="button"  href="#" data-href="{{ action('Certificate\WithdrawalRegisterController@withdrawalPrint') }}">
                                 <i class="lni lni-printer"></i>@lang('english.withdrawal_register_print')</a>
                                </div>
                                @endcan
                     </div>
                             </div>
                         </div>
                     </div>
                 </div>

             </div>
         </div>

        <div class="card">
            <div class="card-body">
                            <h5 class="card-title text-primary">@lang('english.withdrawal_register_list')</h5>
                <hr>

                <div class="table-responsive">
                    <table style="" class="table table-striped table-bordered " cellspacing="0" width="100%" id="withdrawal_register_table">
                        <thead class="table-light" width="100%">
                            <tr>
                             <th>@lang('english.action')</th>
                             <th>@lang('english.admission_date')</th>
                                <th>@lang('english.sr_no')</th>
                                <th>@lang('english.name_of_student')</th>
                                <th>@lang('english.date_of_birth_in_words_and_figures')</th>
                                <th>@lang('english.father_name')</th>
                                {{-- <th>@lang('english.cast')</th> --}}
                                <th>@lang('english.father_occupation')</th>
                                <th>@lang('english.residence')</th>
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
  <div class="modal fade withdrawal_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  </div>
@endsection
@section('javascript')
<script src="{{ asset('/js/withdrawal.js?v=' . $asset_v) }}"></script>

@endsection
