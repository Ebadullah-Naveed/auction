<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use Auth;
use App\Models\{User};
use App\Traits\EmailTrait;
use App\Mail\OtpMail;
use JWTAuth;

class AuthController extends Controller
{
    use EmailTrait;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','verifyOtp','activeUser']]);
    }

    public function login(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return $this->response(false,null,$validator->messages()->all(),300);
            }

            $credentials = $request->only('email', 'password');

            $token = JWTAuth::attempt($credentials);
            if (!$token) {
                return $this->response(false,null,'Unauthorized',300);
            }
            if(auth()->user()->status == 0)
            {
                return $this->response(false,null,'Your account is blocked by admin. Please contact site admin',422);
            }

            if(auth()->user()->is_email_verified == 0 )
            {
                auth()->user()->update(['otp'=>rand(1000,9999)]);
                $this->sendEmail(auth()->user()->email,new OtpMail(auth()->user()->otp));
                return $this->response(true,null,'Otp generated successfully',200);
            }
            auth()->user()->update(['last_login'=>date('Y-m-d h:i:s'),'last_login_ip'=>$this->get_client_ip()]);
            return $this->response(true,['token' => $token],'Login successfully',200);
        }
        catch(\Exception $e)
        {
            return $this->response(false,null,$e->getMessage(),500);
        }
    }

    public function register(Request $request){
        try
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|between:2,100',
                'email' => 'required|string|email|max:100|unique:users',
                'username' => 'required|string|max:100|unique:users',
                'password' => 'required|string|confirmed|min:6',
                'phone_number' => 'required|string|max:100|unique:users',
                'language' => 'required',
                'fcm_token' => 'sometimes',
                'cnic' => 'required|unique:users',
                'cnic_front_image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
                'cnic_back_image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
                'image' => 'sometimes|image|mimes:jpg,png,jpeg|max:2048',
            ]);
            if($validator->fails()){
                return $this->response(false,null,$validator->messages()->all(),300);
            }
            $password = Hash::make($request->password);
            $role_id = 3;
            if($request->hasFile('cnic_front_image'))
            {
                $cnic_front_image = $this->imageUpload($request->cnic_front_image,'cnic');
            }
            if($request->hasFile('cnic_back_image'))
            {
                $cnic_back_image = $this->imageUpload($request->cnic_back_image,'cnic');
            }
            if($request->hasFile('image'))
            {
                $profile_picture = $this->imageUpload($request->image,'profile_pictures');
            }
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $role_id,
                'password' => $password,
                'username' => $request->username,
                'phone_number' => $request->phone_number,
                'cnic' => $request->cnic,
                'cnic_front_image' => $cnic_front_image,
                'cnic_back_image' => $cnic_back_image,
                'language' => $request->language,
                'image' => $profile_picture ?? null,
                'fcm_token' => $request->fcm_token
            ]);

            if(!$user)
            {
                return $this->response(false,null,'User registration failed',300);
            }
            if(env('APP_ENV') == 'stage')
            {
                User::where('id',$user->id)->update(['status'=>1]);
            }
            return $this->response(true,null,'Your account is under review.',200);
        }
        catch(\Exception $e)
        {
            return $this->response(false,null,$e->getMessage(),500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->response(false,null,$validator->messages()->all(),300);
        }
        $user = User::where('email',$request->email)->first();
        if($user->otp != $request->otp)
        {
            return $this->response(false,null,"Incorrect OTP",422);
        }

        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token)->toUser();
        if(!$token)
        {
            return $this->response(false,null,"Failed",422);
        }
        auth()->user()->update(['otp'=>null,'is_email_verified' => 1,'last_login'=>date('Y-m-d h:i:s'),'last_login_ip'=>$this->get_client_ip()]);

        return $this->response(true,['token' => $token],'OTP verified successfully',200);
    }

    public function me()
    {
        return $this->response(true,Auth::user(),'User fetch successfully',200);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function activeUser($user)
    {
        User::where('id',$user)->update(['status'=>1]);
    }
}
