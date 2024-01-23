<?php

use App\Models\Settings;
use App\Models\User;

function send_notification($user, $title, $body, $type)
{
    $FcmToken = User::where('fcm_id', '!=', '')->whereIn('id', $user)->get()->pluck('fcm_id');
    
   // $FcmToken = "fvrFhVShTwecB48WOa9xeV:APA91bF66EGpSekhxak-XcNE-CqRk5HU6IFbCWwW5S5Pqz8l7XgEqLsL788lJJyUCmPHyUWioZ-bpPkKqS1-UbpFGW3KGMLhcR_tbqHRDZ3Tajbz5T5cP93FiTR8uqiidicS7ZquKcR4";

    $url = 'https://fcm.googleapis.com/fcm/send';
   /* $serverKey = "AAAA_i8HSGc:APA91bHs-Xw4bYn5KKjkjH8biV3x_z3_ClshM1ffmcbhNqVBfExNt6OxkmiXqCC0WfJpWcIxoTG1Sl00UbPP0I0YEc2wFw2N3__-rMoyEaQi32c_rxHvH6d-unZqUuM6m6HPyFxVYScT";*/
   $serverKey ="AAAA6XSTsf8:APA91bHVKLJ4EGWgztswaH7Gud2ItXZevaGxl-KYpEWaqMRhGOBjFms6zWdxJ0VMmbYuTvlyN_TvHG6JlL_qZnsKhOHkBFSBq0kXxcVVXlUGVC624qD8H_Ie_PYYKS-fhbVWmWpE_8SK";

    $notification_data1 = [
        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        "title" => $title,
        "body" => $body,
        "type" => $type,

    ];
    $notification_data2 = [
        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        "type" => $type,

    ];

    $data = [
        "registration_ids" => $FcmToken,
        "notification" => $notification_data1,
        "data" => $notification_data2,
        "priority" => "high"
    ];
    $encodedData = json_encode($data);

    $headers = [
        'Authorization:key=' . $serverKey,
        'Content-Type: application/json',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

    // Disabling SSL Certificate support temporarly
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

    // Execute post
    $result = curl_exec($ch);
    if ($result == FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }
    //dd($result);

    // Close connection
    curl_close($ch);
}
