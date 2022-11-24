<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterTruckTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_truck_type', function (Blueprint $table) {
            $table->bigIncrements('master_truck_type_id');
            $table->string('type',50)->comment('ประเภทรถ')->unique();
            $table->timestamp('created_date')->useCurrent();
            $table->timestamp('updated_date')->nullable();
            $table->timestamp('deleted_date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->comment('internal FK ของ users');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('internal FK ของ users');
            $table->unsignedBigInteger('deleted_by')->nullable()->comment('internal FK ของ users');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_truck_type');
    }
}
