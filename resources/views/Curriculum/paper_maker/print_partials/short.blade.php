 @if (!empty($short_questions))

 <div class="row">
     @if (!empty($input['short_question_header']))
     <div class="head">
         <h6>{{ $input['short_question_header'] }}
         </h6>
     </div>
     @endif
     <table style="border:none">
         <tbody>
             <tr class="tr1">
                 <td colspan="4" class="td1 QuestHeadLeft" style="font-weight:bold;text-align:left;font-size:16px ">
                     Q.{{ $question_count }}: {{ $input['short_question_top_text'] }}
                     @php
                     $question_count++;

                     @endphp
                 </td>

                 <td colspan="4" class="QuestHeadLeft textright  td1" style="">

                     <span style="float:right;font-size:16px" id="qmrks"><strong>({{ $input['total_short_marks'] }})</strong></span>

                 </td>
             </tr>

         </tbody>
     </table>
     @foreach ($short_questions as $short)
     <table style="border:none ; margin-left:15px;" class="shortstable">
         <tbody>
             <tr class="tr1 shortstable">
                 <td colspan="8" class=" halfdevider td1 shortstable" style="text-align:left!important; font-size:14px !important">
                     <span style="font-weight:bold !important font-size:14px; display: inline-block;width: 2%;text-align: center;">
                         {{ numberToRoman($loop->iteration) }}:</span>

                     <span style="margin-left:15px;">
                         {!! $short->question !!}</span>
                 </td>

             </tr>
         </tbody>
     </table>
     {{-- Ans.<hr><br><br><hr> --}}
     @endforeach

     <div class="find">

         <div contenteditable="true" class="pto" id="short_pto" style="text-align:center;" data-text="Page break "></div>
         <div class="custom_break" id=""> </div>
     </div>
 </div>
 @endif

