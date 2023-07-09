@php
// $f = new \NumberFormatter('eng', \NumberFormatter::SPELLOUT);
$nf = new NumberFormatter('eng', NumberFormatter::ORDINAL);
@endphp
@foreach ($exam as $details)

<div class="card">
    <div class="card-body">
        <h3 class="card-title text-primary">@lang('english.exam') : {{ $details->exam_create->term->name }} ({{ $details->session->title  }}) </h3>

        <div class="table-responsive">
            <table class="table table-bordered table-striped" width="100%" id="students_table">
                <thead class="table-light" width="100%">
                    <tr>
                        {{-- <th rowspan="2">Sr No</th> --}}
                        <th rowspan="2">@lang('english.name')</th>
                        <th colspan="3">@lang('english.total_marks')</th>
                        <th colspan="3">@lang('english.obtained_marks')</th>
                        {{-- <th rowspan="2">In Words </th> --}}
                        <th rowspan="2">@lang('english.sub')<br>@lang('english.rank')</th>
                        <th rowspan="2">@lang('english.grade')</th>
                        <th rowspan="2">@lang('english.remarks')</th>

                    </tr>
                    <tr>
                        <th>@lang('english.marks_theory')</th>
                        <th>@lang('english.practical')/@lang('english.viva')</th>
                        <th>@lang('english.total')</th>

                        <th>@lang('english.marks_theory')</th>
                        <th>@lang('english.practical')/@lang('english.viva')</th>
                        <th>@lang('english.total')</th>
                    </tr>
                </thead>
                <tbody>
                    @php

                    $total_theory_marks = 0;
                    $total_practical_marks = 0;
                    $total_total_marks = 0;

                    $obtain_theory_marks = 0;
                    $obtain_practical_marks = 0;
                    $obtain_total_marks = 0;
                    $school_name =session()->get('system_details.org_name') ;
                    $student_info=ucwords($details->student->first_name . ' ' . $details->student->last_name)."\r\n" . __('english.s/d_of') . ucwords($details->student->father_name) ."\r\n". 'Roll No: ' . $details->student->roll_no."\r\n" . ucwords($details->current_class->title) ." ".$details->current_class_section->section_name ."\r\n";
                    $qr_code_details=[$school_name,$student_info];
                    @endphp
                    @foreach ($details->subject_result as $subject)
                    <tr class="@if($loop->iteration%2==0) even @else odd @endif">
                        {{-- <td>{{ $loop->iteration }}</td> --}}
                        @php
                        $total_theory_marks += $subject->theory_mark;
                        $total_practical_marks += $subject->parc_mark;
                        $total_total_marks += $subject->total_mark;

                        $obtain_theory_marks += $subject->obtain_theory_mark;
                        $obtain_practical_marks += $subject->obtain_parc_mark;
                        $obtain_total_marks += $subject->total_obtain_mark;
                        $qr_code_details[]=$subject->subject_name->name .'='.$subject->total_obtain_mark.'/'.$subject->total_mark."\r\n";
                        @endphp
                        <td>{{ $subject->subject_name->name }}</td>
                        <td>{{ $subject->theory_mark}}</td>
                        <td>{{ $subject->parc_mark}}</td>
                        <td>{{ $subject->total_mark}}</td>

                        <td>{{ $subject->obtain_theory_mark}}</td>
                        <td>{{ $subject->obtain_parc_mark}}</td>
                        <td>{{ $subject->total_obtain_mark}}</td>
                        {{-- <td>@php
                                      echo $f->format($subject->total_obtain_mark);
                         @endphp</td> --}}
                        <td>{{ $nf->format($subject->position_in_subject)}}</td>
                        <td>{{ $subject->subject_grade->name ?? null}}</td>
                        <td>{{ $subject->subject_grade->remark ?? null }}</td>
                    </tr>

                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-dark text-white">
                        <td>@lang('english.grand_total')</td>
                        <td>{{ $total_theory_marks }}</td>
                        <td>{{ $total_practical_marks }}</td>
                        <td>{{ $total_total_marks }}</td>
                        <td>{{ $obtain_theory_marks }}</td>
                        <td>{{ $obtain_practical_marks}}</td>
                        <td>{{$obtain_total_marks }}</td>
                        {{-- <td colspan="4">@php
                                      echo $f->format($obtain_total_marks);
                         @endphp</td> --}}
                        <td></td>
                        <td></td>
                        <td></td>


                    </tr>
                </tfoot>
            </table>
            <table width="100%">
                <thead>
                    <tr>
                        <th>
                            <div class=" text-align-center">@lang('english.percentage'):</div>
                        </th>
                        <td>
                            <div class="underline text-align-center"><strong>{{ @num_format($details->final_percentage) }}%</strong> </div>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <div class=" text-align-center">@lang('english.grade'):</div>
                        </th>
                        <td>
                            <div class="underline text-align-center"><strong>{{ ucwords($details->grade ? $details->grade->name :' ') }}</strong> </div>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <div class=" text-align-center2">@lang('english.remarks'):</div>
                        </th>
                        <td>
                            <div class="underline text-align-center"><strong>{{ ucwords($details->grade ? $details->grade->remark :' ') }}</strong> </div>
                        </td>
                    </tr>

                </thead>
            </table>
        </div>
    </div>
</div>
@endforeach

