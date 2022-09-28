<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function __construct() {
        $this->middleware('auth:admin_api', ['except' => ['login', 'register','sendcode','verycode','upfile','loginGoogle','getfile',
        'testGet','testPost','realTime']]);
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

        if($request->rolelogin == 'supper admin'){
            $validator = Validator::make($request->all(), [
                'fullname' => 'required|string|between:2,100',
                'email' => 'required|string|email|max:100|unique:users',
                'password' => 'required|string|min:6',
                'role' => 'required|in:admin,supper admin',
            ]);
    
            if($validator->fails()){
                return response()->json($validator->errors(), 400);
            }
    
            $user = User::create(array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)]
            ));
    
            return response()->json([
                'message' => 'User successfully registered',
                'user' => $user
            ], 201);
        }
        return response()->json([
            'message' => 'User failse registered !',
        ],400);
    }

    public function changePassword()
    {
        print_r('doi mat khau');
        // return response()->json(auth('admin_api')->user());
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

    // Delete one Admin or Supper Admin 
    public function deleteAdmin(Request $request) {
        if($request->rolelogin == 'supper admin'){
            $user = User::find($request->id);
            $user->delete();
            return response()->json([
                'message' => 'Delete admin successfully',
            ], 201);
        }
    }

    // Information Admins 
    public function allAdmins(Request $request) {
        if($request->rolelogin == 'supper admin'){
            $users = User::where('id','!=',$request->idlogin)->get(); // lấy tất cả ngoại trừ cái đứa đăng nhập 
            return response()->json([
                'message' => 'Get all admins information successfully !',
                'user' => $users
            ], 201);
        }
        return response()->json([
            'message' => 'Get all admins fails !',
        ], 400);
    }

    public function editRole(Request $request) {
        if($request->rolelogin == 'supper admin'){
            $user = User::find($request->id);
            $user->update('role',$request->role);
            return response()->json([
                'message' => 'Delete admin successfully',
            ], 201);
        }
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

    
}