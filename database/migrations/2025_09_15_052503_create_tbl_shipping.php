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
        Schema::create('tbl_shipping', function (Blueprint $table) {
            $table->increments('shipping_id');

            // Sửa kiểu dữ liệu và thêm khóa ngoại
            $table->unsignedInteger('customer_id');
            $table->string('shipping_name');
            $table->string('shipping_address');
            $table->string('shipping_phone');
            $table->string('shipping_email');
            $table->text('shipping_note');
            $table->timestamps();

            // Định nghĩa liên kết
            $table->foreign('customer_id')->references('customer_id')->on('tbl_customer')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_shipping');
    }
};