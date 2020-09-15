<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class SponsorSeeder extends Seeder
{
    public function run()
    {
        DB::table('sponsors')->insert([
            'name' => 'Escuela Informática',
        ]);
        DB::table('sponsors')->insert([
            'name' => 'Facultad de Ingeniería Civil',
        ]);
        DB::table('sponsors')->insert([
            'name' => 'Vicerrectora Académico',
        ]);
    }
}
