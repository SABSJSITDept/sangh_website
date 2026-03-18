<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AppRegistration\RegistrationStatus;

class RegistrationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RegistrationStatus::insert([
            ['status' => 'enable'],
            ['status' => 'disable'],
        ]);
    }
}
