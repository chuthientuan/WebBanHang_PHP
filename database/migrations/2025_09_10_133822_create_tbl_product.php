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
        Schema::create('tbl_product', function (Blueprint $table) {
            $table->increments('product_id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('brand_id');
            $table->string('product_name');
            $table->text('product_desc');
            $table->text('product_content');
            $table->string('product_price');
            $table->string('product_image');
            $table->integer('product_status');
            $table->timestamps();

            // Định nghĩa liên kết
            $table->foreign('category_id')->references('category_id')->on('tbl_category_product')->onDelete('cascade');
            $table->foreign('brand_id')->references('brand_id')->on('tbl_brand')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_product');
    }
};