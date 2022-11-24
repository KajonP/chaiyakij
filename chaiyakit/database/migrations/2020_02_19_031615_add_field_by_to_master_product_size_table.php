<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldByToMasterProductSizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_product_size', function (Blueprint $table) {
            $table->string('size_unit',2)->default('00')->comment('00 = ตร.ม. , 01 = เมตร,02 = ศอก,03 = วา,04 = กร้ม ,05 = กิโลกรัม ,06 = ตัน');
            $table->string('formula',2)->default('00')->comment('00 = สูตรคำนวนเสา ,01 = สูตรคำนวนแผ่นพื้น');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master_product_size', function (Blueprint $table) {
            //
        });
    }
}
