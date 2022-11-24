<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new User();
        $admin->email = 'admin@admin.com';
        $admin->name = 'admin';
        $admin->password = bcrypt('12345678');
        $admin->created_by = 1;
        $admin->save();
        DB::table('model_has_roles')->insert([
            'role_id' =>1,
            'model_type' => 'App\User',
            'model_id' =>1,
           
        ]);
    
    }
}
