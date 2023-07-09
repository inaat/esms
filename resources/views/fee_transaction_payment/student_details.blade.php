 <div class="row  student-details">
     <div class="col-lg-4">
         <div class="border border-3 p-2 rounded">
             <div class="d-flex flex-column align-items-center text-center">
                 <img src="{{ !empty($student_details->student_image) ? url('uploads/student_image/', $student_details->student_image) : url('uploads/student_image/default.png') }}" alt="Admin" class="rounded-circle p-1 bg-primary" width="110">
                 <div class="mt-0">
                     <h4 class="mt-0 p-0" style="display:inline">{{ ucwords($student_details->student_name) }}</h4>
                     <p class="text-secondary">{{ $student_details->roll_no }}({{ $student_details->old_roll_no }}) <br>
                         {{ $student_details->current_class . '  '.$student_details->current_class_section }}<br>
                         @lang('english.father_name'): {{ ucwords($student_details->father_name)}} <br>
                         @lang('english.date_of_birth'): {{ @format_date($student_details->birth_date) }}
                         <br>
                         @lang('english.student_tuition_fee'): {{ @num_format($student_details->student_tuition_fee) }}<br>
                         @if($student_details->is_transport)
                            @lang('english.student_transport_fee') :{{ @num_format($student_details->student_transport_fee) }}</p>
                         @endif

                 </div>
                 <ul class="list-group list-group-flush">
                     <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                         <h6 class="mb-0"><i class="lni lni-phone"></i></h6>
                         <span class="text-secondary ">{{ $student_details->mobile_no }}</span>
                     </li>
                     <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                         <h6 class="mb-0"><i class="lni lni-map-marker"></i></h6>
                         <span class="text-secondary">{{ $student_details->std_current_address }}</span>
                     </li>

                 </ul>
             </div>
             <div class="row g-2 ">
                 {!! Form::hidden('student_id', $student_details->student_id, ['id' => 'student_id']) !!}

             </div>

         </div>
     </div>

     <div class="col-lg-8">
         {!! Form::open(['url' => action('FeeTransactionPaymentController@feeReceiptPayStudentDue'), 'method' => 'post', 'id' => 'student_due_form', 'files' => true]) !!}

         <div class="border border-3 p-2 rounded">
             <div class="row payment_row p-2">
                 {!! Form::hidden('student_id', $student_details->student_id) !!}

                 <div class="col-md-6 p-1">
                     {!! Form::label('english.amount', __('english.amount') . ':*') !!}
                     <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fas fa-money-bill-alt"></i></span>
                         {!! Form::text("amount", @num_format($payment_line->amount), ['class' => 'form-control input_number amount', 'required', 'placeholder' => 'Amount']); !!}
                       {!! Form::hidden('student_due', $payment_line->amount,['id'=>'student_due']) !!}

                     </div>
                 </div>

                 <div class="col-md-6 p-1">
                     {!! Form::label('english.amount', __('english.discount_amount') ) !!}
                     <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fas fa-money-bill-alt"></i></span>
                         {!! Form::text("discount_amount",0, ['class' => 'form-control input_number discount_amount', 'required', 'placeholder' => 'Amount', 'id'=>'discount_amount']); !!}
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
                 <div class="col-md-4">
                     <div class="form-group">
                         {!! Form::label('note', __('english.payment_note') . ':') !!}
                         {!! Form::textarea('note', $payment_line->note, ['class' => 'form-control', 'rows' => 3]) !!}
                     </div>
                 </div>
                 <div class="col-md-8">
                       <h5>Remaining Balance:<span class="remaining-balance">Rs 0</span></h5>
                       {!! Form::hidden('balance', 0, ['id' => 'balance']) !!}

                 </div>
             </div>

             <div class="col-12">
                 <div class="d-grid">
                     <button type="submit" class="btn btn-primary fee_due">Save</button>
                 </div>
             </div>
         </div>
     </div>
     {!! Form::close() !!}

 <script type="text/javascript">
     $(document).ready(function() {
   
    $('#datetimepicker').datetimepicker({
                format: moment_date_format + ' ' + moment_time_format,
                
            });

  $('form#student_due_form').validate({
                         rules: {
                       amount: {
                        required: true,
                    },
                   
                 
                },
              
            });


function pos_print(receipt) {
    //If printer type then connect with websocket
    if (receipt.print_type == 'printer') {
        var content = receipt;
        content.type = 'print-receipt';

        //Check if ready or not, then print.
        if (socket != null && socket.readyState == 1) {
            socket.send(JSON.stringify(content));
        } else {
            initializeSocket();
            setTimeout(function() {
                socket.send(JSON.stringify(content));
            }, 700);
        }

    } else if (receipt.html_content != '') {
        var title = document.title;
        if (typeof receipt.print_title != 'undefined') {
            document.title = receipt.print_title;
        }

        //If printer type browser then print content
        $('#receipt_section').html(receipt.html_content);
        
        __currency_convert_recursively($('#receipt_section'));

        __print_receipt('receipt_section');
           
        setTimeout(function() {
            document.title = title;
        }, 1200);
    }
}

     });

 </script>

 </div>