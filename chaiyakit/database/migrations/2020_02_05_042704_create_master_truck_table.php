<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterTruckTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_truck', function (Blueprint $table) {
            $table->bigIncrements('master_truck_id');
            $table->unsignedBigInteger('master_truck_type_id')->comment('FK Table master_truck_type');
            $table->decimal('weight', 10, 2)->default(0)->comment('น้ำหนักรถ (กิโลกรัม)');
            $table->string('license_plate',50)->comment('ป้ายทะเบียน')->unique();
            $table->dateTime('date_vat_expire', 0)->nullable()->comment('วันหมดอายุภาษี');
            $table->timestamp('created_date')->useCurrent();
            $table->timestamp('updated_date')->nullable();
            $table->timestamp('deleted_date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->comment('internal FK ของ users');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('internal FK ของ users');
            $table->unsignedBigInteger('deleted_by')->nullable()->comment('internal FK ของ users');
            $table->index('master_truck_type_id');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_truck');
    }
}
