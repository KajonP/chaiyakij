<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MasterProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('master_product_main')->insert([
            ['name' => 'เสาบ้าน','status_detail'=>'00','status_special'=>'01','created_by'=>1],
            ['name' => 'เสารั้ว','status_detail'=>'01','status_special'=>'01','created_by'=>1],
            ['name' => 'เสาไอ','status_detail'=>'00','status_special'=>'01','created_by'=>1],
            ['name' => 'เสาเข็ม','status_detail'=>'01','status_special'=>'01','created_by'=>1],
            ['name' => 'แผ่นพื้น','status_detail'=>'01','status_special'=>'01','created_by'=>1],
        ]);
        DB::table('master_product_type')->insert([
            ['name' => 'บ่าเวียน','master_product_main_id'=>1,'created_by'=>1],
            ['name' => 'บ่าเส่','master_product_main_id'=>1,'created_by'=>1],
            ['name' => 'บ่าต่อ','master_product_main_id'=>1,'created_by'=>1],
            ['name' => 'เสริมเหล็ก','master_product_main_id'=>3,'created_by'=>1],
            ['name' => 'ไพลท','master_product_main_id'=>3,'created_by'=>1],

        ]);
        DB::table('master_product_size')->insert([
            ['name' => '4x4','weight'=>900,'master_product_main_id'=>1,'master_product_type_id'=>1,'created_by'=>1],
            ['name' => '5x5','weight'=>1000,'master_product_main_id'=>1,'master_product_type_id'=>1,'created_by'=>1],
            ['name' => '6x6','weight'=>1200,'master_product_main_id'=>1,'master_product_type_id'=>1,'created_by'=>1],

            ['name' => '4x4','weight'=>900,'master_product_main_id'=>1,'master_product_type_id'=>2,'created_by'=>1],
            ['name' => '5x5','weight'=>1000,'master_product_main_id'=>1,'master_product_type_id'=>2,'created_by'=>1],
            ['name' => '6x6','weight'=>1200,'master_product_main_id'=>1,'master_product_type_id'=>2,'created_by'=>1],

            ['name' => '4x4','weight'=>900,'master_product_main_id'=>1,'master_product_type_id'=>3,'created_by'=>1],
            ['name' => '5x5','weight'=>1000,'master_product_main_id'=>1,'master_product_type_id'=>3,'created_by'=>1],
            ['name' => '6x6','weight'=>1200,'master_product_main_id'=>1,'master_product_type_id'=>3,'created_by'=>1],

            ['name' => '3x3','weight'=>1000,'master_product_main_id'=>2,'master_product_type_id'=>null,'created_by'=>1],
            ['name' => '4x4','weight'=>1200,'master_product_main_id'=>2,'master_product_type_id'=>null,'created_by'=>1],

            ['name' => 'I15','weight'=>900,'master_product_main_id'=>3,'master_product_type_id'=>4,'created_by'=>1],
            ['name' => 'I18','weight'=>1000,'master_product_main_id'=>3,'master_product_type_id'=>4,'created_by'=>1],

            ['name' => 'I15','weight'=>900,'master_product_main_id'=>3,'master_product_type_id'=>5,'created_by'=>1],
            ['name' => 'I18','weight'=>1000,'master_product_main_id'=>3,'master_product_type_id'=>5,'created_by'=>1],


            ['name' => 'อัดแรง','weight'=>900,'master_product_main_id'=>4,'master_product_type_id'=>null,'created_by'=>1],
            ['name' => 'ธรรมดา','weight'=>1000,'master_product_main_id'=>4,'master_product_type_id'=>null,'created_by'=>1],

            ['name' => 'ลาด4','weight'=>0,'master_product_main_id'=>5,'master_product_type_id'=>null,'created_by'=>1],
            ['name' => 'ลาด5','weight'=>0,'master_product_main_id'=>5,'master_product_type_id'=>null,'created_by'=>1],
            ['name' => 'ลาด6','weight'=>0,'master_product_main_id'=>5,'master_product_type_id'=>null,'created_by'=>1],
            ['name' => 'ลาด7','weight'=>0,'master_product_main_id'=>5,'master_product_type_id'=>null,'created_by'=>1],
        ]);
    }
}
