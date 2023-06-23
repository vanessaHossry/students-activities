<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            WeekDaySeeder::class,
            PortalSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            PermissionSeeder::class,
            apiKeySeeder::class,
            ActivitySeeder::class,
        ]);
    }
}
