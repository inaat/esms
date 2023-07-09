<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WhatsappDevice;
use App\Rules\WhatsappDeviceRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsappDeviceController extends Controller
{
    /**
     * create form show
     */
    public function create()
    {
         $whatsapp = WhatsappDevice::first();
        $findWhatsappsession = Http::withoutVerifying()->get('http://whatsapp.sfsc.edu.pk/session/find/'.$whatsapp->name);
        $findWhatsappsession = json_decode($findWhatsappsession);
        dd($findWhatsappsession);
        $tilte = "WhatsApp Device";
        $whatsapps = WhatsappDevice::orderBy('id','desc');
        foreach ($whatsapps as $key => $value) {
            try {
                $findWhatsappsession = Http::withoutVerifying()->get('http://whatsapp.sfsc.edu.pk/session/find/'.$value->name);
                $findWhatsappsession = json_decode($findWhatsappsession);
                $wpu = WhatsappDevice::where('id', $value->id)->first();
                if ($findWhatsappsession->message == "Session found.") {
                    $wpu->status = 'connected';
                }else{
                    $wpu->status = 'disconnected';
                }
                $wpu->save();
            } catch (Exception $e) {

            }
        }
        $whatsapps = WhatsappDevice::orderBy('id', 'desc')->paginate(paginateNumber());
        return view('admin.whatsapp.create', [
            'title' => $tilte,
            'whatsapps' => $whatsapps,
        ]);
    }

    /**
     * whatsapp store method
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:wa_device,name',
            'number' => 'required|numeric|unique:wa_device,number',
            'multidevice' => 'required|in:yes,no',
            'delay_time' => 'required',
        ]);

        $whatsapp = new WhatsappDevice();
        $whatsapp->user_id = auth()->guard('admin')->user()->id;
        $whatsapp->name = $request->name;
        $whatsapp->number = $request->number;
        $whatsapp->description = $request->description;
        $whatsapp->delay_time = $request->delay_time;
        $whatsapp->status = 'initiate';
        $whatsapp->multidevice = $request->multidevice;
        $whatsapp->save();
        $notify[] = ['success', 'Whatsapp Device successfully added'];
        //return back()->withNotify($notify);
    }

    /**
     * whatsapp edit form
     *
     * @param $ID
     */
    public function edit($id)
    {
        $tilte = "WhatsApp Device Edit";
        $whatsapps = WhatsappDevice::orderBy('id','desc');
        foreach ($whatsapps as $key => $value) {
            try {
                $findWhatsappsession = Http::withoutVerifying()->get('http://whatsapp.sfsc.edu.pk/session/find/'.$value->name);
                $findWhatsappsession = json_decode($findWhatsappsession);
                $wpu = WhatsappDevice::where('id', $value->id)->first();
                if ($findWhatsappsession->message == "Session found.") {
                    $wpu->status = 'connected';
                }else{
                    $wpu->status = 'disconnected';
                }
                $wpu->save();
            } catch (Exception $e) {

            }
        }
        $whatsapps = WhatsappDevice::orderBy('id', 'desc')->paginate(paginateNumber());
        $whatsapp = WhatsappDevice::where('id', $id)->first();
        return view('admin.whatsapp.edit', [
            'title' => $tilte,
            'whatsapp' => $whatsapp,
            'whatsapps' => $whatsapps,
        ]);
    }

    /**
     * whatsapp update method
     *
     * @param Request $request
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:wa_device,name,'.$request->id,
            'number' => 'required|numeric|unique:wa_device,number,'.$request->id,
            'multidevice' => 'required|in:YES,NO',
            'delay_time' => 'required',
            'status' => 'required|in:initiate,connected,disconnected',
        ]);

        $whatsapp = WhatsappDevice::where('id', $request->id)->first();
        $whatsapp->user_id = auth()->guard('admin')->user()->id;
        if ($whatsapp->status!='connected') {
            $whatsapp->name = $request->name;
        }
        $whatsapp->number = $request->number;
        $whatsapp->description = $request->description;
        $whatsapp->status = $request->status;
        $whatsapp->multidevice = $request->multidevice;
        $whatsapp->delay_time = $request->delay_time;
        $whatsapp->update();
        $notify[] = ['success', 'WhatsApp Device successfully Updated'];
        return back()->withNotify($notify);
    }

    /**
     * whatsapp delete method
     *
     * @param Request $request
     */
    public function delete(Request $request)
    {
        $whatsapp = WhatsappDevice::where('id', $request->id)->first();
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://whatsapp.sfsc.edu.pk/session/delete/'.$whatsapp->name,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'DELETE',
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $whatsapp->delete();
        } catch (Exception $e) {

        }
        $notify[] = ['success', 'Whatsapp Device successfully Deleted'];
        return back()->withNotify($notify);
    }

    /**
     * whatsapp device status update method
     *
     * @param Request $request
     */
    public function statusUpdate(Request $request)
    {
        $whatsapp = WhatsappDevice::first();
 try {
                $findWhatsappsession = Http::withoutVerifying()->get('http://whatsapp.sfsc.edu.pk/session/find/'.$whatsapp->name);
                $findWhatsappsession = json_decode($findWhatsappsession);
                if ($findWhatsappsession->message == "Session found.") {
                    $whatsapp->status = 'connected';
                }else{
                    $whatsapp->status = 'disconnected';

                }
                $whatsapp->update();
            } catch (Exception $e) {

            }
       /* if ($request->status=='connected') {
            try {
                $findWhatsappsession = Http::withoutVerifying()->get('http://whatsapp.sfsc.edu.pk/session/find/'.$whatsapp->name);
                $findWhatsappsession = json_decode($findWhatsappsession);
                if ($findWhatsappsession->message == "Session found.") {
                    $whatsapp->status = 'connected';
                }else{
                    $whatsapp->status = 'disconnected';

                }
                $whatsapp->update();
            } catch (Exception $e) {

            }
        }elseif ($request->status=='disconnected') {
            try {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'http://whatsapp.sfsc.edu.pk/session/delete/'.$whatsapp->name,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'DELETE',
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $whatsapp->status = 'disconnected';
                $whatsapp->update();
            } catch (Exception $e) {

            }
        }else{
            try {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'http://whatsapp.sfsc.edu.pk/session/delete/'.$whatsapp->name,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'DELETE',
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $whatsapp->status = 'disconnected';
                $whatsapp->update();
            } catch (Exception $e) {

            }
            $whatsapp->status = $request->status;
            $whatsapp->update();
        }
*/
        return json_encode([
            'success' => "WhatsApp device updated"
        ]);
    }

    /**
     * whatsapp qr quote scan method
     *
     * @param Request $request
     */
    public function getWaqr(Request $request)
    {
        $whatsapp = WhatsappDevice::where('id', $request->id)->first();
      
        if($whatsapp->multidevice == "YES"){
            $islegacy = "false";
        }else{
            $islegacy = "true";
        }
        $findWhatsappsession = "";
        try {
            $findWhatsappsession = Http::withoutVerifying()->get('http://whatsapp.sfsc.edu.pk/session/find/'.$whatsapp->name);
            $findWhatsappsession = json_decode($findWhatsappsession);
          
        } catch (Exception $e) {
            $data = 'error';
            session()->put('error','Error in connecting whatsapp server');
        }
        $qr = "";
        $data = null;
        if ($findWhatsappsession) {
            if($findWhatsappsession->message == "Session found."){
                $whatsapp->status = 'connected';
                $data = 'connected';
                $qr = asset('assets/images/done.gif');
                
                session()->put('message','Successfully connected');
            }else{
                
                if ($whatsapp->status=='initiate' || $whatsapp->status=='disconnected') {
                    $whatsapp->status = 'disconnected';

                    try {

                        $apiURL = 'http://whatsapp.sfsc.edu.pk/session/add';

                        $postInput = [
                            'id' => $whatsapp->name,
                            'isLegacy' => $islegacy,
                            'domain' => 'https://xsender.igensolutionsltd.com'
                        ];

                        $headers = [
                            'Content-Type' => 'application/json',
                            'Cache-Control' => 'no-cache'
                        ];

                        $response = Http::withoutVerifying()->withHeaders($headers)
                                                            ->post($apiURL, $postInput);
                        $statusCode = $response->status();
                        $responseBody = json_decode($response->getBody(), true);
                       // dd($responseBody);
                        if (array_key_exists('data',$responseBody)) {
                            if (array_key_exists('qr',$responseBody['data'])) {
                                $qr = $responseBody['data']['qr'];
                            }
                        }

                    } catch (Exception $e) {
                        $data = 'error';
                        session()->put('error','Error in connecting whatsapp server');
                    }

                }
                else{
                    $data = null;
                }
            }
            $whatsapp->save();
        }else{
            $data = 'error';
            session()->put('error','Error in connecting whatsapp server');
        }
        return json_encode([
            'response' => $whatsapp,
            'data' => $data,
            'qr' => $qr
        ]);
    }

}
