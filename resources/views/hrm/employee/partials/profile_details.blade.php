<div class="tshadow mb25 bozero">
    <div class="table-responsive around10 pt0">
     <h5 class="card-title text-primary">@lang('english.details')
        </h5>
        <table class="table table-hover table-striped tmb0">
            <tbody>
             <tr>
                    <td>@lang('english.father_name')</td>
                    <td>{{ $employee->father_name}}</td>
                </tr>
                <tr>
                    <td>@lang('english.joining_date')</td>
                        <td>{{ @format_date($employee->joining_date) }}</td>
                </tr>
                <tr>
                    <td>@lang('english.date_of_birth')</td>
                        <td>{{ @format_date($employee->birth_date) }}</td>
                </tr>
               
                <tr>
                    <td>@lang('english.mobile_no')</td>
                    <td>{{ $employee->mobile_no }}</td>
                </tr>
                <tr>
                    <td>@lang('english.mother_tongue')</td>
                    <td>{{ $employee->mother_tongue }}</td>
                </tr>
                <tr>
                    <td>@lang('english.religion')</td>
                    <td>{{ $employee->religion }}</td>
                </tr>
                <tr>
                    <td>@lang('english.email')</td>
                    <td class="col-md-5">{{ $employee->email }}</td>
                </tr>
                <tr>
                    <td class="col-md-4">@lang('english.blood_group')</td>
                    <td class="col-md-5">{{ $employee->blood_group }}</td>
                </tr>
                <tr>
                    <td>@lang('english.cnic_no')</td>
                    <td>{{ $employee->cnic_no }}</td>
                </tr>
                <tr>
                    <td class="col-md-4">@lang('english.department')</td>
                    <td class="col-md-5">{{ $employee->department? $employee->department->department:''}}</td>
                </tr>
                <tr>
                    <td class="col-md-4">@lang('english.education')</td>
                    <td class="col-md-5">{{ $employee->education?$employee->education->education:'' }}</td>
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
                    <td class="col-md-4">@lang('english.nationality')</td>
                    <td class="col-md-5">{{ $employee->nationality}}</td>
                </tr>
                <tr>
                    <td class="col-md-4">@lang('english.current_address')</td>
                    <td class="col-md-5">{{ $employee->current_address}}</td>
                </tr>
                <tr>
                    <td>@lang('english.permanent_address')</td>
                    <td class="col-md-5">{{ $employee->permanent_address}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@if(!empty($bank_details))
<div class="tshadow mb25 bozero">
    <h3 class="pagetitleh2">@lang('english.bank_details') </h3>
    <div class="table-responsive around10 pt10">
        <table class="table table-hover table-striped tmb0">
            <tbody>
                <tr>
                    <td class="col-md-4">@lang('english.account_holder')</td>
                    <td class="col-md-5">{{ $bank_details->account_name}}</td>
                </tr>
               
                <tr>
                    <td>@lang('english.account_number')</td>
                    <td>{{$bank_details->account_number}}</td>
                </tr>
                <tr>
                    <td>@lang('english.bank')</td>
                    <td>{{ $bank_details->bank}}</td>
                </tr>
                <tr>
                    <td>@lang('english.bin')</td>
                    <td>{{ $bank_details->bin}}</td>
                </tr>
                <tr>
                    <td>@lang('english.branch')</td>
                    <td>{{ $bank_details->branch}}</td>

                </tr>
                <tr>
                    <td>@lang('english.tax_payer_id')</td>
                    <td>{{ $bank_details->tax_payer_id}}</td>
                </tr>
                
            </tbody>
        </table>
    </div>
</div>
@endif
<div class="tshadow mb25  bozero">
    <h3 class="pagetitleh2">@lang('english.payroll_details')</h3>
    <div class="table-responsive around10 pt0">
        <table class="table table-hover table-striped tmb0">
            <tbody>
                <tr>
                    <td class="col-md-4">@lang('english.basic_salary')</td>
                    <td class="col-md-5">{{ @num_format($employee->basic_salary) }}</td>
                </tr>
              
                <tr>
                    <td class="col-md-4">@lang('english.pay_period')</td>
                    <td class="col-md-5">{{ $employee->pay_period }}</td>
                </tr>
              
                
            </tbody>
        </table>
    </div>
</div>
