 <div class="fee-heads-details">


     <div class="row">
         <div class="col-md-8">

             <div class="card">

                 <div class="card-body" style="zoom:70%">

                     <div class="row">
                                               <div class="col-12">

                    <span class="" style="  color: red;">Total Arrears:{{ @num_format($payment_line->amount+$other_payment_line->amount+ $transport_payment_line->amount) }}</span> <span class="blinking">{{ strtoupper($student_details->student_name .'('.$student_details->roll_no.')') }}</span><br />
</div>
                         <div class=" @if($transport_payment_line->amount<=0 &&  $other_payment_line->amount<=0) col-md-8 @elseif($transport_payment_line->amount<=0  || $other_payment_line->amount<=0) col-md-6  @else col-md-4 @endif">

                             <div class="border border-3 p-2 rounded">
                                 <span class="" style="  color: red;">Total Fee Arrears:{{ @num_format($payment_line->amount) }}</span> <span class="blinking">{{ strtoupper($student_details->student_name .'('.$student_details->roll_no.')') }}</span><br />
                                 <div class="row payment_row p-2">
                                     {{-- {!! Form::hidden('student_id', $student_details->student_id) !!} --}}
                                     <input type="hidden" name="student_id" value="{{ $student_details->student_id }}" id="student_id" />
                                     <div class="col-md-6 p-1">

                                         {!! Form::label('english.amount', __('english.amount') . ':*') !!}
                                         <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fas fa-money-bill-alt"></i></span>
                                             {!! Form::text("amount", null, ['class' => 'form-control input_number amount tabkey', 'required', 'placeholder' => 'Amount']); !!}
                                             {!! Form::hidden('student_due', $payment_line->amount,['id'=>'student_due']) !!}

                                         </div>
                                     </div>

                                     <div class="col-md-6 p-1">
                                         {!! Form::label('english.amount', __('english.discount_amount') ) !!}
                                         <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fas fa-money-bill-alt"></i></span>
                                             {!! Form::text("discount_amount",0, ['class' => 'form-control input_number discount_amount tabkey', 'required', 'placeholder' => 'Amount', 'id'=>'discount_amount']); !!}
                                         </div>
                                     </div>
                                     <div class="clearfix"></div>

                                     <div class="col-md-6 p-1" id="datetimepicker" data-target-input="nearest" data-target="#datetimepicker" data-toggle="datetimepicker">
                                         {!! Form::label('paid_on', __('english.paid_on') . ':*') !!}
                                         <div class="input-group flex-nowrap input-group-append  input-group date"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>
                                             {!! Form::text('paid_on', @format_datetime($payment_line->paid_on), ['class' => 'form-control datetimepicker-input', 'data-target' => '#datetimepicker', 'required']) !!}
                                         </div>
                                     </div>
                                     <div class="col-md-6 p-1">
                                         {!! Form::label('method', __('english.payment_method') . ':*') !!}
                                         <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fas fa-money-bill-alt"></i></span>
                                             {!! Form::select('method', $payment_types, $payment_line->method, ['class' => 'form-select select2 payment_types_dropdown', 'required', 'style' => 'width:100%;']) !!}
                                         </div>
                                     </div>

                                     <div class="clearfix"></div>
                                     <div class="col-md-6 p-1">
                                         {!! Form::label('document', __('english.attach_document') . ':') !!}
                                         {!! Form::file('document', ['class' => 'form-control ', 'id' => 'upload_document', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]) !!}

                                         @lang('english.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                                         @includeIf('components.document_help_text')

                                     </div>
                                     @if (!empty($accounts))
                                     <div class="col-md-6 p-1">
                                         {!! Form::label('account_id', __('english.payment_account') . ':') !!}
                                         <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fas fa-money-bill-alt"></i></span>
                                             {!! Form::select('account_id', $accounts, !empty($payment_line->account_id) ? $payment_line->account_id : '', ['class' => 'form-select select2 ', 'id' => 'account_id', 'required', 'style' => 'width:100%;']) !!}

                                         </div>
                                     </div>
                                     @endif
                                     <div class="clearfix"></div>

                                     @include('fee_transaction_payment.payment_type_details')
                                     <div class="col-md-4 ">
                                         <div class="form-group hide">
                                             {!! Form::label('note', __('english.payment_note') . ':') !!}
                                             {!! Form::textarea('note', $payment_line->note, ['class' => 'form-control', 'rows' => 3]) !!}
                                         </div>
                                     </div>
                                     <div class="col-md-8">
                                         <h5>Remaining Balance:<span class="remaining-balance">Rs 0</span></h5>
                                         {!! Form::hidden('balance', 0, ['id' => 'balance']) !!}

                                     </div>
                                 </div>

                                
                             </div>
                         </div>
                         @include('individual-fee-collection.other_payment_row')
                         @include('individual-fee-collection.transport_payment_row')
                          <div class="col-12">
                                     {{-- <div class="d-grid">
                                 <button type="submit" class="btn btn-primary fee_due">Save</button>
                             </div> --}}

                                     <div style="margin:20px;text-align: center;">
                                         <button type="submit" class="btn btn-primary  fee_due tabkey">@lang('english.save')</button>

                                         @can('print.challan_print')
                                         <a class="btn btn-primary  print-invoice " href="#" data-href="{{ action('SchoolPrinting\FeeCardPrintController@singlePrint', [$fee_transaction->id]) }}"><i class="fas fa-print"></i> @lang('english.challan_print')</a>
                                         @endcan
                                     </div>

                                 </div>
                     </div>
                 </div>
             </div>
             <div class="card">
                 <div class="card-header">
                     <h4 class="card-title">@lang('english.payment_history')</h4>
                 </div>
                 <div class="card-body">
                     <div class="table-responsive">
                         <table class="table table-bordered table-striped" id="payment_line_table">
                             <thead>
                                 <tr>
                                     <th>@lang('english.date')</th>
                                     <th>@lang('english.amount')</th>
                                     <th>@lang('english.discount_amount')</th>
                                     <th>@lang('english.payment_method')</th>
                                     <th>@lang('english.note')</th>
                                     <th>@lang('english.account')</th>
                                     <th>@lang('english.action')</th>
                                 </tr>
                             </thead>
                             <tbody>
                                 @foreach($fee_payments as $payment)
                                 <tr>
                                     <td>{{@format_datetime($payment->paid_on)}}</td>
                                     <td>{{@num_format($payment->amount)}}</td>
                                     <td>{{@num_format($payment->discount_amount)}}</td>
                                     <td>{{$payment->method}}</td>
                                     <td>{{$payment->note}}
                                        @foreach ($payment->sub_payments as $sub)
                                        
                                        <span class="badge text-white text-uppercase  bg-primary">{{$sub->fee_transaction->type}}</span>
                                     
                                    @endforeach </td>
                                     <td>{{$payment->payment_account->name}}</td>
                                     <td>
                                       
                                         {{-- <button type="button" class="btn btn-info btn-xs edit_payment" data-href="{{action('FeeTransactionPaymentController@edit', [$payment->id]) }}"><i class="glyphicon glyphicon-edit"></i></button>
                                         &nbsp; --}}
                                         @can('fee.fee_payment_delete')
                                         <button type="button" class="btn btn-danger btn-xs pay_delete_payment" data-href="{{ action('FeeTransactionPaymentController@destroy', [$payment->id]) }}"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
                                     @endcan
                                 </tr>
                                 @endforeach
                             </tbody>
                         </table>
                     </div>

                 </div>
             </div>
         </div>


         <div class="col-md-4">
             <div class="card  ">
                 <div class="card-body">
                     <div class="form-check">
                         <input type="checkbox" class="form-check-input" name="update_transaction_fee" id="" value="1">
                         <label class="form-check-label" for="">
                             @lang('english.update_transaction_and_tuition_transport_fee')
                         </label>
                     </div>
                     <div class="col-md-12 ">
                         {!! Form::label('english.classes', __('english.classes') . '') !!}
                         {!! Form::select('transaction_class_id', $classes, $fee_transaction->class_id, ['class' => 'form-select select2 global-classes', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all'), 'id' => 'students_list_filter_class_id']) !!}
                     </div>
                     <div class="col-md-12 ">
                         {!! Form::label('english.sections', __('english.sections') . '') !!}
                         {!! Form::select('transaction_class_section_id', $sections, $fee_transaction->class_section_id, ['class' => 'form-select select2 global-class_sections', 'id' => 'students_list_filter_class_section_id', 'style' => 'width:100%', 'placeholder' => __('english.all')]) !!}
                     </div>
                     <table id="table_id_table" class="table table-condensed table-striped " id="admisssion-table">
                         <thead>
                             <tr>
                                 <th class="text-center">@lang('english.fee_heads')</th>
                                 <th class="text-center">@lang('english.enable')</th>
                                 <th class="text-center ">@lang('english.amount')</th>
                             </tr>
                         </thead>
                         <tbody>
                             {!! Form::hidden('fee_transaction_id', $fee_transaction->id) !!}
                             @php
                             $iteration=0;
                             $check_transport=1;

                             @endphp
                             @foreach ( $fee_transaction->fee_lines as $fee_line)
                             @php
                             if($fee_line->feeHead->id==3){
                             $check_transport=0;
                             }
                             @endphp
                             <tr>
                                 <td class="text-center">
                                     <div class="mt-2">{{ $fee_line->feeHead->description }}</div>
                                 </td>
                                 <td class="text-center">

                                     @php
                                     $iteration++;
                                     @endphp
                                     {!! Form::checkbox('fee_heads[' . $fee_line->feeHead->id . '][is_enabled]', 1, 1, ['class' => 'form-check-input mt-2 fee-head-check']) !!} </td>


                                 </td>

                                 <td class="text-center ">
                                     <input name="fee_heads[{{ $fee_line->feeHead->id }}][amount]" type="text" value={{ @num_format($fee_line->amount) }} class="form-control head-amount input_number">
                                     <input type="hidden" name="fee_heads[{{ $fee_line->feeHead->id }}][fee_head_id]" value="{{ $fee_line->feeHead->id }}">

                                 </td>
                             </tr>

                             @endforeach
                             @foreach ($fee_heads as $fee_head)


                             <tr>
                                 <td class="text-center">
                                     {{ $fee_head->description }}
                                 </td>
                                 <td class="text-center">
                                     @php
                                     $iteration++;
                                     @endphp
                                     {!! Form::checkbox('fee_heads[' . $fee_head->id . '][is_enabled]', 1, null, ['class' => 'form-check-input mt-2 fee-head-check']) !!} </td>


                                 </td>

                                 <td class="text-center ">
                                     <input name="fee_heads[{{ $fee_head->id }}][amount]" type="number" value={{ @num_format($fee_head->amount) }} class="form-control head-amount" value="0">
                                     <input type="hidden" name="fee_heads[{{ $fee_head->id }}][fee_head_id]" value="{{ $fee_head->id }}">

                                 </td>
                             </tr>
                             @endforeach
                             @if($check_transport==1)
                             <tr>
                                 <td class="text-center">
                                     @lang('english.transport')
                                 </td>
                                 <td class="text-center">
                                     {!! Form::checkbox('fee_heads[3][is_enabled]', 1, null, ['class' => 'form-check-input mt-2 fee-head-check']) !!} </td>
                                 </td>
                                 <td class="text-center ">
                                     <input name="fee_heads[3][amount]" type="number" value={{ @num_format(0) }} class="form-control head-amount">
                                     <input type="hidden" name="fee_heads[3][fee_head_id]" value="{{ 3}}">

                                 </td>
                             </tr>
                             @endif
                         <tfoot>
                             <tr>
                                 <td colspan="2" class="text-center">
                                     @lang('english.total')
                                 </td>
                                 <td class="text-center ">
                                     <input name="before_discount_total" type="text" readonly value="{{ @num_format($fee_transaction->before_discount_total) }}" class="form-control before_discount_total input_number">
                                 </td>
                             </tr>
                             <tr>
                                 <td colspan="2" class="text-center">
                                     @lang('english.total_discount')
                                 </td>
                                 <td class="text-center ">
                                     <input name="transaction_discount_amount" type="text" readonly value="{{ @num_format($fee_transaction->discount_amount) }}" class="form-control transaction_discount_amount input_number">
                                 </td>
                             </tr>

                             <tr>
                                 <th colspan="2" class="text-center">@lang('english.final_total')</th>
                                 <td><span class="final_total">{{ @num_format($fee_transaction->final_total) }}</span></td>
                                 <input type="hidden" name="final_total" id="final_total" value="{{ $fee_transaction->final_total }}">
                             </tr>
                         </tfoot>
                         </tbody>


                     </table>
                     <div class="row align-items-center">
                     <h4 class="card-title">@lang('english.roll_no_slip')</h4>
                         <div class="col-sm-7">
                             {!! Form::label('term', __( 'english.term' ) . ':*') !!}
                             {!! Form::select('exam_create_id',$terms,null, ['class' => 'form-select select2 exam_create_id exam_term_id', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                         </div>


                         <div class="col-sm-5 mt-3">
                             <a class="btn btn-primary radius-30  mt-lg-0 exam-print-single " type="button" href="#" data-href="{{ action('Examination\ExamDateSheetController@RollSlipPrint') }}">
                                 <i class="lni lni-printer"></i>@lang('english.print')</a> </div>
                     </div>
                 </div>
             </div>



         </div>
     </div>

     <div class="modal fade edit_payment_modal contains_select2" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
     </div>
 </div>

