<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = ['school_id', 'name', 'code'];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function coefficients(): HasMany
    {
        return $this->hasMany(SubjectCoefficient::class);
    }

    public function teacherAssignments(): HasMany
    {
        return $this->hasMany(TeacherAssignment::class);
    }

    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }
}