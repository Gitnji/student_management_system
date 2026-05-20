<?php

namespace Database\Seeders;

use App\Models\Stream;
use Illuminate\Database\Seeder;

class StreamSeeder extends Seeder
{
    public function run(): void
    {
        $streams = ['General', 'Science', 'Arts', 'Commercial', 'Technical'];

        foreach ($streams as $name) {
            Stream::firstOrCreate(['name' => $name]);
        }
    }
}
