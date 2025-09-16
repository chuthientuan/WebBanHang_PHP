<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_order_details', function (Blueprint $table) {
            $table->increments('order_details_id');

            // Sửa kiểu dữ liệu và thêm khóa ngoại
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('product_id');

            $table->string('product_name');
            $table->string('product_price', 50);
            $table->integer('product_sales_quantity');
            $table->timestamps();

            // Định nghĩa liên kết
            $table->foreign('order_id')->references('order_id')->on('tbl_order')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('tbl_product')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_order_details');
    }
};
