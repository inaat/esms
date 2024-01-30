<?php

namespace App\Utils;

use App\Models\Account;
use App\Models\Currency;
use App\Models\FeeHead;
use App\Models\ReferenceCount;
use App\Models\Session;
use App\Models\ClassSection;
use App\Models\AccountTransaction;
use App\Jobs\WhatsAppJob;
use App\Jobs\ProcessWhatsapp;
use App\Models\WhatsappLog;

use App\Models\SystemSetting;
use App\Models\User;
use Carbon\Carbon;
use Config;
use DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Services\WhatsappApiService;

class Util
{
    private $whatsappApiService;

    public function __construct(WhatsappApiService $whatsappApiService)
    {
        $this->whatsappApiService = $whatsappApiService;
    }
    public function getDays()
    {
        return [
            'sunday' => __('lang.sunday'),
            'monday' => __('lang.monday'),
            'tuesday' => __('lang.tuesday'),
            'wednesday' => __('lang.wednesday'),
            'thursday' => __('lang.thursday'),
            'friday' => __('lang.friday'),
            'saturday' => __('lang.saturday'),
        ];
    }
    public function getMonthList()
    {
        $months = array(
            1 => __('lang.january'),
            2 => __('lang.february'),
            3 => __('lang.march'),
            4 => __('lang.april'),
            5 => __('lang.may'),
            6 => __('lang.june'),
            7 => __('lang.july'),
            8 => __('lang.august'),
            9 => __('lang.september'),
            10 => __('lang.october'),
            11 => __('lang.november'),
            12 => __('lang.december')
        );
        return $months;
    }
    /**
     * Gives a list of all currencies
     *
     * @return array
     */
    public function allCurrencies()
    {
        $currencies = Currency::select('id', DB::raw("concat(country, ' - ',currency, '(', code, ') ') as info"))
            ->orderBy('country')
            ->pluck('info', 'id');

        return $currencies;
    }
    /**
     * Gives a list of all countries
     *
     * @return array
     */
    public function allCountries()
    {
        $countries = Currency::orderBy('country')
            ->pluck('country', 'id');

        return $countries;
    }
    /**
     * Gives a list of all timezone
     *
     * @return array
     */
    public function allTimeZones()
    {
        $datetime = new \DateTimeZone("EDT");

        $timezones = $datetime->listIdentifiers();
        $timezone_list = [];
        foreach ($timezones as $timezone) {
            $timezone_list[$timezone] = $timezone;
        }

        return $timezone_list;
    }
    /**
     * Uploads document to the server if present in the request
     * @param obj $request, string $file_name, string dir_name
     *
     * @return string
     */
    public function uploadFile($request, $file_name, $dir_name, $file_type = 'document', $roll_file_name = null)
    {
        //If app environment is demo return null
        if (config('app.env') == 'demo') {
            return null;
        }

        $uploaded_file_name = null;
        if ($request->hasFile($file_name) && $request->file($file_name)->isValid()) {
            //Check if mime type is image
            if ($file_type == 'image') {
                if (strpos($request->$file_name->getClientMimeType(), 'image/') === false) {
                    throw new \Exception("Invalid image file");
                }
            }

            if ($file_type == 'document') {
                if (!in_array($request->$file_name->getClientMimeType(), array_keys(config('constants.document_upload_mimes_types')))) {
                    throw new \Exception("Invalid document file");
                }
            }

            if ($request->$file_name->getSize() <= config('constants.document_size_limit')) {
                if (!empty($roll_file_name)) {
                    $new_file_name = $roll_file_name . '.' . $request->$file_name->getClientOriginalExtension();
                    if ($request->$file_name->storeAs($dir_name, $new_file_name)) {
                        $uploaded_file_name = $new_file_name;
                    }
                } else {
                    $new_file_name = time() . '_' . $request->$file_name->getClientOriginalName();
                    if ($request->$file_name->storeAs($dir_name, $new_file_name)) {
                        $uploaded_file_name = $new_file_name;
                    }
                }
            }
        }

        return $uploaded_file_name;
    }
    /**
     * Converts date in System Details format to mysql format
     *
     * @param string $date
     * @param bool $time (default = false)
     * @return string
     */
    public function uf_date($date, $time = false)
    {
        $date_format = session('system_details.date_format');
        $mysql_format = 'Y-m-d';
        if ($time) {
            if (session('system_details.time_format') == 12) {
                $date_format = $date_format . ' h:i A';
            } else {
                $date_format = $date_format . ' H:i';
            }
            $mysql_format = 'Y-m-d H:i:s';
        }

        return !empty($date_format) ? \Carbon::createFromFormat($date_format, $date)->format($mysql_format) : null;
    }
    /**
     * Converts time in business format to mysql format
     *
     * @param string $time
     * @return strin
     */
    public function uf_time($time)
    {
        $time_format = 'H:i';
        if (session('system_details.time_format') == 12) {
            $time_format = 'h:i A';
        }
        return !empty($time_format) ? \Carbon::createFromFormat($time_format, $time)->format('H:i') : null;
    }
    /**
     * Converts time in business format to mysql format
     *
     * @param string $time
     * @return strin
     */
    public function format_time($time)
    {
        $time_format = 'H:i';
        if (session('system_details.time_format') == 12) {
            $time_format = 'h:i A';
        }
        return !empty($time) ? \Carbon::createFromFormat('H:i:s', $time)->format($time_format) : null;
    }
    /**
     * This function unformats a number and returns them in plain eng format
     *
     * @param int $input_number
     *
     * @return float
     */
    public function num_uf($input_number, $currency_details = null)
    {
        $thousand_separator = '';
        $decimal_separator = '';

        if (!empty($currency_details)) {
            $thousand_separator = $currency_details->thousand_separator;
            $decimal_separator = $currency_details->decimal_separator;
        } else {
            $thousand_separator = session()->has('currency') ? session('currency')['thousand_separator'] : '';
            $decimal_separator = session()->has('currency') ? session('currency')['decimal_separator'] : '';
        }

        $num = str_replace($thousand_separator, '', $input_number);
        $num = str_replace($decimal_separator, '.', $num);

        return (float) $num;
    }
    /**
     * Converts date in mysql format to business format
     *
     * @param string $date
     * @param bool $time (default = false)
     * @return strin
     */
    public function format_date($date, $show_time = false, $system_details = null)
    {
        $format = !empty($system_details) ? $system_details->date_format : session('system_details.date_format');
        if (!empty($show_time)) {
            $time_format = !empty($system_details) ? $system_details->time_format : session('system_details.time_format');
            if ($time_format == 12) {
                $format .= ' h:i A';
            } else {
                $format .= ' H:i';
            }
        }

        return !empty($date) ? \Carbon::createFromTimestamp(strtotime($date))->format($format) : null;
    }
    public function age($dateOfBirth)
    {
        $age = \Carbon::parse($dateOfBirth);
        return \Carbon::createFromDate($age)->diff(Carbon::now())->format('%y years, %m months and %d days')
        ;
    }

