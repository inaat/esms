<div class="card">
    <div class="card-body">
        <h5 class="card-title text-primary">@lang('english.assessment_list')
            <small class="text-info font-13"></small>
        </h5>
                             {!! Form::hidden('student_id',$student->id ,['class' => 'form-control ', 'id' => 'assessment_student_id']); !!}

       


        <hr>

        <div class="table-responsive">
            <table class="table mb-0" width="100%" id="assessments_table">
                <thead class="table-light" width="100%">
                    <tr>
                        <th>@lang('english.action')</th>
                        <th>@lang('english.date')</th>
                    </tr>
                </thead>

            </table>
        </div>
    </div>
</div>

