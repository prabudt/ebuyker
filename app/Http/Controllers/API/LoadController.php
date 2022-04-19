<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use JWTAuth;
use App\Models\Loads;
use App\Models\Truck;
use App\Models\Booking;

class LoadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $this->getRequest($request);
        $currentDate = Carbon::now()->format('Y-m-d');

        $data = Loads::with(['loadCreatedBy', 'vehicleType','vehicles', 'booking.users.truckData.truckFileFata'])->where('active_flag', '1');
        if(isset($params['is_expiry']) && $params['is_expiry'] == 1 ) {
            $data->whereDate('pickup_date','<=',$currentDate);
        } elseif(isset($params['is_expiry']) && $params['is_expiry'] == 0 ) {
            $data->whereDate('pickup_date','>=', $currentDate);
        } 

        if(isset($params['from_location']) && $params['to_location']) {
            $data->where('load_location','like', '%' .$params['from_location']. '%');
            $data->where('unload_location','like', '%' .$params['to_location']. '%');
        }

        if(isset($params['is_customer_view'])) {
            $data->has('booking');
        }
        
        if(isset($params['show']) && $params['show'] == true ) {
        } else {
            $data->where('user_id', JWTAuth::user()->id);
        }

        $data = $data->orderBy('id', 'desc')->get();

        $result = collect();
        if( JWTAuth::user()->user_type_id != 2) {
            if(isset($params['show_booking']) && $params['show_booking'] == 1) { 
                if(count($data) > 0) {
                    foreach ($data as $key => $value) {
                        if(isset($value->booking) && !empty($value->booking) && isset($value->booking->approval_flag) && $value->booking->approval_flag == 1 ) {
                            if(JWTAuth::user()->id == $value->booking->user_id) {
                                $result[$key] = $value;
                            }
                        } else {
                            $result[$key] = $value;
                        }                   
                    }
                }
            }
            $result = $result->values();
            $result->all();
        } else {
            $result = $data;
        }
        return $this->sendSuccess($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $params = $this->getRequest($request);
        $validator = Validator::make($params, [
            'load_location' => 'required',
            'unload_location' => 'required',
            'pickup_date' => 'required|date',
            'vehicle_type_id' => 'required',
            'vehicle_id' => 'required',
            'material_type' => 'required',
            'material_weight' => 'required',
            'material_length' => 'required',
            'material_width' => 'required',
            'material_height' => 'required',
            'amount' => 'required|numeric|between:0,999999999.99'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            $errors = objectToSingle($validator->errors());
            return $this->validationError($errors);
        }
        // dd($params);
        $params['user_id'] = JWTAuth::user()->id;
        $params['pickup_date'] = date('Y-m-d H:m:s', \strtotime($params['pickup_date']));
        $response = Loads::create($params);
        $message = 'Load created successfully.';

        return $this->sendSuccess($response, $message);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Loads::with(['loadCreatedBy', 'vehicleType','vehicles', 'booking.users.truckData.truckFileFata'])->where('active_flag', '1')->find($id);

        if( JWTAuth::user()->user_type_id != 2) {            
            if(isset($data->booking) && !empty($data->booking)) {
                if(JWTAuth::user()->id != $data->booking->user_id) {
                    dd('test');
                    $data->booking = null;
                }
            }
        }
        return $this->sendSuccess($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $params = $this->getRequest($request);   
        $validator = Validator::make($params, [           
            'amount' => 'required|numeric|between:0,99.99'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            $errors = objectToSingle($validator->errors());
            return $this->validationError($errors);
        }    
        if(isset($params['pickup_date'])) {
            $params['pickup_date'] = date('Y-m-d H:m:s', \strtotime($params['pickup_date']));
        }
        
        $response = Loads::find($id);
        $response->update($params);
        $message = 'Load updated successfully.';

        return $this->sendSuccess($response, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Loads::find($id);
        if($data->is_expiry == 0) {
            $response = Loads::destroy($id);
            $message = 'Load cancelled successfully.';
            return $this->sendSuccess($response, $message);
        } else {
            $message = 'Load already expired.';  
            return $this->validationError($message);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function locationBasedLoad(Request $request)
    {
        $params = $this->getRequest($request);
        $currentDate = Carbon::now()->format('Y-m-d');

        $truckLocation = Truck::where('active_flag', 1)->where('user_id', JWTAuth::user()->id)->pluck('location')->toArray();
        $data = Loads::with(['loadCreatedBy', 'vehicleType','vehicles', 'booking.users.truckData.truckFileFata'])
                    ->where('active_flag', '1');
        if(isset($params['is_expiry']) && $params['is_expiry'] == 1 ) {
            $data->whereDate('pickup_date','<=',$currentDate);
        } elseif(isset($params['is_expiry']) && $params['is_expiry'] == 0 ) {
            $data->whereDate('pickup_date','>=', $currentDate);
        } 

        $data->where(function ($query) use($truckLocation) {
            for ($i = 0; $i < count($truckLocation); $i++){
               $query->orwhere('load_location', 'like',  '%' . $truckLocation[$i] .'%');
            }      
        });

        /* $data->doesntHave('booking')->orWhereHas('booking', function($q){
            $q->where('user_id', JWTAuth::user()->id);
        }); */

        $data->orWhereHas('booking', function($q){
            $q->where('user_id', JWTAuth::user()->id)->where('approval_flag', 0);
        });
        $data = $data->orderBy('id', 'desc')->get();
        
        $result = collect();
        if( JWTAuth::user()->user_type_id != 2) {
            if(count($data) > 0) {
                foreach ($data as $key => $value) {
                    if(isset($value->booking) && !empty($value->booking) && isset($value->booking->approval_flag) && $value->booking->approval_flag ==1) {
                        if(JWTAuth::user()->id == $value->booking->user_id ) {
                            $result[$key] = $value;
                        }
                    } else {
                        $result[$key] = $value;
                    }                                
                }
                $result = $result->values();
                $result->all();
            }
        } else {
            $result = $data;
        }
        return $this->sendSuccess($result);
    }
}
