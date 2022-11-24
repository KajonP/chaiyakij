<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterProductSizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_product_size', function (Blueprint $table) {
            $table->bigIncrements('master_product_size_id');
            $table->unsignedBigInteger('master_product_main_id')->comment('FK Table master_product_main');
            $table->unsignedBigInteger('master_product_type_id')->nullable()->comment('FK Table master_product_type');
            $table->string('name')->comment('ชื่อรายละเอียดสินค้า');
            $table->decimal('weight', 10, 2)->default(0)->comment('ชื่อรายละเอียดสินค้า');
            $table->timestamp('created_date')->useCurrent();
            $table->timestamp('updated_date')->nullable();
            $table->timestamp('deleted_date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->comment('internal FK ของ users');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('internal FK ของ users');
            $table->unsignedBigInteger('deleted_by')->nullable()->comment('internal FK ของ users');
            $table->index(['master_product_main_id', 'master_product_type_id'],'mps_master_product');
            
        });
      
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_product_size');
    }
}
