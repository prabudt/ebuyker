<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserType;
use App\Models\Booking;


use Carbon\Carbon;

class CustomerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $result = User::with(['userType']);
        $params = $request->all();

        if(isset($request->mobile_no) && !empty($request->mobile_no)) {
            $result->where('mobile_no', 'like', '%' .  $request->mobile_no . '%');
        }

        if(isset($request->date_range) && !empty($request->date_range)) {
            $fromTODate = explode(' - ', $request->date_range);
            $fromDate = isset($fromTODate[0]) ? Carbon::parse($fromTODate[0])->format('Y-m-d') : Carbon::now()->format('Y-m-d');
            $toDate = isset($fromTODate[1]) ?  Carbon::parse($fromTODate[1])->format('Y-m-d') : Carbon::now()->format('Y-m-d');
            $result->whereBetween('created_at', [$fromDate, $toDate]);
        }

        $result = $result->where('is_admin', 0)->where('user_type_id', 2)->where('approval_flag',  1)->get();
        return view('customer.list', compact('result', 'params'));
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
        $userList = User::find($params['user_id']); 

        if(!empty($userList)) {
            $paramdata['approval_date'] = Carbon::now();
            $paramdata['approval_flag'] = $params['status'];
            $userList->update($paramdata);
            if($params['status'] == 1) {
                $message = 'Approved successfully.';
                return redirect()->back()->with('message-success', $message);
            } else {
                $message = 'Off-boarded successfully.';
                return redirect()->back()->with('message-error', $message);
            }
            
        } else {
            return redirect()->back()->with('message-error', 'Oops! Failed.');
        }
        
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $params = $this->getRequest($request);
        $userList = User::find($id); 
        if(!empty($userList)) {
            return view('customer.show', compact('userList', 'params'));
        } else {
            return redirect()->back()->with('message-error', 'Oops! Failed.');
        }
        
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loadHistory(Request $request, $id)
    {
        
        $params = $this->getRequest($request);
        $params['id'] = $id;
        $result = Booking::with(['loads.vehicles', 'loads.vehicleType', 'users'])
                    ->whereHas('loads', function($q) use($id) {
                        $q->where('user_id', $id);
                    })
                    ->where('approval_flag', '1');

        if(isset($request->date_range) && !empty($request->date_range)) {
            $fromTODate = explode(' - ', $request->date_range);
            $fromDate = isset($fromTODate[0]) ? Carbon::parse($fromTODate[0])->format('Y-m-d') : Carbon::now()->format('Y-m-d');
            $toDate = isset($fromTODate[1]) ?  Carbon::parse($fromTODate[1])->format('Y-m-d') : Carbon::now()->format('Y-m-d');
            $result->whereBetween('created_at', [$fromDate, $toDate]);
        }

        $result = $result->get(); 
            
        if(!empty($result)) {
            return view('customer.load-history', compact('result', 'params'));
        } else {
            return redirect()->back()->with('message-error', 'Oops! Failed.');
        }
        
    }
}
