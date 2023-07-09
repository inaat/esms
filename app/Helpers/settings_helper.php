<?php

use App\Models\Grade;
use App\Models\Language;
use App\Models\Settings;

function getSettings($type = '')
{
    $settingList = array();
    if ($type == '') {
        $setting = Settings::get();
    } else {
        $setting = Settings::where('type', $type)->get();
    }
    foreach ($setting as $row) {
        $settingList[$row->type] = $row->message;
    }
    return $settingList;
}

function get_language()
{
    return Language::get();
}


function getTimeFormat()
{
    $timeFormat = array();
    $timeFormat['h:i a'] = 'h:i a - ' . date('h:i a');
    $timeFormat['h:i A'] = 'h:i A - ' . date('h:i A');
    $timeFormat['H:i'] = 'H:i - ' . date('H:i');
    return $timeFormat;
}

function getDateFormat()
{
    $dateFormat = array();
    $dateFormat['d/m/Y'] = 'd/m/Y - ' . date('d/m/Y');
    $dateFormat['m/d/Y'] = 'm/d/Y - ' . date('m/d/Y');
    $dateFormat['Y/m/d'] = 'Y/m/d - ' . date('Y/m/d');
    $dateFormat['Y/d/m'] = 'Y/d/m - ' . date('Y/d/m');
    $dateFormat['m-d-Y'] = 'm-d-Y - ' . date('m-d-Y');
    $dateFormat['d-m-Y'] = 'd-m-Y - ' . date('d-m-Y');
    $dateFormat['Y-m-d'] = 'Y-m-d - ' . date('Y-m-d');
    $dateFormat['Y-d-m'] = 'Y-d-m - ' . date('Y-d-m');
    $dateFormat['F j, Y'] = 'F j, Y - ' . date('F j, Y');
    $dateFormat['jS F Y'] = 'jS F Y - ' . date('jS F Y');
    $dateFormat['l jS F'] = 'l jS F - ' . date('l jS F');
    $dateFormat['d M, y'] = 'd M, y - ' . date('d M, y');
    return $dateFormat;
}

function getTimezoneList()
{
    static $timezones = null;

    if ($timezones === null) {
        $list = DateTimeZone::listAbbreviations();
        $idents = DateTimeZone::listIdentifiers();

        $data = $offset = $added = array();
        foreach ($list as $abbr => $info) {
            foreach ($info as $zone) {
                if (!empty($zone['timezone_id']) and !in_array($zone['timezone_id'], $added) and in_array($zone['timezone_id'], $idents)) {
                    $z = new DateTimeZone($zone['timezone_id']);
                    $c = new DateTime('', $z);
                    $zone['time'] = $c->format('H:i a');
                    $offset[] = $zone['offset'] = $z->getOffset($c);
                    $data[] = $zone;
                    $added[] = $zone['timezone_id'];
                }
            }
        }

        array_multisort($offset, SORT_ASC, $data);
        $i = 0;
        $temp = array();
        foreach ($data as $key => $row) {
            $temp[0] = $row['time'];
            $temp[1] = formatOffset($row['offset']);
            $temp[2] = $row['timezone_id'];
            $timezones[$i++] = $temp;
        }
    }
    return $timezones;
}

function formatOffset($offset)
{
    $hours = $offset / 3600;
    $remainder = $offset % 3600;
    $sign = $hours > 0 ? '+' : '-';
    $hour = (int)abs($hours);
    $minutes = (int)abs($remainder / 60);

    if ($hour == 0 and $minutes == 0) {
        $sign = ' ';
    }
    return $sign . str_pad($hour, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0');
}

// function flattenMyModel($model)
// {
//     $modelArr = $model->toArray();
//     $data = [];
//     array_walk_recursive($modelArr, function ($item, $key) use (&$data) {
//         $data[$key] = $item;
//     });
//     return $data;
// }

function changeEnv($data = array())
{
    if (count($data) > 0) {

        // Read .env-file
        $env = file_get_contents(base_path() . '/.env');
        // Split string on every " " and write into array
        $env = explode(PHP_EOL, $env);
        // $env = preg_split('/\s+/', $env);

        // Loop through given data
        foreach ((array)$data as $key => $value) {
            $key_value = $key . "=" . $value;

            // Loop through .env-data
            foreach ($env as $env_key => $env_value) {
                // Turn the value into an array and stop after the first split
                // So it's not possible to split e.g. the App-Key by accident
                $entry = explode("=", $env_value);
                // Check, if new key fits the actual .env-key
                if ($entry[0] == $key) {
                    // If yes, overwrite it with the new one
                    $env[$env_key] = $key . "=" . str_replace('"', '', $value);
                } else {
                    if ((in_array($key, $entry))) {
                        $env[] = $key_value;
                    }
                    // If not, keep the old one
                    $env[$env_key] = $env_value;
                }
            }
        }
        // Turn the array back to an String
        $env = implode("\n", $env);

        // And overwrite the .env with the new data
        file_put_contents(base_path() . '/.env', $env);

        return true;
    } else {
        return false;
    }
}
function findExamGrade($percentage)
{
    $grades = Grade::get();
    if (sizeof($grades)) {
        foreach ($grades as $row) {
            if (floor($percentage) >= $row['starting_range'] && floor($percentage) <= $row['ending_range']) {
                return $row->grade;
            }
        }
    }else{
        return '';
    }
}
