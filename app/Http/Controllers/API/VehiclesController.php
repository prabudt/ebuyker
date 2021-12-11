<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use JWTAuth;
use App\Models\Vehicles;
use Illuminate\Http\Request;

class VehiclesController extends Controller
{
    public function index(Request $request)
    {
        $params = $this->getRequest($request);

        $data = Vehicles::where('active_flag', '1');
        if(isset($params['vehicle_type_id'])) {
            $data->where('vehicle_type_id',$params['vehicle_type_id']);
        }
        $data = $data->get();
        return $this->sendSuccess($data);  
    }
 
   
}