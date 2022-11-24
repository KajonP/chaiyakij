<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDeliveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_delivery', function (Blueprint $table) {
            $table->bigIncrements('order_delivery_id');
            $table->unsignedBigInteger('order_id')->comment('FK Table order')->index();
            $table->unsignedBigInteger('product_return_claim_id')->nullable()->comment('FK Table product_claim')->index();
            $table->string('remark')->nullable()->comment('ประเภทรถ');
            $table->string('type',2)->default('00')->comment('00 = ปกติ , 01 = มีการเลื่อน');
            $table->string('status',2)->default('01')->comment('00 = ยังไม่จัดส่ง , 01 = เตรียมการจัดส่ง , 02 = กำลังจัดส่ง , 03 = เลื่อน , 04 = เครม ,05 = จัดส่งสำเร็จ ');
            $table->timestamp('created_date')->useCurrent();
            $table->timestamp('updated_date')->nullable();
            $table->timestamp('deleted_date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->comment('internal FK ของ users');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('internal FK ของ users');
            $table->unsignedBigInteger('deleted_by')->nullable()->comment('internal FK ของ users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_delivery');
    }
}
