<?php

namespace Zeynallow\Hashtags\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Builder;

class Hashtag extends Model
{
    protected $fillable = ['name'];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'name';
    }

    /**
     * Get all models that use this hashtag
     */
    public function taggables(): MorphToMany
    {
        return $this->morphedByMany(
            config('laravel-hashtags.taggable_models', []),
            'taggable',
            'hashtaggables'
        );
    }

    /**
     * Scope to search hashtags by name
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    /**
     * Scope to get trending hashtags
     */
    public function scopeTrending(Builder $query, int $limit = 10): Builder
    {
        return $query->withCount('taggables')
            ->orderBy('taggables_count', 'desc')
            ->limit($limit);
    }

    /**
     * Get hashtag name with # prefix
     */
    public function getDisplayNameAttribute(): string
    {
        return '#' . $this->name;
    }

    /**
     * Get hashtag URL
     */
    public function getUrlAttribute(): string
    {
        $prefix = config('laravel-hashtags.hashtag_url_prefix', '/tags/');
        return $prefix . $this->name;
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Ensure name is lowercase
        static::saving(function ($hashtag) {
            $hashtag->name = strtolower($hashtag->name);
        });
    }
}
