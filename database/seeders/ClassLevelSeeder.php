<?php

namespace Database\Seeders;

use App\Models\ClassLevel;
use Illuminate\Database\Seeder;

class ClassLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['name' => 'Form 1', 'order' => 1],
            ['name' => 'Form 2', 'order' => 2],
            ['name' => 'Form 3', 'order' => 3],
            ['name' => 'Form 4', 'order' => 4],
            ['name' => 'Form 5', 'order' => 5],
            ['name' => 'Lower 6', 'order' => 6],
            ['name' => 'Upper 6', 'order' => 7],
        ];

        foreach ($levels as $level) {
            ClassLevel::firstOrCreate(['name' => $level['name']], $level);
        }
    }
}