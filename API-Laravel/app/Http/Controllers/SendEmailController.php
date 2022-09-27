<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
 
use Mail;
use App\Mail\NotifyMail;
 
 
class SendEmailController extends Controller
{
     
    public function index(Request $request)
    {
 
     // Gửi bên Auth -> senmail 
     //  Mail::to($request->email)->send(new NotifyMail(111));
 
     //  if (Mail::failures()) {
     //       return response()->Fail('Sorry! Please try again latter');
     //  }else{
     //       return response()->success('Great! Successfully send in your mail');
     //     }
    } 
}