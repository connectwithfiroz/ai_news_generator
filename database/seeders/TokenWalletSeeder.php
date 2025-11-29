<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TokenWalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('token_wallets')->insert([
            'user_id' => 1,
            'total_token_available' => 10000,
            'total_token_credited' => 10000,
            'total_token_used' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }
}
