<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use GuzzleHttp\Client;

class WhatsAppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkLogin()
    {
        if (request()->ajax()) {
            $client = new Client();

            $response = $client->get('https://ekschool.tk/getqr', [
                'headers'=>[
                    'Content-Type' => 'application/json'
                ]
            ]);

            $bodyResponse = $response->getBody();
            $result = $bodyResponse->getContents();
            $result = json_decode($result);
            // dd($result->qrcode);
            return view('whatsapp.checkLogin')->with(compact('result'));
        }
    }
    public function checkAuth()
    {
        if (request()->ajax()) {
            try {
                $client = new Client();

                $response = $client->get('https://ekschool.tk/checkauth', [
                    'headers'=>[
                        'Content-Type' => 'application/json'
                    ]
                ]);
              

                $bodyResponse = $response->getBody();
                $result = $bodyResponse->getContents();
                $result = json_decode($result);
                $output = ['success' => true,
                'data' =>$result
            ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

                $output = ['success' => false,
                                'data' => __("english.not_connected")
                            ];
            }

                                     return $output;
        }
    }

    
}
