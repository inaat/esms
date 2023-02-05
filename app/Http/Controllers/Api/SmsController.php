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

    public function  index(){

        $date=Carbon::now()->toDateString();
        $sms_count=Sim::WhereDate('date',$date)->count();
       // dd($sms_count);
        if($sms_count>700){
            
        return  response([
            'success' => 1
        ]);
        }else{
            $sms=Sms::limit(150)->where('status','not_send')->get();
            Sms::limit(150)->where('status','not_send')->delete();
            return response()->json($sms);

        }

    }

    public function store(Request $request){
        
        $sms = Sms::where('id', $request->input('id'))->first();
        $sms->status='send';
        $sms->save();

        $sim_insert=[
            'sim_id'=>$request->input('sim_id'),
            'date'=>Carbon::now()->toDateString()
        ];
        $sim= Sim::create($sim_insert);

        return  response([
            'id' => 12,
            'mobile' => "035555",
            'sms_body'=>"dsadasd",
            'success' => 1
        ]);

    }
}