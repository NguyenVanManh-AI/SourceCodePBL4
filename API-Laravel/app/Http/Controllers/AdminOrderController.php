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

class AdminOrderController extends Controller
{
    // hiện tất cả đơn hàng chờ xác nhận 
    public function WaitForConfirmation(Request $request){
        $search = $request->search; 
        $sort = $request->sort; 
        if($sort == 'true') $sort = 'DESC';
        else $sort = 'ASC';

        $customer_orders = CustomerOrder::orderBy('id',$sort)->whereNull('confirm_time')->where('order_status',1) 
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

    // hủy đơn
    public function cancelOrder(Request $request){
        $id = $request->id_cancel;
        $customer_order = CustomerOrder::find($id);
        $customer_order->update([
            'order_status' => 0
        ]);

        $order_details = OrderDetail::where('customer_order_id',$id)->get();
        foreach($order_details as $pr){
            $product = Product::find($pr->product_id); 
            $product->update([
                'quantity' => $product->quantity + $pr->quantity
            ]);
        }

        $customer = Customer::find($customer_order->customer_id); 
        Mail::to($customer->email)->send(new CancelOrder($customer_order->hex_id)); 

        return response()->json([
            'message' => 'Cancel success !',
        ],201);
    }

    // hiện chi tiết đơn hàng 
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

    // xác nhận đơn hàng => chuyển sang chờ giao hàng 
    public function confirm(Request $request){

        $hex_id = $request->hex_id; 
        $customer_order = CustomerOrder::where('hex_id',$hex_id)->first();

        $date_now = Carbon::now('Asia/Ho_Chi_Minh');
        $customer_order->update([
            'confirm_time' => $date_now
        ]);

        $customer = Customer::find($customer_order->customer_id); 
        Mail::to($customer->email)->send(new ConfirmSuccess($customer_order->hex_id)); 

        return response()->json([
            'message' => 'Confirm success !',
        ],201);
    }

    // hiện tất cả chờ giao hàng 
    public function WaitingForShipping(Request $request){
        $search = $request->search; 
        $sort = $request->sort; 
        if($sort == 'true') $sort = 'DESC';
        else $sort = 'ASC';

        $customer_orders = CustomerOrder::orderBy('id',$sort)->whereNull('ship_time')->whereNotNull('confirm_time')->where('order_status',1) 
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

    // xác nhận đang giao hàng => chuyển sang đang giao hàng 
    public function ship(Request $request){

        $hex_id = $request->hex_id; 
        $customer_order = CustomerOrder::where('hex_id',$hex_id)->first();

        $date_now = Carbon::now('Asia/Ho_Chi_Minh');
        $customer_order->update([
            'ship_time' => $date_now
        ]);

        $customer = Customer::find($customer_order->customer_id); 
        Mail::to($customer->email)->send(new Delivering($customer_order->hex_id)); 

        return response()->json([
            'message' => 'Confirm success !',
        ],201);
    }

    // hiện tất cả đơn hàng đang được giao 
    public function Delivering(Request $request){
        $search = $request->search; 
        $sort = $request->sort; 
        if($sort == 'true') $sort = 'DESC';
        else $sort = 'ASC';

        $customer_orders = CustomerOrder::orderBy('id',$sort)->whereNull('completed_time')->whereNotNull('ship_time')->where('order_status',1) 
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

    // xác nhận đã giao hàng 
    public function delivered(Request $request){

        $hex_id = $request->hex_id; 
        $customer_order = CustomerOrder::where('hex_id',$hex_id)->first();

        $date_now = Carbon::now('Asia/Ho_Chi_Minh');
        $customer_order->update([
            'completed_time' => $date_now
        ]);

        $customer = Customer::find($customer_order->customer_id); 
        Mail::to($customer->email)->send(new Delivered($customer_order->hex_id)); 

        return response()->json([
            'message' => 'Confirm success !',
        ],201);
    }

    // hiện tất cả đơn hàng đã được giao 
    public function OrderDelivered(Request $request){
        $search = $request->search; 
        $sort = $request->sort; 
        if($sort == 'true') $sort = 'DESC';
        else $sort = 'ASC';

        $customer_orders = CustomerOrder::orderBy('id',$sort)->whereNotNull('completed_time')->where('order_status',1) 
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

    // in phiếu 
    public function print(Request $request){
        $hex_id = $request->hex_id; 
        $customer_order = CustomerOrder::where('hex_id',$hex_id)->first();
        $customer_order_id = $customer_order->id;
        $order_details = OrderDetail::where('customer_order_id',$customer_order_id)->get();

        $date_now = Carbon::now('Asia/Ho_Chi_Minh');

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
            'customer_order' => $customer_order,
            'date_now' => $date_now
        ],201);

    }
    

}

