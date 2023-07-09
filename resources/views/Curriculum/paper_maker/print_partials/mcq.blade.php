        <div>
           

         
            @if (!empty($mcq_questions))
            @if (!empty($input['mcq_header']))
            <div class="head">
                <h6>{{ $input['mcq_header'] }}
                </h6>
            </div>
            @endif
               @if (!empty($input['single_page']))
            <div style="display:flex; margin-top:10px;">
                <span style="margin-top:5px;">@lang('english.roll_#'):</span>
                <input type="text" style="width: 30px;border-radius: 2%;">
                <input type="text" style="width: 30px;border-radius: 2%;">
                <input type="text" style="width: 30px;border-radius: 2%;">
                <input type="text" style="width: 30px;border-radius: 2%;">
                <input type="text" style="width: 30px;border-radius: 2%;">
                <input type="text" style="width: 30px;border-radius: 2%;">
                <input type="text" style="width: 30px;border-radius: 2%;">
                <input type="text" style="width: 30px;border-radius: 2%;">
                <input type="text" style="width: 30px;border-radius: 2%;">
                <span style="margin-top:5px; margin-left:10px">@lang('english.name'):_______________________</span>

            </div>
            @endif
            <div class="row" style=" ">

                <table style="border:none">
                    <tbody>
                        <tr class="tr1" >
                            <td colspan="4" class="td1 QuestHeadLeft" style="font-weight:bold;text-align:left;font-size:16px ">
                                Q.{{ $question_count }}: {{ $input['mcq_top_text'] }}
                                @php
                                $question_count++;
                                @endphp
                            </td>

                            <td colspan="4" class="QuestHeadLeft textright  td1" style="">

                                <span style="float:right;font-size:16px" id="qmrks"><strong>({{ $input['total_mcq_marks'] }})</strong></span>

                            </td>
                        </tr>

                    </tbody>
                </table>

                @foreach ($mcq_questions as $mcq)
                <table style="border:none; margin-left:15px; " class="mcqstable">
                    <tbody>
                        <tr class="tr1 mcqstable">
                            <td colspan="8" class=" halfdevider td1 mcqstable" style="text-align:left !important;font-size:14px !important">

                                <span style="font-weight:bold !important font-size:14px ">
                                    {{ numberToRoman($loop->iteration) }}: &nbsp;</span>
                                {!! $mcq->question !!}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table style="border:none; margin-left:20px;" class="mcqstable">
                    <tbody>
                        <tr class="tr1 mcqstable">

                            <td style="" class="td1 mcqstable">
                                <span class="circle">a</span>

                                <span style="text-align:center;font-size:14px !important">{!! $mcq->option_a !!}</span>

                            </td>
                            <td style="" class="td1 mcqstable">
                                <span class="circle">b</span>

                                <span style="text-align:center;font-size:14px !important">{!! $mcq->option_b !!}</span>

                            </td>
                            <td style="" class="td1 mcqstable">
                                @if(!empty($mcq->option_c))

                                <span class="circle">c</span>

                                <span style="text-align:center;font-size:14px !important">{!! $mcq->option_c !!}</span>
                                @endif

                            </td>
                            <td style="" class="td1 mcqstable">
                                @if(!empty($mcq->option_d))
                                <span class="circle">d</span>
                                <span style="text-align:center;font-size:14px !important">{!! $mcq->option_d !!}</span>
                                @endif
                            </td>

                        </tr>
                    </tbody>
                </table>
                @endforeach
            </div>
            <div class="find">
                <div contenteditable="true" class="pto" id="mcq_pto" style="text-align:center;" data-text="Page break "></div>
                <div class="custom_break" id=""> </div>
            </div>
        </div>
        @endif
