<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('terms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('term');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('next_term_begins')->nullable();
            $table->timestamps();

            $table->unique(['academic_year_id', 'term']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('terms');
    }
};