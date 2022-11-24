<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_item', function (Blueprint $table) {
            $table->bigIncrements('order_item_id');
            $table->unsignedBigInteger('order_id')->comment('FK Table order')->index();
            $table->unsignedBigInteger('master_product_main_id')->comment('FK Table master_product_main');
            $table->unsignedBigInteger('master_product_type_id')->nullable()->comment('FK Table master_product_type');
            $table->unsignedBigInteger('master_product_size_id')->comment('FK Table master_product_size');
            $table->decimal('price', 10, 2)->default(0)->comment('ราคา');
            $table->integer('qty_all')->comment('จำนวนสินค้ารายไอเทมทั้งหมด');
            $table->integer('qty_pending')->default(0)->comment('จำนวนสินค้าค้างส่ง');
            $table->index(['order_id','master_product_main_id', 'master_product_type_id','master_product_size_id'],'oi_key1');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_item');
    }
}
