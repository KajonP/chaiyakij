<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add6fieldByToOrderDeliveryItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_delivery_item', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->default(0)->comment('ราคาต่อหน่วย');
            $table->decimal('total_price', 10, 2)->default(0)->comment('จำนวนเงิน');
            $table->decimal('total_size', 10, 2)->default(0)->comment('จำนวนตร.ม. (จำนวนรวมขนาด)');
            $table->string('size_unit',2)->default('00')->comment('00 = ตร.ม. , 01 = เมตร,02 = ศอก,03 = วา,04 = กร้ม ,05 = กิโลกรัม ,06 = ตัน');
            $table->decimal('addition', 10, 2)->nullable()->default(0)->comment('ราคาเพิ่มเติมต่อหน่วย');
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
        Schema::table('order_delivery_item', function (Blueprint $table) {
            //
        });
    }
}
