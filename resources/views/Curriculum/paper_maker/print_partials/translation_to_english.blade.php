  @if (!empty($translation_to_english_questions))

        <div class="row">
            @if (!empty($input['translation_to_english_question_header']))

            <div class="head">
                <h6>{{ $input['translation_to_english_question_header'] }}
                </h6>
            </div>
            @endif

            @foreach ($translation_to_english_questions as $translation_to_english)

            <table style="border:none">
                <tbody>
                    <tr class="tr1">
                        <td colspan="4" class="td1 QuestHeadLeft" style="text-align:left;font-size:12px ">
                            Q.{{ $question_count }}: {!! $translation_to_english->question !!}</p>
                            @php
                            $question_count++;
                            @endphp
                        </td>

                        <td colspan="4" class="QuestHeadLeft textright  td1" style="vertical-align: baseline;">

                            <span style="float:right;font-size:16px" id="qmrks"><strong>({{ $input['total_translation_to_english_marks'] }})</strong></span>

                        </td>
                    </tr>

                </tbody>
            </table>
            @endforeach

        </div>
        @endif