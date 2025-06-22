<?php

namespace Zeynallow\Hashtags\Traits;

use Zeynallow\Hashtags\Models\Hashtag;
use Zeynallow\Hashtags\Services\HashtagService;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

trait HasTags
{
    /**
     * Get hashtags relationship
     */
    public function hashtags(): MorphToMany
    {
        return $this->morphToMany(Hashtag::class, 'taggable', 'hashtaggables');
    }

    /**
     * Attach hashtags from text field
     */
    public function attachHashtags(string $textField = 'body'): void
    {
        if (!isset($this->{$textField})) {
            throw new \InvalidArgumentException("Field '{$textField}' does not exist on model");
        }

        $service = app(HashtagService::class);
        $service->attachToModel($this, $textField);
    }

    /**
     * Detach all hashtags
     */
    public function detachAllHashtags(): void
    {
        $this->hashtags()->detach();
    }

    /**
     * Sync hashtags from text field
     */
    public function syncHashtags(string $textField = 'body'): void
    {
        if (!isset($this->{$textField})) {
            throw new \InvalidArgumentException("Field '{$textField}' does not exist on model");
        }

        $service = app(HashtagService::class);
        $hashtags = $service->extractHashtags($this->{$textField});
        
        $this->hashtags()->sync($hashtags->pluck('id'));
    }

    /**
     * Check if model has specific hashtag
     */
    public function hasHashtag(string $hashtagName): bool
    {
        return $this->hashtags()->where('name', strtolower($hashtagName))->exists();
    }

    /**
     * Get hashtag names as array
     */
    public function getHashtagNames(): array
    {
        return $this->hashtags()->pluck('name')->toArray();
    }

    /**
     * Get hashtag display names (with # prefix)
     */
    public function getHashtagDisplayNames(): array
    {
        return $this->hashtags()->get()->pluck('display_name')->toArray();
    }

    /**
     * Scope to find models with specific hashtag
     */
    public function scopeWithHashtag($query, string $hashtagName)
    {
        return $query->whereHas('hashtags', function ($q) use ($hashtagName) {
            $q->where('name', strtolower($hashtagName));
        });
    }

    /**
     * Scope to find models with any of the given hashtags
     */
    public function scopeWithAnyHashtag($query, array $hashtagNames)
    {
        return $query->whereHas('hashtags', function ($q) use ($hashtagNames) {
            $q->whereIn('name', array_map('strtolower', $hashtagNames));
        });
    }

    /**
     * Scope to find models with all of the given hashtags
     */
    public function scopeWithAllHashtags($query, array $hashtagNames)
    {
        foreach ($hashtagNames as $hashtagName) {
            $query->withHashtag($hashtagName);
        }
        
        return $query;
    }
}
