<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use Auth;
use App\Models\{User};

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function login(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return $this->response(false,null,$validator->messages()->all(),300);
            }

            $credentials = $request->only('email', 'password');

            $token = Auth::attempt($credentials);
            if (!$token) {
                return $this->response(false,null,'Unauthorized',300);
            }

            return $this->response(true,$token,'Login successfully',200);
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
                'full_name' => 'required|string|between:2,100',
                'email' => 'required|string|email|max:100|unique:users',
                'user_name' => 'required|string|max:100|unique:users',
                'password' => 'required|string|confirmed|min:6',
                'mobile_number' => 'required|string|max:100|unique:users',
                'perferred_language' => 'required',
            ]);

            if($validator->fails()){
                return $this->response(false,null,$validator->messages()->all(),300);
            }
            // dd($validator->validated());
            $request['password'] = Hash::make($request->password);
            $user = User::create($request->all());

            if(!$user)
            {
                return $this->response(false,null,'User registration failed',300);
            }

            $token = Auth::login($user);

            if (!$token) {
                return $this->response(false,null,'Unauthorized',300);
            }

            return $this->response(true,$token,'User registered successfully',200);
        }
        catch(\Exception $e)
        {
            return $this->response(false,null,$e->getMessage(),500);
        }
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

}
