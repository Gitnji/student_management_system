<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ClassLevelSeeder::class,
            StreamSeeder::class,
            SchoolSeeder::class,
            HomepageSeeder::class,
        ]);
    }
}
