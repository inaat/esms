@if (!empty($fill_in_the_blank_questions))
<div class="row" style=" ">
    @if (!empty($input['fill_in_the_blanks_header']))

    <div class="head">
        <h6>
            {{ $input['fill_in_the_blanks_header'] }}
        </h6>
    </div>
    @endif
    <table style="border:none">
        <tbody>
            <tr class="tr1">
                <td colspan="4" class="td1 QuestHeadLeft" style="font-weight:bold;text-align:left;font-size:16px ">
                    Q.{{ $question_count }}: {{ $input['fill_in_the_blanks_top_text'] }}
                </td>

                <td colspan="4" class="QuestHeadLeft textright  td1" style="">

                    <span style="float:right;font-size:16px" id="qmrks"><strong>({{ $input['total_fill_marks'] }})</strong></span>

                </td>
            </tr>

        </tbody>
    </table>
    @foreach ($fill_in_the_blank_questions as $fill)
    <table style="border:none; margin-left:15px;" class="fillstable">
        <tbody>
            <tr class="tr1 fillstable">
                <td colspan="8" class=" halfdevider td1 fillstable" style="text-align:left !important;font-size:14px !important">

                    <span style="font-weight:bold !important font-size:14px; display: inline-block;width: 2%;text-align: center;">
                        {{ numberToRoman($loop->iteration) }}:</span>

                    <span style="margin-left:15px;">
                        {!! $fill->question !!}</span>
                </td>
            </tr>
        </tbody>
    </table>
    @endforeach



</div>
@endif

