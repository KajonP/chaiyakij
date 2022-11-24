<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterMerchantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_merchant', function (Blueprint $table) {
            $table->bigIncrements('master_merchant_id');
            $table->string('name_merchant')->comment('ชื่อร้านค้า');
            $table->string('name_department')->nullable()->comment('ชื่อหน่วยงาน');
            $table->string('tax_number',13)->nullable()->comment('เลขประจำตัวภาษีอากร');
            $table->string('phone_number',100)->nullable()->comment('เบอร์โทรศัพท์');
            $table->text('address')->nullable()->comment('ที่อยู่');
            $table->decimal('latitude', 10, 8)->nullable()->comment('ละจิจูด');
            $table->decimal('longitude', 11, 8)->nullable()->comment('ลองจิจูด');
            $table->text('link_google_map')->nullable()->comment('Link Google Map');
            $table->string('remark')->nullable()->comment('หมายเหตุ');
            $table->timestamp('created_date')->useCurrent();
            $table->timestamp('updated_date')->nullable();
            $table->timestamp('deleted_date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->comment('internal FK ของ users');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('internal FK ของ users');
            $table->unsignedBigInteger('deleted_by')->nullable()->comment('internal FK ของ users');
            $table->unique(['name_merchant','name_department','tax_number'],'mm_key1');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_merchant');
    }
}
