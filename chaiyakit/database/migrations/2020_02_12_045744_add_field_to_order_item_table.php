<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToOrderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_item', function (Blueprint $table) {
            $table->decimal('total_price', 10, 2)->default(0)->comment('จำนวนเงิน');
            $table->decimal('price', 10, 2)->default(0)->comment('ราคาต่อหน่วย')->change();
            $table->renameColumn('qty_all', 'qty');
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
        Schema::table('order_item', function (Blueprint $table) {
            //
        });
    }
}
