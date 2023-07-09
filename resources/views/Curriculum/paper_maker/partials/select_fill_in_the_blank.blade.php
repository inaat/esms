  <div class="accordion" id="fill_in_the_blanks">
                         <div class="accordion-item">
                             <h2 class="accordion-header" id="fill_in_the_blanks">
                                 <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fill_in_the_blanks_collapseOne" aria-expanded="false" aria-controls="fill_in_the_blanks_collapseOne">
                                     <h5 class="card-title text-primary">Fill In The Blanks Questions</h5>
                                 </button>
                             </h2>
                             <div id="fill_in_the_blanks_collapseOne" class="accordion-collapse collapse" aria-labelledby="fill_in_the_blanks" data-bs-parent="#fill_in_the_blanks" style="">
                                 <div class="accordion-body">
                                     <div class="row">
                                         <div class="col-md-12">
                                             <fieldset class="fieldSet" id="dialog " style="overflow: hidden;">
                                                 <legend class="lengend">
                                                     <h4><strong>@lang('english.question_configuration') <b style="color:red">(Fill In The Blanks Questions)</b> </strong></h4>
                                                 </legend>
                                                 <br>
                                             </fieldset>
                                                   <div class="row">
                          <div class="col-md-4 p-2 ">
                             {!! Form::label('header', __('english.header') . ':*') !!}
                             {!! Form::textarea('fill_in_the_blanks_header','', [
                                 'class' => 'form-control',
                                 'style' => 'width:100%',
                                 'placeholder'=>'Fill In The Blanks Header',
                                 'rows'=>2
                             ]) !!}
                         </div> 
                         <div class="col-md-6 p-2 ">
                             {!! Form::label('', 'Additional Text(Fill In The Blanks Questions)' . ':*') !!}
                             {!! Form::textarea('fill_in_the_blanks_top_text',($class_subject->subject_input=='ur') ? 'خالی جگہ پرُ کریں  ۔':'Fill in the blanks	', [
                                 'class' => 'form-control'. ' '. $urdu_input ,
                                 'style' => 'width:100%',
                                 'required',
                                 'rows'=>2
                             ]) !!}
                         </div>
                          <div class="col-md-2 p-2 ">
                    {!! Form::label('total_fill_marks', __('english.total_fill_marks') . ':*') !!}
                    {!! Form::text('total_fill_marks',($fill_in_blanks_question_number*$fill_in_the_blank_marks)-($fill_in_the_blanks_question_choice*$fill_in_the_blank_marks), ['class' => 'form-control input_number'  , 'required','placeholder' => __('english.total_fill_marks')]) !!}
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
                                                         @foreach ($fill_in_the_blanks_questions as $fill_in_the_blank)
                                                         <tr>
                                                             <td>{{ $loop->iteration }}</td>
                                                             <td>
                                                                 <input type="checkbox" name="fill_in_the_blank_questions[]" value="{{ $fill_in_the_blank->id }}" id="{{ $fill_in_the_blank->id }}" class="fill_in_the_blank_question">
                                                             </td>
                                                              <td>
                                         {{ $fill_in_the_blank->chapter->chapter_name }}
                                        </td>
                                                             <td >
                                                                 <strong >
                                                                     {{ $fill_in_the_blank->question }}
                                                                 </strong> <br>
                                                             </td>

                                                             <td>@lang('english.fill_in_the_blanks')</td>


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