    /**
     * Increments reference count for a given type and given business
     * and gives the updated reference count
     *
     * @param string $type
     * @param int $business_id
     *
     * @return int
     */
    public function setAndGetReferenceCount($type, $before = false, $after = false)
    {
        $system_settings_id = 1;

        $ref = ReferenceCount::where('ref_type', $type)
            ->where('system_settings_id', $system_settings_id)
            ->first();
        if (!empty($ref)) {
            if ($before) {
                $ref->ref_count += 1;
                return $ref->ref_count;
            }
            if ($after) {
                $ref->ref_count += 1;
                $ref->save();
                return $ref->ref_count;
            }
        } else {
            $new_ref = ReferenceCount::create([
                'ref_type' => $type,
                'system_settings_id' => $system_settings_id,
                'system_settings_id' => $system_settings_id,
                'ref_count' => 1,
            ]);
            return $new_ref->ref_count;
        }
    }
    /**
     * Increments reference count for a given type and given business
     * and gives the updated reference count
     *
     * @param string $type
     * @param int $business_id
     *
     * @return int
     */
    public function setAndGetRollNoCount($type, $before = false, $after = false)
    {
        $system_settings_id = 1;
        $ref = ReferenceCount::where('ref_type', $type)
            ->where('system_settings_id', $system_settings_id)
            //  ->where('session_close','=','open')
            ->first();
        //dd($ref);
        if (!empty($ref)) {
            if ($before) {
                $ref->ref_count += 1;
                return $ref->ref_count;
            }
            if ($after) {
                $ref->ref_count += 1;
                $ref->save();
                return $ref->ref_count;
            }
        }
    }

    /**
     * Generates reference number
     *
     * @param string $type
     * @param int $business_id
     *
     * @return int
     */
    public function generateReferenceNumber($type, $ref_count, $system_settings_id = null, $default_prefix = null)
    {
        $prefix = '';
        $system_settings_id = 1;

        if (session()->has('system_details') && !empty(request()->session()->get('system_details.ref_no_prefixes')[$type])) {
            $prefix = request()->session()->get('system_details.ref_no_prefixes')[$type];
        }
        if (!empty($system_settings_id)) {
            $system_details = SystemSetting::find($system_settings_id);
            $prefixes = $system_details->ref_no_prefixes;
            $prefix = !empty($prefixes[$type]) ? $prefixes[$type] : '';
        }

        if (!empty($default_prefix)) {
            $prefix = $default_prefix;
        }

        $ref_digits = str_pad($ref_count, 4, 0, STR_PAD_LEFT);
        if ($type == 'roll_no') {
            $ref_number = $prefix . '-' . $ref_digits;
        } elseif ($type == 'employee') {
            $ref_number = $prefix . '-' . $ref_digits;
        } else {
            if (in_array($type, ['admission', 'employee'])) {
                $ref_year = \Carbon::now()->year;
                $ref_number = $prefix . '-' . $ref_year . '-' . $ref_digits;
            } else {
                $ref_year = \Carbon::now()->year;
                $ref_number = $ref_year . '-' . $ref_digits;
            }
        }

        return $ref_number;
    }

