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

        Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'school_id' => $school->id,
                'title'     => 'Home',
                'content'   => [
                    'hero' => [
                        'heading'  => 'A Pinnacle of Excellence in Education',
                        'subtext'  => 'ICC NITOP III Mankon — delivering the best GCE results in General and Technical Education for both Ordinary, Intermediate and Advanced Level over the past five years.',
                        'cta_text' => 'Enroll Today',
                        'cta_link' => '#programs',
                    ],
                    'about' => [
                        'text' => 'Imperial Comprehensive College (ICC NITOP III) is located at Azire Old Church, Mankon, Bamenda. We are committed to academic excellence and holistic student development, with 70% of our teachers being certified GCE Examiners. Our campus features a Computer Lab, Science Lab, Home Economics Lab, and a football field. We also run a Special Multi-Purpose Evening School from 3pm every working day.',
                    ],
                    'stats' => [
                        ['label' => 'GCE Pass Rate',      'value' => '95%+'],
                        ['label' => 'GCE Examiner Staff', 'value' => '70%'],
                        ['label' => 'Programs Offered',   'value' => '4'],
                        ['label' => 'Years of Excellence','value' => '5+'],
                    ],
                    'programs' => [
                        [
                            'name'  => 'Sciences',
                            'range' => 'Form 1 – Upper 6',
                            'fee'   => '65,000 XAF',
                            'icon'  => 'science',
                        ],
                        [
                            'name'  => 'Arts',
                            'range' => 'Form 1 – Upper 6',
                            'fee'   => '65,000 XAF',
                            'icon'  => 'arts',
                        ],
                        [
                            'name'  => 'Technical Commercial',
                            'range' => 'Form 1 – Upper 6',
                            'fee'   => '65,000 XAF',
                            'icon'  => 'commercial',
                        ],
                        [
                            'name'  => 'Technical Industrial',
                            'range' => 'Form 1 – Upper 6',
                            'fee'   => '70,000 XAF',
                            'icon'  => 'industrial',
                        ],
                    ],
                    'facilities' => [
                        'Computer Lab',
                        'Science Lab',
                        'Home Economics Lab',
                        'Football Field',
                        'Evening School (3pm daily)',
                    ],
                    'contact' => [
                        'phones' => [
                            '+237 677 345 785',
                            '+237 677 123 626',
                            '+237 654 209 673',
                            '+237 672 419 190',
                            '+237 673 013 421',
                        ],
                        'address' => 'Azire Old Church, Mankon, Bamenda',
                        'hours'   => 'Tuesday – Friday, Working Hours',
                    ],
                ],
                'meta_title'       => 'Imperial Comprehensive College — ICC NITOP III Mankon, Bamenda',
                'meta_description' => 'ICC NITOP III Mankon offers Sciences, Arts, Technical Commercial and Technical Industrial programs from Form 1 to Upper 6. Best GCE results in Bamenda.',
            ]
        );
    }
}