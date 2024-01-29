<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Frontend\FrontSetting;
use App\Models\Frontend\FrontCounter;
use App\Models\SystemSetting;
use App\Models\Session;


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

     
            $system_details= SystemSetting::first();
            
            $currency = $system_details->currency;
            $currency_data = ['id' => $currency->id,
                                'code' => $currency->code,
                                'symbol' => $currency->symbol,
                                'thousand_separator' => $currency->thousand_separator,
                                'decimal_separator' => $currency->decimal_separator
                            ];
            $session=Session::WHERE('status','ACTIVE')->first();
            $now = \Carbon::now();
            $request->session()->put('current_month', $now->month);
            $request->session()->put('session', $session);
            $request->session()->put('system_details', $system_details);
            $request->session()->put('currency', $currency_data);

            //set current financial year to session
            $financial_year = $this->getCurrentFinancialYear();
            $request->session()->put('financial_year', $financial_year);
 

        return $next($request);
    }
    public function getCurrentFinancialYear()
    {
        
        $start_month = 1;
        $end_month = $start_month -1;
        if ($start_month == 1) {
            $end_month = 12;
        }
        
        $start_year = date('Y');
        //if current month is less than start month change start year to last year
        if (date('n') < $start_month) {
            $start_year = $start_year - 1;
        }

        $end_year = date('Y');
        //if current month is greater than end month change end year to next year
        if (date('n') > $end_month) {
            $end_year = $start_year + 1;
        }
        $start_date = $start_year . '-' . str_pad($start_month, 2, 0, STR_PAD_LEFT) . '-01';
        $end_date = $end_year . '-' . str_pad($end_month, 2, 0, STR_PAD_LEFT) . '-01';
        $end_date = date('Y-m-t', strtotime($end_date));

        $output = [
                'start' => $start_date,
                'end' =>  $end_date
            ];
        return $output;
    }
    
}
