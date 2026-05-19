<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TermReport extends Model
{
    protected $fillable = [
        'enrollment_id', 'term_id',
        'seq1_average', 'seq2_average',
        'term_average', 'total_coefficient_points',
        'position_in_class', 'class_size',
        'class_average', 'highest_in_class',
        'conduct_rating', 'principal_remark',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'seq1_average'             => 'decimal:2',
            'seq2_average'             => 'decimal:2',
            'term_average'             => 'decimal:2',
            'total_coefficient_points' => 'decimal:2',
            'class_average'            => 'decimal:2',
            'highest_in_class'         => 'decimal:2',
            'generated_at'             => 'datetime',
        ];
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(StudentEnrollment::class, 'enrollment_id');
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    public function getRemarkLetterAttribute(): string
    {
        $avg = (float) $this->term_average;

        return match(true) {
            $avg >= 18  => 'A',
            $avg >= 15  => 'B',
            $avg >= 12  => 'C',
            $avg >= 10  => 'D',
            default     => 'E',
        };
    }

    public function getConductRatingAutoAttribute(): string
    {
        $avg = (float) $this->term_average;

        return match(true) {
            $avg >= 16  => 'excellent',
            $avg >= 12  => 'good',
            $avg >= 10  => 'average',
            default     => 'poor',
        };
    }
}