<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add7fieldByToOrderDeliveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_delivery', function (Blueprint $table) {
            $table->string('status_delivery')->comment('00 = ส่งปกติ , 01= ส่งล่าช้า');
            $table->string('delivery_type')->comment('00 = ส่งของ , 01 = คืน , 02 = เคลม');
            $table->integer('qty_all')->comment('จำนวนสินค้าทั้งหมด');
            $table->decimal('price_all', 10, 2)->default(0)->comment('รวมเงินยกเว้นภาษี');
            $table->decimal('vat', 4, 2)->default(0)->comment('จำนวนภาษีมูลค่าเพิ่ม');
            $table->decimal('grand_total', 10, 2)->default(0)->comment('จำนวนเงินรวมทั้งสิ้น');
            $table->decimal('weight', 12, 2)->default(0)->comment('น้ำหนักทั้งหมด (กรัม)');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_delivery', function (Blueprint $table) {
            //
        });
    }
}