    /**
     * This function formats a number and returns them in specified format
     *
     * @param int $input_number
     * @param boolean $add_symbol = false
     * @param array $systems_details = null
     * @param boolean $is_quantity = false; If number represents quantity
     *
     * @return string
     */
    public function num_f($input_number, $add_symbol = false, $systems_details = null, $is_quantity = false)
    {
        $thousand_separator = !empty($systems_details) ? $systems_details->thousand_separator : session('currency')['thousand_separator'];
        $decimal_separator = !empty($systems_details) ? $systems_details->decimal_separator : session('currency')['decimal_separator'];

        $currency_precision = config('constants.currency_precision', 2);

        if ($is_quantity) {
            $currency_precision = config('constants.quantity_precision', 2);
        }

        $formatted = number_format($input_number, $currency_precision, $decimal_separator, $thousand_separator);

        if ($add_symbol) {
            $currency_symbol_placement = !empty($systems_details) ? $systems_details->currency_symbol_placement : session('systems_details.currency_symbol_placement');
            $symbol = !empty($systems_details) ? $systems_details->currency_symbol : session('currency')['symbol'];

            if ($currency_symbol_placement == 'after') {
                $formatted = $formatted . ' ' . $symbol;
            } else {
                $formatted = $symbol . ' ' . $formatted;
            }
        }

        return $formatted;
    }

    public function getFeeHeads($campus_id, $class_id)
    {
        //$query=FeeHead::whereNotIn('description',['Admission','Prospectus','Security','Tuition','Transport']);

        $query = FeeHead::
        //where('campus_id', $campus_id)
            where('class_id', $class_id)->whereNotIn('description', ['Admission', 'Prospectus', 'Security', 'Tuition', 'Transport']);

        $fee_heads = $query->get();
        return $fee_heads;
    }
    public function getAdmissionFeeHeads($campus_id, $class_id)
    {
        $query = FeeHead::whereIn('description', ['Admission', 'Prospectus', 'Security']);

        // $query=FeeHead::where('campus_id', $campus_id)
        // ->where('class_id', $class_id)->whereIn('description',['Admission','Prospectus','Security']);

        $fee_heads = $query->get();
        return $fee_heads;
    }
    public function getOtherFeeHeads()
    {
        $query = FeeHead::whereIn('description', ['Others']);

        // $query=FeeHead::where('campus_id', $campus_id)
        // ->where('class_id', $class_id)->whereIn('description',['Admission','Prospectus','Security']);

        $fee_heads = $query->get();
        return $fee_heads;
    }
    public function getActiveSession()
    {
        $session = Session::where('status', 'ACTIVE')->first();
        return $session->id;
    }
    public function syncDevice()
    {
        $client = new Client();
    }
    /**
     * Defines available Payment Types
     *
     * @return array
     */
    public function payment_types()
    {
        // if(!empty($location)){
        //     $location = is_object($location) ? $location : BusinessLocation::find($location);

        //     //Get custom label from business settings
        //     $custom_labels = Business::find($location->business_id)->custom_labels;
        //     $custom_labels = json_decode($custom_labels, true);
        // } else {
        //     if (!empty($business_id)) {
        //         $custom_labels = Business::find($business_id)->custom_labels;
        //         $custom_labels = json_decode($custom_labels, true);
        //     } else {
        //         $custom_labels = [];
        //     }
        // }

        $payment_types = ['cash' => __('english.cash'), 'card' => __('english.card'), 'cheque' => __('english.cheque'), 'bank_transfer' => __('english.bank_transfer'), 'advance_pay' => __('english.advance_pay'), 'other' => __('english.other')];

        // $payment_types['custom_pay_1'] = !empty($custom_labels['payments']['custom_pay_1']) ? $custom_labels['payments']['custom_pay_1'] : __('english.custom_payment', ['number' => 1]);
        // $payment_types['custom_pay_2'] = !empty($custom_labels['payments']['custom_pay_2']) ? $custom_labels['payments']['custom_pay_2'] : __('english.custom_payment', ['number' => 2]);
        // $payment_types['custom_pay_3'] = !empty($custom_labels['payments']['custom_pay_3']) ? $custom_labels['payments']['custom_pay_3'] : __('english.custom_payment', ['number' => 3]);
        // $payment_types['custom_pay_4'] = !empty($custom_labels['payments']['custom_pay_4']) ? $custom_labels['payments']['custom_pay_4'] : __('english.custom_payment', ['number' => 4]);
        // $payment_types['custom_pay_5'] = !empty($custom_labels['payments']['custom_pay_5']) ? $custom_labels['payments']['custom_pay_5'] : __('english.custom_payment', ['number' => 5]);
        // $payment_types['custom_pay_6'] = !empty($custom_labels['payments']['custom_pay_6']) ? $custom_labels['payments']['custom_pay_6'] : __('english.custom_payment', ['number' => 6]);
        // $payment_types['custom_pay_7'] = !empty($custom_labels['payments']['custom_pay_7']) ? $custom_labels['payments']['custom_pay_7'] : __('english.custom_payment', ['number' => 7]);

        // //Unset payment types if not enabled in business location
        // if (!empty($location)) {
        //     $location_account_settings = !empty($location->default_payment_accounts) ? json_decode($location->default_payment_accounts, true) : [];
        //     $enabled_accounts = [];
        //     foreach ($location_account_settings as $key => $value) {
        //         if (!empty($value['is_enabled'])) {
        //             $enabled_accounts[] = $key;
        //         }
        //     }
        //     foreach ($payment_types as $key => $value) {
        //         if (!in_array($key, $enabled_accounts)) {
        //             unset($payment_types[$key]);
        //         }
        //     }
        // }

        // if ($show_advance) {
        //   $payment_types = ['advance' => __('english.advance')] + $payment_types;
        // }

        return $payment_types;
    }
    public function accountsDropdown($system_settings_id, $campus_id, $prepend_none = false, $closed = false, $default_campus_account = false, $show_balance = false)
    {
        $dropdown = [];

        // if ($this->isModuleEnabled('account')) {
        $dropdown = Account::forDropdown($system_settings_id, $campus_id, $prepend_none, $closed, $default_campus_account, $show_balance);
        //}

        return $dropdown;
    }

