  <div class="accordion" id="short_question">
      <div class="accordion-item">
          <h2 class="accordion-header" id="short_question">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#short_question_collapseOne" aria-expanded="false" aria-controls="short_question_collapseOne">
                  <h5 class="card-title text-primary">Short Questions</h5>
              </button>
          </h2>
          <div id="short_question_collapseOne" class="accordion-collapse collapse" aria-labelledby="short_question" data-bs-parent="#short_question" style="">
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
                             {!! Form::textarea('short_question_header', ($class_subject->subject_input=='ur') ? 'مختصر سوالات ۔ ':'Short Question', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'placeholder'=>'Short Question Header',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-6 p-2 ">
                             {!! Form::label('', 'Additional Text(Short Question Questions)' . ':*') !!}
                             {!! Form::textarea('short_question_top_text', ($class_subject->subject_input=='ur') ? 'مختصر سوالات کے جواب دیں۔':'Attempt any SIX Parts out of the following. Eash Part carries equal marks.', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-2 p-2 ">
                    {!! Form::label('total_short_marks', __('english.total_short_marks') . ':*') !!}
                    {!! Form::text('total_short_marks', ($short_question_question_number*$short_question_question_marks)-($short_question_choice*$short_question_question_marks), ['class' => 'form-control input_number'  , 'required','placeholder' => __('english.total_short_marks')]) !!}
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
                                      @foreach ($short_question_questions as $short_question)
                                      <tr>
                                          <td>{{ $loop->iteration }}</td>
                                          <td>
                                              <input type="checkbox" name="short_question_questions[]" value="{{ $short_question->id }}" id="{{ $short_question->id }}" class="short_question_question">
                                          </td>
                                           <td>
                                         {!! $short_question->chapter->chapter_name !!}
                                        </td>
                                          <td>
                                              <strong>
                                                  {!! $short_question->question !!}
                                              </strong> <br>
                                          </td>

                                          <td>Short Question</td>


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
