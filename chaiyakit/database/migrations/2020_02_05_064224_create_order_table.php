<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->bigIncrements('order_id');
            $table->unsignedBigInteger('merchant_id')->comment('FK Table master_merchant')->index();
            $table->string('order_number',50)->comment('หมายเลขใบสั่งซื้อ')->unique();
            $table->string('staus_send',2)->default('00')->comment('00 = สั่งซื้อสินค้า , 01 = ดำเนินการจัดส่ง , 02 = เคลม , 03 = ส่งสำเร็จ')->index();
            $table->string('status_order',2)->default('00')->comment('0 = True , 01 = False')->index();
            $table->string('status_vat',2)->default('00')->comment('00 = มี , 01 = ไม่มี')->index();
            $table->string('order_type',2)->default('00')->comment('00 = ปกติ , 01 = เหมา')->index();
            $table->decimal('price_all', 10, 2)->default(0)->comment('ราคารวม');
            $table->decimal('vat', 4, 2)->default(0)->comment('ภาษี');
            $table->decimal('weight', 10, 2)->default(0)->comment('น้ำหนักทั้งหมด (กรัม)');
            $table->integer('qty_all')->comment('จำนวนสินค้าที่สั่งซื้อทั้งหมด');
            $table->integer('qty_pending')->default(0)->comment('จำนวนสินค้าค้างส่ง');
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
        Schema::dropIfExists('order');
    }
}
