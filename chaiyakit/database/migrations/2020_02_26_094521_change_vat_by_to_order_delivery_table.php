<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeVatByToOrderDeliveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_delivery', function (Blueprint $table) {
            $table->decimal('vat', 10, 2)->default(0)->comment('จำนวนภาษีมูลค่าเพิ่ม')->change();
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
