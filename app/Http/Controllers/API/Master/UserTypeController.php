<?php

namespace App\Http\Controllers\API\Master;

use App\Http\Controllers\Controller;
use JWTAuth;
use App\Models\UserType;
use Illuminate\Http\Request;

class UserTypeController extends Controller
{
    public function index(Request $request)
    {
        $data = UserType::where('is_admin', 0)->get();
        return $this->sendSuccess($data);  
    }
 
   
}