<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promotion extends Model
{
    protected $fillable = [
        'enrollment_id', 'annual_average', 'decision',
        'confirmed_by', 'confirmed_at', 'next_enrollment_id',
    ];

    protected function casts(): array
    {
        return [
            'annual_average' => 'decimal:2',
            'confirmed_at'   => 'datetime',
        ];
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(StudentEnrollment::class, 'enrollment_id');
    }

    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function nextEnrollment(): BelongsTo
    {
        return $this->belongsTo(StudentEnrollment::class, 'next_enrollment_id');
    }
}