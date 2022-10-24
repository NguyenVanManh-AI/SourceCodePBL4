<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('quantity'); // số lượng 0 (nếu chưa nhập hoặc đã bán hết)
            $table->date('warranty_period');
            $table->longText('description');
            $table->foreignId('category_id')->nullable();
            $table->float('price');
            $table->string('material');
            $table->string('dimension');
            $table->string('uri')->unique();
            $table->timestamps();
        });
        // LƯU Ý : Ở đây kiểu float nhưng đôi lúc vào database kiểu double -> vào đổi lại nếu không khi lưu dữ liệu có giá trị lớn 
        // sẽ gây lỗi 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
