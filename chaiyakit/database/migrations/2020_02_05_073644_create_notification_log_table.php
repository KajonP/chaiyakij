<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_log', function (Blueprint $table) {
            $table->bigIncrements('notification_log_id');
            $table->unsignedBigInteger('notification_id')->comment('FK Table notification');
            $table->unsignedBigInteger('read_by')->comment('internal FK ของ users');
            $table->timestamp('created_date')->useCurrent();
            $table->unique(['notification_id','read_by',],'nl_key1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_log');
    }
}
