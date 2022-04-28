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
        $filePath = $data->storeAs('/', $fileName, 'do_spaces');      
        return Storage::disk('do_spaces')->url($filePath);
    }
    
}

if (! function_exists('pushToMobile')) {
    function pushToMobile($data, $title, $chatBody)
    {
        try {
            $url = 'https://fcm.googleapis.com/fcm/send';
            $serverKey = 'duc5x2pu5PQ:APA91bE0ddy7mNri9AvGed4YTwq1W86aT1bImzxvgaCzJSOfJhW2ZwPcZ-aRTFcBMpWisIE9W0ss61EC9EQAlg8BQKaPln8EVheZI74KO56IC9l0q54_kavDeAfRnkZmFefpoVzPgZMp';

            $data = [
                "to" => $data->push_token,
                "notification" => [
                    "title" => $title,
                    "body" => $chatBody,  
                ]
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
            if ($result === FALSE) {
                $message = ('Curl failed: ' . curl_error($ch));
            }        
            // Close connection
            $message = "success";
            curl_close($ch);
        } catch (\Exception $ex) {
            if (isset($ex->errorInfo[2])) {
                $message = $ex->errorInfo[2];
            } else {
                $message = $ex->getMessage();
            }
        }

        return  $message;
    }
}
