@props([
    'title' => null,
    'description' => null,
    'themeColor' => null,
])
<x-layouts.main :title="$title" :description="$description" :theme-color="$themeColor">
    {{ $slot }}
</x-layouts.main>
