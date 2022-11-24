<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldByToMasterVatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_vat', function (Blueprint $table) {
            $table->string('is_default',2)->default('00')->comment('00 = ไม่ใช่ค่าเริ่มต้น , 01 = เป็นค่าเริ่มต้น');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master_vat', function (Blueprint $table) {
            //
        });
    }
}
