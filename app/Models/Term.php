<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Term extends Model
{
    protected $fillable = [
        'academic_year_id', 'term', 'start_date', 'end_date', 'next_term_begins',
    ];

    protected function casts(): array
    {
        return [
            'start_date'       => 'date',
            'end_date'         => 'date',
            'next_term_begins' => 'date',
        ];
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function sequences(): HasMany
    {
        return $this->hasMany(Sequence::class);
    }

    public function termReports(): HasMany
    {
        return $this->hasMany(TermReport::class);
    }

    public function getTermNameAttribute(): string
    {
        return match($this->term) {
            1 => 'First Term',
            2 => 'Second Term',
            3 => 'Third Term',
            default => "Term {$this->term}",
        };
    }
}