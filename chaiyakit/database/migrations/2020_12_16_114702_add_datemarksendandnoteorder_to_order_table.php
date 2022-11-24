<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatemarksendandnoteorderToOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('noteorder')->comment('หมายเหตุบิล')->nullable();
            $table->timestamp('datemarksend')->comment('วันที่จัดส่ง(เอาไว้เพื่อแจ้งเตือนกรณียังไม่ได้สร้างบิลย่อย)')->nullable();
            //
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
