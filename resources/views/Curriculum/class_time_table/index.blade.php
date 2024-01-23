@extends("admin_layouts.app")
@section('title', __('english.class_routine'))
@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.timetables')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">@lang('english.classes_timetable')<br>
                    </small>
                </h5>


                <div class="d-lg-flex align-items-center mb-4 gap-3">
                      @can('class_routine.create')
                    <div class="ms-auto"><button type="button" class="btn btn-primary radius-30 mt-2 mt-lg-0 btn-modal" data-href="{{ action('Curriculum\ClassTimeTableController@create') }}" data-container=".time_table_modal">
                            <i class="bx bxs-plus-square"></i>@lang('english.assign_period')</button></div>
                            @endcan

                </div>


                <hr>
                {!! Form::open(['url' => action('Curriculum\ClassTimeTableController@index'), 'method' => 'GET' ,'id'=>'time-table-form']) !!}
                <div class="row align-items-center">
                    <div class="col-sm-3">
                        {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                        {!! Form::select('campus_id', $campuses, $campus_id,['class' => 'form-select select2 global-campuses','style' => 'width:100%', 'required', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-sm-3">
                        {!! Form::label('english.classes', __('english.classes') . ':') !!}
                        {!! Form::select('class_id',$classes,$class_id, ['class' => 'form-select select2 global-classes ','style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-sm-3">
                        {!! Form::label('english.sections', __('english.sections') . ':') !!}
                        {!! Form::select('class_section_id', $class_sections, $class_section_id, ['class' => 'form-select select2 global-class_sections', 'style' => 'width:100%', 'placeholder' => __('english.please_select')]) !!}
                    </div>
                    <div class="col-sm-3 mt-3">
                        <button type="submit" class="btn  btn-primary">@lang( 'lang.filter' )</button>
                        {!! Form::hidden('print', null,[ 'id' => 'print', ]) !!}
                        @can('class_routine.view')
                        <button class="btn  btn-primary print">@lang( 'lang.print' )</button>
                        @endcan
                    </div>
                </div>
                {!! Form::close() !!}

                <hr>
                <div class="table-responsive">
                    <table class="table mb-0" width="100%" id="class_time_table" style="zoom:60%">
                        <thead class="table-light" width="100%">

                            <tr>
                                <th>#</th>
                                <th>@lang('english.classes')</th>
                                @php
                                foreach ($class_time_table_title as $t) {
                                echo '<th>'.$t.'</th>';
                                }
                                @endphp

                            <tr>
                        </thead>
                        <tbody>
                            @foreach ( $sections as $section)

                            <tr>
                                <td> {{$loop->iteration}}
                                </td>
                                <td>{{ $section['section_name']}}</td>
                                @foreach ($section['timetables'] as $time_table)
                                @if(!empty($time_table->subjects))
                                      <td> 
                                <a class=" delete_period_button" data-href="{{ action('Curriculum\ClassTimeTableController@destroy', [$time_table->id])}}"><i class="bx bxs-trash "></i> Delete</a><br>
                                
                                
                                <a data-href="{{ action('Curriculum\ClassTimeTableController@edit', [$time_table->id])}}" class="edit_time_table">{{$time_table->subjects->name }}
                                    {{ $time_table->other ? '('.__('english.'.$time_table->other).')' : null }}
                                    <br>@if(!empty($time_table->teacher)) <strong>({{ ucwords($time_table->teacher->first_name . ' ' . $time_table->teacher->last_name) }})@endif</strong></a></td>
                                   @elseif(!empty($time_table->periods))
                                                                @if($time_table->periods->type=='lunch_break' || $time_table->periods->type=='paryer_time')
                                <td style="text-align:center;   vertical-align: middle;
                                   writing-mode: vertical-lr;">
                                   
                                                                   <a class=" delete_period_button" data-href="{{ action('Curriculum\ClassTimeTableController@destroy', [$time_table->id])}}"><i class="bx bxs-trash "></i> Delete</a><br>

                                   <a data-href="{{ action('Curriculum\ClassTimeTableController@edit', [$time_table->id])}}" class="edit_time_table">@lang('english.'.$time_table->periods->type)</a></td>
                                @else
                                <td style="text-align:center;
                                ">
                                                                <a class=" delete_period_button" data-href="{{ action('Curriculum\ClassTimeTableController@destroy', [$time_table->id])}}"><i class="bx bxs-trash "></i> Delete</a><br>

                                <a data-href="{{ action('Curriculum\ClassTimeTableController@edit', [$time_table->id])}}" class="edit_time_table">
                                @if(!empty($time_table->other))
                                @lang('english.'.$time_table->other) 
                                @endif
                                    @if(!empty($time_table->multi_subject_ids))
                                @foreach ($time_table->multi_subject_ids as $multi_subject )
                                    {{ $all_subjects[$multi_subject] }} +
                                    
                                @endforeach
                                <br>
                                @foreach ($time_table->multi_teacher as $multi_teach )
                                    {{ $teachers[$multi_teach] }} +
                                    
                                @endforeach
                                
                                @endif
                                   <br>@if(!empty($time_table->note)) <strong>({{ ucwords($time_table->note ) }})@endif</strong></a></td>

                                @endif

                                   @else
                                   <td></td>
                                @endif
                                
                                @endforeach 
                            </tr>
                            @endforeach
                        </tbody>


                    </table>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<div class="modal fade time_table_modal contains_select2" id="time_table_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

@endsection


@section('javascript')
<script type="text/javascript">
    $(document).ready(function() {


        $(document).on("submit", "form#add_time_table_form", function(e) {
            e.preventDefault();
            var form = $(this);
            var data = form.serialize();

            $.ajax({
                method: "POST"
                , url: $(this).attr("action")
                , dataType: "json"
                , data: data
                , beforeSend: function(xhr) {
                    __disable_submit_button(form.find('button[type="submit"]'));
                }
                , success: function(result) {
                    if (result.success == true) {
                        $("div.time_table_modal").modal("hide");
                        toastr.success(result.msg);
                         window.location.reload(true);
                    } else {
                        toastr.error(result.msg);
                    }
                }
            , });
        });
         
 $("form#time-table-form").validate({
        rules: {
            campus_id: {
                required: true,
            },
        },
    });
        $(document).on("click", "button.print", function() {

            $('#print').val('print');
            $('#time-table-form').submit();
        });
           $(document).on("click", "a.edit_time_table", function() {
                $("div.time_table_modal").load($(this).data("href"), function() {
                    $(this).modal("show");

                    $("form#edit_time_table_form").submit(function(e) {
                        e.preventDefault();
                        var form = $(this);
                        var data = form.serialize();

                        $.ajax({
                            method: "POST",
                            url: $(this).attr("action"),
                            dataType: "json",
                            data: data,
                            beforeSend: function(xhr) {
                                __disable_submit_button(
                                    form.find('button[type="submit"]')
                                );
                            },
                            success: function(result) {
                                if (result.success == true) {
                                    $("div.time_table_modal").modal("hide");
                                    toastr.success(result.msg);
                                    window.location.reload(true);
                                } else {
                                    toastr.error(result.msg);
                                }
                            },
                        });
                    });
                });
            });

             $(document).on("click", "a.delete_period_button", function() {
                swal({
                    title: LANG.sure,
                    text: LANG.confirm_delete_class_subject,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        var href = $(this).data("href");
                        var data = $(this).serialize();

                        $.ajax({
                            method: "DELETE",
                            url: href,
                            dataType: "json",
                            data: data,
                            success: function(result) {
                                if (result.success == true) {
                                    toastr.success(result.msg);
                                    window.location.reload(true);
                                } else {
                                    toastr.error(result.msg);
                                }
                            },
                        });
                    }
                });
            });
    });

</script>
@endsection
