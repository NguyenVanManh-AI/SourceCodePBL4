<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ShippingAddress;
use App\Models\CustomerOrder;
use App\Models\OrderDetail;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use SebastianBergmann\Environment\Console;
use Exception;
use Hash;
use Mail;        
use Illuminate\Support\Facades\DB;
use App\Mail\OrderSuccess;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CustomerOrderController extends Controller
{
    public function buyNow(Request $request){

        // kiểm tra xem với id_user gửi lên có địa chỉ nhận hàng chưa , nếu chưa thì trả về false

        $shippingAddress = ShippingAddress::where('customer_id',$request->id_user)->first(); 
        if(empty($shippingAddress)) {
            return response()->json(['error' => 'You do not have a shipping address yet !'], 400);
        }
        
        $customer = Customer::find($request->id_user); 
        $bytes = random_bytes(10);
        $transaction_id = (bin2hex($bytes)); // hash (random ra uri)

        $date_now = Carbon::now('Asia/Ho_Chi_Minh');

        // thêm vào CustomerOrder
        $customer_order = CustomerOrder::create([
            'hex_id' => $transaction_id,
            'customer_id' => $request->id_user,
            'customer_name' => $customer->fullname,
            'recipient_name' => $shippingAddress->recipient_name,
            'phone_number' => $shippingAddress->phone_number,
            'address' => $shippingAddress->address,
            'order_status' => 0,
            'order_time' => $date_now,
            'shipping_fee' => round(0.1*$request->totalPrice)
        ]);

        // thêm các sản phẩm vào OrderDetail
        $buy_now = $request->buy_now;
        foreach($buy_now as $pr){
            $product = (object)($pr);
            OrderDetail::create([
                'customer_order_id' => $customer_order->id,
                'product_id' => $product->product_id,
                'product_name' => $product->product_name,
                'quantity' => $product->buy_number,
                'price' => $product->price
            ]);

            // trừ số lượng của sản phẩm đó ra
            $product_old = Product::find($product->product_id);
            $product_old->update([
                'quantity' => ($product_old->quantity - $product->buy_number),
            ]); 
        }

        // gửi Mail thông báo order thành công 
        // nếu là mail không tồn tại thì cũng không sao , nó sẽ tiếp tục thực hiện câu lệnh return ở dưới 
        // còn nếu mail tồn tại thì nó gửi mail thông báo về 
        Mail::to($customer->email)->send(new OrderSuccess($customer_order->hex_id)); 

        return response()->json([
            'message' => 'Order Success ! Order code is '. $customer_order->hex_id,
        ], 201);


    }









    // recipient_name, phone_number, address có thể null bởi vì người dùng có thểm thêm vào giỏ hàng trước 
    // rồi sau đó khi người dùng bấm đặt hàng => ta kiểm tra chưa cập nhật thông tin mới báo để người dùng
    // cập nhật, 
    // còn chỉ cần người dùng bấm thêm vào giỏ hàng 

    // hex_id được tạo tự động , customer_id được lấy từ người dùng 
    // order_status => 0
    // shipping_fee => giá sản phẩm nhân với số lượng sản phẩm đầu tiên được thêm vào giỏ hàng 

    // nếu bỏ đi sản phẩm nào hoặc thêm bớt số lượng => cập nhật ở bảng order_detail 
    // nếu thêm thì trừ quantity trong product đi , nếu bớt thì cộng lại cho quantity của sản phẩm đó 
    // quantity < 0 thì báo hết < số hàng order thì báo không đủ hàng và báo số hàng tối đa còn lại   
    // sau đó tiếp tục tính tổng toàn bộ trong order_detail rồi cập nhật lại shipping_fee
    // cứ như thế 
    // sau đó nếu admin hủy đơn thì thôi cho về lại toàn bộ product 
    // còn xóa hay không thì sau này code rồi tính tiếp ,...
}


// Như thế này cũng được 
// $customer_order = [
//     'hex_id' => $transaction_id,
//     'customer_id' => $request->id_user,
//     'customer_name' => $customer->fullname,
//     'recipient_name' => $shippingAddress->recipient_name,
//     'phone_number' => $shippingAddress->phone_number,
//     'address' => $shippingAddress->address,
//     'order_status' => 0,
//     'order_time' => $date_now,
//     'shipping_fee' => 0.1*$request->totalPrice
// ];
// CustomerOrder::create($customer_order);

// Hoặc như thế này cũng được 

// $customer_order = CustomerOrder::create([
//     'hex_id' => $transaction_id,
//     'customer_id' => $request->id_user,
//     'customer_name' => $customer->fullname,
//     'recipient_name' => $shippingAddress->recipient_name,
//     'phone_number' => $shippingAddress->phone_number,
//     'address' => $shippingAddress->address,
//     'order_status' => 0,
//     'order_time' => $date_now,
//     'shipping_fee' => 0.1*$request->totalPrice
// ]);