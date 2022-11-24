<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductReturnClaimTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_return_claim', function (Blueprint $table) {
            $table->bigIncrements('product_return_claim_id');
            $table->unsignedBigInteger('order_id')->comment('FK Table order')->index();
            $table->string('remark')->nullable()->comment('หมายเหตุ');
            $table->string('type',2)->comment('00 = คืน , 01 = เคลม');
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
        Schema::dropIfExists('product_return_claim');
    }
}
