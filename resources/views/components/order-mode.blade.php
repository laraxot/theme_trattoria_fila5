@props(['icon', 'title', 'description', 'href' => '#'])
<a class="mode-card" href="{{ $href }}"><span class="mode-card__icon" aria-hidden="true">{{ $icon }}</span><span><strong>{{ $title }}</strong><small>{{ $description }}</small></span><span class="mode-card__arrow" aria-hidden="true">→</span></a>
