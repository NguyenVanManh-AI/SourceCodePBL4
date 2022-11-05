<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_order_id'); // customer_order_id liên kết với id table customer_orders // không cần set null vì không xóa người dùng 
            $table->foreignId('product_id')->nullable(); // xóa product đi thì setnull cái này  
            $table->string('product_name'); 
            $table->integer('quantity');
            $table->float('price'); 
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
        Schema::dropIfExists('order_details');
    }

    // ở bảng customer_order chỉ có việc hủy đơn là cho order_details cho toàn bộ về lại với product thôi 
    // không xóa gì cả , còn đơn đã giao rồi thì không xóa , nó cũng giống như nhập kho và nhập kho chi tiết 
    // vậy => để lại làm lịch sử chứ không xóa . 
    // bảng customer_order chỉ có ghi thêm status nào mà từ 0 sang 1 rồi thì tạo cái mới 
    // còn đơn nào hủy thì cho từ status 1 về 0 rồi tiếp tục thêm vào giỏ hàng đó . 
    
    // cho toàn bộ về product rồi (cộng thêm số lượng về lại cho product) thì xóa dòng đó đi 
}
