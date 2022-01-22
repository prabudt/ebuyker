<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $users;

    public function __construct(User $users)
    {
        $this->middleware('auth');
        $this->users = $users;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $result = $this->users->getData();
        $countData = [
            "customer_count" => $result->where('user_type_id', 2)->where('approval_flag', 1)->count(),
            "driver_count" => $result->where('user_type_id', 3)->where('approval_flag', 1)->count(),
            "transporter_count" => $result->where('user_type_id', 4)->where('approval_flag', 1)->count(),
            "approval_count" => $result->where('is_admin', 0)->where('approval_flag', 0)->count()
        ];
        return view('users.list', compact('result', 'countData'));
    }
}
