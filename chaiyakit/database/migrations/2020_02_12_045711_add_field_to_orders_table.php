<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('grand_total', 10, 2)->default(0)->comment('จำนวนเงินรวมทั้งสิ้น');
            $table->decimal('price_all', 10, 2)->default(0)->comment('รวมเงินยกเว้นภาษี')->change();
            $table->dropColumn('qty_pending');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
