<?php

namespace Zeynallow\Hashtags\Tests;

use Zeynallow\Hashtags\HashtagExtractor;

class HashtagExtractorTest extends TestCase
{
    protected HashtagExtractor $extractor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->extractor = new HashtagExtractor();
    }

    /** @test */
    public function it_can_extract_hashtags_from_text()
    {
        $text = "This is a post about #Laravel and #PHP development";
        $result = $this->extractor->parse($text);

        $this->assertEquals(['laravel', 'php'], $result['hashtags']);
        $this->assertEquals([], $result['mentions']);
    }

    /** @test */
    public function it_can_extract_mentions_from_text()
    {
        $text = "Hello @zeynallow and @john_doe, how are you?";
        $result = $this->extractor->parse($text);

        $this->assertEquals([], $result['hashtags']);
        $this->assertEquals(['zeynallow', 'john_doe'], $result['mentions']);
    }

    /** @test */
    public function it_can_extract_both_hashtags_and_mentions()
    {
        $text = "Check out this #Laravel tutorial by @zeynallow #PHP";
        $result = $this->extractor->parse($text);

        $this->assertEquals(['laravel', 'php'], $result['hashtags']);
        $this->assertEquals(['zeynallow'], $result['mentions']);
    }

    /** @test */
    public function it_removes_duplicate_hashtags()
    {
        $text = "I love #Laravel and #laravel and #LARAVEL";
        $result = $this->extractor->parse($text);

        $this->assertEquals(['laravel'], $result['hashtags']);
    }

    /** @test */
    public function it_handles_empty_text()
    {
        $result = $this->extractor->parse('');
        
        $this->assertEquals([], $result['hashtags']);
        $this->assertEquals([], $result['mentions']);
    }

    /** @test */
    public function it_can_extract_only_hashtags()
    {
        $text = "This is about #Laravel and @zeynallow";
        $hashtags = $this->extractor->extractHashtags($text);

        $this->assertEquals(['laravel'], $hashtags);
    }

    /** @test */
    public function it_can_extract_only_mentions()
    {
        $text = "This is about #Laravel and @zeynallow";
        $mentions = $this->extractor->extractMentions($text);

        $this->assertEquals(['zeynallow'], $mentions);
    }

    /** @test */
    public function it_can_check_if_text_has_hashtags()
    {
        $this->assertTrue($this->extractor->hasHashtags("Text with #hashtag"));
        $this->assertFalse($this->extractor->hasHashtags("Text without hashtags"));
    }

    /** @test */
    public function it_can_check_if_text_has_mentions()
    {
        $this->assertTrue($this->extractor->hasMentions("Text with @mention"));
        $this->assertFalse($this->extractor->hasMentions("Text without mentions"));
    }
} 