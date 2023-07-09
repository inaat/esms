  @if (!empty($long_questions))
        {{-- <p style="page-break-after: always;"></p> --}}
        <div class="row" style=" margin-top:50px">
        
                 @if (!empty($input['long_question_header']))

                <div class="head">
                    <h6 >{{ $input['long_question_header'] }}
                    </h6>
                </div>
                @endif
    <table style="border:none">
            <tbody>
                     <tr class="tr1">
                        <td class="textleft td1 QuestHead" style="width:5%;font-weight:bold !important;">
                            ({{ $input['total_long_marks'] }})
                        </td>
                        <td colspan="4" class=" td1 QuestHead" style="text-align:right;font-weight:bold !important;font-family:Jameel Noori Nastaleeq;">
                            سوال {{ $question_count }} : &nbsp; {{ $input['long_top_text'] }}
            
                        </td>

                    </tr>
                
                

            </tbody>
        </table>
                @foreach ($long_questions as $long)
                <table style="border:none">
                    <tbody>
                           
                       
                         <tr class="tr1">
                        <td class="textleft td1" style="width:5%"></td>
                        <td class=" td1 urduText" style="width:85%;text-align:right;font-size:14px !important;font-family: Noori Nastaleeq">
                            {{ $long->question }}
                        </td>
                        <td class="text-right td1" style="width:auto; font-size:14px !important;font-weight:bold;vertical-align:top">
                            :({{ numberToRoman($loop->iteration) }}) </td>
                    </tr>
                    </tbody>
                </table>
                @endforeach
        </div>
        @endif
        