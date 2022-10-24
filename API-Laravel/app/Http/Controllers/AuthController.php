<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use SebastianBergmann\Environment\Console;
use Exception;
use Hash;
use Mail;        
use Illuminate\Support\Facades\DB;
use App\Mail\SendPassword;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function __construct() {
        $this->middleware('auth:admin_api', ['except' => ['login', 'register','getfile','realTime']]);
    }

    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);
        $user = User::where('email',$request->email)->first();
        if (!$token = auth()->guard('admin_api')->attempt($credentials)) {
            return response()->json(['error' => 'Either email or password is wrong. !'], 401);
        }

        return response()->json([
            'user' => $user,
            'message'=>$this->respondWithToken($token)
        ]);
    }

    public function register(Request $request){

        if($request->rolelogin == 'super admin'){
            $password = Str::random(8);
            $validator = Validator::make($request->all(), [
                'fullname' => 'required|string|between:2,100',
                'email' => 'required|string|email|max:100|unique:users',
                // 'password' => 'required|string|min:6',
                'role' => 'required|in:admin,super admin',
            ]);
    
            if($validator->fails()){
                return response()->json($validator->errors(), 400);
            }
    
            $user = User::create(array_merge(
                $validator->validated(),
                // ['password' => bcrypt($request->password)]
                ['password' => bcrypt($password)]
            ));

            Mail::to($request->email)->send(new SendPassword($password)); 
            // Mail không tồn tại nó cũng không báo lỗi , chỉ là nó không gửi password đến thôi 
            // 2 dòng dưới này là bắt sự kiện gửi mail được hay không . 
            // if (Mail::failures()) return response()->json(["message"=>"Sorry! Please try again latter"],400);
            // else return response()->json(["message"=>"Great! Successfully send in your mail"],200);
            // Mail không tồn tại mà không báo lỗi thì càng tốt . Một mặt khi test ta có thể đăng kí thoải mái 
            // Mặt khác email đó không tồn tại => tất nhiên ta sẽ không có password để đăng nhập 
            // về mặt thực tế nếu Nhân viên cung cấp email sai thì cũng không có tài khoản để sử dụng . 
            
            // Trường hợp forgot password cũng tương tự . 
            // Chỉ khi email không tồn tại trong hệ thống mới báo lỗi còn email có trong hệ thống nhưng trên thực tế 
            // không tồn tại thì cũng không báo lỗi khi gửi token đi để xác thực mật khẩu . 

            return response()->json([
                'message' => 'User successfully registered',
                'user' => $user
            ], 201);
        }
        return response()->json([
            'message' => 'User failse registered !',
        ],400);
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
                'date_of_birth' => 'date',
                'gender' => 'in:1,0',
            ]);
    
            if($validator->fails()){
                 return response()->json($validator->errors(), 400);
            }
    
            $u = User::where('id','!=',$request->id)->where('email',$request->email)->first();
            if($u){
                return response()->json([
                    'email' => ['Email already exists'],
                ],401);
            }

            $u2 = User::where('id','!=',$request->id)->where('username',$request->username)->first();
            if($u2){
                return response()->json([
                    'username' => ['Username already exists'],
                ],401);
            }

            $user =User::find($request->id);
            $user->update($request->all());
            // $user->fullname = $request['fullname'];
            // $user->email = $request['email'];
            // $user->username = $request['username'];
            // $user->address = $request['address'];
            // $user->phone = $request['phone'];
            // $user->age = $request['age'];
            // $user->save();
    
            return response()->json([
                'message' => 'User successfully updated',
                'user' => $user
            ], 201);
        }
        catch(Exception $e) {
            return response()->json([
                'message' => 'Username or Email already exists',
            ],201);
        }
    }

    // Informaintion Admin Login  
    public function me()
    {
        return response()->json(auth('admin_api')->user());
    }

    // Information Admins 
    // super admin Lấy ra tất cả admin
    // update lại thành với mỗi page thì lấy ra 5 đứa admin   
    public function allAdmins(Request $request) {
        // $n = count(User::all())-1; // trừ đi đứa đang đăng nhập 
        if($request->rolelogin == 'super admin'){
            $search = $request->search;

            // $users = 
            // DB::table("users")->where(
            //     DB::raw("
            //         (fullname like '%$search%' or role like '%$search%' or date_of_birth like '%$search%' or username like '%$search%'
            //         or email like '%$search%' or phone like '%$search%' or gender like '%$search%') 
            //         and users.id != 1
            // "))->paginate(5);
            $userId = $request->idlogin; 

            $users = User::where(function($query) use($search) {
                $query->where('date_of_birth','LIKE', '%'.$search.'%')
                ->orWhere('phone','LIKE', '%'.$search.'%')
                ->orWhere('address','LIKE', '%'.$search.'%')
                ->orWhere('role','LIKE', '%'.$search.'%')
                ->orWhere('gender','LIKE', '%'.$search.'%')
                ->orWhere('username','LIKE', '%'.$search.'%')
                ->orWhere('email','LIKE', '%'.$search.'%')
                ->orWhere('fullname','LIKE', '%'.$search.'%');
            })->where('id','!=', $userId)->paginate(5);
            // ->orWhere('role','LIKE', '%'.$search.'%')
            // ->orWhere('date_of_birth','LIKE', '%'.$search.'%')
            // ->orWhere('phone','LIKE', '%'.$search.'%')
            // ->orWhere('gender','LIKE', '%'.$search.'%')
            // ->orWhere('username','LIKE', '%'.$search.'%')
            // ->orWhere('email','LIKE', '%'.$search.'%')
            
            // ->get();
            // ->paginate(5);
            
            $users2 = User::where(function($query) use($search) {
                $query->where('date_of_birth','LIKE', '%'.$search.'%')
                ->orWhere('phone','LIKE', '%'.$search.'%')
                ->orWhere('address','LIKE', '%'.$search.'%')
                ->orWhere('role','LIKE', '%'.$search.'%')
                ->orWhere('gender','LIKE', '%'.$search.'%')
                ->orWhere('username','LIKE', '%'.$search.'%')
                ->orWhere('email','LIKE', '%'.$search.'%')
                ->orWhere('fullname','LIKE', '%'.$search.'%');
            })->where('id','!=', $userId)->get();


            // Thêm whreNull vì một số tài khoản được super admin tạo nhưng chưa vào udpate information 
            if($search == 'female'){
                $users = User::where('id','!=', $userId)
                    ->where(function ($query) {
                        $query->where('gender','=','0')->orWhereNull('gender');
                    })->paginate(5);

                $users2 = User::where('id','!=', $userId)
                    ->where(function ($query) {
                        $query->where('gender','=','0')->orWhereNull('gender');
                    })->get();
            }
            if($search == 'male'){
                $users = User::where('id','!=', $userId)
                    ->where(function ($query) {
                        $query->where('gender','=','1')->orWhereNull('gender');
                    })->paginate(5);
                    
                $users2 = User::where('id','!=', $userId)
                    ->where(function ($query) {
                        $query->where('gender','=','1')->orWhereNull('gender');
                    })->get();
            }
            
            $n = count($users2); // lấy ra và search và trừ đi đứa đang đăng nhập 
            return response()->json([
                'quantity' => $n,
                'message' => 'Get all admins information successfully !',
                'user' => $users
            ], 201);
        }
        return response()->json([
            'message' => 'Get all admins fails !',
        ], 400);
    }

    // Delete one Admin or super Admin 
    public function deleteAdmin(Request $request,$id) {
        if($request->rolelogin == 'super admin'){
            $user = User::find($id);
            $user->delete();
            return response()->json([
                'message' => 'Delete admin successfully !',
            ], 201);
        }
    }

    // super admin chỉnh sửa role 
    public function editRole(Request $request) {
        if($request->rolelogin == 'super admin'){
            $user = User::find($request->id);
            $user->role = $request->role;
            $user->save();

            return response()->json([
                'message' => 'Edit Role Admin successfully !',
            ], 201);
        }
    }


    // Admin lấy ra tất cả user 
    public function allUsers(Request $request) {
        // gender và status kiểu bolean nên true false hoặc 1 0 nhưng khi lưu vào database thì chỉ có 1 0 
        // nên việc tìm kiếm sẽ khó khăn => làm cách này 
        $search = $request->search;

        $customers = Customer::where(function($query) use($search) {
            $query->where('date_of_birth','LIKE', '%'.$search.'%')
            ->orWhere('phone','LIKE', '%'.$search.'%')
            ->orWhere('address','LIKE', '%'.$search.'%')
            ->orWhere('status','LIKE', '%'.$search.'%')
            ->orWhere('gender','LIKE', '%'.$search.'%')
            ->orWhere('username','LIKE', '%'.$search.'%')
            ->orWhere('email','LIKE', '%'.$search.'%')
            ->orWhere('fullname','LIKE', '%'.$search.'%')
            ->orWhere('google_id','LIKE', '%'.$search.'%');
        })->paginate(5);

        $customers2 = Customer::where(function($query) use($search) {
            $query->where('date_of_birth','LIKE', '%'.$search.'%')
            ->orWhere('phone','LIKE', '%'.$search.'%')
            ->orWhere('address','LIKE', '%'.$search.'%')
            ->orWhere('status','LIKE', '%'.$search.'%')
            ->orWhere('gender','LIKE', '%'.$search.'%')
            ->orWhere('username','LIKE', '%'.$search.'%')
            ->orWhere('email','LIKE', '%'.$search.'%')
            ->orWhere('fullname','LIKE', '%'.$search.'%')
            ->orWhere('google_id','LIKE', '%'.$search.'%');
        })->get();

        // status 
        if($search == 'block'){
            $customers = Customer::where('status','=','0')->paginate(5);
            $customers2 = Customer::where('status','=','0')->get();
        }
        if($search == 'unblock'){
            $customers = Customer::where('status','=','1')->paginate(5);
            $customers2 = Customer::where('status','=','1')->get();
        }

        // gender
        // vì những tài khoản đăng nhập bằng google có gender null nên mình phải thêm TH null nữa 
        if($search == 'female'){
            $customers = Customer::where('gender','=','0')->orWhereNull('gender')->paginate(5);
            $customers2 = Customer::where('gender','=','0')->orWhereNull('gender')->get();
        }
        if($search == 'male'){
            $customers = Customer::where('gender','=','1')->orWhereNull('gender')->paginate(5);
            $customers2 = Customer::where('gender','=','1')->orWhereNull('gender')->get();
        }

        $n = count($customers2); 
        return response()->json([
            'quantity' => $n,
            'message' => 'Get all users information successfully !',
            'user' => $customers
        ], 201);
    }
    
    // Admin block hoặc unblock user 
    public function editStatus(Request $request) {
        $customer = Customer::find($request->id);
        $customer->status = $request->status;
        $customer->save();

        // $customers = Customer::all();
        return response()->json([
            'message' => 'Change Status User successfully !',
            // 'customers' => $customers,
        ], 201);
    }



    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('admin_api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('admin_api')->refresh());
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
            'expires_in' => auth()->guard('admin_api')->factory()->getTTL() * 60
        ]);
    }

    // change Password for Admin   
    public function changePassword(Request $request) {

        $user = User::find($request->id);
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

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        //Change Password
        $user->update(['password' => bcrypt($request->get('new_password'))]);
        return response()->json([
            'message' => "Password successfully changed !",
        ],200);
    }

    public function upfile(Request $request) {
        $pathToFile = $request->file('photo')->store('images','public'); // lưu ảnh mới vào 

        $user = User::find($request->id); // tìm và xóa ảnh cũ đi (nếu có)
        $filename = $user->url_img;
        if($filename) File::delete($filename); // xóa ảnh cũ đi (nếu có)(còn ai không có thì thôi) 
        $user->update(['url_img' => 'storage/'.$pathToFile]);
        return response()->json([
        //     "message"=>"Great! Successfully upload file !",
            "link"=> 'storage/'.$pathToFile
        ],200);
    } 
    
    
}