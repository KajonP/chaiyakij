<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldByToOrderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_item', function (Blueprint $table) {
            $table->decimal('total_size', 10, 2)->default(0)->comment('จำนวนตร.ม. (จำนวนรวมขนาด)');
            $table->string('size_unit',2)->default('00')->comment('00 = ตร.ม. , 01 = เมตร,02 = ศอก,03 = วา,04 = กร้ม ,05 = กิโลกรัม ,06 = ตัน');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_item', function (Blueprint $table) {
            //
        });
    }
}
