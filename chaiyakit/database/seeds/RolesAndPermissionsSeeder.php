<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
  

       $role = Role::create(['name' => 'super-admin']);
       $role = Role::create(['name' => 'admin']);
       $role = Role::create(['name' => 'accounting']);
       $role = Role::create(['name' => 'manager']);

    }
}
