<!doctype html>
<html lang="it" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#183b36">
    @vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Trattoria')
</head>
<body>
<a class="skip-link" href="#main-content">Vai al contenuto</a>
<x-navigation />
<main id="main-content" class="trattoria-main">{{ $slot }}</main>
<x-footer />
</body>
</html>
