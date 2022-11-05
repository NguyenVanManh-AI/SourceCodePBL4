<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    //







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
