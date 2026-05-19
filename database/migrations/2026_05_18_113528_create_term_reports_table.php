<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('term_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('student_enrollments')->cascadeOnDelete();
            $table->foreignId('term_id')->constrained('terms')->cascadeOnDelete();
            $table->decimal('seq1_average', 5, 2)->nullable();
            $table->decimal('seq2_average', 5, 2)->nullable();
            $table->decimal('term_average', 5, 2)->nullable();
            $table->decimal('total_coefficient_points', 8, 2)->nullable();
            $table->unsignedSmallInteger('position_in_class')->nullable();
            $table->unsignedSmallInteger('class_size')->nullable();
            $table->decimal('class_average', 5, 2)->nullable();
            $table->decimal('highest_in_class', 5, 2)->nullable();
            $table->enum('conduct_rating', ['excellent', 'good', 'average', 'poor'])->nullable();
            $table->text('principal_remark')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            $table->unique(['enrollment_id', 'term_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('term_reports');
    }
};