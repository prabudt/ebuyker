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
use App\Models\UserDevices;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $this->getRequest($request);

        $data = UsersBasedLoadBook::with(['user','BookChatData' => function ($query) {
            $query->orderBy('id', 'desc');
        }])->where('booking_id', $params['booking_id']);
                
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
        if(JWTAuth::user()->user_type_id == 2) {
            $validator = Validator::make($params, [
                'booking_user_list_id' => 'required|numeric',
                'amount' => 'required|numeric|between:0,999999999.99'
            ]);
        } else {
            $validator = Validator::make($params, [
                'booking_id' => 'required|numeric',
                'amount' => 'required|numeric|between:0,999999999.99'
            ]);
        }
        

        //Send failed response if request is not valid
        if ($validator->fails()) {
            $errors = objectToSingle($validator->errors());
            return $this->validationError($errors);
        }
        $bookingList = UsersBasedLoadBook::with(['booking.loads']);

        if(JWTAuth::user()->user_type_id == 2) {
            $bookingList->where('id', $params['booking_user_list_id']);
        } else {
            $bookingList->where('booking_id', $params['booking_id']);
        }
        $bookingList = $bookingList->where('approval_flag', 0)->get(); 
        if(count($bookingList) > 0) {
            $userBasedBooking = (JWTAuth::user()->user_type_id == 2) ? $bookingList->first() : $bookingList->where('user_id', JWTAuth::user()->id)->first();
            if(!empty($userBasedBooking->toArray()) ) {
                $userBasedBookChatCount = UsersBasedLoadBookChat::where('user_id', JWTAuth::user()->id)->where('users_based_load_book_id', $userBasedBooking->id)->count();
                if(config('constants.chat_limit') >= $userBasedBookChatCount) {
                    $paramsData['user_id'] = JWTAuth::user()->id;
                    $paramsData['users_based_load_book_id'] = $userBasedBooking->id;
                    $paramsData['amount'] = $params['amount'];
                    $paramsData['chat_count'] = $userBasedBookChatCount+1;
                    $paramsData['created_at'] = Carbon::now()->format('Y-m-d H:m:s');
                    $paramsData['updated_at'] = Carbon::now()->format('Y-m-d H:m:s');
                    UsersBasedLoadBookChat::insert($paramsData);
                    $response = UsersBasedLoadBookChat::with(['user'])->where('users_based_load_book_id',$userBasedBooking->id)->orderBy('id', 'DESC')->get();
                    $title = 'Ebuyker - Chat Amount Conversation';

                    $chatBody = (JWTAuth::user()->user_type_id == 3 || JWTAuth::user()->user_type_id == 4) ? JWTAuth::user()->name.' asked to negotiation for this amount:'.$params['amount'] : 'Customer Can Approach this amount:'.$params['amount'] ;

                    $chatUserId = (JWTAuth::user()->user_type_id == 3 || JWTAuth::user()->user_type_id == 4) ?$userBasedBooking->booking->loads->user_id : $userBasedBooking->user_id ;

                    $userDeviceDetails = UserDevices::where('user_id', $chatUserId)->where('active_flag',1)->first();

                    if(!empty($userDeviceDetails)) {
                        pushToMobile($userDeviceDetails, $title, $chatBody);
                    }
                    
                    return $this->sendSuccess($response, $chatBody);
                }
                return $this->validationError("Oops! Booking Chat Limit count is crossed.");
            }
            return $this->validationError("Oops! This user hasn't booked this Load.");
    
        } else {
            return $this->validationError('Oops! Load does not exist or Already Booked.');
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ApproveLoad(Request $request)
    {
        $params = $this->getRequest($request);
        $validator = Validator::make($params, [
            'booking_id' => 'required|numeric',
            'amount' => 'required|numeric|between:0,999999999.99'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            $errors = objectToSingle($validator->errors());
            return $this->validationError($errors);
        }
        
        $bookingList = UsersBasedLoadBook::with(['booking.loads'])->where('booking_id', $params['booking_id'])->where('user_id', JWTAuth::user()->id)->where('approval_flag', 0)->first();
        if(!empty($bookingList)) {
            $bookingList->update(['approval_flag'=> 1]);

            /*$result = Booking::with(['loads'])->where('approval_flag', 0)->find($params['booking_id']);
            $resultUpdate = ['approval_flag'=> 1, 'booking_amount' => $params['amount'], 'user_id' => JWTAuth::user()->id];
            $result->update($resultUpdate);

            //Load Book
            $resultLoad = Loads::where('approval_flag', 0)->find($result['load_id']);
            $resultLoadUpdate = ['approval_flag'=> 1];
            $resultLoad->update($resultLoadUpdate); */
           
            $title = 'Ebuyker - Chat Amount Conversation';
            $chatBody = JWTAuth::user()->name.' was Approved for this amount:'.$params['amount'];
            if(isset( $bookingList->booking->loads->user_id)) {
                Log::info('working');
                $userDeviceDetails = UserDevices::where('user_id', $bookingList->booking->loads->user_id)->where('active_flag',1)->first();
                if(!empty($userDeviceDetails)) {
                    pushToMobile($userDeviceDetails, $title, $chatBody);
                }
            }
            

            return $this->sendSuccess($bookingList, 'Booked Successfully');
        } else {
            return $this->validationError('Oops! Load does not exist or Already Booked.');
        }
    }


     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function CustomerApproveLoad(Request $request)
    {
        $params = $this->getRequest($request);
        $validator = Validator::make($params, [
            'user_booking_id' => 'required|numeric'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            $errors = objectToSingle($validator->errors());
            return $this->validationError($errors);
        }
        
        $bookingList = UsersBasedLoadBook::where('id', $params['user_booking_id'])->where('approval_flag', 1)->first();
        if(!empty($bookingList)) {
            $getAmountData = UsersBasedLoadBookChat::where('users_based_load_book_id', $bookingList->id)->orderby('id','desc')->first();
            $amount = $getAmountData->amount;
            $result = Booking::with(['loads'])->where('approval_flag', 0)->find($bookingList->booking_id);
            $resultUpdate = ['approval_flag'=> 1, 'booking_amount' =>$amount, 'user_id' => JWTAuth::user()->id];
            $result->update($resultUpdate);

            //Load Book
            $resultLoad = Loads::where('approval_flag', 0)->find($result['load_id']);
            $resultLoadUpdate = ['approval_flag'=> 1];
            $resultLoad->update($resultLoadUpdate);
           
            $title = 'Ebuyker - Chat Amount Conversation';
            $chatBody = JWTAuth::user()->name.' was Approved for this amount:'.$amount;
           
            $userDeviceDetails = UserDevices::where('user_id', $bookingList->user_id)->where('active_flag',1)->first();
            if(!empty($userDeviceDetails)) {
                pushToMobile($userDeviceDetails, $title, $chatBody);
            }

            return $this->sendSuccess($result, 'Booked Successfully');
        } else {
            return $this->validationError('Oops! Load does not exist or Already Booked.');
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
        $result = Booking::with(['loads'])->where('approval_flag', 0)->find($id);
        if(!empty($result)) {
            $userBasedBookChatCount = UsersBasedLoadBookChat::with(['userBasedChat'])
            ->whereHas('userBasedChat', function($q) use($id) {
                $q->where('booking_id', $id)
                    ->where('user_id', JWTAuth::user()->id);
            })->where('user_id', JWTAuth::user()->id)
            ->count();

            $data = UsersBasedLoadBookChat::with(['user','userBasedChat'])->whereHas('userBasedChat', function($q) use($id) {
                $q->where('booking_id', $id)
                    ->where('user_id', JWTAuth::user()->id)
                    ->orderBy('id', 'desc');
            })->orderBy('id', 'DESC')->get();
            return $this->sendSuccess($data, 'OK', 200, $userBasedBookChatCount);
        } else {
            return $this->validationError('Oops! This Load already Booked.');
        }
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
       //
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
