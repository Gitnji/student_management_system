<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subject_coefficients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_level_id')->constrained()->cascadeOnDelete();
            $table->foreignId('stream_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('coefficient');
            $table->timestamps();

            $table->unique(['subject_id', 'class_level_id', 'stream_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_coefficients');
    }
};