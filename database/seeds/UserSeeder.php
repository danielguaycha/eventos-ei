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

        $dev =  DB::table("persons")->insertGetId([
            'name' => 'USER',
            'surname' =>'DEV',
            'dni' => "9999999999",
            'status' => -999,
        ]);

        $user = \App\User::create([
            'person_id' => $root,
            'email' => 'root@mail.com',
            'email_verified_at' => \Carbon\Carbon::now(),
            'password' => bcrypt('root'),
            'type' => 'other',
        ]);

        $userDev = \App\User::create([
            'person_id' => $dev,
            'email' => 'detzerg@gmail.com',
            'password' => bcrypt('dev'),
            'email_verified_at' => \Carbon\Carbon::now(),
            'type' => 'other',
        ]);



        $user->assignRole('root');
        $userDev->assignRole('root');

        if (env('APP_DEBUG')) {
            $adminPerson =  DB::table("persons")->insertGetId([
                'name' => 'ADMIN',
                'dni' => "0000000000",
                'surname' =>'ADMIN',
                'status' => 1,
            ]);

            $userAdmin = \App\User::create([
                'person_id' => $adminPerson,
                'email' => 'admin@mail.com',
                'password' => bcrypt('admin'),
                'email_verified_at' => \Carbon\Carbon::now(),
                'type' => 'other',
            ]);

            $userAdmin->assignRole('admin');
        }

    }
}
