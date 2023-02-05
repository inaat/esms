 @extends("admin_layouts.qrcode-header")
 @section('wrapper')
   {{-- <div class="page-wrapper">
         <div class="page-content"> --}}
 <div class="row">
     <div class="col-md-6" style="padding:10px;background:#fff;border-radius: 5px;" id="divvideo">
         <center>
             <p class="login-box-msg"> <i class="glyphicon glyphicon-camera"></i> TAP HERE</p>
         </center>
         <video id="preview" width="100%"  style="border-radius:10px;"></video>
         <br>
         <br>


     </div>

     <div class="col-md-6">
         <i class="glyphicon glyphicon-qrcode"></i> <label>SCAN QR CODE</label>
         <p id="time"></p>
         <input type="text" name="studentID" id="text" placeholder="scan qrcode" class="form-control" autofocus>
         <div style="border-radius: 5px;padding:10px;background:#fff;" id="divvideo">
             <table id="example1" class="table table-bordered">
                 <thead>
                     <tr>
                         <td>NAME</td>
                         <td>STUDENT ID</td>
                         <td>TIME IN</td>
                         <td>TIME OUT</td>
                     </tr>
                 </thead>
                 <tbody>

                 </tbody>
             </table>

         </div>

     </div>

 </div>
 {{-- </div>
 </div> --}}
  @endsection

 @section('javascript')

 <script type="text/javascript">
     
         let scanner = new Instascan.Scanner({
             video: document.getElementById('preview')
         });
         Instascan.Camera.getCameras().then(function(cameras) {
             if (cameras.length > 0) {
                 scanner.start(cameras[0]);
             } else {
                 alert('No cameras found');
             }

         }).catch(function(e) {
             console.error(e);
         });

         scanner.addListener('scan', function(c) {
             document.getElementById('text').value = c;
            toastr.success('result.msg');

             //document.forms[0].submit();
             sayItOutLoud('ok')
         });

         function sayItOutLoud(message) {

             var speech = new SpeechSynthesisUtterance();
             speech.lang = "en-US";
             speech.text = message;
             speech.volume = 11;
             speech.rate = 1;
             speech.pitch = 1;


             window.speechSynthesis.speak(speech);
         }
  
 </script>
 @endsection





<html>
<head>
    <title>Html-Qrcode Demo</title>
<body>
    <div id="qr-reader" style="width:500px"></div>
    <div id="qr-reader-results"></div>
</body>
<script src="https://scanapp.org/assets/js/html5-qrcode.min.v2.2.9.js"></script>

<script>
    function docReady(fn) {
        // see if DOM is already available
        if (document.readyState === "complete"
            || document.readyState === "interactive") {
            // call on next available tick
            setTimeout(fn, 1);
        } else {
            document.addEventListener("DOMContentLoaded", fn);
        }
    }

    docReady(function () {
        var resultContainer = document.getElementById('qr-reader-results');
        var lastResult, countResults = 0;
        function onScanSuccess(decodedText, decodedResult) {
            if (decodedText !== lastResult) {
                ++countResults;
                lastResult = decodedText;
                // Handle on success condition with the decoded message.
                console.log(`Scan result ${decodedText}`, decodedResult);
            }
        }

        var html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", { fps: 10, qrbox: 250 });
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
</head>
</html>
