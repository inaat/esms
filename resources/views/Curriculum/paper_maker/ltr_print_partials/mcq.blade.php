 @if (!empty($input['single_page']))
 <div style="display:flex;">
     <span style="margin-top:5px">@lang('english.roll_no'):</span>
     <input type="text" style="width: 30px;border-radius: 2%;">
     <input type="text" style="width: 30px;border-radius: 2%;">
     <input type="text" style="width: 30px;border-radius: 2%;">
     <input type="text" style="width: 30px;border-radius: 2%;">
     <input type="text" style="width: 30px;border-radius: 2%;">
     <input type="text" style="width: 30px;border-radius: 2%;">
     <input type="text" style="width: 30px;border-radius: 2%;">
     <input type="text" style="width: 30px;border-radius: 2%;">
     <input type="text" style="width: 30px;border-radius: 2%;">
     <span style="margin-top:5px; margin-left:10px">@lang('english.name'):___________________________________</span>

 </div>
 @endif
 @if (!empty($mcq_questions))

 <div class="row" >

     <table style="border:none">
         <tbody>
             @if (!empty($input['mcq_header']))

             <div class="head">
                 <h6>{{ $input['mcq_header'] }}
                 </h6>
             </div>
             @endif
             <table style="border:none">
                 <tbody>
                     <tr class="tr1">
                         <td class="textleft td1 QuestHead" style="width:5%;font-weight:bold !important;">
                             ({{ $input['total_mcq_marks'] }})
                         </td>
                         <td colspan="4" class=" td1 QuestHead" style="text-align:right;font-weight:bold !important;font-family:Jameel Noori Nastaleeq;">
                             سوال {{ $question_count }} : &nbsp; {{ $input['mcq_top_text'] }}
                             @php
                             $question_count++;
                             $page_break_count = $page_break_count+ $mcq_questions->count();
                             @endphp
                         </td>

                     </tr>
                 </tbody>
             </table>
         </tbody>
     </table>

     @foreach ($mcq_questions as $mcq)
     <table style="border:none; margin-left:15px; " class="mcqstable">
         <tbody>
             <tr class="tr1 mcqstable">
                 <td class=" td1 urduText mcqstable brdrnone" style="width:90% ;text-align:right;font-size:16px !important;
                                font-weight:bold !imprtanr;font-family:Jameel Noori Nastaleeq">

                     {!! $mcq->question !!}
                 </td>
                 <td class="text-right td1 mcqstable brdrnone" style="width:8%;font-weight:bold;vertical-align:top;">
                     <span style="font-weight:bold !important;font-size:14px !important">
                         &nbsp;:({{ numberToRoman($loop->iteration) }})</span>
                 </td>
             </tr>

         </tbody>
     </table>
     <table style="border:none" class="mcqstable">
         <tbody>
             <tr class="tr1 mcqstable">
                 <td style="font-size:12px !important" class="td1 mcqstable urduText">
                     @if(!empty($mcq->option_d))
                     <span class="numberCircle"><span>ث۔</span></span>
                     <span><span style="font-family:Jameel Noori Nastaleeq">{!! $mcq->option_d !!}</span></span>
                     @endif
                 </td>
                 <td style="font-size:12px !important" class="td1 mcqstable urduText">
                     @if(!empty($mcq->option_c))

                     <span class="numberCircle"><span>ت۔</span></span>
                     <span><span style="font-family:Jameel Noori Nastaleeq">{!! $mcq->option_c !!}</span></span>
                     @endif
                 </td>
                 <td style="font-size:12px !important" class="td1 mcqstable urduText">

                     <span class="numberCircle"><span>ب۔</span></span>
                     <span><span style="font-family:Jameel Noori Nastaleeq">{!! $mcq->option_b !!}</span></span>
                 </td>
                 <td style="font-size:12px !important" class="td1 mcqstable urduText">

                     <span class="numberCircle"><span>ا۔</span></span>
                     <span><span style="font-family:Jameel Noori Nastaleeq">{!! $mcq->option_a !!}</span></span>
                 </td>





             </tr>
         </tbody>
     </table>
     @endforeach
     <div class="find">
         <div contenteditable="true" class="pto" id="mcq_pto" style="text-align:center;" data-text="Page break "></div>
         <div class="custom_break" id=""> </div>
     </div>
 </div>
 @endif
