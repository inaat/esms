<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsappApiService
{
    //private $baseUrl = 'http://localhost:3333';
    private $baseUrl = 'http://whatsapp_api.injazatsoftware.net';

    private $token = 'InjazatHR'; // Replace with your actual token

    public function instanceInit($instance)
    {
        $apiURL = $this->baseUrl . '/instance/init?';

        $postInput = [
            "key" => $instance,
            "browser" => "Chrome (Linux)",
            "webhook" => false,
            "base64" => true,
            "webhookUrl" => "",
            "webhookEvents" => ["messages.upsert"],
            "ignoreGroups" => true,
            "messagesRead" => false
        ];

        return $this->makeApiCall($apiURL, 'post', $postInput);
    }

    public function getQrCodebase64($instance)
    {
        $apiURL = $this->baseUrl . '/instance/qrbase64?key=' . $instance;

        return $this->makeApiCall($apiURL, 'get');
    }

    public function sendTestMsg($instance, $number, $text)
    {
        $apiURL = $this->baseUrl . '/message/text?key=' . $instance;

        $postInput = [
            "id" => $number,
            "typeId" => "user",
            "message" => $text,
            "options" => [
                "delay" => 60,
                "replyFrom" => ""
            ],
            "groupOptions" => [
                "markUser" => "ghostMention"
            ]
        ];

        return $this->makeApiCall($apiURL, 'post', $postInput);
    }
 public function sendDocument($instance,$filePath,$number,$filename,$caption){
    // $whatsapp = WhatsappDevice::first();
    // $filePath = storage_path('app/pdf/1.pdf');

    // dd($this->whatsappApiService->sendDocument($whatsapp->name,$filePath, "923428927305", "1.pdf",  "final_"));
   

    $apiURL = $this->baseUrl . '/message/doc?key=' . $instance;

    $response = Http::attach(
        'file',
        file_get_contents($filePath),
        basename($filePath) // Use basename() to get the file name
    )->withToken($this->token)
    ->post($apiURL, [
        'id'              => $number,
        'filename'        => $filename,
        'userType'        => 'user',
        'replyFrom'       => '',
        'caption'         => $caption
    ]);
    $responseData = $response->json();
    return $responseData;
 }
    private function makeApiCall($url, $method, $data = [])
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Cache-Control' => 'no-cache',
            'Authorization' => 'Bearer ' . $this->token,
        ];

        $http = Http::withoutVerifying()->withHeaders($headers);

        if ($method === 'post') {
            return $http->post($url, $data)->json();
        } elseif ($method === 'get') {
            return $http->get($url)->json();
        }

        // Handle other HTTP methods if needed
        return null;
    }
}
