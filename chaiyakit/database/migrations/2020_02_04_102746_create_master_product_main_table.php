<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterProductMainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_product_main', function (Blueprint $table) {
            $table->bigIncrements('master_product_main_id');
            $table->string('name')->comment('ชื่อสินค้า');
            $table->string('status_detail', 2)->default('00')->comment('00 = มีรายละเอียด , 01 = ไม่มีรายละเอียด');
            $table->string('status_special', 2)->default('00')->comment('00 = เป็นสินค้าที่เพิ่มจากหน้าสั่งซื้อ , 01 = ไม่เป็นสินค้าที่เพิ่มจากหน้าสั่งซื้อ');
 
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
        Schema::dropIfExists('master_product_main');
    }
}
