@extends("admin_layouts.app")
@section('title', __('english.add_new_admission'))
@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.student_admission_form')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.student_admission_form')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        {!! Form::open(['url' => action('\App\Http\Controllers\StudentController@store'), 'method' => 'post','id' =>'student_add_form' ,'files' => true]) !!}

        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-primary">@lang('english.student_admission_form')</h6>
                <div class="row">
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-4 p-1">
                                {!! Form::label('english.campuses', __('english.campuses') . ':*') !!}
                                {!! Form::select('campus_id',$campuses,null, ['class' => 'form-select select2 campuses','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'),'id' =>'campus_id']) !!}
                            </div>
                            <div class="col-md-4 p-1">
                                {!! Form::label('english.sessions', __('english.sessions') . ':*') !!}
                                {!! Form::select('adm_session_id',$sessions,null, ['class' => 'form-select select2 ','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'),'id'=>'session_id']) !!}
                            </div>
                            <div class="col-md-4 p-1">
                                {!! Form::label('admission_no', __('english.admission_no') . ':*', ['classs' => 'form-lable']) !!}
                                {!! Form::text('admission_no',$admission_no, ['class' => 'form-control', 'required', 'readonly','placeholder' => __('english.admission_no')]) !!}

                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-3 p-1">
                                {!! Form::label('english.admission_date', __('english.admission_date') . ':*') !!}
                                <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>

                                    {!! Form::text('admission_date',@format_date('now'), ['class' => 'form-control date-picker', 'readonly', 'placeholder' => __('english.admission_date')]) !!}

                                </div>
                            </div>
                            <div class="col-md-3 p-1">
                                {!! Form::label('roll_no', __('english.roll_no') . ':*') !!}
                                {!! Form::text('roll_no', null, ['class' => 'form-control','required','readonly', 'placeholder' => __('english.roll_no'),'id' => 'roll_no']) !!}
                            </div>
                            <div class="col-md-3 p-1">
                                {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                                {!! Form::select('adm_class_id',[],null, ['class' => 'form-select select2 classes','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'),'id' =>'class_ids']) !!}
                            </div>
                            <div class="col-md-3 p-1">
                                {!! Form::label('english.sections', __('english.sections') . ':*') !!}
                                {!! Form::select('adm_class_section_id',[],null, ['class' => 'form-select select2 class_sections','id'=>'class_section_ids','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                            </div>
                            <div class="clearfix"></div>

                            <div class="col-md-3 p-1">
                                {!! Form::label('english.first_name', __('english.first_name') . ':*') !!}
                                {!! Form::text('first_name', null, ['class' => 'form-control','required', 'placeholder' => __('english.first_name')]) !!}
                            </div>
                            <div class="col-md-3 p-1">
                                {!! Form::label('english.last_name', __('english.last_name') . ':*') !!}
                                {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => __('english.last_name')]) !!}
                            </div>
                            <div class="col-md-3 p-1">
                                {!! Form::label('english.gender', __('english.gender') . ':*') !!}
                                {!! Form::select('gender', ['male' => __('english.male'), 'female' => __('english.female'), 'others' => __('english.others')],null, ['class' => 'form-select select2 ','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                            </div>
                            <div class="col-md-3 p-1">
                                {!! Form::label('english.date_of_birth', __('english.date_of_birth') . ':*') !!}
                                <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>

                                    {!! Form::text('birth_date',@format_date('now'), ['class' => 'form-control start-date-picker', 'placeholder' => __('english.date_of_birth')]) !!}

                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-3 p-1">
                                {!! Form::label('english.student_categories', __('english.student_categories') . ':*') !!}
                                {!! Form::select('category_id',$categories,null, ['class' => 'form-select select2 ', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                            </div>
                            <div class="col-md-3 p-1">
                                {!! Form::label('english.domicile', __('english.domicile') . ':*') !!}
                                {!! Form::select('domicile_id',$districts, null, ['class' => 'form-select select2 ','style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                            </div>
                            <div class="col-md-3 p-1">
                                {!! Form::label('english.religion', __('english.religion') . ':*') !!}
                                {!! Form::select('religion',['Islam'=>'Islam', 'Hinduism'=>'Hinduism', 'Christianity'=>'Christianity','Sikhism'=>'Sikhism','Buddhism'=>'Buddhism','Secular/Nonreligious/Agnostic/Atheist'=>'Secular/Nonreligious/Atheist','Other'=>'Other'],'Islam', ['class' => 'form-select select2 ','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                            </div> 
                            <div class="col-md-3 p-1">
                                {!! Form::label('mobile', __('english.mobile') . ':') !!}
                                <input type="text" name="mobile_no" class="student_mobile form-control">

                            </div>
                            <div class="clearfix"></div>


                            <div class="col-md-3 p-1">
                                {!! Form::label('email', __('english.email') . ':') !!}
                                {!! Form::email('email', null, ['class' => 'form-control','placeholder' => __('english.email'),'id' => 'student_email']) !!}
                            </div>
                            <div class="col-md-3 p-1">
                                {!! Form::label('english.cnic_number', __('english.cnic_number') . ':*') !!}
                                {!! Form::text('cnic_no', null, ['class' => 'form-control cnic', 'placeholder' => __('XXXXX-XXXXXXX-X')]) !!}
                            </div>
                            <div class="col-md-3 p-1">
                                {!! Form::label('english.blood_group', __('english.blood_group') . ':') !!}
                                {!! Form::select('blood_group',['O+'=>'O+', 'O-'=>'O-', 'A+'=>'A+','A-'=>'A-','B+'=>'B+','B-'=>'B-','AB+'=>'AB+','AB-'=>'AB-'],null, ['class' => 'form-select select2 ', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                            </div>
                            <div class="col-md-3 p-1">
                                {!! Form::label('nationality', __('english.nationality') . ':') !!}
                                {!! Form::text('nationality', 'Pakistani', ['class' => 'form-control','placeholder' => __('english.nationality')]) !!}

                            </div>
                            <div class="clearfix"></div>

                            <div class="col-md-3 p-1">
                                {!! Form::label('mother_tongue', __('english.mother_tongue') . ':') !!}
                                {!! Form::text('mother_tongue', null, ['class' => 'form-control','placeholder' => __('english.mother_tongue')]) !!}

                            </div>
                            <div class="col-md-3 p-1">
                                {!! Form::label('medical_history', __('english.medical_history') . ':') !!}
                                {!! Form::textarea('medical_history', null, ['class' => 'form-control','rows' => 1,'placeholder' => __('english.medical_history')]) !!}

                            </div> 
                            <div class="col-md-3 p-1">

                                <div class="ms-auto p-3 ">
                                    <button type="button" class="btn btn-primary radius-30 mt-lg-0 btn-modal" data-href="{{ action('StudentController@addSibling') }}" data-container=".sibling_modal">
                                        <i class="bx bxs-plus-square"></i>@lang('english.add_sibling')</button>
                                </div>

                            </div>
                            <div class="col-md-3 p-1">

                                <div class="ms-auto p-3 ">
                                    <span id="sibling_name" class="badge bg-success"></span>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="card-title ">@lang('english.student_image')</h5>
                                <img src="{{ url('uploads/student_image/default.png') }}" class="student_image card-img-top" width="192px" height="192px" alt="...">
                                {!! Form::label('student_image', __('english.student_image') . ':') !!}
                                {!! Form::file('student_image', ['accept' => 'image/*','class' => 'form-control upload_student_image']); !!}
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Parent Guardian Detail --}}
                <div class="row pt-4  remove">
                    <h5 class="card-title text-primary">@lang('english.parent_detail')</h5>

                    <div class="col-md-3 p-1">
                        {!! Form::label('english.father_name', __('english.father_name') . ':*') !!}
                        {!! Form::text('father_name', null, ['class' => 'form-control', 'required','id'=>'father_name','placeholder' => __('english.father_name')]) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('english.father_phone', __('english.father_phone') . ':*') !!}
                        {!! Form::text('father_phone',null, ['class' => 'form-control father_mobile','required','id'=>'father_phone', 'placeholder' => __('english.father_phone')]) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('english.father_occupation', __('english.father_occupation') . ':*') !!}
                        {!! Form::text('father_occupation', null, ['class' => 'form-control','id'=>'father_occupation', 'placeholder' => __('english.father_occupation')]) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('english.father_cnic_no', __('english.father_cnic_no') . ':*') !!}
                        {!! Form::text('father_cnic_no', null, ['class' => 'form-control cnic','id'=>'father_cnic_no', 'placeholder' => __('XXXXX-XXXXXXX-X')]) !!}
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('english.mother_name', __('english.mother_name') . ':*') !!}
                        {!! Form::text('mother_name', null, ['class' => 'form-control','id'=>'mother_name', 'placeholder' => __('english.mother_name')]) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('english.mother_phone', __('english.mother_phone') . ':*') !!}
                        {!! Form::text('mother_phone',null, ['class' => 'form-control mother_mobile', 'id'=>'mother_phone','placeholder' => __('english.mother_phone')]) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('english.mother_occupation', __('english.mother_occupation') . ':*') !!}
                        {!! Form::text('mother_occupation', null, ['class' => 'form-control', 'id'=>'mother_occupation','placeholder' => __('english.mother_occupation')]) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('english.mother_cnic_no', __('english.mother_cnic_no') . ':*') !!}
                        {!! Form::text('mother_cnic_no', null, ['class' => 'form-control cnic','id'=>'mother_cnic_no', 'placeholder' => __('XXXXX-XXXXXXX-X')]) !!}
                    </div>
                    <div class="clearfix"></div>

                </div>
                <div class="row  remove">
                    <div class="form-group col-md-12">
                        <label>@lang('english.if_guardian_is')<small class="req form-check-label"> *</small>&nbsp;&nbsp;&nbsp;</label>
                        <label class="radio-inline">
                            <input class="form-check-input" type="radio" name="guardian_is" required value="Father" autocomplete="off"> @lang('english.father') </label>
                        <label class="radio-inline">
                            <input class="form-check-input" type="radio" name="guardian_is" value="Mother" autocomplete="off"> @lang('english.mother') </label>
                        <label class="radio-inline">
                            <input class="form-check-input" type="radio" name="guardian_is" value="Other" autocomplete="off"> @lang('english.other') </label>
                        <span class="text-danger"></span>
                    </div>
                </div>

                <div class="row pt-4  remove ">
                    <div class="col-md-12">
                        <div class="row">
                            <h5 class="card-title text-primary">@lang('english.parent_guardian_detail')</h5>

                            <div class="col-md-3 p-1">
                                {!! Form::label('english.guardian_name', __('english.guardian_name') . ':*') !!}
                                {!! Form::text('guardian[guardian_name]', null, ['class' => 'form-control', 'id'=>'guardian_name','required','placeholder' => __('english.guardian_name')]) !!}
                            </div>
                            <div class="col-md-3 p-1">
                                {!! Form::label('english.guardian_relation', __('english.guardian_relation') . ':*') !!}
                                {!! Form::text('guardian[guardian_relation]', null, ['class' => 'form-control','id'=>'guardian_relation', 'required','placeholder' => __('english.guardian_relation')]) !!}
                            </div>

                            <div class="col-md-3 p-1">
                                {!! Form::label('english.guardian_occupation', __('english.guardian_occupation') . ':*') !!}
                                {!! Form::text('guardian[guardian_occupation]', null, ['class' => 'form-control','id'=>'guardian_occupation', 'placeholder' => __('english.guardian_occupation')]) !!}
                            </div>
                            <div class="col-md-3 p-1">
                                {!! Form::label('english.guardian_email', __('english.guardian_email') . ':*') !!}
                                {!! Form::email('guardian[guardian_email]', null, ['class' => 'form-control','id'=>'guardian_email', 'placeholder' => __('english.guardian_email')]) !!}
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-3 p-1">
                                {!! Form::label('english.guardian_phone', __('english.guardian_phone') . ':*') !!}
                                {!! Form::text('guardian[guardian_phone]',null, ['class' => 'form-control guardian_mobile','id'=>'guardian_phone','required', 'placeholder' => __('english.guardian_phone')]) !!}
                            </div>
                            <div class="col-md-9 p-1">
                                {!! Form::label('english.guardian_address', __('english.guardian_address') . ':*') !!}
                                {!! Form::textarea('guardian[guardian_address]', null, ['class' => 'form-control ','rows' => 1,'id'=>'guardian_address', 'placeholder' => __('english.guardian_address')]) !!}
                            </div>
                        </div>
                    </div>


                </div>
                 <div class="row pt-4  remove">
                    <h5 class="card-title text-primary">@lang('english.student_address_details')</h5>


                    <div class="col-md-3 p-1">
                        {!! Form::label('country_id', __('english.country_name') . ':*') !!}
                        {!! Form::select('country_id',$countries,null, ['class' => 'form-select select2 ','required','id'=>'country_id', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('province_id', __('english.provinces') . ':*') !!}
                        {!! Form::select('province_id',[],null, ['class' => 'form-select select2 ','required', 'style' => 'width:100%', 'required','id' => 'provinces_ids','placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('district_id', __('english.district_name') . ':*') !!}
                        {!! Form::select('district_id',[],null, ['class' => 'form-select select2 ','required', 'style' => 'width:100%', 'required','id' => 'district_ids','placeholder' => __('english.district_name')]) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('city_id', __('english.city_name') . ':*') !!}
                        {!! Form::select('city_id',[],null, ['class' => 'form-select select2 ','required', 'style' => 'width:100%', 'required','id' => 'city_ids','placeholder' => __('english.city_name')]) !!}
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('english.regions', __('english.regions') . ':*') !!}
                        {!! Form::select('region_id',[],null, ['class' => 'form-select select2 ','required','id'=>'region_ids', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('english.current_address', __('english.current_address') . ':*') !!}
                        {!! Form::textarea('std_current_address', null, ['class' => 'form-control ','rows' => 1, 'placeholder' => __('english.current_address')]) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('english.permanent_address', __('english.permanent_address') . ':*') !!}
                        {!! Form::textarea('std_permanent_address', null, ['class' => 'form-control ','rows' => 1, 'placeholder' => __('english.permanent_address')]) !!}
                    </div>

                    <div class="clearfix"></div>

                </div> 

                <div class="row pt-4">

                    <h5 class="card-title text-primary">@lang('english.miscellaneous_details')</h5>
                    <div class="row ">
                        <div class="form-group col-md-12">
                            <label>@lang('english.if_is_transport')<small class="req form-check-label"> *</small>&nbsp;&nbsp;&nbsp;</label>
                            <label class="radio-inline">
                                <input class="form-check-input" type="radio" name="is_transport" required value="1" autocomplete="off"> @lang('english.yes') </label>
                            <label class="radio-inline">
                                <input class="form-check-input" type="radio" name="is_transport" value="0" autocomplete="off"> @lang('english.no') </label>

                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('english.student_tuition_fee', __('english.student_tuition_fee') . ':*') !!}
                        {!! Form::number('student_tuition_fee', null, ['class' => 'form-control', 'required','placeholder' => __('english.student_tuition_fee'),'id' => 'student_tuition_fee']) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('english.student_transport_fee', __('english.student_transport_fee') . ':*') !!}
                        {!! Form::number('student_transport_fee', null, ['class' => 'form-control', 'required','placeholder' => __('english.student_transport_fee'),'id' => 'student_transport_fee']) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('english.student_vehicles', __('english.vehicles') . ':*') !!}
                        {!! Form::select('vehicle_id',$vehicles,null, ['class' => 'form-select select2 ', 'style' => 'width:100%', 'id'=>'vehicle_id','placeholder' => __('english.please_select')]) !!}
                    </div>


                    <div class="col-md-3  opening_balance p-1">
                        {!! Form::label('opening_balance', __('english.opening_balance') . ':') !!}
                        <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fas fa-money-bill-alt"></i></span>

                            {!! Form::text('opening_balance', 0, ['class' => 'form-control input_number']); !!}

                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('previous_school_name', __('english.previous_school_name') . ':') !!}
                        {!! Form::text('previous_school_name', null, ['class' => 'form-control','placeholder' => __('english.previous_school_name')]) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('last_grade', __('english.last_grade') . ':') !!}
                        {!! Form::text('last_grade', null, ['class' => 'form-control','placeholder' => __('english.last_grade')]) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('remark', __('english.remark') . ':') !!}
                        {!! Form::textarea('remark', null, ['class' => 'form-control','rows' => 3,'placeholder' => __('english.remark')]) !!}

                    </div>

                </div>
            </div>
            <input type="hidden" name="sibling_id" id="sibling_id">
            <input type="hidden" name="guardian_link_id" id="guardian_link_id">

            <div class="row">
                <div class="col-sm-12">

                    <div class="d-lg-flex align-items-center mb-4 gap-3">
                        <div class="ms-auto">
                            <button class="btn- btn btn-primary radius-30 mt-2 mt-lg-0">@lang('english.save')</button>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <!--end row-->


    {!! Form::close() !!}

</div>
</div>
<div class="modal fade sibling_modal contains_select2" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
</div>
@endsection

@section('javascript')
<script src="{{ asset('/js/lib/inputmask.bundle.js') }}"></script>
<script src="{{ asset('/js/student.js') }}"></script>
@endsection

