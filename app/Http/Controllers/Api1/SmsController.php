<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Sms;
use GuzzleHttp\Client;
use App\Models\Sim;
use Carbon\Carbon;


class SmsController extends Controller
{
     /**
     * @OA\Get(
     *     path="/api/sms",
  
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     )
  
     * )
     */

    public function  index(){

    //     $date=Carbon::now()->toDateString();
    //     $sms_count=Sim::WhereDate('date',$date)->count();
    //    // dd($sms_count);
    //     if($sms_count>700){
            
    //     return  response([
    //         'success' => 1
    //     ]);
    //     }else{
          //  $sms=Sms::limit(150)->where('status','not_send')->get();
           $sms= Sms::where('status','not_send')->get();
            return response()->json($sms);

       // }

    }

    public function store(Request $request){
        
        $sms = Sms::where('id', $request->input('id'))->first();
        $sms->status='send';
        $sms->save();

        return  response([
            'success' => 1
        ]);

    }
}