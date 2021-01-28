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
            'content' => '這是借用規則',
        ]);
        User::truncate();
        User::create([
            'name' => 'chien',
            'privilege' => 'sa_admin',
            'email' => 'chien@chienyld.github.io',
            'email_verified_at' => '2020-11-08 00:30:00',
            'password' => Hash::make('00000000'),
        ]);
    }
}
