<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    protected $fillable = [
        'school_id', 'slug', 'title', 'content',
        'meta_title', 'meta_description', 'og_image',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    // App/Models/Page.php
public function updateContent(array $newData): void
{
    $current = $this->content ?? [];
    $this->content = array_merge_recursive($current, $newData);
    $this->save();
}
}