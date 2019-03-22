<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class user extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create(array(
                'id'=>1,
                'first_name' => 'Goseeum',
                'last_name' => 'Admin',
                'email' => 'admin@goseeum.com',
                'password' => Hash::make('123456'),
                'role_id' => 1
            )
        );

        \App\User::create(array(
                'id'=>2,
                'first_name' => 'End',
                'last_name' => 'User',
                'email' => 'enduser@goseeum.com',
                'password' => Hash::make('123456'),
                'role_id' => 2
            )
        );
    }
}
