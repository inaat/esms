<div class="tshadow mb25 bozero">
    <div class="table-responsive around10 pt0">
     <h5 class="card-title text-primary">@lang('english.student_details')
        </h5>
        <table class="table table-hover table-striped tmb0">
            <tbody>
                <tr>
                    <td class="col-md-4">@lang('english.admission_date')</td>
                    <td class="col-md-5">
                        {{ @format_date($student->admission_date) }}</td>
                </tr>
                <tr>
                    <td>@lang('english.date_of_birth')</td>
                        <td>{{ @format_date($student->birth_date) }}</td>
                </tr>
                <tr>
                    <td>@lang('english.student_category')</td>

                    <td>@if(!empty($student->studentCategory)){{ $student->studentCategory->cat_name }}@endif</td>
                </tr>
                <tr>
                    <td>@lang('english.mobile_no')</td>
                    <td>{{ $student->mobile_no }}</td>
                </tr>
                <tr>
                    <td>@lang('english.mother_tongue')</td>
                    <td>{{ $student->mother_tongue }}</td>
                </tr>
                <tr>
                    <td>@lang('english.religion')</td>
                    <td>{{ $student->religion }}</td>
                </tr>
                <tr>
                    <td>@lang('english.email')</td>
                    <td>{{ $student->email }}</td>
                </tr>
                <tr>
                    <td>@lang('english.medical_history')</td>
                    <td>{{ $student->medical_history}}</td>

                </tr>

                

            </tbody>
        </table>
    </div>
</div>
<div class="tshadow mb25 bozero">
    <h3 class="pagetitleh2">@lang('english.address_detail')</h3>
    <div class="table-responsive around10 pt0">
        <table class="table table-hover table-striped tmb0">
            <tbody>
                <tr>
                    <td class="col-md-4">@lang('english.current_address')</td>
                    <td>{{ $student->std_current_address}}</td>
                </tr>
                <tr>
                    <td>@lang('english.permanent_address')</td>
                    <td>{{ $student->std_permanent_address}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="tshadow mb25 bozero">
    <h3 class="pagetitleh2">@lang('english.parent_guardian_detail') </h3>
    <div class="table-responsive around10 pt10">
        <table class="table table-hover table-striped tmb0">
            <tbody>
                <tr>
                    <td class="col-md-4">@lang('english.father_name')</td>
                    <td class="col-md-5">{{ $student->father_name}}</td>
                    {{-- <td rowspan="3"><img class="profile-user-img img-responsive img-circle" src="https://demo.smart-school.in/uploads/student_images/no_image.png"></td> --}}
                </tr>
                <tr>
                    <td>@lang('english.father_phone')</td>
                    <td>{{ $student->father_phone}}</td>
                </tr>
                <tr>
                    <td>@lang('english.father_occupation')</td>
                    <td>{{ $student->father_occupation}}</td>
                </tr>
                <tr>
                    <td>@lang('english.father_cnic_no')</td>
                    <td>{{ $student->father_cnic_no}}</td>
                </tr>
                <tr>
                    <td>@lang('english.mother_name')</td>
                    <td>{{ $student->mother_name}}</td>
                    {{-- <td rowspan="3"><img class="profile-user-img img-responsive img-circle" src="https://demo.smart-school.in/uploads/student_images/no_image.png"></td> --}}
                </tr>
                <tr>
                    <td>@lang('english.mother_phone')</td>
                    <td>{{ $student->mother_phone}}</td>

                </tr>
                <tr>
                    <td>@lang('english.mother_occupation')</td>
                    <td>{{ $student->mother_occupation}}</td>
                </tr>
                <tr>

                    <td>@lang('english.guardian_name')</td>
                    <td>{{ $student->guardian->student_guardian->guardian_name }}</td>


                    {{-- <td rowspan="3"><img class="profile-user-img img-responsive img-circle" src="https://demo.smart-school.in/uploads/student_images/no_image.png"> </td> --}}

                </tr>
                <tr>
                    <td>@lang('english.guardian_email')</td>
                    <td>{{ $student->guardian->student_guardian->guardian_email }}</td>
                </tr>
                <tr>
                    <td>@lang('english.guardian_relation')</td>
                    <td>{{ $student->guardian->student_guardian->guardian_relation }}</td>
                </tr>
                <tr>
                    <td>@lang('english.guardian_phone')</td>
                    <td>{{ $student->guardian->student_guardian->guardian_phone }}</td>
                </tr>
                <tr>
                    <td>@lang('english.guardian_occupation')</td>
                    <td>{{ $student->guardian->student_guardian->guardian_occupation }}</td>
                </tr>
                <tr>
                    <td>@lang('english.guardian_address')</td>
                    <td>{{ $student->guardian->student_guardian->guardian_address }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="tshadow mb25  bozero">
    <h3 class="pagetitleh2">@lang('english.miscellaneous_details')</h3>
    <div class="table-responsive around10 pt0">
        <table class="table table-hover table-striped tmb0">
            <tbody>
                <tr>
                    <td class="col-md-4">@lang('english.blood_group')</td>
                    <td class="col-md-5">{{ $student->blood_group }}</td>
                </tr>
              
                <tr>
                    <td class="col-md-4">@lang('english.previous_school_details')</td>
                    <td class="col-md-5">{{ $student->previous_school_name }}</td>
                </tr>
                <tr>
                    <td class="col-md-4">@lang('english.cnic_no')</td>
                    <td class="col-md-5">{{ $student->cnic_no }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
