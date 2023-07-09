  @if (!empty($words_and_use_questions))

        <div class="row">
            @if (!empty($input['words_and_use_question_header']))

            <div class="head">
                <h6>{{ $input['words_and_use_question_header'] }}
                </h6>
            </div>
            @endif

            @foreach ($words_and_use_questions as $words_and_use)

            <table style="border:none">
                <tbody>
                    <tr class="tr1">
                        <td colspan="4" class="td1 QuestHeadLeft" style="text-align:left;font-size:12px ">
                            Q.{{ $question_count }}: {!! $words_and_use->question !!}</p>
                            @php
                            $question_count++;
                            @endphp
                        </td>

                        <td colspan="4" class="QuestHeadLeft textright  td1" style="vertical-align: baseline;">

                            <span style="float:right;font-size:16px" id="qmrks"><strong>({{ $input['total_words_and_use_marks'] }})</strong></span>

                        </td>
                    </tr>

                </tbody>
            </table>
            @endforeach

        </div>
        @endif