    /**
     * Sends SMS notification.
     *
     * @param  array $data
     * @return void
     */
    public function sendSms($data)
    {
        $sms_settings = $data['sms_settings'];
        $sms_service = isset($sms_settings['sms_service']) ? $sms_settings['sms_service'] : 'other';

        if ($sms_service == 'nexmo') {
            return $this->sendSmsViaNexmo($data);
        }

        if ($sms_service == 'twilio') {
            return $this->sendSmsViaTwilio($data);
        }
        $sms_send_through_whatsapp = config('constants.sms_send_through_whatsapp');

        if ($sms_send_through_whatsapp) {

            return $this->sendSmsOnWhatsapp($data);
        }

        $request_data = [
            $sms_settings['send_to_param_name'] => $data['mobile_number'],
            $sms_settings['msg_param_name'] => $data['sms_body'],
        ];

        if (!empty($sms_settings['param_1'])) {
            $request_data[$sms_settings['param_1']] = $sms_settings['param_val_1'];
        }
        if (!empty($sms_settings['param_2'])) {
            $request_data[$sms_settings['param_2']] = $sms_settings['param_val_2'];
        }
        if (!empty($sms_settings['param_3'])) {
            $request_data[$sms_settings['param_3']] = $sms_settings['param_val_3'];
        }
        if (!empty($sms_settings['param_4'])) {
            $request_data[$sms_settings['param_4']] = $sms_settings['param_val_4'];
        }
        if (!empty($sms_settings['param_5'])) {
            $request_data[$sms_settings['param_5']] = $sms_settings['param_val_5'];
        }
        if (!empty($sms_settings['param_6'])) {
            $request_data[$sms_settings['param_6']] = $sms_settings['param_val_6'];
        }
        if (!empty($sms_settings['param_7'])) {
            $request_data[$sms_settings['param_7']] = $sms_settings['param_val_7'];
        }
        if (!empty($sms_settings['param_8'])) {
            $request_data[$sms_settings['param_8']] = $sms_settings['param_val_8'];
        }
        if (!empty($sms_settings['param_9'])) {
            $request_data[$sms_settings['param_9']] = $sms_settings['param_val_9'];
        }
        if (!empty($sms_settings['param_10'])) {
            $request_data[$sms_settings['param_10']] = $sms_settings['param_val_10'];
        }

        $client = new Client();

        $headers = [];
        if (!empty($sms_settings['header_1'])) {
            $headers[$sms_settings['header_1']] = $sms_settings['header_val_1'];
        }
        if (!empty($sms_settings['header_2'])) {
            $headers[$sms_settings['header_2']] = $sms_settings['header_val_2'];
        }
        if (!empty($sms_settings['header_3'])) {
            $headers[$sms_settings['header_3']] = $sms_settings['header_val_3'];
        }

        $options = [];
        if (!empty($headers)) {
            $options['headers'] = $headers;
        }

        if (empty($sms_settings['url'])) {
            return false;
        }

        if ($sms_settings['request_method'] == 'get') {
            $response = $client->get($sms_settings['url'] . '?' . http_build_query($request_data), $options);
        } else {
            $options['form_params'] = $request_data;

            $response = $client->post($sms_settings['url'], $options);
            $body = $response->getBody();
            $return_body = json_decode($body);
        }

        return $return_body;
    }
    public function sendSmsOnWhatsapp($data)
    {
       
        if(strlen(trim($data['mobile_number'])) > 10){
        $addSeconds = 30;
        $schedule = 1;
        $log = new WhatsappLog();
        $log->message = $data['sms_body'];
        $log->to = $data['mobile_number'];
        $log->status = $schedule == 2 ? 2 : 1;
        $log->schedule_status = $schedule;
        $log->initiated_time = $schedule == 1 ? Carbon::now() : Carbon::now();
        $log->whatsapp_id = 8;
        $log->save();
        if (!empty($data['add_second'])) {
            $addSeconds = $data['add_second'];
        }
        $setTimeInDelay = \Carbon::now();
        dispatch(new ProcessWhatsapp($data['sms_body'], $data['mobile_number'], $log->id, [],$this->whatsappApiService))->delay(Carbon::parse($setTimeInDelay)->addSeconds($addSeconds));
    }


    }
    public function sendSmsOnWhatsappResend($id,$add_second)
    {
        
        $addSeconds = 30;
        $schedule = 1;
        $log = WhatsappLog::find($id);
        $log->status = 1;
        $log->save();
        if (!empty($add_second)) {
            $addSeconds = $add_second;
        }
        $setTimeInDelay = \Carbon::now();
        dispatch(new ProcessWhatsapp($log->message, $log->to, $log->id, [],$this->whatsappApiService))->delay(Carbon::parse($setTimeInDelay)->addSeconds($addSeconds));



    }

