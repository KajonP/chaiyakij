<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderDeliveryIdByToProductReturnClaimTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_return_claim', function (Blueprint $table) {
            $table->unsignedBigInteger('order_delivery_id')->comment('FK Table order_delivery');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_return_claim', function (Blueprint $table) {
            //
        });
    }
}
