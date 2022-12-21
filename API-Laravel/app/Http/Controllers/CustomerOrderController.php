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
use App\Mail\CancelOrder;
use App\Mail\ConfirmSuccess;
use App\Mail\Delivered;
use App\Mail\Delivering;
use App\Models\Image;
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
            'order_status' => 1,
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

    // đúng ra là phải query đến bảng Order_detail để lấy ra đơn hàng rồi cộng tất cả sản phẩm lại là ta sẽ được tổng tiền 
    // nhưng trước đó mình có lưu tiền ship vào bằng cách nhân 0.1 tổng tiền hàng nên giờ lấy ra như thế này cx được  

    public function WaitForConfirmation(Request $request){
        $search = $request->search; 
        $sort = $request->sort; 
        if($sort == 'true') $sort = 'DESC';
        else $sort = 'ASC';

        $id_customer = $request->id ; // những cái mới nhất cho lên đầu , những cái chưa có confirm_time là chưa xác nhận 
        $customer_orders = CustomerOrder::orderBy('id',$sort)->where('customer_id',$id_customer)->whereNull('confirm_time')->where('order_status',1) 
        ->where(function($query) use($search){
            $query->where('hex_id','LIKE', '%'.$search.'%')
            ->orWhere('recipient_name','LIKE', '%'.$search.'%')
            ->orWhere('address','LIKE', '%'.$search.'%');
        })->paginate(5);

        // chỉ hiện ra các đơn hàng có status 1
        // các đơn hàng có status 0 cho qua bảng cancel  

        foreach($customer_orders as $order){
            $order->total = $order->shipping_fee*10; // php cũng giống như js , trước đó total là thuộc tính chưa có 
            // ta chỉ cần gọi là thuộc tính này tự động thêm vào object 
        }

        return response()->json([
            'message' => 'Get all order success !',
            'customer_order' => $customer_orders
        ],201);
    }

    public function orderDetails(Request $request){
        $hex_id = $request->hex_id; 
        $customer_order = CustomerOrder::where('hex_id',$hex_id)->first();
        $customer_order_id = $customer_order->id;
        $order_details = OrderDetail::where('customer_order_id',$customer_order_id)->get();

        $total = 0 ; 
        foreach($order_details as $pr){
            $img = Image::where('product_id',$pr->product_id)->first();
            $pr->image_path = $img->image_path;
            $total += $pr->price * $pr->quantity;
        }

        return response()->json([
            'message' => 'Get all order products success !',
            'order_details' => $order_details,
            'total' => $total,
            'order_time' => $customer_order->order_time
        ],201);

    }

    public function cancelOrder(Request $request){
        $id = $request->id_cancel;
        $customer_order = CustomerOrder::find($id);
        $customer_order->update([
            'order_status' => 0
        ]);

        // sau khi cho status = 0 thì phải cộng các sản phẩm đã được order vào lại cho product 
        $order_details = OrderDetail::where('customer_order_id',$id)->get();
        foreach($order_details as $pr){
            $product = Product::find($pr->product_id); 
            $product->update([
                'quantity' => $product->quantity + $pr->quantity
            ]);
        }

        // sau đó gửi mail về cho user là đơn đã được user hủy thành công hoặc là bị admin hủy 
        $customer = Customer::find($request->id_customer); 
        Mail::to($customer->email)->send(new CancelOrder($customer_order->hex_id)); 

        return response()->json([
            'message' => 'Get cancel success !',
        ],201);

        // đúng ra trong mỗi lần where hay find thế này , chắc ăn nhất là phải kiểm tra 
        // nó có empty hay không , không mới tiếp tục thực hiện (tất cả các cái chứ không riêng gì cái này)
    }
    

    // là những cái đã có confirm_time mà chưa có ship_time 
    public function WaitingForShipping(Request $request){
        $search = $request->search; 
        $sort = $request->sort; 
        if($sort == 'true') $sort = 'DESC';
        else $sort = 'ASC';

        $id_customer = $request->id ;
        $customer_orders = CustomerOrder::orderBy('id',$sort)->where('customer_id',$id_customer)->whereNull('ship_time')->whereNotNull('confirm_time')->where('order_status',1) 
        ->where(function($query) use($search){
            $query->where('hex_id','LIKE', '%'.$search.'%')
            ->orWhere('recipient_name','LIKE', '%'.$search.'%')
            ->orWhere('address','LIKE', '%'.$search.'%');
        })->paginate(5);

        foreach($customer_orders as $order){
            $order->total = $order->shipping_fee*10; 
        }

        return response()->json([
            'message' => 'Get all order success !',
            'customer_order' => $customer_orders
        ],201);
    }

    // là những cái đã có ship_time mà chưa có completed_time 
    public function Delivering(Request $request){
        $search = $request->search; 
        $sort = $request->sort; 
        if($sort == 'true') $sort = 'DESC';
        else $sort = 'ASC';

        $id_customer = $request->id ;
        $customer_orders = CustomerOrder::orderBy('id',$sort)->where('customer_id',$id_customer)->whereNull('completed_time')->whereNotNull('ship_time')->where('order_status',1) 
        ->where(function($query) use($search){
            $query->where('hex_id','LIKE', '%'.$search.'%')
            ->orWhere('recipient_name','LIKE', '%'.$search.'%')
            ->orWhere('address','LIKE', '%'.$search.'%');
        })->paginate(5);

        foreach($customer_orders as $order){
            $order->total = $order->shipping_fee*10; 
        }

        return response()->json([
            'message' => 'Get all order success !',
            'customer_order' => $customer_orders
        ],201);
    }

    // là những cái đã có completed_time
    public function Delivered(Request $request){
        $search = $request->search; 
        $sort = $request->sort; 
        if($sort == 'true') $sort = 'DESC';
        else $sort = 'ASC';

        $id_customer = $request->id ;
        $customer_orders = CustomerOrder::orderBy('id',$sort)->where('customer_id',$id_customer)->whereNotNull('completed_time')->where('order_status',1) 
        ->where(function($query) use($search){
            $query->where('hex_id','LIKE', '%'.$search.'%')
            ->orWhere('recipient_name','LIKE', '%'.$search.'%')
            ->orWhere('address','LIKE', '%'.$search.'%');
        })->paginate(5);

        foreach($customer_orders as $order){
            $order->total = $order->shipping_fee*10; 
        }

        return response()->json([
            'message' => 'Get all order success !',
            'customer_order' => $customer_orders
        ],201);
    }

    // những đơn bị hủy order_status = 0 
    public function OrderCancel(Request $request){
        $search = $request->search; 
        $sort = $request->sort; 
        if($sort == 'true') $sort = 'DESC';
        else $sort = 'ASC';

        $id_customer = $request->id ;
        $customer_orders = CustomerOrder::orderBy('id',$sort)->where('customer_id',$id_customer)->where('order_status',0) 
        ->where(function($query) use($search){
            $query->where('hex_id','LIKE', '%'.$search.'%')
            ->orWhere('recipient_name','LIKE', '%'.$search.'%')
            ->orWhere('address','LIKE', '%'.$search.'%');
        })->paginate(5);

        foreach($customer_orders as $order){
            $order->total = $order->shipping_fee*10; 
        }

        return response()->json([
            'message' => 'Get all order success !',
            'customer_order' => $customer_orders
        ],201);
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