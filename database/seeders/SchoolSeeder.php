<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::firstOrCreate(
            ['name' => 'Imperial Comprehensive College'],
            [
                'address' => 'Bamenda, Cameroon',
                'email'   => 'info@icc.cm',
                'phone'   => null,
                'logo'    => null,
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@icc.cm'],
            [
                'school_id'           => $school->id,
                'first_name'          => 'ICC',
                'last_name'           => 'Admin',
                'password'            => bcrypt('Admin@1234'),
                'role'                => 'admin',
                'must_change_password' => true,
                'is_active'           => true,
            ]
        );
    }
}