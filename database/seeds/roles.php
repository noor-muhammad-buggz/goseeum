<?php

use Illuminate\Database\Seeder;

class roles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('roles')->delete();
        \App\Models\Roles::create(array(
                'id'=>1,
                'role_name' => 'admin',
                'role_description' => 'System Administrator'
            )
        );
        \App\Models\Roles::create(array(
                'id'=>2,
                'role_name' => 'user',
                'role_description' => 'End User'
            )
        );
    }
}
