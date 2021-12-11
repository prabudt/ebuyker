<?php
use Carbon\Carbon;

if (! function_exists('convertLocalToUTC')) {
    function convertLocalToUTC($time)
    {
        return Carbon::parse($time, 'Asia/Kolkata')->tz('UTC')->format('m-d-Y h:m:s');
    }
}

if (! function_exists('convertUTCToLocal')) {
    function convertUTCToLocal($time)
    {
        return Carbon::parse($time, 'UTC')->tz('Asia/Kolkata')->format('m-d-Y h:m:s');
    }
}

if (! function_exists('generateOTPNumber')) {
    function generateOTPNumber()
    {
        return (int)rand(100000,999999);
    }
}

if (! function_exists('objectToSingle')) {
    function objectToSingle($errors)
    {
        $results = [];
        $errors = $errors->toArray();
        foreach ($errors as $key => $value) {
            $results[$key] = isset($value[0]) ? $value[0] : $value;
        }
        return $results;
    }
}
