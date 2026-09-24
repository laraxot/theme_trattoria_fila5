@props(['name', 'description' => null, 'price' => null, 'image' => null, 'badge' => null])
<article class="menu-card">
    @if($image)<img class="menu-card__image" src="{{ $image }}" alt="{{ $name }}" loading="lazy">@else<div class="menu-card__image menu-card__image--placeholder" aria-hidden="true">✦</div>@endif
    <div class="menu-card__body"><div class="menu-card__heading"><h3>{{ $name }}</h3>@if($price)<span class="price">{{ $price }}</span>@endif</div>@if($description)<p>{{ $description }}</p>@endif @if($badge)<span class="badge">{{ $badge }}</span>@endif{{ $slot }}</div>
</article>
