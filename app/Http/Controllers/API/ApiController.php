<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Mail;
use Auth;

class ApiController extends Controller
{
    public function register(Request $request)
    {
    	//Validate data
        $params = $this->getRequest($request);
        
        $validator = Validator::make($params, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'mobile_no' => 'required|min:11|numeric|unique:users,mobile_no,null,null',
            'password' => 'required|confirmed|string|min:6|max:50',
            'user_type' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            $errors = objectToSingle($validator->errors());
            return $this->validationError($errors);
        }

        //Request is valid, create new user
        $storeData['name'] = $params['name'];
        $storeData['email'] = $params['email'];
        $storeData['mobile_no'] = $params['mobile_no'];
        $storeData['password'] = bcrypt($request['password']);
        $storeData['user_type_id'] = $params['user_type'];

        $response = User::create($storeData);
        $message = 'Account created successfully, Kindly wait for that, So admin can approve after that you can login it.';

        return $this->sendSuccess($response, $message);
    }
 
    public function authenticate(Request $request)
    {
        $params = $this->getRequest($request);

        //valid credential
        $validator = Validator::make($params, [
            'mobile_no' => 'required|digits:10',
            // 'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            $errors = objectToSingle($validator->errors());
            return $this->validationError($errors);
        }
        
        $users = User::where('mobile_no', $params['mobile_no'])->first();
        
        $errorMessage = '';
        if(!empty($users)) {
            if($users->active_flag == 0) {
                $errorMessage = 'Oops! Your login account was Inactive';
            } else if($users->approval_flag == 0) {
                $errorMessage = 'Oops! Your login account was not approved, Please contact Your Administrator';
            }
        } else {
            $errorMessage = 'Oops! Your login credentials are invalid.';
        }
        
        if(!empty($errorMessage)) {
            return $this->validationError($errorMessage);
        } else {
            //genertaing otp and check valid user or not
            $isOtpEnable = config('constants.is_otp_enable');
                        
            if($isOtpEnable) {
                $getOtp = generateOTPNumber();
                $msgTxt = "Dear ".$users['name'].", Your OTP is: ".$getOtp.". to login";
                $response = app('smsServices')->sendSms($users['mobile_no'], $msgTxt);
                $users->update(['otp'=>$getOtp]);
                $message = 'OTP Generated successfully';
                return $this->sendSuccess($users, $message);
            } else {
                $message = 'Logged in successfully';
                $token = $this->generateToken($users);
                return $this->sendSuccessWithToken($users, $message, $token);
            }
            
        }
        //Request is validated
        //Crean token        
    }
 
    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            $errors = objectToSingle($validator->errors());
            return $this->validationError($errors);
        }

		//Request is validated, do logout        
        try {
            $logout = JWTAuth::invalidate($request->token);
            $message = 'User has been logged out';
            return $this->sendSuccess($message, $message);   

        } catch (JWTException $exception) {          
            $errors = 'Sorry, user cannot be logged out';
            return $this->validationError($errors);
        }
    }
 
    public function get_user(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
 
        $user = JWTAuth::authenticate($request->token);
 
        return response()->json(['user' => $user]);
    }

    public function submitLoginOTP(Request $request)
    {
        $params = $this->getRequest($request);        
        //valid credential
        $validator = Validator::make($params, [
            'mobile_no' => 'required|digits:10',
            'otp' => 'required|digits:6'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            $errors = objectToSingle($validator->errors());
            return $this->validationError($errors);
        } else {
            $users = User::where('mobile_no', $params['mobile_no'])->first();
            if(!empty($users) ) {

                if(!empty($users->otp) && $params['otp'] == $users->otp) {
                    $token = $this->generateToken($users);
                } else {
                    $message = 'Your OTP is invalid';
                    return $this->validationError($message);
                }
               
            } else {
                $message = 'Mobile Number is invalid';
                return $this->validationError($message);
            }
        }
        $message = 'Logged in successfully';
        return $this->sendSuccessWithToken($users, $message, $token);
    }

    public function changePassword(Request $request)
    {
        $params = $this->getRequest($request);        
        //valid credential
        $validator = Validator::make($params, [
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);
        if ($validator->fails()) {
            $errors = objectToSingle($validator->errors());
            return $this->validationError($errors);
        } else {

            try {
                if ((Hash::check($params['old_password'], Auth::user()->password)) == false) {
                    $message = "Check your old password.";
                } else if ((Hash::check($params['new_password'], Auth::user()->password)) == true) {
                    $message = "Same password does not accept";                   
                } else {
                    $users = User::find(Auth::user()->id);
                    $users->update(['password' => Hash::make($params['new_password'])]);
                    $message = "Password updated successfully.";  
                    return $this->sendSuccessWithToken($users, $message);
                }
            } catch (\Exception $ex) {
                if (isset($ex->errorInfo[2])) {
                    $message = $ex->errorInfo[2];
                } else {
                    $message = $ex->getMessage();
                }
            }
            return $this->validationError($message);
        }

    }

    public function approveUser(Request $request)
    {
        $params = $this->getRequest($request);        
        //valid credential
        $validator = Validator::make($params, [
            'mobile_no' => 'required|digits:10',
            'approve_flag' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = objectToSingle($validator->errors());
            return $this->validationError($errors);
        } else {
            $users = User::where('mobile_no', $params['mobile_no'])->first();
            if(!empty($users)) {
                $users->update(['approval_flag' => $params['approve_flag']]);
                $message = "Approved successfully.";  
                return $this->sendSuccess($users, $message);
            } else {
                $message = 'Oops! Your login credentials are invalid.';
            } 
            return $this->validationError($message);
        }

    }

    public function generateToken($user) {
        try {
            if (!$token=JWTAuth::fromUser($user)) {
                $errorMessage = 'Token is expired';
                return $this->validationError($errorMessage, 409);
            }
        } catch (JWTException $exception) {
            $errorMessage = 'Sorry, user cannot be logged out';
            return $this->validationError($errorMessage, 409);
        }
        $user->update(['otp'=>NULL]);
 		return $token;
    }
}