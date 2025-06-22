<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Hashtag Regex Pattern
    |--------------------------------------------------------------------------
    |
    | This pattern is used to extract hashtags from text.
    | You can modify this if you want to support different characters.
    | Default: /#(\p{L}[\p{L}0-9-_]*)/u
    |
    */

    'hashtag_pattern' => '/#(\p{L}[\p{L}0-9-_]*)/u',

    /*
    |--------------------------------------------------------------------------
    | Mention Regex Pattern
    |--------------------------------------------------------------------------
    |
    | This pattern is used to extract mentions like @username.
    | Default: /@(\p{L}[\p{L}0-9-_]*)/u
    |
    */

    'mention_pattern' => '/@(\p{L}[\p{L}0-9-_]*)/u',

    /*
    |--------------------------------------------------------------------------
    | Hashtag URL Prefix
    |--------------------------------------------------------------------------
    |
    | The URL used when converting #tags into clickable links.
    | Example: #Laravel => <a href="/tags/Laravel">#Laravel</a>
    |
    */

    'hashtag_url_prefix' => '/tags/',

    /*
    |--------------------------------------------------------------------------
    | Mention URL Prefix
    |--------------------------------------------------------------------------
    |
    | The URL used when converting @username into clickable links.
    | Example: @zeynal => <a href="/users/zeynal">@zeynal</a>
    |
    */

    'mention_url_prefix' => '/users/',

    /*
    |--------------------------------------------------------------------------
    | Hashtag CSS Class
    |--------------------------------------------------------------------------
    |
    | CSS class applied to hashtag links in tagify() function.
    |
    */

    'hashtag_css_class' => 'hashtag-link',

    /*
    |--------------------------------------------------------------------------
    | Mention CSS Class
    |--------------------------------------------------------------------------
    |
    | CSS class applied to mention links in tagify() function.
    |
    */

    'mention_css_class' => 'mention-link',

    /*
    |--------------------------------------------------------------------------
    | Enable Auto Linking in tagify()
    |--------------------------------------------------------------------------
    |
    | If false, tagify() will just return original text without links.
    |
    */

    'enable_tagify_links' => true,

    /*
    |--------------------------------------------------------------------------
    | Taggable Models
    |--------------------------------------------------------------------------
    |
    | Array of model classes that can use hashtags.
    | This is used for the morphedByMany relationship.
    |
    */

    'taggable_models' => [
        // 'App\Models\Post',
        // 'App\Models\Comment',
        // 'App\Models\Article',
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto Attach on Model Events
    |--------------------------------------------------------------------------
    |
    | Automatically attach hashtags when model is saved/updated.
    | Set to false to disable automatic attachment.
    |
    */

    'auto_attach_on_save' => false,

    /*
    |--------------------------------------------------------------------------
    | Text Field for Auto Attachment
    |--------------------------------------------------------------------------
    |
    | The field name to scan for hashtags when auto_attach_on_save is true.
    |
    */

    'auto_attach_text_field' => 'body',

    /*
    |--------------------------------------------------------------------------
    | Cache Trending Hashtags
    |--------------------------------------------------------------------------
    |
    | Cache trending hashtags for better performance.
    | Set to false to disable caching.
    |
    */

    'cache_trending' => true,

    /*
    |--------------------------------------------------------------------------
    | Trending Cache TTL (seconds)
    |--------------------------------------------------------------------------
    |
    | How long to cache trending hashtags in seconds.
    |
    */

    'trending_cache_ttl' => 3600, // 1 hour

];
