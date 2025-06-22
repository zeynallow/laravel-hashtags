<?php

namespace Zeynallow\Hashtags;

use InvalidArgumentException;

class HashtagExtractor
{
    /**
     * Parse text and extract hashtags and mentions
     */
    public function parse(string $text): array
    {
        if (empty($text)) {
            return [
                'hashtags' => [],
                'mentions' => [],
            ];
        }

        $hashtagPattern = config('laravel-hashtags.hashtag_pattern', '/#(\p{L}[\p{L}0-9-_]*)/u');
        $mentionPattern = config('laravel-hashtags.mention_pattern', '/@(\p{L}[\p{L}0-9-_]*)/u');

        // Validate patterns
        if (!$this->isValidRegex($hashtagPattern)) {
            throw new InvalidArgumentException('Invalid hashtag pattern in config');
        }

        if (!$this->isValidRegex($mentionPattern)) {
            throw new InvalidArgumentException('Invalid mention pattern in config');
        }

        preg_match_all($hashtagPattern, $text, $hashtags);
        preg_match_all($mentionPattern, $text, $mentions);

        return [
            'hashtags' => array_unique(array_filter($hashtags[1] ?? [])),
            'mentions' => array_unique(array_filter($mentions[1] ?? [])),
        ];
    }

    /**
     * Extract only hashtags from text
     */
    public function extractHashtags(string $text): array
    {
        $parsed = $this->parse($text);
        return $parsed['hashtags'];
    }

    /**
     * Extract only mentions from text
     */
    public function extractMentions(string $text): array
    {
        $parsed = $this->parse($text);
        return $parsed['mentions'];
    }

    /**
     * Check if text contains any hashtags
     */
    public function hasHashtags(string $text): bool
    {
        return !empty($this->extractHashtags($text));
    }

    /**
     * Check if text contains any mentions
     */
    public function hasMentions(string $text): bool
    {
        return !empty($this->extractMentions($text));
    }

    /**
     * Validate regex pattern
     */
    protected function isValidRegex(string $pattern): bool
    {
        return @preg_match($pattern, '') !== false;
    }
}
