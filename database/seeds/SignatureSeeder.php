<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class SignatureSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i<=3 ; $i++) {
            DB::table('signatures')->insertGetId([
                'name' => 'FIRMA '.$i,
                'cargo' => 'CARGO '.$i,
                'image' => 'signatures/firma.png'
            ]);
        }
    }
}
