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
                'address' => 'Azire Old Church, Mankon, Bamenda, North-West Region',
                'email'   => 'contact@icc.edu.cm',
                'phone'   => '+237 677 345 785',
                'logo'    => null,
            ]
        );

        // Update if already exists
        $school->update([
            'address' => 'Azire Old Church, Mankon, Bamenda, North-West Region',
            'email'   => 'contact@icc.edu.cm',
            'phone'   => '+237 677 345 785',
        ]);

        User::firstOrCreate(
            ['email' => 'admin@icc.cm'],
            [
                'school_id'            => $school->id,
                'first_name'           => 'ICC',
                'last_name'            => 'Admin',
                'password'             => bcrypt('Admin@1234'),
                'role'                 => 'admin',
                'must_change_password' => true,
                'is_active'            => true,
            ]
        );
    }
}