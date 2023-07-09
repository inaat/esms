  <div class="accordion" id="translation_to_urdu_question">
      <div class="accordion-item">
          <h2 class="accordion-header" id="translation_to_urdu_question">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#translation_to_urdu_question_collapseOne" aria-expanded="false" aria-controls="translation_to_urdu_question_collapseOne">
                  <h5 class="card-title text-primary">translation_to_urdu Questions</h5>
              </button>
          </h2>
          <div id="translation_to_urdu_question_collapseOne" class="accordion-collapse collapse" aria-labelledby="translation_to_urdu_question" data-bs-parent="#translation_to_urdu_question" style="">
              <div class="accordion-body">
                  <div class="row">
                      <div class="col-md-12">
                          <fieldset class="fieldSet" id="dialog " style="overflow: hidden;">
                              <legend class="lengend">
                                  <h4><strong>@lang('english.question_configuration') <b style="color:red">(translation_to_urdu Questions)</b> </strong></h4>
                              </legend>
                              <br>
                          </fieldset>
                                                       <div class="row">
                         <div class="col-md-4 p-2 ">
                             {!! Form::label('header', __('english.header') . ':*') !!}
                             {!! Form::textarea('translation_to_urdu_question_header', ($class_subject->subject_input=='ur') ? 'مختصر سوالات ۔ ':'Translate the following into English', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'placeholder'=>'translation_to_urdu Question Header',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-6 p-2 ">
                             {!! Form::label('', 'Additional Text(translation_to_urdu Question Questions)' . ':*') !!}
                             {!! Form::textarea('translation_to_urdu_question_top_text', ($class_subject->subject_input=='ur') ? 'مختصر سوالات کے جواب دیں۔':'Translate the following into English.', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-2 p-2 ">
                    {!! Form::label('total_translation_to_urdu_marks', __('english.total_translation_to_urdu_marks') . ':*') !!}
                    {!! Form::text('total_translation_to_urdu_marks', ($translation_to_urdu_question_number*$translation_to_urdu_question_marks)-($translation_to_urdu_choice*$translation_to_urdu_question_marks), ['class' => 'form-control input_number'  , 'required','placeholder' => __('english.total_translation_to_urdu_marks')]) !!}
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
                                      @foreach ($translation_to_urdu_questions as $translation_to_urdu)
                                      <tr>
                                          <td>{{ $loop->iteration }}</td>
                                          <td>
                                              <input type="checkbox" name="translation_to_urdu_questions[]" value="{{ $translation_to_urdu->id }}" id="{{ $translation_to_urdu->id }}" class="translation_to_urdu_question">
                                          </td>
                                           <td>
                                         {!! $translation_to_urdu->chapter->chapter_name !!}
                                        </td>
                                          <td>
                                              <strong>
                                                  {!! $translation_to_urdu->question !!}
                                              </strong> <br>
                                          </td>

                                          <td>translation_to_urdu Question</td>


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
