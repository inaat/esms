 @extends('admin_layouts.sidebar-header-less')
 @section('wrapper')
     <div class="page print_area" id="page">

         <div class="card row col-md-12 mx-auto my-3" style="max-width: 80rem;">

             <div class="card-body">
                 <div class="card-content">

                     <h1 class="card-title text-uppercase text-primary text-center">Apply Online in {{ session()->get("front_details.school_name") }}
                     </h1>
                     <hr>
                             {!! Form::open(['url' => action('\App\Http\Controllers\Frontend\FrontHomeController@saveApply'), 'method' => 'post','id' =>'student_add_form' ,'files' => true]) !!}

<div class="card">
            <div class="card-body">
            @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                <h6 class="card-title text-primary">@lang('english.student_admission_form')</h6>
                <div class="row">
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-3 p-1">
                                {!! Form::label('english.campuses', __('english.campuses') . ':*') !!}
                                {!! Form::select('campus_id',$campuses,null, ['class' => 'form-select select2 campuses','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'),'id' =>'campus_id']) !!}
                            </div>
                            <div class="col-md-3 p-1">
                                {!! Form::label('english.sessions', __('english.sessions') . ':*') !!}
                                {!! Form::select('adm_session_id',$sessions,null, ['class' => 'form-select select2 ','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'),'id'=>'session_id']) !!}
                            </div>
                            
                    
                            <div class="col-md-3 p-1">
                                {!! Form::label('english.classes', __('english.classes') . ':*') !!}
                                {!! Form::select('adm_class_id',[],null, ['class' => 'form-select select2 classes','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select'),'id' =>'class_ids']) !!}
                            </div>
                            <div class="clearfix"></div>

                            <div class="col-md-3 p-1">
                                {!! Form::label('english.first_name', __('english.first_name') . ':*') !!}
                                {!! Form::text('first_name', null, ['class' => 'form-control','required', 'placeholder' => __('english.first_name')]) !!}
                            </div>
                            <div class="col-md-3 p-1">
                                {!! Form::label('english.last_name', __('english.last_name') . ':') !!}
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
                                {!! Form::label('english.domicile', __('english.domicile') . ':*') !!}
                                {!! Form::select('domicile_id',$districts, null, ['class' => 'form-select select2 ','required','style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                            </div>
                            <div class="col-md-3 p-1">
                                {!! Form::label('english.religion', __('english.religion') . ':*') !!}
                                {!! Form::select('religion',['Islam'=>'Islam', 'Hinduism'=>'Hinduism', 'Christianity'=>'Christianity','Sikhism'=>'Sikhism','Buddhism'=>'Buddhism','Secular/Nonreligious/Agnostic/Atheist'=>'Secular/Nonreligious/Atheist','Other'=>'Other'],'Islam', ['class' => 'form-select select2 ','required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                            </div> 
                            <div class="col-md-3 p-1">
                                {!! Form::label('mobile', __('english.mobile') . ':') !!}
                                <input type="text" name="mobile_no"  class="student_mobile form-control">

                            </div>
                            <div class="clearfix"></div>


                            <div class="col-md-3 p-1">
                                {!! Form::label('email', __('english.email') . ':*') !!}
                                {!! Form::email('email', null, ['class' => 'form-control','required','placeholder' => __('english.email'),'id' => 'student_email']) !!}
                            </div>
                            <div class="col-md-3 p-1">
                                {!! Form::label('english.cnic_number', __('english.cnic_number') . ':*') !!}
                                {!! Form::text('cnic_no', null, ['class' => 'form-control cnic','required', 'placeholder' => __('XXXXX-XXXXXXX-X')]) !!}
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
                        
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="card-title ">@lang('english.student_image')</h5>
                                <img src="{{ url('uploads/student_image/default.png') }}" class="student_image card-img-top" width="192px" height="192px" alt="...">
                                {!! Form::label('student_image', __('english.student_image') . ':') !!}
                                {!! Form::file('student_image', ['accept' => 'image/*','class' => 'form-control upload_student_image']) !!}
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
                   
                </div>
                <div class="row  remove">
                    <div class="form-group col-md-12">
                        <label>@lang('english.if_guardian_is')<small class="req form-check-label"> *</small>&nbsp;&nbsp;&nbsp;</label>
                        <label class="radio-inline">
                            <input class="form-check-input" type="radio" name="guardian_is" required value="Father" autocomplete="off"> @lang('english.father') </label>
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
                                {!! Form::text('guardian_name', null, ['class' => 'form-control', 'id'=>'guardian_name','required','placeholder' => __('english.guardian_name')]) !!}
                            </div>
                            <div class="col-md-3 p-1">
                                {!! Form::label('english.guardian_relation', __('english.guardian_relation') . ':*') !!}
                                {!! Form::text('guardian_relation', null, ['class' => 'form-control','required','id'=>'guardian_relation', 'required','placeholder' => __('english.guardian_relation')]) !!}
                            </div>

                            <div class="col-md-3 p-1">
                                {!! Form::label('english.guardian_occupation', __('english.guardian_occupation') . ':*') !!}
                                {!! Form::text('guardian_occupation', null, ['class' => 'form-control','required','id'=>'guardian_occupation', 'placeholder' => __('english.guardian_occupation')]) !!}
                            </div>
                            <div class="col-md-3 p-1">
                                {!! Form::label('english.guardian_email', __('english.guardian_email') . ':*') !!}
                                {!! Form::email('guardian_email', null, ['class' => 'form-control','required','id'=>'guardian_email', 'placeholder' => __('english.guardian_email')]) !!}
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-3 p-1">
                                {!! Form::label('english.guardian_phone', __('english.guardian_phone') . ':*') !!}
                                {!! Form::text('guardian_phone',null, ['class' => 'form-control guardian_mobile','id'=>'guardian_phone','required', 'placeholder' => __('english.guardian_phone')]) !!}
                            </div>
                            <div class="col-md-9 p-1">
                                {!! Form::label('english.guardian_address', __('english.guardian_address') . ':*') !!}
                                {!! Form::textarea('guardian_address', null, ['class' => 'form-control ','required','rows' => 1,'id'=>'guardian_address', 'placeholder' => __('english.guardian_address')]) !!}
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
                        {!! Form::label('english.current_address', __('english.current_address') . ':*') !!}
                        {!! Form::textarea('std_current_address', null, ['class' => 'form-control ','required','rows' => 1, 'placeholder' => __('english.current_address')]) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('english.permanent_address', __('english.permanent_address') . ':*') !!}
                        {!! Form::textarea('std_permanent_address', null, ['class' => 'form-control ','required','rows' => 1, 'placeholder' => __('english.permanent_address')]) !!}
                    </div>

                    <div class="clearfix"></div>

                </div> 

                <div class="row pt-4">
                   <h5 class="card-title text-primary">@lang('english.miscellaneous_details')</h5>
                    <div class="row ">
                        <div class="form-group col-md-12">
                            <label>@lang('english.if_is_kmu_cat')<small class="req form-check-label"> *</small>&nbsp;&nbsp;&nbsp;</label>
                            <label class="radio-inline">
                                <input class="form-check-input" type="radio" name="is_kmu_cat" required value="1" autocomplete="off"> @lang('english.yes') </label>
                            <label class="radio-inline">
                                <input class="form-check-input" type="radio" name="is_kmu_cat" value="0" autocomplete="off"> @lang('english.no') </label>

                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('previous_college_name', __('english.previous_college_name') . ':*') !!}
                        {!! Form::text('previous_college_name', null, ['class' => 'form-control','required','placeholder' => __('english.previous_college_name')]) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('board_name', __('english.board_name') . ':*') !!}
                        {!! Form::text('board_name', null, ['class' => 'form-control','required','placeholder' => __('english.board_name')]) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('fsc_roll_no', __('english.fsc_roll_no') . ':*') !!}
                        {!! Form::text('fsc_roll_no', null, ['class' => 'form-control','required','placeholder' => __('english.fsc_roll_no')]) !!}
                    </div>
                    <div class="col-md-3 p-1">
                        {!! Form::label('fsc_marks', __('english.fsc_marks') . ':*') !!}
                        {!! Form::text('fsc_marks', null, ['class' => 'form-control','required','placeholder' => __('english.fsc_marks')]) !!}
                    </div>
                   

                </div>
            </div>
 <div class="row pt-4">
                        <h5 class="card-title text-info">@lang('english.document_details')</h5>


                        <div class="col-md-4  p-1">
                            {!! Form::label('resume', __('english.attach_fsc_mark_sheet') . ':') !!}
                            {!! Form::file('fsc_mark_sheet', ['class' => 'form-control upload_document', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]) !!}
                            @lang('english.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                            @includeIf('components.document_help_text')

                        </div>
                        <div class="col-md-4  p-1">
                            {!! Form::label('cnic_front_side', __('english.attach_cnic_front_side') . ':') !!}
                            {!! Form::file('cnic_front_side', ['class' => 'form-control upload_document', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]) !!}
                            @lang('english.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                            @includeIf('components.document_help_text')

                        </div>
                        <div class="col-md-4  p-1">
                            {!! Form::label('cnic_back_side', __('english.attach_cnic_back_side') . ':') !!}
                            {!! Form::file('cnic_back_side', ['class' => 'form-control upload_document', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]) !!}
                            @lang('english.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                            @includeIf('components.document_help_text')

                        </div>

                       


                    </div>
            <div class="row">
                <div class="col-sm-12">

                    <div class="d-lg-flex align-items-center mb-4 gap-3">
                        <div class="ms-auto">
                            <button id="yourSubmitButtonID" class="btn- btn btn-primary radius-30 mt-2 mt-lg-0">@lang('english.submit')</button>
                        </div>

                    </div>
                </div>

            </div>
        </div>


 {!! Form::close() !!}
                 </div>
             </div>
         </div>
     @endsection

   @section('javascript')
     <script type="text/javascript">
              $(document).ready(function() {

 $(document).on("change", ".campuses", function() {
        var doc = $(this);
        get_campus_class(doc);
    });



        $('input:radio[name="guardian_is"]').change(function() {
        if ($(this).is(":checked")) {
            var value = $(this).val();
            if (value == "Father") {
                $("#guardian_name").val($("#father_name").val());
                $("#guardian_phone").val($("#father_phone").val());
                $("#guardian_occupation").val($("#father_occupation").val());
                $("#guardian_relation").val("Father");
            
            } else {
                $("#guardian_name").val("");
                $("#guardian_phone").val("");
                $("#guardian_occupation").val("");
                $("#guardian_relation").val("");
            }
        }
    });
    
      var validator=$("form#student_add_form").validate({
     rules: {
          
            first_name: {
                required: true,
            },
           
           student_image: {
                required: true,
                extension: "jpg|jpeg|png",
                 // 5 MB in bytes
            },
            cnic_front_side: {
                required: true,
                extension: "jpg|jpeg|png",
                
            },
            cnic_back_side: {
                required: true,
                extension: "jpg|jpeg|png",
                
            },
            fsc_mark_sheet: {
                required: true,
                extension: "jpg|jpeg|png|pdf",
                
            },
            // Add rules for other fields as needed
        },
        messages: {
            // Existing messages...
               // Existing messages...
            first_name: {
                required: "Please enter first name",
            },
            student_image: {
                required: "Please upload a student image.",
                extension: "Please upload a valid image file (jpg, jpeg, or png).",
               
            },
            cnic_front_side: {
                required: "Please upload the NIC front side image.",
                extension: "Please upload a valid image file (jpg, jpeg, or png).",
               
            },
            cnic_back_side: {
                required: "Please upload the NIC back side image.",
                extension: "Please upload a valid image file (jpg, jpeg, or png).",
               
            },
            fsc_mark_sheet: {
                required: "Please upload the FSC mark sheet.",
                extension: "Please upload a valid image file or PDF.",
               
            },
           
            // Add messages for other fields as needed
        },
 

    
    }); 

    // Now, handle the form submission using a click event
    $("#yourSubmitButtonID").on("click", function (event) {
        event.preventDefault(); // Prevent the default form submission

        // Manually trigger the validation
        if (validator.form()) {
            // If the form is valid, submit it
            $("form#student_add_form").submit();
        }
    });
    });
  function get_campus_class(doc) {
        //Add dropdown for sub units if sub unit field is visible
        var campus_id = doc.closest(".row").find(".campuses").val();
        $.ajax({
            method: "GET",
            url: "/classes/get_campus_classes",
            dataType: "html",
            data: {
                campus_id: campus_id,
            },
            success: function(result) {
                if (result) {
                    doc.closest(".row").find(".classes").html(result);
                }
            },
        });
    }

</script>
     @endsection
