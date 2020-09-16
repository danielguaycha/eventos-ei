<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(SponsorSeeder::class);

        if (env('APP_DEBUG')) {
            factory(App\User::class, 50)
                ->create()
                ->each(function ($u) {
                    $u->assignRole(\App\User::rolStudent);
                });
        }
    }
}
