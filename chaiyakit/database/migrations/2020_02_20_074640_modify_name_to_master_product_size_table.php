<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class ModifyNameToMasterProductSizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE master_product_size CHANGE name name NUMERIC(12, 4) NOT NULL COMMENT 'ขนาดสินค้า'");
        Schema::table('master_product_size', function (Blueprint $table) {
            $table->decimal('weight', 10, 2)->comment('น้ำหนักของสินค้า')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master_product_size', function (Blueprint $table) {
            //
        });
    }
}
