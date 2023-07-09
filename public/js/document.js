$(document).ready(function() {
    var  url=null;
    if($("#employee_id").length > 0){
         url='/employee/documents?employee_id='+$("#employee_id").val();
     
    }
    if($("#student_id").length > 0){
         url='/student/documents?student_id='+$("#student_id").val();
        
    }
   
    $(document).on("submit", "form#add_new_document_form", function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = new FormData(this);

        $.ajax({
            method: "POST"
            , url: $(this).attr("action")
            , dataType: "json"
            , data: formData
            , cache: false
            , contentType: false
            , processData: false
            , beforeSend: function(xhr) {
                __disable_submit_button(form.find('button[type="submit"]'));
            }
            , success: function(result) {
                if (result.success == true) {
                    $("div.document_modal").modal("hide");
                    toastr.success(result.msg);
                    documents_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            }
        , });
    });


    //documents_table
    var documents_table = $("#documents_table").DataTable({
       
        processing: true
        , serverSide: true
        , dom: 't'
        , "ajax": {
            "url": url
        }
        , columns: [{
                data: "type"
                , name: "type"
            }
            , {
                data: "action"
                , name: "action"
            }

        , ]

    });


    $(document).on("click", "a.delete_document_destroy_button", function() {
        swal({
            title: LANG.sure
            , text: LANG.confirm_delete_allowance
            , icon: "warning"
            , buttons: true
            , dangerMode: true
        , }).then((willDelete) => {
            if (willDelete) {
                var href = $(this).data("href");
                var data = $(this).serialize();

                $.ajax({
                    method: "DELETE"
                    , url: href
                    , dataType: "json"
                    , data: data
                    , success: function(result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            documents_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    }
                , });
            }
        });
    }); 




      //assessments_table
      var assessments_table = $("#assessments_table").DataTable({
       
        processing: true
        , serverSide: true
        , dom: 't'
        , "ajax": {
            "url": '/student/assessments?student_id='+$("#student_id").val()
        }
        , columns: [
             {
                data: "action"
                , name: "action"
            },
            {
                data: "assessment_date"
                , name: "assessment_date"
            }

        , ]

    });

});