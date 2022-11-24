<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusDepartmentToOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status_departmen',2)->default('00')->comment('00 = ไม่มีหน่วยงาน , 01 = มีหน่วยงาน')->index();
            $table->string('phone_department')->comment('เบอร์โทรศัพท์ หน่วยงาน')->nullable();
            $table->string('address_department')->comment('ที่อยู่หน่วยงาน')->nullable();
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
