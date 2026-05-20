<?php

use App\Models\Stream;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Stream::firstOrCreate(['name' => 'General']);
    }

    public function down(): void
    {
        //
    }
};
