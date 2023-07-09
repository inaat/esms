@if (!empty($long_questions))

<div class="row">
    @if (!empty($input['long_question_header']))

    <div class="head">
        <h6>{{ $input['long_question_header'] }}
        </h6>
    </div>
    @endif
    @if($input['long_question_choice'] >0)


    <table style="border:none">
        <tbody>
            <tr class="tr1">
                <td colspan="4" class="td1 QuestHeadLeft" style="font-weight:bold;text-align:left;font-size:16px; ">
                    Q.{{ $question_count }}: {{ $input['long_top_text'] }}
                    @php
                    $question_count++;
                    @endphp
                </td>

                <td colspan="4" class="QuestHeadLeft textright  td1" style="">

                    <span style="float:right;font-size:16px" id="qmrks"><strong>({{ $input['total_long_marks'] }})</strong></span>

                </td>
            </tr>

        </tbody>
    </table>
    @foreach ($long_questions as $long)

    <table style="border:none">
        <tbody>

            <tr class="tr1 shortstable">
                <td colspan="8" class=" halfdevider td1 shortstable" style="text-align:left !important;font-size:14px !important">
                    <span style="font-weight:bold !important font-size:14px; display: inline-block;width: 2%;text-align: center;">
                        {{ numberToRoman($loop->iteration) }}:</span>

                    <span style="margin-left:15px;">
                        {!! $long->question !!}</span>
                </td>
            </tr>
        </tbody>
    </table>
    @endforeach
    @else

    @foreach ($long_questions as $long)

    <table style="border:none">
        <tbody>
            <tr class="tr1">
                <td colspan="4" class="td1 QuestHeadLeft" style="text-align:left;font-size:12px ">
                    Q.{{ $question_count }}: {!! $long->question !!}</p>
                    @php
                    $question_count++;
                    @endphp
                </td>

                <td colspan="4" class="QuestHeadLeft textright  td1" style="">

                    <span style="float:right;font-size:16px" id="qmrks"><strong>({{ $input['long_question_question_marks'] }})</strong></span>

                </td>
            </tr>

        </tbody>
    </table>
    @endforeach

    @endif


    <div class="find">
        <div contenteditable="true" class="pto" id="long_pto" style="text-align:center;" data-text="Page break "></div>
        <div class="custom_break" id=""> </div>
    </div>
</div>
@endif

