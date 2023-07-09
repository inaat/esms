$(document).ready(function () {
   
   

    $(document).on('click', '.question-btn', function (e) {
        e.preventDefault();
        var container = $(this).data("container");

        $.ajax({
            url: $(this).data("href"),
            dataType: "html",
            success: function (result) {
                $(container).html(result).modal("show");
                var elem = $('.ckeditor');
                console.log(base_path +'/js/ckeditor_config.js');
                $(elem).each(function(_, ckeditor) {
                
                CKEDITOR.replace(ckeditor, {
                  toolbar: 'Ques',    
                  allowedContent : true,              
                  extraPlugins: 'ckeditor_wiris',
                  enterMode : CKEDITOR.ENTER_BR,
                  shiftEnterMode: CKEDITOR.ENTER_P,
                  customConfig: base_path+'/js/ckeditor_config.js'
                });
                 });
            },
        });
    
    });
});