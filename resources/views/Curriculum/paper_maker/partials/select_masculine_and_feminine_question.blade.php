  <div class="accordion" id="masculine_and_feminine_question">
      <div class="accordion-item">
          <h2 class="accordion-header" id="masculine_and_feminine_question">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#masculine_and_feminine_question_collapseOne" aria-expanded="false" aria-controls="masculine_and_feminine_question_collapseOne">
                  <h5 class="card-title text-primary">masculine_and_feminine Questions</h5>
              </button>
          </h2>
          <div id="masculine_and_feminine_question_collapseOne" class="accordion-collapse collapse" aria-labelledby="masculine_and_feminine_question" data-bs-parent="#masculine_and_feminine_question" style="">
              <div class="accordion-body">
                  <div class="row">
                      <div class="col-md-12">
                          <fieldset class="fieldSet" id="dialog " style="overflow: hidden;">
                              <legend class="lengend">
                                  <h4><strong>@lang('english.question_configuration') <b style="color:red">(masculine_and_feminine Questions)</b> </strong></h4>
                              </legend>
                              <br>
                          </fieldset>
                                                       <div class="row">
                         <div class="col-md-4 p-2 ">
                             {!! Form::label('header', __('english.header') . ':*') !!}
                             {!! Form::textarea('masculine_and_feminine_question_header', ($class_subject->subject_input=='ur') ? 'مختصر سوالات ۔ ':'masculine_and_feminine Question', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'placeholder'=>'masculine_and_feminine Question Header',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-6 p-2 ">
                             {!! Form::label('', 'Additional Text(masculine_and_feminine Question Questions)' . ':*') !!}
                             {!! Form::textarea('masculine_and_feminine_question_top_text', ($class_subject->subject_input=='ur') ? 'مختصر سوالات کے جواب دیں۔':'Read the following masculine_and_feminine and answer the questions given at the end.', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-2 p-2 ">
                    {!! Form::label('total_masculine_and_feminine_marks', __('english.total_masculine_and_feminine_marks') . ':*') !!}
                    {!! Form::text('total_masculine_and_feminine_marks', ($masculine_and_feminine_question_number*$masculine_and_feminine_question_marks)-($masculine_and_feminine_choice*$masculine_and_feminine_question_marks), ['class' => 'form-control input_number'  , 'required','placeholder' => __('english.total_masculine_and_feminine_marks')]) !!}
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
                                      @foreach ($masculine_and_feminine_questions as $masculine_and_feminine)
                                      <tr>
                                          <td>{{ $loop->iteration }}</td>
                                          <td>
                                              <input type="checkbox" name="masculine_and_feminine_questions[]" value="{{ $masculine_and_feminine->id }}" id="{{ $masculine_and_feminine->id }}" class="masculine_and_feminine_question">
                                          </td>
                                           <td>
                                         {!! $masculine_and_feminine->chapter->chapter_name !!}
                                        </td>
                                          <td>
                                              <strong>
                                                  {!! $masculine_and_feminine->question !!}
                                              </strong> <br>
                                          </td>

                                          <td>masculine_and_feminine Question</td>


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
