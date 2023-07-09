

    @if (!empty($true_and_false_questions))
    <div class="row">

        <table style="border:none">
            <tbody>

                <tr class="tr1">
                    <td class="textleft td1 QuestHead" style="width:5%;font-weight:bold !important;">
                        ({{  $input['total_true_marks'] }})
                    </td>
                    <td colspan="4" class=" td1 QuestHead" style="text-align:right;font-weight:bold !important;font-family:Jameel Noori Nastaleeq;">
                        سوال {{ $question_count }} : &nbsp; {{ $input['true_and_false_top_text'] }}
                        @php
                        $question_count++;
                        $page_break_count = $page_break_count+ $true_and_false_questions->count();

                        @endphp
                    </td>

                </tr>

            </tbody>
        </table>
        @foreach ($true_and_false_questions as $true_and_false)
        <table style="border:none;margin-right:10px">
            <tbody>
                <tr class="tr1">
                    <td style="width:5%;"></td>
                    <td class="textleft td1" style="width:5%"></td>
                    <td class=" td1 urduText" style="width:80%;text-align:right;font-size:14px !important;font-family: Noori Nastaleeq">
                        {!! $true_and_false->question !!}
                    </td>
                    <td class="text-right td1" style="width:auto; font-size:14px !important;font-weight:bold;vertical-align:top">
                        :({{ numberToRoman($loop->iteration) }}) </td>
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