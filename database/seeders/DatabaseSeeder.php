<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Bulletin;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Bulletin::truncate();
        Bulletin::create([
            'content' => '這是公告告',
        ]);
        Bulletin::create([
            'content' => '這是醫院院內機能整合系統，本平台提供所有本院周邊店家一個自由交易的空間，以及訂單系統，庫存管理等功能。',
        ]);
        User::truncate();
        User::create([
            'name' => 'admin',
            'privilege' => 'sa_admin',
            'active' => true,
            'email' => 'chien@github.io',
            'email_verified_at' => '2021-01-01 00:30:00',
            'password' => Hash::make('00000000'),
        ]);
    }
}
