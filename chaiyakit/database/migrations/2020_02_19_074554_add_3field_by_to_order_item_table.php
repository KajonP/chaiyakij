<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add3fieldByToOrderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_item', function (Blueprint $table) {
            $table->decimal('addition', 10, 2)->nullable()->default(0)->comment('ราคาเพิ่มเติมต่อหน่วย');
            $table->decimal('weight', 10, 2)->default(0)->comment('น้ำหนักทั้งหมด (กรัม)');
            $table->string('remark')->nullable()->comment('หมายเหตุ');
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
