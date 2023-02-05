  <div class="accordion" id="true_and_false">
      <div class="accordion-item">
          <h2 class="accordion-header" id="true_and_false">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#true_and_false_collapseOne" aria-expanded="false" aria-controls="true_and_false_collapseOne">
                  <h5 class="card-title text-primary">True and False Questions</h5>
              </button>
          </h2>
          <div id="true_and_false_collapseOne" class="accordion-collapse collapse" aria-labelledby="true_and_false" data-bs-parent="#true_and_false" style="">
              <div class="accordion-body">
                  <div class="row">
                      <div class="col-md-12">
                          <fieldset class="fieldSet" id="dialog " style="overflow: hidden;">
                              <legend class="lengend">
                                  <h4><strong>@lang('english.question_configuration') <b style="color:red">(True and False Questions)</b> </strong></h4>
                              </legend>
                              <br>
                          </fieldset>
                        <div class="row">
                         {{-- <div class="col-md-6 p-2 ">
                             {!! Form::label('header', __('english.header') . ':*') !!}
                             {!! Form::textarea('true_and_false_header','', [
                                 'class' => 'form-control',
                                 'style' => 'width:100%',
                                 'required',
                                 'placeholder'=>'True And False Header',
                                 'rows'=>2
                             ]) !!}
                         </div> --}}
                         <div class="col-md-6 p-2 ">
                             {!! Form::label('', 'Additional Text(True And False Questions)' . ':*') !!}
                             {!! Form::textarea('true_and_false_top_text',($class_subject->subject_input=='ur') ? 'سوال کو صحیح یا غلط پر نشان زد کریں۔':'Mark the statement either True or False', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'rows'=>2
                             ]) !!}
                         </div>
                         <div class="col-md-2 p-2 ">
                    {!! Form::label('total_true_marks', __('english.total_true_marks') . ':*') !!}
                    {!! Form::text('total_true_marks', ($true_and_false_question_number*$true_and_false_question_marks)-($true_and_false_question_choice*$true_and_false_question_marks), ['class' => 'form-control input_number'  , 'required','placeholder' => __('english.total_true_marks')]) !!}
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
                                      @foreach ($true_and_false_questions as $true_and_false)
                                      <tr>
                                          <td>{{ $loop->iteration }}</td>
                                          <td>
                                              <input type="checkbox" name="true_and_false_questions[]" value="{{ $true_and_false->id }}" id="{{ $true_and_false->id }}" class="true_and_false_question">
                                          </td>
                                           <td>
                                         {{ $true_and_false->chapter->chapter_name }}
                                        </td>
                                          <td>
                                              <strong>
                                                  {!! $true_and_false->question !!}
                                              </strong> <br>
                                          </td>

                                          <td>True And False</td>


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
