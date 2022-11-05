<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->string('hex_id')->unique();
            $table->string('customer_name');
            $table->string('recipient_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->boolean('order_status');
            $table->dateTime('order_time')->nullable();
            $table->dateTime('confirm_time')->nullable();
            $table->dateTime('ship_time')->nullable();
            $table->dateTime('completed_time')->nullable();
            $table->float('shipping_fee');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_orders');
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
    // cho về toàn bộ với product luôn chứ không cho về trạng thái giỏ hàng 
    // nếu cho về giỏ hàng thì admin không làm gì được 
    // trước khi cho toàn bộ về giỏ hàng thì kiểm tra product đó còn tồn tại không đã , 
    // nếu đã bị xóa và bị setnull trường hiện tại rồi thì thôi . 
    // nói chung trước chi cho về toàn bộ thì kiểm tra product_id khác null là được 
    // sau đó xóa hết thôi không còn liên quan gì nữa 
}
