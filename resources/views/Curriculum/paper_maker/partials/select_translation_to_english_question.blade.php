  <div class="accordion" id="translation_to_english_question">
      <div class="accordion-item">
          <h2 class="accordion-header" id="translation_to_english_question">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#translation_to_english_question_collapseOne" aria-expanded="false" aria-controls="translation_to_english_question_collapseOne">
                  <h5 class="card-title text-primary">translation_to_english Questions</h5>
              </button>
          </h2>
          <div id="translation_to_english_question_collapseOne" class="accordion-collapse collapse" aria-labelledby="translation_to_english_question" data-bs-parent="#translation_to_english_question" style="">
              <div class="accordion-body">
                  <div class="row">
                      <div class="col-md-12">
                          <fieldset class="fieldSet" id="dialog " style="overflow: hidden;">
                              <legend class="lengend">
                                  <h4><strong>@lang('english.question_configuration') <b style="color:red">(translation_to_english Questions)</b> </strong></h4>
                              </legend>
                              <br>
                          </fieldset>
                                                       <div class="row">
                         <div class="col-md-4 p-2 ">
                             {!! Form::label('header', __('english.header') . ':*') !!}
                             {!! Form::textarea('translation_to_english_question_header', ($class_subject->subject_input=='ur') ? 'مختصر سوالات ۔ ':'Translate the following into English', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'placeholder'=>'translation_to_english Question Header',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-6 p-2 ">
                             {!! Form::label('', 'Additional Text(translation_to_english Question Questions)' . ':*') !!}
                             {!! Form::textarea('translation_to_english_question_top_text', ($class_subject->subject_input=='ur') ? 'مختصر سوالات کے جواب دیں۔':'Translate the following into English.', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-2 p-2 ">
                    {!! Form::label('total_translation_to_english_marks', __('english.total_translation_to_english_marks') . ':*') !!}
                    {!! Form::text('total_translation_to_english_marks', ($translation_to_english_question_number*$translation_to_english_question_marks)-($translation_to_english_choice*$translation_to_english_question_marks), ['class' => 'form-control input_number'  , 'required','placeholder' => __('english.total_translation_to_english_marks')]) !!}
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
                                      @foreach ($translation_to_english_questions as $translation_to_english)
                                      <tr>
                                          <td>{{ $loop->iteration }}</td>
                                          <td>
                                              <input type="checkbox" name="translation_to_english_questions[]" value="{{ $translation_to_english->id }}" id="{{ $translation_to_english->id }}" class="translation_to_english_question">
                                          </td>
                                           <td>
                                         {!! $translation_to_english->chapter->chapter_name !!}
                                        </td>
                                          <td>
                                              <strong>
                                                  {!! $translation_to_english->question !!}
                                              </strong> <br>
                                          </td>

                                          <td>translation_to_english Question</td>


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
