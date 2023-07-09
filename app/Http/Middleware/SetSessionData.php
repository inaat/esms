<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Utils\OrganizationUtil;
use App\Models\SystemSetting;
use App\Models\Session;


class SetSessionData
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

        if (!$request->session()->has('user')) {
            // $business_util = new BusinessUtil;
             
            $user = Auth::user();
            
            $session_data = ['id' => $user->id,
                            'surname' => $user->surname,
                            'first_name' => $user->first_name,
                            'last_name' => $user->last_name,
                            'email' => $user->email,
                            'hook_id' => $user->hook_id,
                            'user_type' => $user->user_type,
                            'image' => $user->image,
                            'system_settings_id' => $user->system_settings_id,
                            'language' => $user->language,
                            'campus_id'=>$user->campus_id
                            ];
            
            $system_details= SystemSetting::findOrFail($user->system_settings_id);
            
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
            $request->session()->put('user', $session_data);
            $request->session()->put('system_details', $system_details);
            $request->session()->put('currency', $currency_data);

            //set current financial year to session
            $financial_year = $this->getCurrentFinancialYear();
            $request->session()->put('financial_year', $financial_year);
        }

        return $next($request);
    }
      /**
     * Gives current financial year
     *
     * @return array
     */
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
