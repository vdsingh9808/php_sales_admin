<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => config('services.seeder.name'),
            'email' => config('services.seeder.email'),
            'password' => Hash::make(config('services.seeder.password')),
        ]);
    }
}
