<?php

use Zeynallow\Hashtags\HashtagExtractor;

if (!function_exists('tagify')) {
    /**
     * Convert hashtags and mentions in text to clickable links
     */
    function tagify(string $text, bool $withLinks = true): string
    {
        if (empty($text)) {
            return $text;
        }

        if (!$withLinks || !config('laravel-hashtags.enable_tagify_links', true)) {
            return $text;
        }

        $extractor = new HashtagExtractor();
        $parsed = $extractor->parse($text);

        $hashtagUrl = config('laravel-hashtags.hashtag_url_prefix', '/tags/');
        $mentionUrl = config('laravel-hashtags.mention_url_prefix', '/users/');
        $hashtagClass = config('laravel-hashtags.hashtag_css_class', 'hashtag-link');
        $mentionClass = config('laravel-hashtags.mention_css_class', 'mention-link');

        // Process hashtags
        foreach ($parsed['hashtags'] as $hashtag) {
            $escapedHashtag = preg_quote($hashtag, '/');
            $replacement = sprintf(
                '<a href="%s" class="%s" data-hashtag="%s">#%s</a>',
                e($hashtagUrl . $hashtag),
                e($hashtagClass),
                e($hashtag),
                e($hashtag)
            );
            
            $text = preg_replace(
                "/(?<!\\w)#{$escapedHashtag}\\b/u",
                $replacement,
                $text
            );
        }

        // Process mentions
        foreach ($parsed['mentions'] as $mention) {
            $escapedMention = preg_quote($mention, '/');
            $replacement = sprintf(
                '<a href="%s" class="%s" data-mention="%s">@%s</a>',
                e($mentionUrl . $mention),
                e($mentionClass),
                e($mention),
                e($mention)
            );
            
            $text = preg_replace(
                "/(?<!\\w)@{$escapedMention}\\b/u",
                $replacement,
                $text
            );
        }

        return $text;
    }
}

if (!function_exists('extract_hashtags')) {
    /**
     * Extract hashtags from text
     */
    function extract_hashtags(string $text): array
    {
        $extractor = new HashtagExtractor();
        return $extractor->extractHashtags($text);
    }
}

if (!function_exists('extract_mentions')) {
    /**
     * Extract mentions from text
     */
    function extract_mentions(string $text): array
    {
        $extractor = new HashtagExtractor();
        return $extractor->extractMentions($text);
    }
}

if (!function_exists('has_hashtags')) {
    /**
     * Check if text contains hashtags
     */
    function has_hashtags(string $text): bool
    {
        $extractor = new HashtagExtractor();
        return $extractor->hasHashtags($text);
    }
}

if (!function_exists('has_mentions')) {
    /**
     * Check if text contains mentions
     */
    function has_mentions(string $text): bool
    {
        $extractor = new HashtagExtractor();
        return $extractor->hasMentions($text);
    }
}

