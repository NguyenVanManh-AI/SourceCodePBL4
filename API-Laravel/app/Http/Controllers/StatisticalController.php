<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ShippingAddress;
use App\Models\CustomerOrder;
use App\Models\OrderDetail;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Import;
use App\Models\ImportDetail;
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

class StatisticalController extends Controller
{
    public function chart(Request $request){
        $sort_by = $request->sort_by;
        $date_now = (int)date('d');
        $month_now = (int)date('m');
        $year_now = (int)date('y');

        if($sort_by == 'year'){

            // line 
            $datas_import = [];
            for($i=1;$i<=12;$i++){
                $total = 0 ; 
                $imports = Import::whereYear('import_date', '20'.$year_now)->whereMonth('import_date', $i)->get();// lượt ra danh sách của mỗi tháng trong 12 tháng 
                foreach($imports as $import){ // lượt qua mỗi import trong danh sách các import của tháng đó 
                    $import_details = ImportDetail::where('import_id',$import->id)->get(); // trong mỗi import lại có nhiều product được nhập vào 
                    foreach($import_details as $import_detail){ // lượt qua các product đó và tính tiền 
                        $total += ($import_detail->quantity * $import_detail->price)*(100 + $import_detail->tax)/100; 
                    }
                }
                // cuối cùng được total sẽ là tổng tiền của danh sách import của một tháng bao gồm nhiều import , mỗi import bao gồm nhiều sản phẩm 
                array_push($datas_import,$total);
            }

            $datas_revenue = [];
            for($i=1;$i<=12;$i++){
                $total = 0 ; 
                $customerOrders = CustomerOrder::whereNotNull('completed_time')->where('order_status',1)
                ->whereYear('order_time', '20'.$year_now)->whereMonth('order_time', $i)->get();
                foreach($customerOrders as $customerOrder){ 
                    $orderDetails = OrderDetail::where('customer_order_id',$customerOrder->id)->get();
                    foreach($orderDetails as $orderDetail){ 
                        $total += $orderDetail->quantity * $orderDetail->price;
                    }
                }
                array_push($datas_revenue,$total);
            }

            // donut 
            $data_donut = [];
            $WaitForConfirmation = CustomerOrder::whereNull('confirm_time')->where('order_status',1)->whereYear('order_time', '20'.$year_now)->get();
            $WaitingForShipping = CustomerOrder::whereNull('ship_time')->whereNotNull('confirm_time')->where('order_status',1)->whereYear('order_time', '20'.$year_now)->get(); 
            $Delivering = CustomerOrder::whereNull('completed_time')->whereNotNull('ship_time')->where('order_status',1)->whereYear('order_time', '20'.$year_now)->get();
            $OrderDelivered = CustomerOrder::whereNotNull('completed_time')->where('order_status',1)->whereYear('order_time', '20'.$year_now)->get();
            $OrderCancel = CustomerOrder::where('order_status',0)->whereYear('order_time', '20'.$year_now)->get();
            array_push($data_donut,count($WaitForConfirmation));
            array_push($data_donut,count($WaitingForShipping));
            array_push($data_donut,count($Delivering));
            array_push($data_donut,count($OrderDelivered));
            array_push($data_donut,count($OrderCancel));
            
            return response()->json([
                'datas_import' => $datas_import,
                'datas_revenue' => $datas_revenue,
                'labels_line' => ['January','February','March','April','May','June','July','August','September','October','November','December'],
                'data_donut' => $data_donut
            ]);

        }

        if($sort_by == 'quarter'){ // tương tự như theo năm nhưng ta chỉ lấy ra 3 tháng thuộc quý hiện tại 
            $start = null;
            $end = null;

            // line 
            if($month_now >= 1 && $month_now <= 3){ $start = 1; $end = 3;}
            if($month_now >= 4 && $month_now <= 6){ $start = 4; $end = 6;}
            if($month_now >= 7 && $month_now <= 9){ $start = 7; $end = 9;}
            if($month_now >= 10 && $month_now <= 12){ $start = 10; $end = 12;}

            $datas_import = [];
            for($i=$start;$i<=$end;$i++){
                $total = 0 ; 
                $imports = Import::whereYear('import_date', '20'.$year_now)->whereMonth('import_date', $i)->get();
                foreach($imports as $import){ 
                    $import_details = ImportDetail::where('import_id',$import->id)->get(); 
                    foreach($import_details as $import_detail){ 
                        $total += ($import_detail->quantity * $import_detail->price)*(100 + $import_detail->tax)/100; 
                    }
                }
                array_push($datas_import,$total);
            }

            $datas_revenue = [];
            $_month = [];
            for($i=$start;$i<=$end;$i++){
                $total = 0 ; 
                array_push($_month,'Month '.$i);
                $customerOrders = CustomerOrder::whereNotNull('completed_time')->where('order_status',1)
                ->whereYear('order_time', '20'.$year_now)->whereMonth('order_time', $i)->get();
                foreach($customerOrders as $customerOrder){ 
                    $orderDetails = OrderDetail::where('customer_order_id',$customerOrder->id)->get();
                    foreach($orderDetails as $orderDetail){ 
                        $total += $orderDetail->quantity * $orderDetail->price;
                    }
                }
                array_push($datas_revenue,$total);
            }
            // line 

            // donut 
            $data_donut = [];
            $_WaitForConfirmation = 0 ;
            $_WaitingForShipping = 0 ;
            $_Delivering = 0;
            $_OrderDelivered = 0;
            $_OrderCancel = 0;
            for($i=$start;$i<=$end;$i++){
                $WaitForConfirmation = CustomerOrder::whereNull('confirm_time')->where('order_status',1)->whereYear('order_time', '20'.$year_now)->whereMonth('order_time', $i)->get();
                $WaitingForShipping = CustomerOrder::whereNull('ship_time')->whereNotNull('confirm_time')->where('order_status',1)->whereYear('order_time', '20'.$year_now)->whereMonth('order_time', $i)->get(); 
                $Delivering = CustomerOrder::whereNull('completed_time')->whereNotNull('ship_time')->where('order_status',1)->whereYear('order_time', '20'.$year_now)->whereMonth('order_time', $i)->get();
                $OrderDelivered = CustomerOrder::whereNotNull('completed_time')->where('order_status',1)->whereYear('order_time', '20'.$year_now)->whereMonth('order_time', $i)->get();
                $OrderCancel = CustomerOrder::where('order_status',0)->whereYear('order_time', '20'.$year_now)->whereMonth('order_time', $i)->get();
                
                $_WaitForConfirmation += count($WaitForConfirmation);
                $_WaitingForShipping += count($WaitingForShipping);
                $_Delivering += count($Delivering);
                $_OrderDelivered += count($OrderDelivered);
                $_OrderCancel += count($OrderCancel);
            }
            array_push($data_donut,$_WaitForConfirmation);
            array_push($data_donut,$_WaitingForShipping);
            array_push($data_donut,$_Delivering);
            array_push($data_donut,$_OrderDelivered);
            array_push($data_donut,$_OrderCancel);
            // donut 

            return response()->json([
                'datas_import' => $datas_import,
                'datas_revenue' => $datas_revenue,
                'labels_line' => $_month,
                'data_donut' => $data_donut
            ]);
        }

        if($sort_by == 'month'){

            // line 
            $datas_import = [];
            for($i=1;$i<=$date_now;$i++){
                $total = 0 ; 
                $imports = Import::whereYear('import_date', '20'.$year_now)->whereMonth('import_date', $month_now)
                ->whereDay('import_date', $i)->get();
                foreach($imports as $import){ 
                    $import_details = ImportDetail::where('import_id',$import->id)->get(); 
                    foreach($import_details as $import_detail){ 
                        $total += ($import_detail->quantity * $import_detail->price)*(100 + $import_detail->tax)/100; 
                    }
                }
                array_push($datas_import,$total);
            }

            $datas_revenue = [];
            $day = [];
            for($i=1;$i<=$date_now;$i++){
                array_push($day,$i);
                $total = 0 ; 
                $customerOrders = CustomerOrder::whereNotNull('completed_time')->where('order_status',1)
                ->whereYear('order_time', '20'.$year_now)->whereMonth('order_time', $month_now)
                ->whereDay('order_time', $i)->get();
                foreach($customerOrders as $customerOrder){ 
                    $orderDetails = OrderDetail::where('customer_order_id',$customerOrder->id)->get();
                    foreach($orderDetails as $orderDetail){ 
                        $total += $orderDetail->quantity * $orderDetail->price;
                    }
                }
                array_push($datas_revenue,$total);
            }
            // line 

            // donut 
            $data_donut = [];
            $WaitForConfirmation = CustomerOrder::whereNull('confirm_time')->where('order_status',1)->whereYear('order_time', '20'.$year_now)->whereMonth('order_time', $month_now)->get();
            $WaitingForShipping = CustomerOrder::whereNull('ship_time')->whereNotNull('confirm_time')->where('order_status',1)->whereYear('order_time', '20'.$year_now)->whereMonth('order_time', $month_now)->get(); 
            $Delivering = CustomerOrder::whereNull('completed_time')->whereNotNull('ship_time')->where('order_status',1)->whereYear('order_time', '20'.$year_now)->whereMonth('order_time', $month_now)->get();
            $OrderDelivered = CustomerOrder::whereNotNull('completed_time')->where('order_status',1)->whereYear('order_time', '20'.$year_now)->whereMonth('order_time', $month_now)->get();
            $OrderCancel = CustomerOrder::where('order_status',0)->whereYear('order_time', '20'.$year_now)->whereMonth('order_time', $month_now)->get();

            array_push($data_donut,count($WaitForConfirmation));
            array_push($data_donut,count($WaitingForShipping));
            array_push($data_donut,count($Delivering));
            array_push($data_donut,count($OrderDelivered));
            array_push($data_donut,count($OrderCancel));

            return response()->json([
                'datas_import' => $datas_import,
                'datas_revenue' => $datas_revenue,
                'labels_line' => $day,
                'data_donut' => $data_donut
            ]);
        }

        // ngày có thể nhầm lẫn một chút vì nếu lấy ngày mở mỹ và ngày ở việt nam sẽ khác nhau 
        // nói chung sau này có gì xem lại fix lại sau 



    }
}

/*
    echo date("d-m-Y"); 22-12-2022 
    echo date('d'); 22 // chữ d thường là số 
    echo date('D'); Thu // chữ D hoa là thứ (dạng chữ)
    // tương tự với tháng , nếu m thường là số , nếu M hoa 
    echo date('m'); // 12
    echo date('M'); // Dec (viết tắt của December)

*/