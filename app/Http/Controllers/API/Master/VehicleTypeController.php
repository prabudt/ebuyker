<?php

namespace App\Http\Controllers\API\Master;

use App\Http\Controllers\Controller;
use JWTAuth;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class VehicleTypeController extends Controller
{
    public function index(Request $request)
    {
        $data = VehicleType::where('active_flag', '1')->get();
        return $this->sendSuccess($data);  
    }
 
   
}