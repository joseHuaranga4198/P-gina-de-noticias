<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'color', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function (Category $category) {
            if ($category->isDirty('name') && !$category->isDirty('slug')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }

    public function publishedNews(): HasMany
    {
        return $this->hasMany(News::class)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->orderByDesc('published_at');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
