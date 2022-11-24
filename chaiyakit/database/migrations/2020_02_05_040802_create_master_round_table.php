<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterRoundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_round', function (Blueprint $table) {
            $table->bigIncrements('master_round_id');
            $table->string('name')->comment('ชื่อรอบจัดส่ง')->unique();
            $table->time('round_time', 0)->comment('เวลาจัดส่ง')->unique();
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
        Schema::dropIfExists('master_round');
    }
}
