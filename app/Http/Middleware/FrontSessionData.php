<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Frontend\FrontSetting;
use App\Models\Frontend\FrontCounter;



class FrontSessionData
{
    /**
     * Checks if session data is set or not for a user. If data is not set then set it.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

    
          
            
            $front_details= FrontSetting::first();
         
            $request->session()->put('front_details', $front_details);
            $front_counters= FrontCounter::get();
         
            $request->session()->put('front_counters', $front_counters);

     

        return $next($request);
    }
    
}
