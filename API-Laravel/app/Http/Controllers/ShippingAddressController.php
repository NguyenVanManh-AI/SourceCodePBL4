<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Validator;

class ShippingAddressController extends Controller
{
    //
    public function updateOrCreateAddress(Request $request){

        // Đầu tiên là phải kiểm tra xem những cái client gửi lên đã đúng chưa 
        $validator = Validator::make($request->all(), [
            'recipient_name' => 'required|string|between:2,100',
            'phone_number' => 'required|min:9|numeric',
            'address' => 'required|string|min:1',
        ]);

        // Nếu chưa thì báo lỗi 
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        // đúng hết rồi thì làm tiếp 
        // updateOrCreate có nghĩa là nếu trong ShippingAddress chưa tồn tại customer_id = $request->customer_id
        // thì tạo mới bao gồm id tự sinh và 4 giá trị customer_id,recipient_name,phone_number,address
        // còn nếu customer_id đó có rồi thì update lại recipient_name,phone_number,address cho customer_id đó 
        $shipping_address = ShippingAddress::updateOrCreate(
            [
                'customer_id'   => $request->customer_id,
            ],
            [
                'recipient_name'     => $request->recipient_name,
                'phone_number' => $request->phone_number,
                'address'    => $request->address,
            ],
        );

        return response()->json([
            "message" => "Update Shipping Address Success !",
            "shipping_address" => $shipping_address
        ],201);
        
    }
    public function getAddress(Request $request){
        $shipping_address = ShippingAddress::Where('customer_id',$request->customer_id)->first();
        if(empty($shipping_address)){
            return response()->json([
                "message" => "Please update your shipping address !",
                "shipping_address" => null
            ],201);
        }
        else {
            return response()->json([
                "message" => "Get shipping address success !",
                "shipping_address" => $shipping_address
            ],201);
        }
        
    }

}
