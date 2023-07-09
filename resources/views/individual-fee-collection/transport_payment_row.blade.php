 <div class=" @if($transport_payment_line->amount<=0) hide @elseif($transport_payment_line->amount<=0  || $other_payment_line->amount<=0) col-md-6 @else col-md-4  @endif">

                             <div class="border border-3 p-2 rounded">
                                 <span class="" style="  color: red;">Total transport Arrears:{{ @num_format($transport_payment_line->amount) }}</span> <span class="blinking">{{ strtoupper($student_details->student_name .'('.$student_details->roll_no.')') }}</span><br />
                                 <div class="row transport_payment_row p-2">
                                     <div class="col-md-6 p-1">

                                         {!! Form::label('english.amount', __('english.amount') . ':*') !!}
                                         <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fas fa-money-bill-alt"></i></span>
                                             {{-- {!! Form::text("transport_amount", 0, ['class' => 'form-control input_number transport_amount tabkey', 'required', 'placeholder' => 'Amount']); !!} --}}
                                             <input class="form-control input_number transport_amount tabkey" required="" placeholder="Amount" name="transport_amount" data-rule-max-value="{{$transport_payment_line->amount}}" data-msg-max-value="{{__('english.minimum_value_error_msg', ['value' => @num_format($transport_payment_line->amount)])}}" type="text">
                                             {!! Form::hidden('transport_student_due', $transport_payment_line->amount,['id'=>'transport_student_due']) !!}

                                         </div>
                                     </div>

                                     <div class="col-md-6 p-1">
                                         {!! Form::label('english.amount', __('english.discount_amount') ) !!}
                                         <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fas fa-money-bill-alt"></i></span>
                                             {!! Form::text("transport_discount_amount",0, ['class' => 'form-control input_number transport_discount_amount tabkey', 'required', 'placeholder' => 'Amount', 'id'=>'transport_discount_amount']); !!}
                                         </div>
                                     </div>
                                     <div class="clearfix"></div>

                                     <div class="col-md-6 p-1" id="transport_datetimepicker" data-target-input="nearest" data-target="#transport_datetimepicker" data-toggle="datetimepicker">
                                         {!! Form::label('paid_on', __('english.paid_on') . ':*') !!}
                                         <div class="input-group flex-nowrap input-group-append  input-group date"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                                             {!! Form::text('transport_paid_on', @format_datetime($transport_payment_line->paid_on), ['class' => 'form-control datetimepicker-input', 'data-target' => '#transport_datetimepicker', 'required']) !!}
                                         </div>
                                     </div>
                                     <div class="col-md-6 p-1">
                                         {!! Form::label('method', __('english.payment_method') . ':*') !!}
                                         <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fas fa-money-bill-alt"></i></span>
                                             {!! Form::select('transport_method', $payment_types, $transport_payment_line->method, ['class' => 'form-select select2 transport_payment_types_dropdown', 'required', 'style' => 'width:100%;']) !!}
                                         </div>
                                     </div>

                                     <div class="clearfix"></div>
                                     <div class="col-md-6 p-1">
                                         {!! Form::label('document', __('english.attach_document') . ':') !!}
                                         {!! Form::file('transport_document', ['class' => 'form-control ', 'id' => 'upload_document', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]) !!}

                                         @lang('english.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                                         @includeIf('components.document_help_text')

                                     </div>
                                     @if (!empty($accounts))
                                     <div class="col-md-6 p-1">
                                         {!! Form::label('account_id', __('english.payment_account') . ':') !!}
                                         <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fas fa-money-bill-alt"></i></span>
                                             {!! Form::select('transport_account_id', $accounts, !empty($transport_payment_line->account_id) ? $transport_payment_line->account_id : 3, ['class' => 'form-select select2 ', 'id' => 'account_id', 'required', 'style' => 'width:100%;']) !!}

                                         </div>
                                     </div>
                                     @endif
                                     <div class="clearfix"></div>

                                     @include('individual-fee-collection.transport_type_deatils')
                                     <div class="col-md-4 ">
                                         <div class="form-group hide">
                                             {!! Form::label('note', __('english.payment_note') . ':') !!}
                                             {!! Form::textarea('transport_note', $transport_payment_line->note, ['class' => 'form-control', 'rows' => 3]) !!}
                                         </div>
                                     </div>
                                     <div class="col-md-8">
                                         <h5>Remaining Balance:<span class="transport_remaining-balance">Rs 0</span></h5>
                                         {!! Form::hidden('transport_balance', 0, ['id' => 'transport_balance']) !!}

                                     </div>
                                 </div>

                             </div>
                         </div>