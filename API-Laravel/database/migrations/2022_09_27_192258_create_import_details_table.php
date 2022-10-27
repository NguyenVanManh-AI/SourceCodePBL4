<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_id')->nullable();// null khi xóa import đi 
            $table->foreignId('product_id')->nullable(); // null khi xóa product đi 
            $table->string('product_name');
            $table->integer('quantity');
            $table->float('price');
            $table->float('tax');
            $table->timestamps();
        });
        // bảng import  1-n import_details 
        // bảng product 1-n import_details (nhập về các sản phẩm đã có trong danh mục sản phẩm)
        // mỗi sản phẩm được thêm mới vào có số lượng là 0 , sau khi import_details thì bắt đầu cộng 
        // thêm số lượng (quantity) đã nhập , bán ra thì trừ đi 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('import_details');
    }
}
