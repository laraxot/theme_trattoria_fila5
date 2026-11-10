@props([
    'title' => null,
    'description' => null,
    'themeColor' => null,
])
<x-cms::head :title="$title" :description="$description" :theme-color="$themeColor" />
<body>
    <a class="skip-link" href="#main-content">{{ __('trattoria::nav.skip_to_content') }}</a>
    <x-section slug="header" />
    <main id="main-content" class="main">{{ $slot }}</main>
    <x-section slug="footer" />
    <x-cms::foot />
</body>
</html>
