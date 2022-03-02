<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use JWTAuth;
use App\Models\Booking;
use App\Models\UsersBasedLoadBook;
use App\Models\UsersBasedLoadBookChat;
use App\Models\Loads;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $this->getRequest($request);

        $data = Booking::with(['loads.loadCreatedBy','loads.vehicleType', 'loads.vehicles', 'users'])
                ->where('user_id','>=', JWTAuth::user()->id)
                ->where('approval_flag', '1');
                
        if(isset($params['from_date']) && isset($params['to_date'])) {
            $fromDate = Carbon::parse($params['from_date'])->format('Y-m-d');
            $toDate = Carbon::parse($params['to_date'])->format('Y-m-d');
            $data->whereDate('created_at','>=', $fromDate);
            $data->whereDate('created_at','<=', $toDate);
        }


        $data = $data->get();
        return $this->sendSuccess($data);
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
            'booking_type' => 'required',
            'load_id' => 'required|numeric|unique:booking_load,load_id,null,null,approval_flag,1'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            $errors = objectToSingle($validator->errors());
            return $this->validationError($errors);
        }
        $loadList = Loads::find($params['load_id']); 
        
        if(!empty($loadList)) {
            $params['user_id'] = JWTAuth::user()->id;
            $params['booking_amount'] = $loadList->amount;
            if($params['booking_type'] == 1) {
                $params['approval_flag'] = 0;
            }
            $bookList = Booking::where('load_id', $params['load_id'])->first(); 
            if(!empty($bookList)) {
                $response = $bookList;
                if($params['booking_type'] == 0) {
                    $response->update(['approval_flag'=>0,'user_id'=>JWTAuth::user()->id]);
                }
            } else {
                $response = Booking::create($params);
            }
            
            $message = 'Booking successfully.';
            if($params['booking_type'] == 1 && !empty($response)) {
                $userBasedBook['user_id'] = JWTAuth::user()->id;
                $userBasedBook['booking_id'] = $response->id;
                $userBasedBook['limit_count'] = config('constants.chat_limit');
                $userBasedBook['created_at'] = Carbon::now()->format('Y-m-d H:m:s');
                $userBasedBook['updated_at'] = Carbon::now()->format('Y-m-d H:m:s');
                $userBasedBookResponse = UsersBasedLoadBook::insert($userBasedBook);
                $message = 'Booking Negotiations going on.';
            }
            
            return $this->sendSuccess($response, $message);
    
        } else {
            return $this->validationError('Oops! Loads does not exist.');
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Loads::with(['vehicleType','vehicles'])->where('active_flag', '1')->find($id);
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
        //
    }
}
