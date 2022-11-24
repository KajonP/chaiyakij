<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MasterVatTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        DB::table('master_vat')->insert([
            ['vat' => 0,'updated_by'=>1,'is_default'=>'00'],
            ['vat' => 7,'updated_by'=>1,'is_default'=>'01'],
        ]);
    }
}
