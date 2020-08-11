<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $root =  DB::table("persons")->insertGetId([
            'name' => 'USER',
            'dni' => "0000000000",
            'surname' =>'ROOT',
            'status' => -999,
        ]);

        \App\User::create([
            'person_id' => $root,
            'email' => 'root@mail.com',
            'password' => bcrypt('root'),
            'role' => 'root',
        ]);
    }
}
