  <div class="accordion" id="stanza_question">
      <div class="accordion-item">
          <h2 class="accordion-header" id="stanza_question">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#stanza_question_collapseOne" aria-expanded="false" aria-controls="stanza_question_collapseOne">
                  <h5 class="card-title text-primary">stanza Questions</h5>
              </button>
          </h2>
          <div id="stanza_question_collapseOne" class="accordion-collapse collapse" aria-labelledby="stanza_question" data-bs-parent="#stanza_question" style="">
              <div class="accordion-body">
                  <div class="row">
                      <div class="col-md-12">
                          <fieldset class="fieldSet" id="dialog " style="overflow: hidden;">
                              <legend class="lengend">
                                  <h4><strong>@lang('english.question_configuration') <b style="color:red">(stanza Questions)</b> </strong></h4>
                              </legend>
                              <br>
                          </fieldset>
                                                       <div class="row">
                         <div class="col-md-4 p-2 ">
                             {!! Form::label('header', __('english.header') . ':*') !!}
                             {!! Form::textarea('stanza_question_header', ($class_subject->subject_input=='ur') ? 'مختصر سوالات ۔ ':'stanza Question', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'placeholder'=>'stanza Question Header',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-6 p-2 ">
                             {!! Form::label('', 'Additional Text(stanza Question Questions)' . ':*') !!}
                             {!! Form::textarea('stanza_question_top_text', ($class_subject->subject_input=='ur') ? 'مختصر سوالات کے جواب دیں۔':'Read the following stanza and answer the questions given at the end.', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-2 p-2 ">
                    {!! Form::label('total_stanza_marks', __('english.total_stanza_marks') . ':*') !!}
                    {!! Form::text('total_stanza_marks', ($stanza_question_number*$stanza_question_marks)-($stanza_choice*$stanza_question_marks), ['class' => 'form-control input_number'  , 'required','placeholder' => __('english.total_stanza_marks')]) !!}
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
                                      @foreach ($stanza_questions as $stanza)
                                      <tr>
                                          <td>{{ $loop->iteration }}</td>
                                          <td>
                                              <input type="checkbox" name="stanza_questions[]" value="{{ $stanza->id }}" id="{{ $stanza->id }}" class="stanza_question">
                                          </td>
                                           <td>
                                         {!! $stanza->chapter->chapter_name !!}
                                        </td>
                                          <td>
                                              <strong>
                                                  {!! $stanza->question !!}
                                              </strong> <br>
                                          </td>

                                          <td>stanza Question</td>


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
