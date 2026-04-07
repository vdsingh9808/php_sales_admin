<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UrlSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('urls')->insert([
            'authentication_url' => config('services.authentication_url'),
            'server_url' => config('services.server_url'),
            'payment_base_url' => 'https://api-gateway.sandbox.ngenius-payments.com/',
            'api_key' => 'MzU3YjM0ZTktMDc3YS00NjEyLTk5M2MtM2FiNmQ4ZmJlMTMxOjA1OTkxOGQwLWMzOWYtNDlmZi05MWRlLTdkNWM1MDQ5N2M4ZA==',
            'reference_key' => '476646ca-bf21-4d94-b1a9-bbcd4098b6cb',
        ]);
    }
}
