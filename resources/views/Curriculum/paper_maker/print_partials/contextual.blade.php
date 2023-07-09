 @if (!empty($contextual_questions))

 <div class="row">
     @if (!empty($input['contextual_question_header']))
     <div class="head">
         <h6>{{ $input['contextual_question_header'] }}
         </h6>
     </div>
     @endif
     <table style="border:none">
         <tbody>
             <tr class="tr1">
                 <td colspan="4" class="td1 QuestHeadLeft" style="text-align:left;font-size:12px ">
                     Q.{{ $question_count }}:{{ $input['contextual_question_top_text'] }}
                     @php
                     $question_count++;
                     @endphp
                 </td>

                 <td colspan="4" class="QuestHeadLeft textright  td1" style="">

                     <span style="float:right;font-size:16px" id="qmrks"><strong>({{ $input['total_contextual_marks'] }})</strong></span>

                 </td>
             </tr>

         </tbody>
     </table>

     @foreach ($contextual_questions as $contextual)

     <table style="border:none ; margin-left:15px;" class="shortstable">
         <tbody>
             <tr class="tr1 shortstable">
                 <td colspan="" class=" halfdevider td1 shortstable" style="width: 2%; vertical-align: baseline; font-size:14px !important">

                     {{ numberToRoman($loop->iteration) }}:

                 </td>
                 <td colspan="10" class=" halfdevider td1 shortstable" style="text-align:left!important;font-size:14px !important">

                     <span style="margin-left:15px;">
                         {!! $contextual->question !!}</span>
                 </td>

             </tr>
         </tbody>
     </table>
     @endforeach
 </div>
 @endif

