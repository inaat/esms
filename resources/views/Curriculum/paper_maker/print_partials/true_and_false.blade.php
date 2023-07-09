 @if (!empty($true_and_false_questions))

 <div class="row" style="zoom:80%">

     <table style="border:none">
         <tbody>
             <tr class="tr1">
                 <td colspan="4" class="td1 QuestHeadLeft" style="font-weight:bold;text-align:left;font-size:16px ">
                     Q.{{ $question_count }}: {{ $input['true_and_false_top_text'] }}
                     @php
                     $question_count++;

                     @endphp
                 </td>

                 <td colspan="4" class="QuestHeadLeft textright  td1" style="">

                     <span style="float:right;font-size:16px" id="qmrks"><strong>({{ $input['total_true_marks'] }})</strong></span>

                 </td>
             </tr>

         </tbody>
     </table>
     @foreach ($true_and_false_questions as $true_and_false)
     <table style="border:none ; margin-left:15px;" class="true_and_falsestable">
         <tbody>
             <tr class="tr1 true_and_falsestable">
                 <td colspan="8" class=" halfdevider td1 true_and_falsestable" style="text-align:left !important;font-size:14px !important">

                     <span style="font-weight:bold !important font-size:14px">
                         {{ numberToRoman($loop->iteration) }}: &nbsp;</span>
                     {!! $true_and_false->question !!}
                 </td>
                 <td style="width:5%;"></td>

             </tr>
         </tbody>
     </table>
     @endforeach

 </div>
 @endif

