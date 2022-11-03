<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Notifications\ResetPasswordRequest;



// gui mail cho customer 
use Mail;
use App\Mail\NotifyMail;
use App\Mail\NotifyMailCustomer;
use Exception;



class ResetPasswordController extends Controller
{
    /**
     * Create token password reset.
     *
     * @param  ResetPasswordRequest $request
     * @return JsonResponse
     */

    // Admin Forgot Password by Email
    public function sendMail(Request $request,User $user)
    {
        $user = User::where('email', $request->email)->first();
        if($user){
            $passwordReset = PasswordReset::updateOrCreate([
                'email' => $user->email,
            ], [
                'token' => Str::random(60),
            ]);
            // $token = Str::random(60);
            // $passwordReset = PasswordReset::where('email',$user->email)->first();
            // if($passwordReset) $passwordReset->update(['token' => $token]);
            // else $passwordReset = PasswordReset::create(['email' => $user->email,'token' => $token]);

            if ($passwordReset) {
                $user->notify(new ResetPasswordRequest($passwordReset->token));
            }
            return response()->json([
                'message' => 'We have e-mailed your password reset link!'
            ]);
        }
        return response()->json(['message' => 'Email does not match any account !']);

    }

    public function reset(Request $request, $token)
    {
        $passwordReset = PasswordReset::where('token', $token)->firstOrFail();
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();

            return response()->json([
                'message' => 'This password reset token is invalid.',
            ], 422);
        }
        $user = User::where('email', $passwordReset->email)->firstOrFail();
        $updatePasswordUser = $user->update(['password' => bcrypt($request->password)]);
        $passwordReset->delete();

        return response()->json([
            'success' => $updatePasswordUser,
            "message" => "Đổi mật khẩu thành công !"
        ]);
    }


    // Customer Forgot Password by Email

    public function sendmailCustomer($token,$email) {
        Mail::to($email)->send(new NotifyMailCustomer($token));
        if (Mail::failures()) return response()->json(["message"=>"Sorry ! Please try again latter"],400);
        else return response()->json(["message"=>"Great! Successfully send in your mail"],200);
    } 

    public function sendMail2(Request $request,Customer $customer)
    {
        $customer = Customer::where('email', $request->email)->firstOrFail();
        $passwordReset = PasswordReset::updateOrCreate([
            'email' => $customer->email,
        ], [
            'token' => Str::random(60),
        ]);
        if ($passwordReset) {
            ResetPasswordController::sendmailCustomer($passwordReset->token,$passwordReset->email);
        }
        return response()->json([
        'message' => 'We have e-mailed your password reset link!'
        ]);
    }

    public function reset2(Request $request, $token)
    {
        $passwordReset = PasswordReset::where('token', $token)->firstOrFail();
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();

            return response()->json([
                'message' => 'This password reset token is invalid.',
            ], 422);
        }
        $user = Customer::where('email', $passwordReset->email)->firstOrFail();
        $updatePasswordUser = $user->update(['password' => bcrypt($request->password)]);
        $passwordReset->delete();

        return response()->json([
            'success' => $updatePasswordUser,
            "message" => "Đổi mật khẩu thành công !"
        ]);
    }
}
