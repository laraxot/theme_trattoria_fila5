@props(['href' => null, 'variant' => 'primary', 'type' => 'button'])
@php($classes = 'button button--'.$variant)
@if($href)<a {{ $attributes->merge(['class' => $classes])->except(['type']) }} href="{{ $href }}">{{ $slot }}</a>@else<button {{ $attributes->merge(['class' => $classes, 'type' => $type]) }}>{{ $slot }}</button>@endif
