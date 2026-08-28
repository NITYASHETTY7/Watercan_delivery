<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Basic SEO Meta Tags -->
<meta name="description"
    content="@isset($meta_description){{ @$meta_description ?? '' }}@else{{ getSeoData('seo_description')->description ?? getSetting('seo_meta_description') }}@endisset">
<!-- Short page summary for search engines -->
<meta name="keywords"
    content="@isset($meta_keywords){{ @$meta_keywords ?? '' }}@else{{ getSeoData('seo_keywords')->keyword ?? '' }}@endisset">
<meta name="subject"
    content="@isset($meta_motto){{ @$meta_motto ?? '' }}@else{{ getSeoData('seo_motto')->description ?? '' }}@endisset">
<meta name="copyright" content="{{ env('APP_NAME') }}">
<meta name="language" content="IN">
<meta name="robots" content="index,follow">
<meta name="abstract"
    content="@isset($meta_abstract){{ @$meta_abstract ?? '' }}@else{{ getSeoData('seo_abstract')->description ?? '' }}@endisset">
<meta name="topic" content="Business">
<meta name="summary"
    content="@isset($meta_motto){{ @$meta_motto ?? '' }}@else{{ getSeoData('seo_motto')->description ?? '' }}@endisset">
<meta name="Classification" content="Business">

<!-- Author and Ownership Meta Tags -->
<meta name="author"
    content="@isset($meta_author_name){{ @$meta_author_name ?? '' }}@else{{ getSeoData('seo_author')->title ?? '' }}@endisset">
<meta name="designer" content="Book My Water">
<meta name="reply-to"
    content="@isset($meta_author_name){{ @$meta_author_name ?? '' }}@else{{ getSeoData('seo_reply_to')->description ?? '' }}@endisset">
<meta name="owner"
    content="@isset($meta_owner){{ @$meta_owner ?? '' }}@else{{ getSeoData('seo_owner')->description ?? '' }}@endisset">

<!-- URL and Navigation Meta Tags -->
<meta name="url" content="{{ url()->current() }}">
<meta name="revisit-after" content="7 days">
<meta name="expires" content="never">
<meta name="coverage" content="Worldwide">
<meta name="distribution" content="Global">

<!-- Social Media Optimization Meta Tags -->
<meta name="og:title"
    content="@isset($meta_title){{ @$meta_title ?? '' }}@else{{ getSeoData('og_title')->title ?? '' }}@endisset">
<meta name="og:type"
    content="@isset($meta_motto){{ @$meta_motto ?? '' }}@else{{ getSeoData('og_type')->description ?? '' }}@endisset">
<meta name="og:url" content="{{ url()->current() }}">
<meta name="og:image"
    content="@isset($meta_img){{ @$meta_img ?? '' }}@else{{ getSeoData('og_image')->description ?? '' }}@endisset">
<meta name="og:site_name" content="{{ env('APP_NAME') }}">
<meta name="og:description"
    content="@isset($meta_description){{ @$meta_description ?? '' }}@else{{ getSeoData('og_description')->description ?? getSetting('seo_meta_description') }}@endisset">

<!-- Twitter Card Meta Tags -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:title"
    content="@isset($meta_title){{ @$meta_title ?? '' }}@else{{ getSeoData('twitter_title')->title ?? '' }}@endisset">
<meta property="twitter:description"
    content="@isset($meta_description){{ @$meta_description ?? '' }}@else{{ getSeoData('twitter_description')->description ?? getSetting('seo_meta_description') }}@endisset">
<meta property="twitter:image"
    content="@isset($meta_img){{ @$meta_img ?? '' }}@else{{ getSeoData('twitter_image')->description ?? '' }}@endisset">

<!-- Responsive Design and Accessibility Meta Tags -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color"
    content="@isset($theme_color){{ @$theme_color ?? '' }}@else{{ getSeoData('theme_color')->description ?? '#ffffff' }}@endisset">
<meta name="rating" content="general">

<!-- Structured Data for SEO -->
<meta property="article:published_time"
    content="@isset($published_time){{ @$published_time ?? '' }}@else{{ getSeoData('published_time')->description ?? '' }}@endisset">
<meta property="article:modified_time"
    content="@isset($modified_time){{ @$modified_time ?? '' }}@else{{ getSeoData('modified_time')->description ?? '' }}@endisset">
<meta property="og:locale" content="en_US">
<meta property="og:updated_time"
    content="@isset($modified_time){{ @$modified_time ?? '' }}@else{{ getSeoData('updated_time')->description ?? '' }}@endisset">

<!-- Site Verification for Search Engines -->
<meta name="google-site-verification"
    content="@isset($google_site_verification){{ @$google_site_verification ?? '' }}@else{{ getSeoData('google_site_verification')->description ?? '' }}@endisset">
<meta name="yandex-verification"
    content="@isset($yandex_verification){{ @$yandex_verification ?? '' }}@else{{ getSeoData('yandex_verification')->description ?? '' }}@endisset">
<meta name="msvalidate.01"
    content="@isset($bing_verification){{ @$bing_verification ?? '' }}@else{{ getSeoData('bing_verification')->description ?? '' }}@endisset">

<!-- Branding and App Integration -->
<meta name="msapplication-TileColor"
    content="@isset($theme_color){{ @$theme_color ?? '' }}@else{{ getSeoData('tile_color')->description ?? '#ffffff' }}@endisset">
<meta name="application-name" content="{{ env('APP_NAME') }}">

<!-- Category and Classification Meta Tags -->
<meta name="category" content="Business">
