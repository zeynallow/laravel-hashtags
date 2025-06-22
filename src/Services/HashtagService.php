<?php

namespace Zeynallow\Hashtags\Services;

use Zeynallow\Hashtags\Models\Hashtag;
use Zeynallow\Hashtags\HashtagExtractor;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class HashtagService
{
    protected HashtagExtractor $extractor;

    public function __construct(HashtagExtractor $extractor)
    {
        $this->extractor = $extractor;
    }

    /**
     * Extract hashtags from text and return as collection
     */
    public function extractHashtags(string $text): Collection
    {
        $parsed = $this->extractor->parse($text);
        
        return collect($parsed['hashtags'])->map(function ($name) {
            return Hashtag::firstOrCreate(['name' => $name]);
        });
    }

    /**
     * Extract mentions from text
     */
    public function extractMentions(string $text): Collection
    {
        $parsed = $this->extractor->parse($text);
        
        return collect($parsed['mentions']);
    }

    /**
     * Attach hashtags to a model
     */
    public function attachToModel(Model $model, string $textField = 'body'): void
    {
        if (!method_exists($model, 'hashtags')) {
            throw new \InvalidArgumentException('Model must use HasTags trait');
        }

        $hashtags = $this->extractHashtags($model->{$textField});
        
        $model->hashtags()->syncWithoutDetaching($hashtags->pluck('id'));
    }

    /**
     * Get trending hashtags
     */
    public function getTrending(int $limit = 10): Collection
    {
        return Hashtag::withCount('taggables')
            ->orderBy('taggables_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Search hashtags by name
     */
    public function search(string $query, int $limit = 10): Collection
    {
        return Hashtag::where('name', 'like', "%{$query}%")
            ->limit($limit)
            ->get();
    }
} 