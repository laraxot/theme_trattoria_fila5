@props([
    'title' => null,
    'description' => null,
    'themeColor' => null,
])
{{--
    Layout pubblico del tema. Doctype e <html lang> qui: senza doctype il browser va in quirks mode
    (modello dei box diverso) e senza lang gli screen reader leggono nella lingua sbagliata.
    :panel-assets="false": il sito non usa componenti Filament, quindi niente loro CSS/JS (velocita' su telefono).
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<x-cms::head :title="$title" :description="$description" :theme-color="$themeColor" :panel-assets="false" />
<body>
    <a class="skip-link" href="#main-content">{{ __('pub_theme::nav.skip_to_content') }}</a>
    <x-section slug="header" />
    <main id="main-content" class="main">{{ $slot }}</main>
    <x-section slug="footer" />
    <x-cms::foot :panel-assets="false" />
</body>
</html>
