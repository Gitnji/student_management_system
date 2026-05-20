<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\School;
use Illuminate\Database\Seeder;

class HomepageSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();

        Page::firstOrCreate(
            ['slug' => 'home'],
            [
                'school_id' => $school->id,
                'title'     => 'Home',
                'content'   => [
                    'hero' => [
                        'heading'  => 'Excellence in Education',
                        'subtext'  => 'Nurturing tomorrow\'s leaders through knowledge, discipline, and excellence.',
                        'cta_text' => 'Learn More',
                        'cta_link' => '#about',
                    ],
                    'about' => [
                        'text' => 'Imperial Comprehensive College is a leading secondary school in the North-West Region of Cameroon, committed to academic excellence and holistic student development.',
                    ],
                    'stats' => [
                        ['label' => 'Students', 'value' => '1,200+'],
                        ['label' => 'Teachers', 'value' => '80+'],
                        ['label' => 'Years of Excellence', 'value' => '25+'],
                        ['label' => 'Pass Rate', 'value' => '95%'],
                    ],
                ],
                'meta_title'       => 'Imperial Comprehensive College — Bamenda',
                'meta_description' => 'Imperial Comprehensive College is a leading secondary school in Bamenda, Cameroon.',
            ]
        );
    }
}