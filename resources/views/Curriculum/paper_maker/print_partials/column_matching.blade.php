@if(!empty($column_matching_questions)) <div class="row">

    @if (!empty($input['column_matching_header']))

    <div class="head">
        <h6>{{ $input['column_matching_question_header'] }}
        </h6>
    </div>
    @endif
    @foreach ($column_matching_questions as $cm)

    <table style="border:none">
        <tbody>
            <tr class="tr1">
                <td colspan="4" class="td1 QuestHeadLeft" style="font-weight:bold;text-align:left;font-size:16px ">
                    Q.{{ $question_count }}: {!! $cm->question !!}
                    @php
                    $question_count++;
                    $column_a=explode(',,',$cm->column_a);
                    $column_b=explode(',,',$cm->column_b);
                    @endphp
                </td>

                <td colspan="4" class="QuestHeadLeft textright  td1" style="">

                    <span style="float:right;font-size:16px" id="qmrks"><strong>({{ $input['total_column_matching_marks'] }})</strong></span>

                </td>
            </tr>

        </tbody>
    </table>
    <table style="border:none">
        <tbody style="border:none">
            <tr>
                <td style="width:2%" class="td1"></td>
                <td style="width:30%;border:1px solid black"><strong style="font-weight:bold">Column A </strong></td>
                <td style="width:20%" class="td1"></td>
                <td style="width:30%;border:1px solid black"><strong style="font-weight:bold"> Column B </strong></td>
                <td style="width:2%" class="td1"></td>

            </tr>
            @for($i=0; $i<count($column_a); $i++) <tr>

                <td style="width:2%" class="td1"></td>
                <td style="width:30%;border:1px solid black"><strong style="font-weight:bold">{!! $column_a[$i] !!} </strong></td>
                <td style="width:20%" class="td1"></td>
                <td style="width:30%;border:1px solid black"><strong style="font-weight:bold"> {!! $column_b[$i] !!}</strong></td>
                <td style="width:2%" class="td1"></td>

                </tr>
                @endfor
        </tbody>
    </table>

    @endforeach

</div>
@endif

