<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTruckScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('truck_schedule', function (Blueprint $table) {
            $table->bigIncrements('truck_schedule_id');
            $table->unsignedBigInteger('order_delivery_id')->comment('FK Table order_delivery')->unique();
            $table->unsignedBigInteger('master_round_id')->comment('FK Table master_round');
            $table->unsignedBigInteger('master_truck_id')->nullable()->comment('FK Table master_truck');
            $table->dateTime('date_schedule', 0)->comment('วันที่ส่ง');
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
        Schema::dropIfExists('truck_schedule');
    }
}
