	(function($){
		"use strict";
        $(document).on('click', '.whatsappDelete', function(e){
            e.preventDefault()
            var id = $(this).attr('value')
            var modal = $('#whatsappDelete');
			modal.find('input[name=id]').val(id);
			modal.modal('show');
        })

        // recursive function
        function middlePass(id , url, sessionId){
            whatsappSeesion(id, url, sessionId)
        }

        // qrQuote scan
        $(document).on('click', '.qrQuote', function(e){
            e.preventDefault()
            var id = $(this).attr('value')
            var url =  base_path+"/whatsapp/gateway/qr-code"

            whatsappSeesion(id, url)

        })

        function whatsappSeesion(id, url)
        {
            $.ajax({
                
                url:url,
                data: {id:id},
                dataType: 'json',
                method: 'post',
                beforeSend: function(){
                    $('.textChange'+id).html('Loading...');
                },
                success: function(res){
                    console.log(res)
                   

                    if (res.qr!='') {
                        $('#qrcode').attr('src',res.qr);
                    }

                    if(res.data != 'error' || res.data!='' || res.data!='connected'){
                        $('#qrQuoteModal').modal('show');
                        sleep(10000).then(() => {
                            wapSession(id, url);
                        });
                    }
                },
                complete: function(){
                    $('.textChange'+id).html(`<i class="fas fa-qrcode"></i>&nbsp Scan`);
                }
            })
        }
	})(jQuery);

    function wapSession(id,url) {

        $.ajax({
            
            url:url,
            data: {id:id},
            dataType: 'json',
            method: 'post',
            success: function(res){
                if (res.qr!='')
                {

                    $('#qrcode').attr('src',res.qr);
                }
                if(res.data != 'error' || res.data!='' || res.data!='connected')
                {
                    sleep(10000).then(() => {
                        wapSession(id, url);
                    });
                }

                if (res.data=='connected')
                {
                    sleep(2500).then(() => {

                        $('#qrQuoteModal').modal('hide');
                        //location.reload();
                    });
                }
            }
        })
    }


    function deviceStatusUpdate(id,status,className='',beforeSend='',afterSend='') {
        if (id=='') {
            id = $("#scan_id").val();
        }
        $('#qrQuoteModal').modal('hide');
           $.ajax({
            
            url:base_path+"/whatsapp/gateway/status-update",
            data: {id:id,status:status},
            dataType: 'json',
            method: 'post',
            beforeSend: function(){
                if (beforeSend!='') {
                    $('.'+className+id).html(beforeSend);
                }
            },
            success: function(res){
                sleep(1000).then(()=>{
                    //location.reload();
                })
            },
            complete: function(){
                if (afterSend!='') {
                    $('.'+className+id).html(afterSend);
                }
            }
        })
    }

    function sleep (time) {
        return new Promise((resolve) => setTimeout(resolve, time));
      }