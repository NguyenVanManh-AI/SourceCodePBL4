<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use Exception;


// login Google , use thêm Auth để hỗ trợ đăng nhập không password 
use Illuminate\Support\Facades\Auth;

class CustomerAuthController extends Controller
{
    public function login(Request $request)
    {
        $status = Customer::where('email',$request->email)->first()->status;
        if($status == 0){
            return response()->json(['error' => 'Your account has been locked !'], 401);
        } // sau return là hết vì vậy nếu như không bị khóa thì tiếp tục thực hiện đoạn code tiếp theo  

        $credentials = request(['email', 'password']);
        $customer = Customer::where('email',$request->email)->first();
        if (!$token = auth()->guard('customer_api')->attempt($credentials)) {
            return response()->json(['error' => 'Either email or password is wrong. !'], 401);
        }

        return response()->json([
            'user' => $customer,
            'message'=>$this->respondWithToken($token)
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'username' => 'required|string|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'address' => 'required|string|min:1',
            'date_of_birth' => 'required|string|min:1',
            'phone' => 'required|min:9|numeric',
            'gender' => 'required|in:1,0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $customer = Customer::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password),'status'=> "1"]
        ));

        return response()->json([
            'message' => 'Customer successfully registered',
            'user' => $customer
        ], 201);
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
        
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGoogleCallback()
    {
        try {
      
            $user = Socialite::driver('google')->user();
       
            $finduser = Customer::where('google_id', $user->id)->first();
            $email = $user->email;

            if($finduser){
       
                Auth::login($finduser);
                $this->token = auth()->guard('customer_api')->login($finduser);

                // $r = response()->json([
                //     'user' => $finduser,
                //     'message'=>$this->respondWithToken($this->token)
                // ]);

                $r = $this->respondWithToken($this->token)->getData()->access_token;
                return view('oauth2',['__r'=>$r]);
       
            }else{
                $sysUser = Customer::where('email',$email)->first();
                if($sysUser){
                    $sysUser->update(['google_id' => $user->id]);
                    Auth::login($sysUser);
                    $this->token = auth()->guard('customer_api')->login($sysUser);

                    // $r = response()->json([
                    //     'user' => $finduser,
                    //     'message'=>$this->respondWithToken($this->token)
                    // ]);

                    $r = $this->respondWithToken($this->token)->getData()->access_token;
                    return view('oauth2',['__r'=>$r]);
                }
                else {
                    $newUser = Customer::create([
                        'fullname' => $user->name,
                        'email' => $user->email,
                        'google_id'=> $user->id,
                        'username'=> 'gg_'.$user->id,
                        'status'=>1
                        // 'password' => encrypt('pw_')
                    ]);

                    Auth::login($newUser);
                    $this->token = auth()->guard('customer_api')->login($newUser);

                    // $r = response()->json([
                    //     'user' => $finduser,
                    //     'message'=>$this->respondWithToken($this->token)
                    // ]);

                    $r = $this->respondWithToken($this->token)->getData()->access_token;
                    return view('oauth2',['__r'=>$r]);
                }
            }
      
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
        
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('customer_api')->user());
    }

    public function updateProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'fullname' => 'string|between:2,100',
                'email' => 'string|email|max:100',
                'username' => 'string|max:100',
                'address' => 'string|min:1',
                'phone' => 'min:9|numeric',
                'url_img' => 'string|min:0',
                'date_of_birth' => 'date',
                'gender' => 'in:1,0',
            ]);
    
            if($validator->fails()){
                 return response()->json($validator->errors(), 400);
            }
    
            $customer =Customer::find($request->id);
            $customer->update($request->all());

            return response()->json([
                'message' => 'User successfully updated',
                'user' => $customer
            ], 201);
        }
        catch(Exception $e) {
            return response()->json([
                'message' => 'Username or Email already exists',
            ],201);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('customer_api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('customer_api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('customer_api')->factory()->getTTL() * 60
        ]);
    }


}