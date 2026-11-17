{{--
    Badge di un allergene o di un regime alimentare (EU 1169/2011).
    Variabile: $case (Modules\Restaurant\Enums\AllergenEnum).
    Icona SVG del modulo Restaurant (`restaurant-{icon}`), colore e sigla dall'enum:
    il colore non e' l'unico segnale, restano visibili icona e sigla.
    La etichetta completa NON si ripete nel badge: e' nella legenda
    (`menu/allergen-legend`) e nel tooltip `title` (etichetta + descrizione).
--}}
<span class="allergen-badge" style="--badge-color: {{ $case->color() }}" title="{{ $case->getLabel() }} — {{ $case->getDescription() }}">
    <span class="allergen-badge__icon" aria-hidden="true">@svg('restaurant-'.$case->icon(), 'allergen-icon', ['width' => 14, 'height' => 14])</span>
    <abbr class="allergen-badge__code" title="{{ $case->getLabel() }}">{{ $case->code() }}</abbr>
</span>
