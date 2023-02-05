 @extends("admin_layouts.qrcode-header")
 @section('wrapper')

 <div class="card">

     <div class="card-body">
       
         <div class="row">
             <center>
               <h3 class="card-title text-primary center">@lang('english.digital_attendance_system_for_students')</h3>
         <hr>
                 <div class="col-md-12" id="qr-reader" style="width:500px"></div>
             </center>
         </div>
     </div>
 </div>
 @endsection

 @section('javascript')

 <script type="text/javascript">
     function docReady(fn) {
         // see if DOM is already available
         if (document.readyState === "complete" ||
             document.readyState === "interactive") {
             // call on next available tick
             setTimeout(fn, 1);
         } else {
             document.addEventListener("DOMContentLoaded", fn);
         }
     }

     docReady(function() {
         var resultContainer = document.getElementById('qr-reader-results');
         var lastResult, countResults = 0;

         function onScanSuccess(decodedText, decodedResult) {
             //  if (decodedText !== lastResult) {
             // ++countResults;
             // lastResult = decodedText;
             // Handle on success condition with the decoded message.
           //  console.log(`Scan result ${decodedText}`, decodedResult);

             $.ajax({
                 method: "POST"
                 , url: base_path +'/api/device-attendance-student'
                 , data: {
                     'student_id': decodedText
                 }
                 , success: function(result) {

                     if (result.success == 1) {
                         toastr.success(result.msg);
                         sayItOutLoud(result.msg)
                     } else {
                         toastr.error(result.msg);
                        sayItOutLoud(result.msg)

                     }
                 }
             });
             //}
         }
  //Play notification sound on success, error and warning
    toastr.options.onShown = function() {
        if ($(this).hasClass('toast-success')) {
            var audio = $('#success-audio')[0];
            if (audio !== undefined) {
                audio.play();
            }
        } else if ($(this).hasClass('toast-error')) {
            var audio = $('#error-audio')[0];
            if (audio !== undefined) {
                audio.play();
            }
        } else if ($(this).hasClass('toast-warning')) {
            var audio = $('#warning-audio')[0];
            if (audio !== undefined) {
                audio.play();
            }
        }
    };
         var html5QrcodeScanner = new Html5QrcodeScanner(
             "qr-reader", {
                 fps: 10
                 , qrbox: 300
             });
         html5QrcodeScanner.render(onScanSuccess);

         function sayItOutLoud(message) {

             var speech = new SpeechSynthesisUtterance();
             speech.lang = "en-US";
             speech.text = message;
             speech.volume = 11;
             speech.rate = 1;
             speech.pitch = 1;


             window.speechSynthesis.speak(speech);
         }
     });

 </script>

 @endsection
