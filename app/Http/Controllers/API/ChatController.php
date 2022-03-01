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

        $data = UsersBasedLoadBook::with(['user','BookChatData'])->where('booking_id', $params['booking_id']);
                
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
            'booking_id' => 'required|numeric',
            'amount' => 'required|numeric|between:0,999999999.99'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            $errors = objectToSingle($validator->errors());
            return $this->validationError($errors);
        }
        $bookingList = UsersBasedLoadBook::where('booking_id', $params['booking_id'])->get(); 
        if(!empty($bookingList)) {
            $userBasedBooking = $bookingList->where('user_id', JWTAuth::user()->id)->first();
            if(!empty($userBasedBooking->toArray())) {
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
                    $chatBody = (JWTAuth::user()->user_type == 3 || JWTAuth::user()->user_type == 4) ? JWTAuth::user()->name.' asked negotiation for this amount:'.$params['amount'] : 'Customer Can Approach this amount:'.$params['amount'] ;
                    $userDeviceDetails = UserDevices::where('user_id', JWTAuth::user()->id)->where('active_flag',1)->first();

                    if(!empty($userDeviceDetails)) {
                        pushToMobile($userDeviceDetails, $title, $chatBody);
                    }
                    
                    return $this->sendSuccess($response, $chatBody);
                }
                return $this->validationError("Oops! Booking Chat Limit count is crossed.");
            }
            return $this->validationError("Oops! This user hasn't booked this Load.");
    
        } else {
            return $this->validationError('Oops! Booking does not happened. So Please booking it.');
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
        $data = UsersBasedLoadBookChat::with(['user','userBasedChat'])->whereHas('userBasedChat', function($q) use($id) {
            $q->where('booking_id', $id);
        })->orderBy('id', 'DESC')->get();
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
