@extends("admin_layouts.app")
@section('title', __('english.edit_employee'))
@section('wrapper')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">@lang('english.employee')</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('english.edit_employee')</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->
        {!! Form::open(['url' => action('Hrm\HrmEmployeeController@update',$employee->id), 'method' => 'PUT','id' =>'employee_edit_form' ,'files' => true]) !!}

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-primary">@lang('english.add_new_employee')</h6>
                    <hr>

                    <h6 class="card-title text-info"><i class="fa fa-user p-1"></i>@lang('english.personal_details')</h6>

                    <div class="row">
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-4 p-1">
                                    {!! Form::label('english.campuses', __('english.campuses') . ':*') !!}
                                    {!! Form::select('campus_id', $campuses,$employee->campus_id, ['class' => 'form-select select2 campuses', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'), 'id' => 'campus_id']) !!}
                                </div>

                                <div class="col-md-4 p-1">
                                    {!! Form::label('employeeID', __('english.employee_id') . ':*', ['classs' => 'form-lable']) !!}
                                    {!! Form::text('employeeID', $employee->employeeID, ['class' => 'form-control', 'required', 'readonly', 'placeholder' => __('english.employee_id')]) !!}

                                </div>
                                <div class="col-md-4 p-1">
                                    {!! Form::label('english.first_name', __('english.first_name') . ':*') !!}
                                    {!! Form::text('first_name',$employee->first_name, ['class' => 'form-control', 'required', 'placeholder' => __('english.first_name')]) !!}
                                </div>
                                <div class="clearfix"></div>


                                <div class="col-md-4 p-1">
                                    {!! Form::label('english.last_name', __('english.last_name') . ':*') !!}
                                    {!! Form::text('last_name',$employee->last_name, ['class' => 'form-control', 'placeholder' => __('english.last_name')]) !!}
                                </div>
                                <div class="col-md-4 p-1">
                                    {!! Form::label('english.father_name', __('english.father_name') . ':*') !!}
                                    {!! Form::text('father_name', $employee->father_name, ['class' => 'form-control', 'required', 'id' => 'father_name', 'placeholder' => __('english.father_name')]) !!}
                                </div>
                                <div class="col-md-4 p-1">
                                    {!! Form::label('english.gender', __('english.gender') . ':*') !!}
                                    {!! Form::select('gender', ['male' => __('english.male'), 'female' => __('english.female'), 'others' => __('english.others')],$employee->gender, ['class' => 'form-select select2 ', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                                </div>
                                <div class="clearfix"></div>

                                <div class="col-md-4 p-1">
                                    {!! Form::label('english.date_of_birth', __('english.date_of_birth') . ':*') !!}
                                    <div class="input-group flex-nowrap"> <span class="input-group-text"
                                            id="addon-wrapping"><i class="fa fa-calendar"></i></span>

                                        {!! Form::text('birth_date', @format_date($employee->birth_date), ['class' => 'form-control start-date-picker', 'placeholder' => __('english.date_of_birth')]) !!}

                                    </div>
                                </div>
                                <div class="col-md-4 p-1">
                                    {!! Form::label('english.joining_date', __('english.joining_date') . ':*') !!}
                                    <div class="input-group flex-nowrap"> <span class="input-group-text"
                                            id="addon-wrapping"><i class="fa fa-calendar"></i></span>

                                        {!! Form::text('joining_date', @format_date($employee->joining_date), ['class' => 'form-control date-picker', 'readonly', 'placeholder' => __('english.joining_date')]) !!}

                                    </div>
                                </div>
                             <div class="col-md-4 p-1">
                                    {!! Form::label('english.religion', __('english.religion') . ':*') !!}
                                    {!! Form::select('religion', ['Islam' => 'Islam', 'Hinduism' => 'Hinduism', 'Christianity' => 'Christianity', 'Sikhism' => 'Sikhism', 'Buddhism' => 'Buddhism', 'Secular/Nonreligious/Agnostic/Atheist' => 'Secular/Nonreligious/Atheist', 'Other' => 'Other'],$employee->religion, ['class' => 'form-select select2 ', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                                </div>

                             <div class="clearfix"></div>

                                <div class="col-md-3 p-1">
                                    {!! Form::label('mobile', __('english.mobile') . ':') !!}
                                    <input type="tel" name="mobile_no" class="mobile form-control" value="{{$employee->mobile_no }}">

                                </div>


                                <div class="col-md-3 p-1">
                                    {!! Form::label('english.cnic_number', __('english.cnic_number') . ':*') !!}
                                    {!! Form::text('cnic_no',$employee->cnic_no, ['class' => 'form-control cnic', 'placeholder' => __('XXXXX-XXXXXXX-X')]) !!}
                                </div>
                                <div class="col-md-3 p-1">
                                    {!! Form::label('english.blood_group', __('english.blood_group') . ':') !!}
                                    {!! Form::select('blood_group', ['O+' => 'O+', 'O-' => 'O-', 'A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-', 'AB+' => 'AB+', 'AB-' => 'AB-'],$employee->blood_group, ['class' => 'form-select select2 ', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                                </div>

                                <div class="clearfix"></div>
                                <div class="col-md-3 p-1">
                                    {!! Form::label('nationality', __('english.nationality') . ':') !!}
                                    {!! Form::text('nationality',$employee->nationality, ['class' => 'form-control', 'placeholder' => __('english.nationality')]) !!}

                                </div>
                                <div class="col-md-3 p-1">
                                    {!! Form::label('mother_tongue', __('english.mother_tongue') . ':') !!}
                                    {!! Form::text('mother_tongue',$employee->mother_tongue, ['class' => 'form-control', 'placeholder' => __('english.mother_tongue')]) !!}

                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="row">
                                <div class="col-md-12">
                                <img src="{{ !empty($employee->employee_image)  ? url('uploads/employee_image/', $employee->employee_image) : url('uploads/employee_image/default.png') }}" class="employee_image card-img-top" width="192px" height="192px" alt="...">
                                {!! Form::label('employee_image', __('english.employee_image') . ':') !!}
                                {!! Form::file('employee_image', ['accept' => 'image/*','class' => 'form-control upload_employee_image']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-4">

                        <div class="col-md-6">

                            <div class="row">
                                <div class="card border-top border-0 border-4 border-info">
                                    <div class="card-body">
                                        <div class="card-title d-flex align-items-center">
                                            <div><i class="fas fa-address-card me-1 font-22 text-info"></i>
                                            </div>
                                            <h5 class="mb-0 text-info">@lang('english.address_details')</h5>
                                        </div>
                                        <hr>
                                        <div class="row mb-3">
                                            {!! Form::label('country_id', __('english.country_name') . ':*', ['class' => 'col-sm-4 col-form-label']) !!}

                                            <div class="col-sm-8">
                                                {!! Form::select('country_id', $countries, $employee->country_id, ['class' => 'form-select select2 ', 'required', 'id' => 'country_id', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}

                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            {!! Form::label('province_id', __('english.province_name') . ':*', ['class' => 'col-sm-4 col-form-label']) !!}

                                            <div class="col-sm-8">
                                                {!! Form::select('province_id',$provinces, $employee->province_id, ['class' => 'form-select select2 ', 'required', 'style' => 'width:100%', 'required', 'id' => 'provinces_ids', 'placeholder' => __('english.please_select')]) !!}

                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            {!! Form::label('district_id', __('english.district_name') . ':*', ['class' => 'col-sm-4 col-form-label']) !!}

                                            <div class="col-sm-8">
                                                {!! Form::select('district_id', $districts,$employee->district_id, ['class' => 'form-select select2 ', 'required', 'style' => 'width:100%', 'required', 'id' => 'district_ids', 'placeholder' => __('english.district_name')]) !!}

                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            {!! Form::label('city_id', __('english.city_name') . ':*', ['class' => 'col-sm-4 col-form-label']) !!}

                                            <div class="col-sm-8">
                                                {!! Form::select('city_id',$cities,$employee->city_id, ['class' => 'form-select select2 ', 'required', 'style' => 'width:100%', 'required', 'id' => 'city_ids', 'placeholder' => __('english.city_name')]) !!}

                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            {!! Form::label('region_id', __('english.regions') . ':*', ['class' => 'col-sm-4 col-form-label']) !!}

                                            <div class="col-sm-8">
                                                {!! Form::select('region_id',$regions,$employee->region_id, ['class' => 'form-select select2 ', 'required', 'id' => 'region_ids', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}

                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            {!! Form::label('current_address', __('english.current_address') . ':*', ['class' => 'col-sm-4 col-form-label']) !!}

                                            <div class="col-sm-8">
                                                {!! Form::textarea('current_address',$employee->current_address, ['class' => 'form-control ', 'rows' => 1, 'placeholder' => __('english.current_address')]) !!}

                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            {!! Form::label('permanent_address', __('english.permanent_address') . ':*', ['class' => 'col-sm-4 col-form-label']) !!}

                                            <div class="col-sm-8">
                                                {!! Form::textarea('permanent_address',$employee->permanent_address, ['class' => 'form-control ', 'rows' => 1, 'placeholder' => __('english.permanent_address')]) !!}

                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="row">
                                <div class="card border-top border-0 border-4 border-info">
                                    <div class="card-body">
                                        <div class="card-title d-flex align-items-center">
                                            <div><i class="fas fa-money-bill-alt  me-1 font-22 text-info"></i>
                                            </div>
                                            <h5 class="mb-0 text-info">@lang('english.bank_details')</h5>
                                        </div>
                                        <hr>
                                        <div class="row mb-3">
                                            <label for="inputEnterYourName"
                                                class="col-sm-4 col-form-label">@lang('english.account_holder')</label>
                                            <div class="col-sm-8">
                                                {!! Form::text('bank_details[account_name]',$bank_details? $bank_details->account_name:null, ['class' => 'form-control', 'placeholder' => __('english.account_holder')]) !!}

                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="account_number"
                                                class="col-sm-4 col-form-label">@lang('english.account_number')</label>
                                            <div class="col-sm-8">
                                                {!! Form::text('bank_details[account_number]',$bank_details?  $bank_details->account_number:null, ['class' => 'form-control', 'placeholder' => __('english.account_number')]) !!}

                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="bank" class="col-sm-4 col-form-label">@lang('english.bank')</label>
                                            <div class="col-sm-8">
                                                {!! Form::text('bank_details[bank]',$bank_details? $bank_details->bank:null, ['class' => 'form-control', 'placeholder' => __('english.bank')]) !!}

                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="bin" class="col-sm-4 col-form-label">@lang('english.bin')</label>
                                            <div class="col-sm-8">
                                                {!! Form::text('bank_details[bin]',$bank_details? $bank_details->bin:null, ['class' => 'form-control', 'placeholder' => __('english.bin')]) !!}

                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="branch" class="col-sm-4 col-form-label">@lang('english.branch')</label>
                                            <div class="col-sm-8">
                                                {!! Form::text('bank_details[branch]',$bank_details? $bank_details->branch:null, ['class' => 'form-control', 'placeholder' => __('english.branch')]) !!}

                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="tax_payer_id"
                                                class="col-sm-4 col-form-label">@lang('english.tax_payer_id')</label>
                                            <div class="col-sm-8">
                                                {!! Form::text('bank_details[tax_payer_id]',$bank_details? $bank_details->tax_payer_id:null, ['class' => 'form-control', 'placeholder' => __('english.tax_payer_id')]) !!}

                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>



                    <div class="row pt-4">

                        <h5 class="card-title text-info">@lang('english.hrm_details')</h5>

                        <div class="col-md-4  p-1">
                            {!! Form::label('english.departments', __('english.departments') . ':*') !!}
                            {!! Form::select('department_id', $departments, $employee->department_id, ['class' => 'form-select select2', 'required', 'style' => 'width:100%', 'placeholder' => __('english.please_select'), 'id' => 'department_id']) !!}
                        </div>
                        <div class="col-md-4  p-1">
                            {!! Form::label('english.designations', __('english.designations') . ':*') !!}
                            {!! Form::select('designation_id', $designations,$employee->designation_id, ['class' => 'form-select select2','required', 'style' => 'width:100%', 'placeholder' => __('english.please_select'), 'id' => 'designation_id']) !!}
                        </div>
                        <div class="col-md-4  p-1">
                            {!! Form::label('english.education', __('english.education') . ':*') !!}
    {!! Form::select('education_ids[]', $educations,$employee->education_ids, ['class' => 'form-select select2', 'multiple' => 'multiple', 'required', 'style' => 'width:100%', 'placeholder' => __('english.please_select'), 'id' => 'education_id']) !!}
                        </div>
                        <div class="col-md-4  p-1">
                  {!! Form::label('role', __( 'english.role' ) . ':*') !!} @show_tooltip(__('english.admin_role_location_permission_help'))
                    {!! Form::select('role', $roles, !empty($employee->user) ? $employee->user->roles->first()->id : null, ['class' => 'form-control select2', 'style' => 'width: 100%;']); !!}
                        </div>


                    </div>
                    <div class="row pt-4">

                        <h5 class="card-title text-info">@lang('english.payroll_details')</h5>

                        <div class="col-md-6  p-1">
                            {!! Form::label('english.basic_salary', __('english.basic_salary') . ':*') !!}
                            <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i
                                        class="fas fa-money-bill-alt"></i></span>
                                {!! Form::text('basic_salary',@num_format($employee->basic_salary), ['class' => 'form-control input_number', 'required', 'placeholder' => __('english.basic_salary'), 'id' => 'basic salary']) !!}
                                {!! Form::select('pay_period', ['month' => __('english.per') . ' ' . __('english.month'), 'week' => __('english.per') . ' ' . __('english.week'), 'day' => __('english.per') . ' ' . __('english.day')], $employee->pay_period, ['class' => 'width-60 pull-left form-select select2']) !!}

                            </div>
                        </div>
                        <div class="col-md-6  p-1">
                            {!! Form::label('english.default_allowance', __('english.default_allowance') . ':*') !!}
                            <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i
                                        class="fas fa-money-bill-alt"></i></span>
                                {!! Form::text('default_allowance',@num_format($employee->default_allowance), ['class' => 'form-control input_number', 'required', 'placeholder' => __('english.default_allowance'), 'id' => 'default_allowance']) !!}

                            </div>
                        </div>
                        <div class="col-md-6  p-1">
                            {!! Form::label('english.default_deduction', __('english.default_deduction') . ':*') !!}
                            <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i
                                        class="fas fa-money-bill-alt"></i></span>
                                {!! Form::text('default_deduction',@num_format($employee->default_deduction), ['class' => 'form-control input_number', 'required', 'placeholder' => __('english.default_deduction'), 'id' => 'default_deduction']) !!}

                            </div>
                        </div>
                        <div class="col-md-6 p-1">
                            {!! Form::label('remark', __('english.remark') . ':') !!}
                            {!! Form::textarea('remark',$employee->remark, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('english.remark')]) !!}

                        </div>

                    </div>
                    <div class="row pt-4">

                        <h5 class="card-title text-info">@lang('english.roles_and_permissions')</h5>
                        <div class="col-md-4 p-1">
                            {!! Form::label('email', __('english.email') . ':') !!}
                            {!! Form::email('email', $employee->email, ['class' => 'form-control', 'placeholder' => __('english.email'), 'id' => 'email']) !!}
                        </div>
                        <div class="col-md-4  p-1">
                            {!! Form::label('password', __('english.password') . ':*') !!}
                            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => __('english.password')]) !!}

                        </div>
                        <div class="col-md-4 p-1">
                            {!! Form::label('confirm_password', __('english.confirm_password') . ':*') !!}
                            {!! Form::password('confirm_password', ['class' => 'form-control', 'placeholder' => __('english.confirm_password')]) !!}

                        </div>

                    </div>
                    <div class="row pt-4">
                        <h5 class="card-title text-info">@lang('english.document_details')</h5>


                        <div class="col-md-4  p-1">
                            {!! Form::label('resume', __('english.attach_resume') . ':') !!}
                            {!! Form::file('resume', ['class' => 'form-control upload_document', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]) !!}
                            @lang('english.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                            @includeIf('components.document_help_text')

                        </div>
                        <div class="col-md-4  p-1">
                            {!! Form::label('offerLetter', __('english.attach_offerLetter') . ':') !!}
                            {!! Form::file('offerLetter', ['class' => 'form-control upload_document', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]) !!}
                            @lang('english.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                            @includeIf('components.document_help_text')

                        </div>
                        <div class="col-md-4  p-1">
                            {!! Form::label('joiningLetter', __('english.attach_joiningLetter') . ':') !!}
                            {!! Form::file('joiningLetter', ['class' => 'form-control upload_document', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]) !!}
                            @lang('english.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                            @includeIf('components.document_help_text')

                        </div>
                        <div class="clearfix"></div>

                        <div class="col-md-4  p-1">
                            {!! Form::label('contractOrAgreement', __('english.attach_contractOrAgreement') . ':') !!}
                            {!! Form::file('contract', ['class' => 'form-control upload_document', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]) !!}
                            @lang('english.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                            @includeIf('components.document_help_text')

                        </div>
                        <div class="col-md-4  p-1">
                            {!! Form::label('IDProof', __('english.attach_IDProof') . ':') !!}
                            {!! Form::file('IDProof', ['class' => 'form-control upload_document', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]) !!}
                            @lang('english.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                            @includeIf('components.document_help_text')

                        </div>


                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">

                        <div class="d-lg-flex align-items-center mb-4 gap-3">
                            <div class="ms-auto">
                                <button class="btn- btn btn-primary radius-30 mt-2 mt-lg-0">@lang('english.update')</button>
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
@endsection

@section('javascript')
<script src="{{ asset('/js/lib/inputmask.bundle.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
              $('.mobile').intlTelInput({
                    initialCountry: "pk"
                    , separateDialCode: true
                    , hiddenInput: "mobile_no",

                    utilsScript: base_path+"/js/intl-tel-input/utils.js"
                });
                
            $('.date-picker').datepicker();
            $('.cnic').inputmask('99999-9999999-9');

        $(".upload_employee_image").on('change', function() {
            __readURL(this, '.employee_image');
        });
       


  $('form#employee_edit_form').validate({
                rules: {
                       first_name: {
                        required: true,
                    },
                    password: {
                        required: false,
                        minlength: 8
                    },
                    confirm_password: {
                        equalTo: "#password"
                    },
                 
                },
                messages: {
                    password: {
                        minlength: 'Password should be minimum 8 characters',
                    },
                    confirm_password: {
                        equalTo: 'Should be same as password'
                    }
                }
            });


        });
    </script>
@endsection



{{-- ALTER TABLE `hrm_employees` ADD `default_allowance` DECIMAL(22,4) NOT NULL DEFAULT '0' AFTER `basic_salary`, ADD `default_deduction` DECIMAL(22,4) NOT NULL DEFAULT '0' AFTER `default_allowance`; --}}
{{-- ALTER TABLE `hrm_transactions` ADD `default_allowance` DECIMAL(22,4) NOT NULL DEFAULT '0' AFTER `basic_salary`, ADD `default_deduction` DECIMAL(22,4) NOT NULL DEFAULT '0' AFTER `default_allowance`;  --}}
