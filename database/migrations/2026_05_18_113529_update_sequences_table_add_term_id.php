<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sequences', function (Blueprint $table) {
            $table->dropColumn(['term', 'sequence_in_term']);
            $table->foreignId('term_id')->after('academic_year_id')->constrained('terms')->cascadeOnDelete();
            $table->unsignedTinyInteger('sequence_number')->after('term_id')->comment('1 or 2 within the term');
        });
    }

    public function down(): void
    {
        Schema::table('sequences', function (Blueprint $table) {
            $table->dropForeign(['term_id']);
            $table->dropColumn(['term_id', 'sequence_number']);
            $table->unsignedTinyInteger('term');
            $table->unsignedTinyInteger('sequence_in_term');
        });
    }
};