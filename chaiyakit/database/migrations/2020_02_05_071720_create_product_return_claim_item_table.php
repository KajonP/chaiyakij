<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductReturnClaimItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_return_claim_item', function (Blueprint $table) {
            $table->bigIncrements('product_return_claim_item_id');
            $table->unsignedBigInteger('product_return_claim_id')->comment('FK Table product_return_claim')->index();
            $table->unsignedBigInteger('order_item_id')->comment('FK Table order_item');
            $table->integer('qty')->comment('จำนวนสินค้า');
            $table->index(['product_return_claim_id','order_item_id'],'prci_key1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_return_claim_item');
    }
}
