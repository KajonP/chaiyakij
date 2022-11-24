<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitCountByToMasterProductMainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_product_main', function (Blueprint $table) {
            $table->string('unit_count',2)->default('00')->comment('หน่วยนับ 00 = แผ่น ,01 = อัน,02 = ชิ้น,03 = กระป๋อง,04 = ขวด,05 = ถัง,06 = กระสอบ,07 = ถุง,08 = ก้อน,09 = ตัว,10 = ดอก,11 = เส้น,12 = ต้น,13 = เล่ม,14=คิว');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master_product_main', function (Blueprint $table) {
            //
        });
    }
}
