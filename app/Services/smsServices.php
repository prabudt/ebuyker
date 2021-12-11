<?php

namespace App\Services;


use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use App\Models\SmsLog;


class smsServices
{
    protected const ACTIVE_FLAG = 1;
    protected const ATTENDANCE_APPROVAL_REASON_TYPE_ID = 14;
    
    public function sendSms($mobileNo, $message) { 
       Log::info($message);
       SmsLog::create([
           'mobile_no' => $mobileNo,
           'active_flag' => 1,
           'message' => $message
       ]);
    }

}