 
    @if (!empty($fill_in_the_blank_questions))
    <div class="row" >

                 @if (!empty($input['fill_in_the_blanks_header']))

                <div class="head">
                    <h6 >{{ $input['fill_in_the_blanks_header'] }}
                    </h6>
                </div>
                @endif
        <table style="border:none">
            <tbody>

                <tr class="tr1">
                    <td class="textleft td1 QuestHead" style="width:5%;font-weight:bold !important;">
                        ({{  $input['total_fill_marks'] }})
                    </td>
                    <td colspan="4" class=" td1 QuestHead" style="text-align:right;font-weight:bold !important;font-family:Jameel Noori Nastaleeq;">
                        سوال {{ $question_count }} : &nbsp; {{ $input['fill_in_the_blanks_top_text'] }}
                        @php
                        $question_count++;
                        $page_break_count = $page_break_count+ $fill_in_the_blank_questions->count();

                        @endphp
                    </td>

                </tr>

            </tbody>
        </table>
        @foreach ($fill_in_the_blank_questions as $fill)
        <table style="border:none;margin-right:10px">
            <tbody>
                <tr class="tr1">
                    <td class="textleft td1" style="border:none; width:5%"></td>
                    <td class=" td1 urduText" style="width:85%;text-align:right;font-size:14px !important;font-family: Noori Nastaleeq">
                        {!! $fill->question !!}
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