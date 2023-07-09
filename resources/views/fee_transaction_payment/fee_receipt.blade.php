 @extends("admin_layouts.app")
@section('title', __('english.collect_fee'))
 @section('wrapper')
 <div class="page-wrapper">
     <div class="page-content">
         <div class="row">
             <div class="col-12">
                 <div class="card">
                     <div class="card-body">
                         <div class="row align-items-center">

                             <div class="row g-2 ">

                                 <div class="col-md-4  ">
                                     {!! Form::label('english.student', __('english.campuses') . '') !!}
                                     {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select select2 global-campuses', 'required', 'id' => 'students_list_filter_campus_id', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all')]) !!}
                                 </div>
                                 <div class="col-md-4 ">
                                     {!! Form::label('english.classes', __('english.classes') . '') !!}
                                     {!! Form::select('class_id', [], null, ['class' => 'form-select select2 global-classes', 'required', 'style' => 'width:100%', 'required', 'placeholder' => __('english.all'), 'id' => 'students_list_filter_class_id']) !!}
                                 </div>
                                 <div class="col-md-4 ">
                                     {!! Form::label('english.sections', __('english.sections') . '') !!}
                                     {!! Form::select('class_section_id', [], null, ['class' => 'form-select select2 global-class_sections', 'id' => 'students_list_filter_class_section_id', 'style' => 'width:100%', 'placeholder' => __('english.all')]) !!}
                                 </div>
                                 <div class="clearfix"></div>
                                <div class="col-md-3 ">
                                    {!! Form::label('english.father_name', __('english.father_name') . '') !!}
                                    {!! Form::text('father_name', null, ['class' => 'form-control','id'=>'father_name','placeholder' => __('english.father_name')]) !!}
                                </div>
                                 <div class="col-md-8">
                                     {!! Form::label('english.student', __('english.search_students') . ':*') !!}

                                     <div class="position-relative">
                                         {!! Form::text('search_student', null, ['class' => 'form-control mousetrap ps-5', 'id' => 'search_student', 'placeholder' => __('Search Students...'), 'autofocus']); !!}
                                         <span class="position-absolute top-50 product-show translate-middle-y"><i class="bx bx-search"></i></span>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
         <div class="card">
             <div class="card-body p-1">
                 <h5 class="card-title">Fee Receipt</h5>
                 <hr>
                 <div class="form-body mt-0">
                    
                     <!--end row-->
                 </div>
             </div>
         </div>
     </div>
 </div>

 @endsection

 @section('javascript')

 <script type="text/javascript">
     $(document).ready(function() {
 
        ///fee serach
    if ($('#search_student').length) {
        //Add Product
        $('#search_student')
            .autocomplete({
                delay: 1000
                , source: function(request, response) {

                    $.getJSON(
                        '/student/list', {
                            campus_id: $('#students_list_filter_campus_id').val()
                            , class_id: $('#students_list_filter_class_id').val()
                            , class_section_id: $('#students_list_filter_class_section_id').val()
                             ,father_name: $('#father_name').val()
                            , term: request.term
                        , }
                        , response
                    );
                }
                , minLength: 2
                , response: function(event, ui) {
                    if (ui.content.length == 1) {
                        ui.item = ui.content[0];
                        if (ui.item.status == 'active') {
                            $(this)
                                .data('ui-autocomplete')
                                ._trigger('select', 'autocompleteselect', ui);
                            $(this).autocomplete('close');
                        }
                    } else if (ui.content.length == 0) {
                        toastr.error(LANG.no_student_found);
                        $('input#search_student').select();
                    }
                }
                , focus: function(event, ui) {
                    if (ui.item.status == 'struck_up' || ui.item.status == 'inactive') {
                        return false;
                    }
                }
                , select: function(event, ui) {
                    var searched_term = $(this).val();
                   fee_receipt_row(ui.item.id, ui.item.campus_id);
                }
            , })
            .autocomplete('instance')._renderItem = function(ul, item) {

                if (item.status == 'struck_up' || item.status == 'inactive') {
                    var string = '<li class="ui-state-disabled ">';
                    string += '<div class="list-group-item"><div class="d-flex"><div class="chat-user-online">';
                    string += '<img src="' + base_path + '/uploads/student_image/' + item.student_image + '" width="42" height="42" class="rounded-circle" alt="">';
                    string += '</div><div class="flex-grow-1 ms-2"><h6 class="mb-0 chat-title">' + item.student_name + '(' + item.roll_no + ')' + ' (Father Name:' + item.father_name + ')</h6>';
                    string += '<p class="mb-0 chat-msg"> Campus:' + item.campus_name + ' Class ' + item.current_class + ' Old Roll No:' + item.old_roll_no + '  Status:' + item.status + '</p></div>';
                    string += '</div></div>';

                    ' (Out of stock) </li>';
                    return $(string).appendTo(ul);
                } else {

                    var string = '<div class="list-group-item"><div class="d-flex">';
                    string += '<img src="' + base_path + '/uploads/student_image/' + item.student_image + '" width="42" height="42" class="rounded-circle" alt="">';
                    string += '<div class="flex-grow-1 ms-2"><h6 class="mb-0 e">' + item.student_name + '(' + item.roll_no + ')' + ' (Father Name:' + item.father_name + ')</h6>';
                    string += '<p class="mb-0 "> Campus:' + item.campus_name + ' Class ' + item.current_class + '<br> Old Roll No:' + item.old_roll_no + '  Status:' + item.status + '</p></div>';
                    string += '</div></div>';

                    return $('<li>')
                        .append(string)
                        .appendTo(ul);
                }
            };
    }
//variation_id is null when weighing_scale_barcode is used.
function fee_receipt_row(student_id , campus_id ){
  chect_data=$('#student_id').val();
  if(chect_data!=undefined){
  if(chect_data.length > 0){
      $('.student-details').remove();
  }}
   
   $.ajax({
       method: 'GET',
       url: '/payment/student/get_student_detail/'+student_id+'/'+campus_id,
       async: false,
       data: {
           student_id: student_id,
           campus_id: campus_id,
       },
       dataType: 'json',
       success: function(result) {
           if (result.success) {
               $('.form-body')
                   .append(result.html_content);
                
               
           } else {
               toastr.error(result.msg);
               $('input#search_student')
                   .focus()
                   .select();
           }
       },
   });
}


    $(document).on('submit', 'form#student_due_form', function(e) {
        e.preventDefault();
        var form = $(this);
        var data = form.serialize();
        $.ajax({
            method: 'POST'
            , url: $(this).attr('action')
            , dataType: 'json'
            , data: data
            , beforeSend: function(xhr) {
                __disable_submit_button(form.find('button[type="submit"]'));
            }
            , success: function(result) {
                if (result.success == true) {
                    toastr.success(result.msg);
                    $('#student_due_form')
                        .find('button[type="submit"]')
                        .attr('disabled', false);
                        $('.student-details').remove();
                       $('#search_student').val('');
                       $('#father_name').val('');
                       $('input#search_student').focus();
                        //Check if enabled or not
                       
                } else {
                    toastr.error(result.msg);
                }
            }
        , });
    });
    
     });

 </script>
 @endsection
