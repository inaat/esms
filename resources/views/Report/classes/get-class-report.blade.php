<div class="remove">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-3 col-xl-2">
                            <button type="button" class="btn btn-primary mb-3 mb-lg-0" id="print_div">
                                <i class="fa fa-print"></i> @lang('english.print')</button>
                        </div>
                       

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row print_area">
        <div class="card">
            <div class=" print_section">
            @include('common.logo')
             </div>
            <div class="card-header  bg-gray mt-2 " style="height">
                <h5 class="card-title text-navy"><i class="fa fa-clipboard"></i>
                    @lang('english.report_for_class') -{{ $classes->title }} ( @lang('english.section') {{ $section->section_name }} )</h5>
            </div>
            <div class="card-body">
                <div class="col-12 row">

                    <div class="col-sm-6">
                        <div class="card card-solid  border-start border-0 border-4 border-primary">
                            <div class="card-header bg-gray bg-gray with-border ">
                                <h5 class="card-title text-navy">@lang('english.class') @lang('english.information')</h5>

                            </div>
                            <div class="card-body">
                                <span class="text-info ">@lang('english.number_of_students') : {{ $student }}</span><br>
                                <span class="text-info ">@lang('english.total_subjects') : {{ $total_subject }}</span><br>
                                <span class="text-info ">@lang('english.total_subjects_assigned_to_section') : {{ $assign_subjects->count() }}</span><br>
                                <span class="text-info "> Total Male Student: {{ $male }}</span><br>
                                <span class="text-info ">Total Female Student: {{ $female }}</span><br>
                            </div>
                        </div>
                        <div class="card card-solid  border-start border-0 border-4 border-primary">
                            <div class="card-header bg-gray bg-gray with-border ">
                                <h5 class="card-title text-navy">@lang('english.fee_types') </h5>

                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>@lang('english.fee_type')</th>
                                            <th>@lang('english.amount') </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>@lang('english.tuition_fee') </td>
                                            <td> @format_currency($classes->tuition_fee) </td>
                                        </tr>

                                        @foreach ($fee_heads as $fee_head)
                                        <tr>
                                            <td>{{ $fee_head->description }} </td>
                                            <td> @format_currency($fee_head->amount) </td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card card-solid  border-start border-0 border-4 border-primary">
                            <div class="card-header bg-gray bg-gray with-border ">
                                <h5 class="card-title text-navy">@lang('english.subjects_and_teachers')</h5>

                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                @lang('english.subjects') </th>
                                            <th>
                                                @lang('english.teachers') </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($assign_subjects as $assign_subject)
                                        <tr>
                                            <td>{{ $assign_subject->class_subject->name }}</td>
                                            <td>{{ $assign_subject->teacher->first_name .' ' . $assign_subject->teacher->last_name}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>


                </div>



            </div>
        </div>
    </div>
</div>

</div>

