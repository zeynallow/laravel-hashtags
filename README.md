# Laravel Hashtags

Laravel package for hashtag and mention analysis, management and rendering. This package is used to extract `#hashtags` and `@mentions` from any text field, store them in the database and convert them to HTML links.

---

## 🚀 Features

- ✅ Hashtag and mention extraction (`#Laravel`, `@zeynallow`)
- ✅ Morphable relationship support (`taggables`)
- ✅ Blade helpers and directives
- ✅ Secure HTML rendering
- ✅ Trending hashtags
- ✅ Search functionality
- ✅ Cache support
- ✅ Eloquent scopes
- ✅ Service layer architecture
- ✅ Configuration options

---

## 📦 Installation

### Add via Composer

```bash
composer require zeynallow/laravel-hashtags
```

### Configuration and migration

```bash
# Publish config file
php artisan vendor:publish --tag=laravel-hashtags-config

# Publish migrations
php artisan vendor:publish --tag=laravel-hashtags-migrations

# Run migrations
php artisan migrate
```

---

## 🧠 Usage

### 1. Add Trait to Your Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Zeynallow\Hashtags\Traits\HasTags;

class Post extends Model
{
    use HasTags;

    protected $fillable = ['title', 'body'];
}
```

### 2. Automatically attach hashtags

```php
$post = Post::create([
    'title' => 'About Laravel',
    'body' => 'This post is about #Laravel and #PHP. Thank you @zeynallow!'
]);

// Automatically attach hashtags
$post->attachHashtags();

// Now hashtags are stored in database and linked to the post
$post->hashtags; // Returns Hashtag model collection
```

### 3. Extract hashtags programmatically

```php
use Zeynallow\Hashtags\HashtagExtractor;

$extractor = new HashtagExtractor();

$parsed = $extractor->parse("Testing #laravel @zeynal #php");

// Result:
[
    'hashtags' => ['laravel', 'php'],
    'mentions' => ['zeynal'],
]
```

### 4. Render in Blade with links

```blade
{{-- Simple usage --}}
{!! tagify($post->body) !!}

{{-- Without links --}}
{!! tagify($post->body, false) !!}

{{-- Using Blade directives --}}
@hashtags($post->body)
@hashtagLinks($post->body)
```

### 5. Helper functions

```php
// Extract hashtags
$hashtags = extract_hashtags($text);

// Extract mentions
$mentions = extract_mentions($text);

// Check if text has hashtags
if (has_hashtags($text)) {
    // Has hashtags
}

// Check if text has mentions
if (has_mentions($text)) {
    // Has mentions
}
```

---

## 🔧 API Documentation

### HashtagExtractor

```php
use Zeynallow\Hashtags\HashtagExtractor;

$extractor = new HashtagExtractor();

// Main parse method
$result = $extractor->parse($text);

// Extract only hashtags
$hashtags = $extractor->extractHashtags($text);

// Extract only mentions
$mentions = $extractor->extractMentions($text);

// Check if text has hashtags
$hasHashtags = $extractor->hasHashtags($text);

// Check if text has mentions
$hasMentions = $extractor->hasMentions($text);
```

### HashtagService

```php
use Zeynallow\Hashtags\Services\HashtagService;

$service = app(HashtagService::class);

// Attach hashtags to model
$service->attachToModel($post, 'body');

// Get trending hashtags
$trending = $service->getTrending(10);

// Search hashtags
$results = $service->search('laravel', 5);
```

### HasTags Trait

```php
// Attach hashtags to model
$post->attachHashtags('body');

// Remove all hashtags
$post->detachAllHashtags();

// Sync hashtags (remove old ones, add new ones)
$post->syncHashtags('body');

// Check if model has specific hashtag
if ($post->hasHashtag('laravel')) {
    // Has Laravel hashtag
}

// Get hashtag names
$names = $post->getHashtagNames(); // ['laravel', 'php']

// Get hashtag display names
$displayNames = $post->getHashtagDisplayNames(); // ['#laravel', '#php']
```

### Eloquent Scopes

```php
// Find posts with specific hashtag
$posts = Post::withHashtag('laravel')->get();

// Find posts with any of the given hashtags
$posts = Post::withAnyHashtag(['laravel', 'php'])->get();

// Find posts with all of the given hashtags
$posts = Post::withAllHashtags(['laravel', 'php'])->get();
```

---

## ⚙️ Configuration

You can configure the following options in `config/laravel-hashtags.php`:

```php
return [
    // Regex patterns
    'hashtag_pattern' => '/#(\p{L}[\p{L}0-9-_]*)/u',
    'mention_pattern' => '/@(\p{L}[\p{L}0-9-_]*)/u',
    
    // URL prefixes
    'hashtag_url_prefix' => '/tags/',
    'mention_url_prefix' => '/users/',
    
    // CSS classes
    'hashtag_css_class' => 'hashtag-link',
    'mention_css_class' => 'mention-link',
    
    // Auto linking
    'enable_tagify_links' => true,
    
    // Taggable models
    'taggable_models' => [
        'App\Models\Post',
        'App\Models\Comment',
    ],
    
    // Auto attach
    'auto_attach_on_save' => false,
    'auto_attach_text_field' => 'body',
    
    // Cache
    'cache_trending' => true,
    'trending_cache_ttl' => 3600,
];
```

---

## 🎨 CSS Styling

CSS for hashtag and mention links:

```css
.hashtag-link {
    color: #1da1f2;
    text-decoration: none;
    font-weight: 500;
}

.hashtag-link:hover {
    text-decoration: underline;
}

.mention-link {
    color: #1da1f2;
    text-decoration: none;
    font-weight: 500;
}

.mention-link:hover {
    text-decoration: underline;
}
```

---

## 🧪 Testing

```bash
# Run tests
composer test
```

---

## 📝 License

This package is released under the MIT license. See [LICENSE](LICENSE) file for details.

---

## 🤝 Contributing

To contribute:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Create a Pull Request

---

## 📞 Contact

- **Author:** Zeynallow
- **Email:** zeynaloffnet@gmail.com
- **GitHub:** [@zeynallow](https://github.com/zeynallow)

---

## 🔄 Updates

### v1.0.0
- Initial release with full feature set
- Service layer architecture
- Better error handling
- Cache support
- Blade directives
- More helper functions
- Performance improvements