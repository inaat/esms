<div class="accordion" id="column_matching_question">
    <div class="accordion-item">
        <h2 class="accordion-header" id="column_matching_question">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#column_matching_question_collapseOne" aria-expanded="false" aria-controls="column_matching_question_collapseOne">
                <h5 class="card-title text-primary">Column Matcting Questions</h5>
            </button>
        </h2>
        <div id="column_matching_question_collapseOne" class="accordion-collapse collapse" aria-labelledby="column_matching_question" data-bs-parent="#column_matching_question" style="">
            <div class="accordion-body">
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="fieldSet" id="dialog " style="overflow: hidden;">
                            <legend class="lengend">
                                <h4><strong>@lang('english.question_configuration') <b style="color:red">(Short Questions)</b> </strong></h4>
                            </legend>
                            <br>
                        </fieldset>
                                                     <div class="row">
                       <div class="col-md-4 p-2 ">
                           {!! Form::label('header', __('english.header') . ':*') !!}
                           {!! Form::textarea('column_matching_question_header', ($class_subject->subject_input=='ur') ? 'مختصر سوالات ۔ ':'Column Matching', [
                               'class' => 'form-control'.($class_subject->subject_input=='ur') ? 'urdu_input urdu':'',
                               'style' => 'width:100%',
                               'required',
                               'placeholder'=>'Short Question Header',
                               'rows'=>2
                           ]) !!}
                       </div>
                       <div class="col-md-6 p-2 ">
                           {!! Form::label('', 'Additional Text(Column Matching Questions)' . ':*') !!}
                           {!! Form::textarea('column_matching_question_top_text', ($class_subject->subject_input=='ur') ? 'مختصر سوالات کے جواب دیں۔':'Column Matching', [
                               'class' => 'form-control'. ' '. $urdu_input ,
                               'style' => 'width:100%',
                               'required',
                               'rows'=>2
                           ]) !!}
                       </div>
                       <div class="col-md-2 p-2 ">
                  {!! Form::label('total_short_marks', __('english.total_column_matching_marks') . ':*') !!}
                  {!! Form::text('total_column_matching_marks', null, ['class' => 'form-control input_number'  , 'required','placeholder' => __('english.column_matching_marks')]) !!}
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
                                    @foreach ($column_matching_questions as $column_matching_question)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <input type="checkbox" name="column_matching_questions[]" value="{{ $column_matching_question->id }}" id="{{ $column_matching_question->id }}" class="column_matching_question_question">
                                        </td>
                                         <td>
                                       {!! $column_matching_question->chapter->chapter_name !!}
                                      </td>
                                        <td>
                                            <strong>
                                                {!! $column_matching_question->question !!}
                                            </strong> <br>
                                        </td>

                                        <td>Column Matching</td>


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