    public function getRollNo($session_id)
    {
        if (!empty($session_id)) {
            $ref_roll_no = $this->setAndGetRollNoCount('roll_no', true, false);
            $session = Session::findOrFail($session_id);
            $prefix = $session->prefix;
            $roll_no = $this->generateReferenceNumber('roll_no', $ref_roll_no, null, $prefix);

            return $roll_no;
        }
    }

    public function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];

        for ($date = $start_date->copy(); $date->lte($end_date); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }
    public function getCronJobCommand()
    {
        $php_binary_path = empty(PHP_BINARY) ? "php" : PHP_BINARY;

        $command = "* * * * * " . $php_binary_path . " " . base_path('artisan') . " schedule:run >> /dev/null 2>&1";

        if (config('app.env') == 'demo') {
            $command = '';
        }
        return $command;
    }

    public function getBackupCleanCronJobCommand()
    {
        $php_binary_path = empty(PHP_BINARY) ? "php" : PHP_BINARY;

        $command = "* * * * * " . $php_binary_path . " " . base_path('artisan') . " backup:clean >> /dev/null 2>&1";

        if (config('app.env') == 'demo') {
            $command = '';
        }

        return $command;
    }

    /**
     * Formats number to words
     * Requires php-intl extension
     *
     * @return string
     */
    public function numToWord($number, $lang = null, $type, $format = 'international')
    {
        if ($format == 'indian') {
            return $this->numToIndianFormat($number);
        }

        if (!extension_loaded('intl')) {
            return '';
        }

        if (empty($lang)) {
            $lang = !empty(auth()->user()) ? auth()->user()->language : 'en';
        }
        if ($type == 'SPELLOUT') {
            $f = new \NumberFormatter($lang, \NumberFormatter::SPELLOUT);
        } else {
            $f = new \NumberFormatter($lang, \NumberFormatter::ORDINAL);
        }

        return $f->format($number);
    }

    /**
     * Formats number to words in indian format
     *
     * @return string
     */
    public function numToIndianFormat(float $number)
    {
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            0 => '',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'forty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety'
        );
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_length) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
            } else {
                $str[] = null;
            }
        }
        $whole_number_part = implode('', array_reverse($str));
        $decimal_part = ($decimal > 0) ? " point " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) : '';
        return ($whole_number_part ? $whole_number_part : '') . $decimal_part;
    }

    /**
     * Retrives roles array (Hides admin role from non admin users)
     *
     * @param  int  $system_settings_id
     * @return array $roles
     */
    public function getRolesArray($system_settings_id)
    {
        $roles_array = Role::where('system_settings_id', $system_settings_id)->get()->pluck('name', 'id');
        $roles = [];

        $is_admin = $this->is_admin(auth()->user(), $system_settings_id);

        foreach ($roles_array as $key => $value) {
            if (!$is_admin && $value == 'Admin#' . $system_settings_id) {
                continue;
            }
            $roles[$key] = str_replace('#' . $system_settings_id, '', $value);
        }
        return $roles;
    }

    /**
     * Checks if the given user is admin
     *
     * @param obj $user
     * @param int $system_settings_id
     *
     * @return bool
     */
    public function is_admin($user, $system_settings_id = null)
    {
        $system_settings_id = empty($system_settings_id) ? $user->system_settings_id : $system_settings_id;

        return $user->hasRole('Admin#' . $system_settings_id) ? true : false;
    }

    public function getAdmins()
    {
        $system_settings_id = 1;
        $admins = User::role('Admin#' . $system_settings_id)->get();

        return $admins;
    }

    public function createEmployeeUpdateLogin($data, $hook_id, $role_id)
    {
        $type = 'other';

        if ($role_id == 1) {
            $type = 'admin';
        } elseif ($role_id == 2) {
            $type = 'teacher';
        } elseif ($role_id == 4) {
            $type = 'staff';
        }
        $system_settings_id = 1;

        $check_user = User::where('hook_id', $hook_id)->whereNotIn('user_type', ['student', 'guardian'])->first();
        //dd($data);
        if (empty($check_user)) {
            if (!empty($data['email'])) {
                $details = [
                    'email' => $data['email'],
                    'user_type' => $type,
                    'hook_id' => $hook_id,
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'campus_id' => $data['campus_id'],
                    'password' => $data['password'],
                    'system_settings_id' => $system_settings_id,
                    'image' => 'uploads/employee_image/' . $data['employee_image']

                ];
                $user = User::create($details);
                $role = Role::findOrFail($role_id);
                $user->assignRole($role->name);
                return $user;
            }
        } else {
            $user_data = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'campus_id' => $data['campus_id'],
                'email' => $data['email'],
                'password' => $data['password'],
                'user_type' => $type,
                'system_settings_id' => $system_settings_id,
                'image' => 'uploads/employee_image/' . $data['employee_image']
            ];
            $check_user->update($user_data);
            $role_id = $role_id;
            $employee_role = $check_user->roles->first();
            $previous_role = !empty($employee_role->id) ? $employee_role->id : 0;

            if ($previous_role != $role_id) {
                $is_admin = $this->is_admin($check_user);
                $all_admins = $this->getAdmins();
                //If only one admin then can not change role
                if ($is_admin && count($all_admins) <= 1) {
                    throw new \Exception(__('english.cannot_change_role'));
                }
                if (!empty($previous_role)) {
                    $check_user->removeRole($employee_role->name);
                }

                $role = Role::findOrFail($role_id);

                $check_user->assignRole($role->name);
                return $check_user;
            } else {
                $role = Role::findOrFail($role_id);
                $check_user->assignRole($role->name);
                return $check_user;
            }
        }
    }

    public function studentCreateUpdateLogin($data, $type, $hook_id)
    {
        $role_id = 3;
        $system_settings_id = 1;
        $check_user = User::where('hook_id', $hook_id)->where('user_type', 'student')->first();
        //dd($data);
        if (empty($check_user)) {
            if (!empty($data['email'])) {
                $details = [
                    'email' => $data['email'],
                    'user_type' => $type,
                    'hook_id' => $hook_id,
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'campus_id' => $data['campus_id'],
                    'password' => Hash::make('111111111'),
                    'system_settings_id' => $system_settings_id,
                    'image' => 'uploads/student_image/' . $data['student_image']

                ];
                $user = User::create($details);
                $role = Role::findOrFail($role_id);
                $user->assignRole($role->name);
                return $user;
            }
        } else {
            $user_data = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'campus_id' => $data['campus_id'],
                'email' => $data['email'],
                'password' => Hash::make('111111111'),
                'user_type' => $type,
                'system_settings_id' => $system_settings_id,
                'image' => 'uploads/student_image/' . $data['student_image']
            ];
            $check_user->update($user_data);
            $role_id = $role_id;
            $student_role = $check_user->roles->first();
            $previous_role = !empty($student_role->id) ? $student_role->id : 0;

            if ($previous_role != $role_id) {
                $is_admin = $this->is_admin($check_user);
                $all_admins = $this->getAdmins();
                //If only one admin then can not change role
                if ($is_admin && count($all_admins) <= 1) {
                    throw new \Exception(__('english.cannot_change_role'));
                }
                if (!empty($previous_role)) {
                    $check_user->removeRole($student_role->name);
                }

                $role = Role::findOrFail($role_id);

                $check_user->assignRole($role->name);
                return $check_user;
            } else {
                $role = Role::findOrFail($role_id);
                $check_user->assignRole($role->name);
                return $check_user;
            }
        }
    }


    public function guardianCreateUpdateLogin($data, $type, $hook_id)
    {
        $role_id = 5;
        $system_settings_id = 1;
        $check_user = User::where('hook_id', $hook_id)->where('user_type', 'guardian')->first();
      // dd($check_user);
        if (empty($check_user)) {
            if (!empty($data['guardian_email'])) {
                $details = [
                    'email' => $data['guardian_email'],
                    'user_type' => $type,
                    'hook_id' => $hook_id,
                    'first_name' => $data['guardian_name'],
                    'last_name' => '',
                    'password' => Hash::make('111111111'),
                    'system_settings_id' => $system_settings_id,
                    'image' => 'uploads/employee_image/default.jpg'

                ];
                $login=User::where('email',$data['guardian_email'])->where('user_type','guardian')->first();
                //dd($login);
                if(!empty($login)){
                    $login->delete(); 
                }
                $user = User::create($details);
                $role = Role::findOrFail($role_id);
                $user->assignRole($role->name);
                return $user;
            } else {
                $details = [
                    'email' => $hook_id . $type . '@gmail.com',
                    'user_type' => $type,
                    'hook_id' => $hook_id,
                    'first_name' => $data['guardian_name'],
                    'last_name' => '',
                    'password' => Hash::make('111111111'),
                    'system_settings_id' => $system_settings_id,
                    'image' => 'uploads/employee_image/default.jpg'

                ];
                //dd($details);
                $user = User::create($details);
                $role = Role::findOrFail($role_id);
                $user->assignRole($role->name);
                return $user;
            }
        } else {
            
            if (!empty($check_user)) {
                $check_email = User::where('email', $data['guardian_email'])->first();
                 if (!empty($check_email)) {
                //$check_email->delete();
                   return false;
                 }
                if (!empty($data['guardian_email'])) {
                    $data['guardian_email'] = $data['guardian_email'];
                } else {
                    $data['guardian_email'] = $check_user->email;
                }
                $user_data = [
                    'first_name' => $data['guardian_name'],
                    'last_name' => '',
                    'email' => $data['guardian_email'],
                    'password' => Hash::make('111111111'),
                    'user_type' => $type,
                    'system_settings_id' => $system_settings_id,
                    'image' => 'uploads/employee_image/default.jpg'
                ];
               //dd($user_data);
                $check_user->update($user_data);
               // dd($check_user);
                $role_id = $role_id;
                $student_role = $check_user->roles->first();
                $previous_role = !empty($student_role->id) ? $student_role->id : 0;

                if ($previous_role != $role_id) {
                    $is_admin = $this->is_admin($check_user);
                    $all_admins = $this->getAdmins();
                    //If only one admin then can not change role
                    if ($is_admin && count($all_admins) <= 1) {
                        throw new \Exception(__('english.cannot_change_role'));
                    }
                    if (!empty($previous_role)) {
                        $check_user->removeRole($student_role->name);
                    }

                    $role = Role::findOrFail($role_id);

                    $check_user->assignRole($role->name);
                    return $check_user;
                } else {
                    $role = Role::findOrFail($role_id);
                    $check_user->assignRole($role->name);
                    return $check_user;
                }
            } else {
               
             
                $user_data = [
                    'first_name' => $data['guardian_name'],
                    'last_name' => '',
                    'email' => $data['guardian_email'],
                    'password' => Hash::make('111111111'),
                    'user_type' => $type,
                    'system_settings_id' => $system_settings_id,
                    'image' => 'uploads/employee_image/default.jpg'
                ];
               
                $check_user->update($user_data);
                $role_id = $role_id;
                $student_role = $check_user->roles->first();
                $previous_role = !empty($student_role->id) ? $student_role->id : 0;

                if ($previous_role != $role_id) {
                    $is_admin = $this->is_admin($check_user);
                    $all_admins = $this->getAdmins();
                    //If only one admin then can not change role
                    if ($is_admin && count($all_admins) <= 1) {
                        throw new \Exception(__('english.cannot_change_role'));
                    }
                    if (!empty($previous_role)) {
                        $check_user->removeRole($student_role->name);
                    }

                    $role = Role::findOrFail($role_id);

                    $check_user->assignRole($role->name);
                    return $check_user;
                } else {
                    $role = Role::findOrFail($role_id);
                    $check_user->assignRole($role->name);
                    return $check_user;
                }
            }
        }
    }

    public function accountOther($system_settings_id, $campus_id, $prepend_none, $closed = false, $default_campus_account = false, $show_balance = false)
    {
        $query = Account::where('system_settings_id', $system_settings_id);


        $query->leftjoin('account_transactions as AT', function ($join) {
            $join->on('AT.account_id', '=', 'accounts.id');
            $join->whereNull('AT.deleted_at');
        })
            ->select(
                'accounts.name',
                'accounts.id',
                DB::raw("SUM( IF(AT.type='credit', amount, -1*amount) ) as balance")
            );
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
            $query->whereIn('accounts.campus_id', $permitted_campuses);
        }
        // if (!empty($campus_id)) {
        //     $query->whereNotIn('accounts.campus_id' ,[$campus_id]);
        // }
        if (!$closed) {
            $query->where('accounts.is_closed', 0);
        }

        $accounts = $query->groupBy('accounts.id')->get();



        return $accounts;
    }
    public function receiptContent($data, $view)
    {
        $output = [
            'is_enabled' => false,
            'print_type' => 'browser',
            'html_content' => null,
            'printer_config' => [],
            'data' => []
        ];

        //Check if printing of invoice is enabled or not.
        //If enabled, get print type.
        $output['is_enabled'] = true;
        $receipt_details = [];
        $output['html_content'] = view($view, compact('data'))->render();

        return $output;
    }

    public function getAccountBalance($end_date, $campus_id = null)
    {
        $query = Account::leftjoin(
            'account_transactions as AT',
            'AT.account_id',
            '=',
            'accounts.id'
        )
                                 ->NotClosed()
                                ->whereNull('AT.deleted_at')
                                ->whereDate('AT.operation_date', '<=', $end_date);

       
//Filter by the campus
$permitted_campuses = auth()->user()->permitted_campuses();
if ($permitted_campuses != 'all') {
 $query->whereIn('accounts.campus_id', $permitted_campuses);
}
 $query->where('accounts.id','!=', 3);

if (!empty($campus_id)) {
    $query->where('accounts.campus_id', $campus_id);
  }
        $account_details = $query->select(['name',
                                        DB::raw("SUM( IF(AT.type='credit', amount, -1*amount) ) as balance")])
                                ->groupBy('accounts.id')
                                ->get()
                                ->pluck('balance', 'name');

        return $account_details;
    }

    public function strengthReport()
    {


        $count_class_sections_student = ClassSection::leftjoin('campuses as cam', 'class_sections.campus_id', '=', 'cam.id')
            ->join('students', 'students.current_class_section_id', '=', 'class_sections.id')
            ->leftJoin('classes as c-class', 'class_sections.class_id', '=', 'c-class.id')
            ->select([
                'cam.campus_name',
                'c-class.title',
                'class_sections.section_name',
                DB::raw('count(students.id) as total_student')
            ])->where('students.status', '=', 'active')
            ->groupBy('class_sections.id')->orderBy('c-class.id');


        return $count_class_sections_student->get();

    }




    public function getTransportAccountBalance($end_date, $campus_id = null)
    {
        $query = Account::leftjoin(
            'account_transactions as AT',
            'AT.account_id',
            '=',
            'accounts.id'
        )
                                 ->NotClosed()
                                ->whereNull('AT.deleted_at')
                                ->whereDate('AT.operation_date', '<=', $end_date);

       
//Filter by the campus
$permitted_campuses = auth()->user()->permitted_campuses();
if ($permitted_campuses != 'all') {
 $query->whereIn('accounts.campus_id', $permitted_campuses);
}
 $query->where('accounts.id','=', 3);

if (!empty($campus_id)) {
    $query->where('accounts.campus_id', $campus_id);
  }
        $account_details = $query->select(['name',
                                        DB::raw("SUM( IF(AT.type='credit', amount, -1*amount) ) as balance")])
                                ->groupBy('accounts.id')
                                ->get()
                                ->pluck('balance', 'name');

        return $account_details;
    }






    public function getAccountOpeningBalance($end_date, $campus_id = null, $type = null)
    {
        $query = Account::leftjoin(
            'account_transactions as AT',
            'AT.account_id',
            '=',
            'accounts.id'
        )
            ->NotClosed()
            ->whereNull('AT.deleted_at')
            ->whereDate('AT.operation_date', '<=', $end_date);
             $query->where('accounts.id','!=', 3);

        //Filter by the campus
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
            $query->whereIn('accounts.campus_id', $permitted_campuses);
        }
        if (!empty($campus_id)) {
            $query->where('accounts.campus_id', $campus_id);
        }
        if ($type == 'opening_balance') {
            $query->where('AT.sub_type', 'opening_balance');
            $account_details = $query->select([
                DB::raw("SUM( IF(AT.type='credit', amount, -1*amount) ) as balance")
            ])
                ->first();


            return $account_details->balance;
        }
        if ($type == 'deposit') {
            $query->where('AT.sub_type', 'deposit');
            $account_details = $query->select([
                DB::raw("SUM( IF(AT.type='credit', amount, -1*amount) ) as balance")
            ])
                ->first();


            return $account_details->balance;
        }
        if ($type == 'debit') {
            $query->where('AT.sub_type', 'debit');
            $account_details = $query->select([
                DB::raw("SUM( IF(AT.type='debit', amount, -1*amount) ) as balance")
            ])
                ->first();


            return $account_details->balance;
        }
        // $account_details = $query->select(['name',
        //                                 DB::raw("SUM( IF(AT.type='credit', amount, -1*amount) ) as balance")])
        //                         ->groupBy('accounts.id')
        //                         ->get()
        //                         ->pluck('balance', 'name');



    }
    public function getAccountBeginningBalance($start_date,$end_date, $campus_id = null, $type = null){
        //dd($start_date,$end_date);

        $query = Account::where('id','!=', 3)->where('id','!=', 4)->get();
        $details=[];
        foreach($query as $ac)
        {
    $before_bal_query = AccountTransaction::
        where('account_transactions.account_id', $ac->id)
        ->whereDate('account_transactions.operation_date', '<', $start_date)
        ->select([
            DB::raw("COALESCE(SUM(IF(account_transactions.type = 'credit', account_transactions.amount, 0)),0) as bf_credit"),
            DB::raw("COALESCE(SUM(IF(account_transactions.type = 'debit', account_transactions.amount, 0)),0) as bf_debit"),
        ])
        ->whereNull('account_transactions.deleted_at')->first();

 $beginning_balance=($before_bal_query->bf_credit-$before_bal_query->bf_debit);
 $details[]=[
    'name'=>$ac->name,
    'id'=>$ac->id,
    'beginning_balance'=>$beginning_balance
 ];
        }
        //dd($details);
        return $details;
        }

    public function dateConversion($start,$end){
       
        $end_date=$end;    
        $_date=explode('-',$end_date) ;
        if($_date[2]>=26){
            $month=$_date['1']+1;
            if($month<=12){
                    $end_date=Carbon::parse($_date[0].'-'.$month.'-01')->format('Y-m-d');           
    
                }else{
                    $end_date=Carbon::parse($_date[0].'-'.$_date['1'].'-26')->format('Y-m-d');           
    
                }
        
            }
            $start_date=$start; 
            $_date = explode('-', $start_date);
            if ($_date[2]>= 1 && $_date[2]<26 ) {
                $month = $_date['1'] - 1;
                if ((int)$month >= 1 && (int)$month <= 12) {
                    $start_date = Carbon::parse($_date[0] . '-' . $month . '-26')->format('Y-m-d');

                } else {
                    $start_date = Carbon::parse($_date[0]-1 . '-' . $_date['1'] . '-26')->format('Y-m-d');

                }
            }
             if ((int)$_date[2]===26 || (int)$_date[2]===27 || (int)$_date[2]===28 || (int)$_date[2]===29 || (int)$_date[2]===30 || (int)$_date[2]===31  ) {
                $month = $_date['1'] ;
                if ($month >= 1 && $month <= 12) {
                    $start_date = Carbon::parse($_date[0] . '-' . $month . '-26')->format('Y-m-d');
                }
            }
        return ['start_date'=>$start_date,
        'end_date'=>$end_date];
    }
}





////DELETE FROM student_guardians WHERE id NOT IN (SELECT * FROM (SELECT MIN(n.id) FROM student_guardians n GROUP BY n.student_id) x);
///--init-command="SET SESSION FOREIGN_KEY_CHECKS=0;"