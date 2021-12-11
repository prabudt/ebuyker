<?php
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

if (! function_exists('convertLocalToUTC')) {
    function convertLocalToUTC($time)
    {
        return Carbon::parse($time, 'Asia/Kolkata')->tz('UTC')->format('Y-m-d h:m:s');
    }
}

if (! function_exists('convertUTCToLocal')) {
    function convertUTCToLocal($time)
    {
        return Carbon::parse($time, 'UTC')->tz('Asia/Kolkata')->format('Y-m-d h:m:s');
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
        return implode(',',$results);
    }
}

if (! function_exists('fileUploadStorage')) {
    function fileUploadStorage($data) {
        $date = date("Y-m-d H:i:s");
        $rand = strtotime($date);
        $fileName = $rand.'_'.$data->getClientOriginalName();
        $filePath = $data->storeAs('/', $fileName, 'public');      
        return Storage::disk('public')->url($filePath);
    }
    
}
