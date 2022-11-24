<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterMerchantPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_merchant_price', function (Blueprint $table) {
            $table->bigIncrements('master_merchant_price_id');
            $table->unsignedBigInteger('master_merchant_id')->comment('FK Table master_merchant');
            $table->unsignedBigInteger('master_product_main_id')->comment('FK Table master_product_main');
            $table->unsignedBigInteger('master_product_type_id')->nullable()->comment('FK Table master_product_type');
            $table->unsignedBigInteger('master_product_size_id')->comment('FK Table master_product_size');
            $table->decimal('price', 10, 2)->comment('ราคา');
            $table->timestamp('created_date')->useCurrent();
            $table->timestamp('updated_date')->nullable();
            $table->timestamp('deleted_date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->comment('internal FK ของ users');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('internal FK ของ users');
            $table->unsignedBigInteger('deleted_by')->nullable()->comment('internal FK ของ users');
            $table->index(['master_merchant_id','master_product_main_id', 'master_product_type_id','master_product_size_id'],'mmp_key1');
            $table->index('master_merchant_id','mmp_key2');
            $table->foreign('master_merchant_id')->references('master_merchant_id')->on('master_merchant')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_merchant_price');
    }
}
