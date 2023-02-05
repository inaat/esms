
     $(document).ready(function() {
        $(document).on('click', '#print_div', function() {
            $('.print_area').printThis();
        });
        $("form#class-report-form").validate({
               rules: {
                   campus_id: {
                       required: true,
                   },
               },
           });
           
               
        
          $(document).on("submit", "form#class-report-form", function (e) {
               e.preventDefault();
               var form = $(this);
               var data = form.serialize();
       
               $.ajax({
                   method: "GET",
                   url: $(this).attr("action"),
                   dataType: "json",
                   data: data,
                   beforeSend: function (xhr) {
                       __disable_submit_button(form.find('button[type="submit"]'));
                   },
                   success: function (result) {
                       if (result.success == true) {
                           $('.remove').remove();
       
                             $('.load-class-report')
                               .append(result.html_content)
                           __enable_submit_button(form.find('button[type="submit"]'));
       
                       } else {
                           toastr.error(result.msg);
                       }
                   },
               });
           });
        
            });