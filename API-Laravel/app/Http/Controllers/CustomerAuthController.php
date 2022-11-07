<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use Exception;
use Hash;
use Illuminate\Support\Facades\File; // upfile 

// login Google , use thêm Auth để hỗ trợ đăng nhập không password 
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Claims\Custom;

class CustomerAuthController extends Controller
{
    public function login(Request $request)
    {
        
        $u = Customer::where('email',$request->email)->first();
        if(empty($u)){
            return response()->json(['error' => 'Email is incorrect !'], 401);
        }
        else {
            $status = $u->status;
            // if($status){ // phải có tồn tại email đó đã 
                if($status == 0){
                    return response()->json(['error' => 'Your account has been locked !'], 401);
                } // sau return là hết vì vậy nếu như không bị khóa thì tiếp tục thực hiện đoạn code tiếp theo  
            // }
        }


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
            'email' => 'required|string|email|max:100|unique:customers',
            'username' => 'required|string|max:100|unique:customers',
            'password' => 'required|string|confirmed|min:6',
            'address' => 'required|string|min:1',
            'date_of_birth' => 'required|string|min:1',
            'phone' => 'required|min:9|numeric',
            'gender' => 'required|in:1,0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
            // return response()->json($validator->errors()->toJson(), 400);
        }

        $customer = Customer::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password),'status'=> "1"]
        ));

        // đến đây chắc chắn là đã có biến $customer 
        // thêm tiếp địa chỉ customer vào cho bảng shipping address
        ShippingAddress::create([
            'customer_id' => $customer->id,
            'recipient_name' => $customer->fullname,
            'phone_number' => $customer->phone,
            'address' => $customer->address
        ]);
        // 

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
        
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100',
            'username' => 'required|string|min:6|max:100',
            'address' => 'required|string|min:1',
            'phone' => 'required|min:9|numeric',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:1,0',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $acc = Customer::find($request->id);
        $emailAc = $acc->email;
        $usernameAc = $acc->username;

        if($request->email != $emailAc){
            if(Customer::where('emai',$request->email)){
                return response()->json([
                    'email' => ['Email already exists !'],
                ], 400);
            }
        }
        // vì dưới client khi lỗi error => trả về mảng của mảng các lỗi mình code lượt qua tất cả các phần tử nên phải trả về một mảng 
        // còn nếu trả về 'email' => 'Email already exists !' thì nó sẽ bị cắt ra thành nhiều chữ => lỗi 

        // thứ 2 là phải return về email hoặc username thay vì message vì dưới client cũng code vậy 

        if($request->username != $usernameAc){
            if(Customer::where('username',$request->username)){
                return response()->json([
                    'username' => ['User already exists !'],
                ], 400);
            }
        }

        // CHÚ Ý PHÁI TRẢ VỀ CODE 400 thay vì 401 . 401 là lỗi token về đăng nhập các kiểu 
        // code 400 là lỗi về người dùng nhập sai hoặc đến địa chỉ không tồn tại (page not found ,...)
        // với lại dưới client mình cũng bắt cho nó nếu gặp lỗi 401 tức là token hết hạn nên cho nó đăng xuất 
        // để đăng nhập lại => nên tuyệt đối tất cả các lỗi không liên quan đến hạn token thì PHẢI TRẢ VỀ 400 

        $customer =Customer::find($request->id);
        $customer->update($request->all());

        // tạo thêm shipping address cho google với lần cập nhật thông tin đầu tiên của họ 
        // đến đây chắc chắn là đã có biến $customer 
        // thêm tiếp địa chỉ customer vào cho bảng shipping address
        $ship = ShippingAddress::Where('customer_id',$customer->id)->first(); // phải là first() chứ không là get();
        // nếu không có tức là đây là toàn khoản google chưa có địa chỉ đang cập nhật thông tin cho mình thì tạo luôn cho nó . 
        if(empty($ship)) { // có rồi tức là tài khoản google này trước đó đã cập nhật thông tin rồi nên thôi 
            ShippingAddress::create([
                'customer_id' => $customer->id,
                'recipient_name' => $customer->fullname,
                'phone_number' => $customer->phone,
                'address' => $customer->address
            ]);
        }

        // shipping address phải luôn đầy đủ , chỉ là có hoặc không thôi nếu không có chắc chắn là đăng nhập bằng google .
        // nếu có rồi thì thôi , bỏ qua bước này  

        return response()->json([
            'message' => 'User successfully updated',
            'user' => $customer
        ], 201);

        // update thì nếu làm như register thì update giống với email hay username hiện tại thì nó cũng 
        // báo lỗi nên phải code tay , khả năng là có cách khác code ngắn hơn , tìm hiểu sau 
    }

    public function upfile(Request $request) {
        $pathToFile = $request->file('photo')->store('avatarcustomer','public'); // lưu vào folder avatarcustomer

        $customer = Customer::find($request->id); // tìm và xóa ảnh cũ đi (nếu có)
        $filename = $customer->url_img;
        if($filename) File::delete($filename); // xóa ảnh cũ đi (nếu có)(còn ai không có thì thôi) 
        $customer->update(['url_img' => 'storage/'.$pathToFile]);
        return response()->json([
            "link"=> 'storage/'.$pathToFile
        ],200);
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


    // Kiểm tra xem tài khoản này có mật khẩu hay không (tài khoản google không có mật khẩu cũ)
    // cái này phục vụ cho việc đổi mật khẩu 
    public function statusPassword(Request $request) {
        $customer = Customer::find($request->id);
        if(empty($customer->password)) return response()->json(['status' => false,],201);
        else return response()->json(['status' => true,],201);
    }

    
    // change Password for Customer  
    public function changePassword(Request $request) {

        $user = Customer::find($request->id);
        // mật khẩu trong database và mật khẩu nhập vào phải giống nhau 
        if (!(Hash::check($request->get('current_password'), $user->password))) {
            return response()->json([
                'message' => 'Your current password does not matches with the password.',
            ],400);
        }

        if(strcmp($request->get('current_password'), $request->get('new_password')) == 0){
            return response()->json([
                'message' => 'New Password cannot be same as your current password. ',
                // mật khẩu mới không được giống với mật khẩu hiện tại (mật khẩu cũ)
            ],400);
        }

        // mật khẩu mới và confirm phải giống nhau 
        if($request->get('new_password') != $request->get('new_password_confirmation')){
            return response()->json([
                'message' => 'Your new password does not matches with the new password confirm.',
            ],400);
        }

        if(strlen($request->get('new_password')) <= 6){
            return response()->json([
                'message' => 'Your new password invalid ! Password minimum 6 characters ',
            ],400);
        }

        //Change Password
        $user->update(['password' => bcrypt($request->get('new_password'))]);
        return response()->json([
            'message' => "Password successfully changed !",
        ],200);
    }

    // Tạo mới mật khẩu cho tài khoản không có mật khẩu 
    public function createPassword(Request $request) {

        $user = Customer::find($request->id);

        // mật khẩu mới và confirm phải giống nhau 
        if($request->get('new_password') != $request->get('new_password_confirmation')){
            return response()->json([
                'message' => 'Your new password does not matches with the new password confirm.',
            ],400);
        }

        if(strlen($request->get('new_password')) <= 6){
            return response()->json([
                'message' => 'Your new password invalid ! Password minimum 6 characters ',
            ],400);
        }

        //Change Password
        $user->update(['password' => bcrypt($request->get('new_password'))]);
        return response()->json([
            'message' => "Password successfully changed ! ",
        ],200);
    }

}