<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFormulaByToMasterProductMainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_product_main', function (Blueprint $table) {
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
        Schema::table('master_product_main', function (Blueprint $table) {
            //
        });
    }
}
