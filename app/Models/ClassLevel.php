<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassLevel extends Model
{
    protected $fillable = ['name', 'order'];

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }
}