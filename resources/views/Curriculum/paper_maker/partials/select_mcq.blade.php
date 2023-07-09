<div class="accordion" id="mcq">
    <div class="accordion-item">
        <h2 class="accordion-header" id="mcq">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mcq_collapseOne" aria-expanded="false" aria-controls="mcq_collapseOne">
                <h5 class="card-title text-primary">Multple Choice Questions</h5>
            </button>
        </h2>
        <div id="mcq_collapseOne" class="accordion-collapse collapse" aria-labelledby="mcq" data-bs-parent="#mcq" style="">
            <div class="accordion-body">
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="fieldSet" id="dialog " style="overflow: hidden;">
                            <legend class="lengend">
                                <h4><strong>@lang('english.question_configuration') <b style="color:red">(Multple Choice Questions)</b> </strong></h4>
                            </legend>
                            <br>
                        </fieldset>
                         <div class="row">
                         <div class="col-md-2 p-2 ">
                             {!! Form::label('single_page', __('english.single_page') . ':*') !!}
                        {!! Form::checkbox('single_page', 1, null, ['class' => 'form-check-input mt-2 ']) !!} </td>
                        </div>
                         <div class="col-md-4 p-2 ">
                             {!! Form::label('header', __('english.header') . ':*') !!}
                             {!! Form::textarea('mcq_header', ($class_subject->subject_input=='ur') ? '':'Objective', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-4 p-2 ">
                             {!! Form::label('', 'Additional Text(Mcq Questions)' . ':*') !!}
                             {!! Form::textarea('mcq_top_text',($class_subject->subject_input=='ur') ? 'دیئے گئے سوالات کے صحیح جواب پر نشان لگائیں  ۔':'Mark the correct answer from given options.', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-2 p-2 ">
                    {!! Form::label('total_mcq_marks', __('english.total_mcq_marks') . ':*') !!}
                    {!! Form::text('total_mcq_marks', ($mcq_question_number*$mcq_question_marks)-($mcq_question_choice*$mcq_question_marks), ['class' => 'form-control input_number'  , 'required','placeholder' => __('english.total_mcq_marks')]) !!}
                </div>
                         </div>
                        <div class="row ">
                            <table class="table table-responsive table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width:5%">#</th>
                                        <th scope="col" style="width:5%">@lang('english.select')</th>
                                        <th scope="col" style="width:10%">@lang('english.chapter_name')</th>
                                        <th scope="col" style="width:70%">@lang('english.question')</th>
                                        <th scope="col" title="Question Type" style="width:10%">@lang('english.type')</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mcq_questions as $mcq)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <input type="checkbox" name="mcq_questions[]" value="{{ $mcq->id }}" id="{{ $mcq->id }}" class="mcq_question">
                                        </td>
                                        <td>
                                         {!! $mcq->chapter->chapter_name !!}
                                        </td>
                                        <td>
                                            <strong>
                                                {!! $mcq->question !!}
                                            </strong> <br>

                                            <table>
                                                <tbody>
                                                    <tr>

                                                        <td style="width:25% !important">
                                                            <strong>&nbsp;&nbsp;1: </strong>
                                                            {!! $mcq->option_a !!}
                                                        </td>
                                                        <td style="width:25% !important">
                                                            <strong>&nbsp;&nbsp;2: </strong>
                                                            {!! $mcq->option_b !!}
                                                        </td>
                                                        <td style="width:25% !important">
                                                            <strong>&nbsp;&nbsp;3: </strong>
                                                            {!! $mcq->option_c !!}
                                                        </td>
                                                        <td style="width:25% !important">
                                                            <strong>&nbsp;&nbsp;4: </strong>
                                                            {!! $mcq->option_d !!}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>

                                        <td>MCQs</td>


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
