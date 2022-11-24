<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFiledByToOrderDeliveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_delivery', function (Blueprint $table) {
            $table->string('status_delivery')->nullable()->comment('00 = ส่งปกติ , 01= ส่งล่าช้า')->change();
            $table->string('delivery_type')->default('00')->comment('00 = ส่งของ , 01 = คืน , 02 = เคลม')->change();
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
