<div class="modal-dialog modal-md" role="document">
    <div class="modal-content">


        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">@lang('english.whatsapp_api')</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row">
             <div class="col-12">
                    
                    {{-- <img class="center-block  hideqr" src="data:image/png;base64,{{DNS2D::getBarcodePNG($result->qrcode, 'QRCODE')}}"> --}}
                      <div id="app">
		<h1>Whatsapp API</h1>
		<img src="" alt="QR Code" id="qrcode">
		<h3>Logs:</h3>
		<ul class="logs"></ul>
	</div> 
                </div>

                <div class="clearfix"></div>
            </div>


            <div class="modal-footer">




            
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">@lang( 'english.close' )</button>
            </div>
        </div>


    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->



