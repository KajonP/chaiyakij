<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDeliveryItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_delivery_item', function (Blueprint $table) {
            $table->bigIncrements('order_delivery_item_id');
            $table->unsignedBigInteger('order_delivery_id')->comment('FK Table order_delivery')->index();
            $table->unsignedBigInteger('order_item_id')->comment('FK Table order_item');
            $table->integer('qty')->comment('จำนวนสินค้าที่ส่ง');
            $table->index(['order_delivery_id','order_item_id'],'odi_key1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_delivery_item');
    }
}
