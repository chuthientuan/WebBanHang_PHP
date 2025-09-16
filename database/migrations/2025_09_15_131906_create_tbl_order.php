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
        Schema::create('tbl_order', function (Blueprint $table) {
            $table->increments('order_id');

            // Sửa kiểu dữ liệu và thêm khóa ngoại
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('shipping_id');
            $table->unsignedInteger('payment_id');

            $table->string('order_total', 50);
            $table->string('order_status', 50);
            $table->timestamps();

            // Định nghĩa liên kết
            $table->foreign('customer_id')->references('customer_id')->on('tbl_customer')->onDelete('cascade');
            $table->foreign('shipping_id')->references('shipping_id')->on('tbl_shipping')->onDelete('cascade');
            $table->foreign('payment_id')->references('payment_id')->on('tbl_payment')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_order');
    }
};
