<?php

declare(strict_types=1);

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use Livewire\Volt\Component;
use Modules\Cms\Actions\ResolvePageAction;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;
use Modules\Cms\Models\Page as PageModel;

name('container0.container1.view');
middleware(PageSlugMiddleware::class);

/*
 * Route a 3 segmenti: /{container0}/{slug0}/{container1}
 * (es. /restaurants/{ristorante}/menu, /restaurants/{ristorante}/book).
 *
 * - `menu`: il record del ristorante viene risolto da ResolvePageAction e passato
 *   come `item` al blocco CMS del menu (che esige un PublicRestaurant).
 * - `book`: pagina di prenotazione del ristorante (slug0 non ancora verificato).
 * Il nome della rotta e' generico: i link di scheda e menu usano `container0.container1.view`.
 */

new class extends Component {
    public string $container0 = '';
    public string $slug0 = '';
    public string $container1 = '';

    public string $pageSlug = '';

    /** @var array<string, mixed> */
    public array $pageData = [];

    public function mount(string $container0, string $slug0, string $container1): void
    {
        $this->container0 = $container0;
        $this->slug0 = $slug0;
        $this->container1 = $container1;

        $this->pageData = [
            'container0' => $container0,
            'slug0' => $slug0,
            'container1' => $container1,
            'restaurantSlug' => $slug0,
        ];

        if ($container1 === 'menu') {
            $this->mountMenu($container0, $slug0);

            return;
        }

        // Costruisci lo slug della pagina: restaurants.book
        $this->pageSlug = $container0.'.'.$container1;
    }

    private function mountMenu(string $container0, string $slug0): void
    {
        $resolved = app(ResolvePageAction::class)->execute($container0, $slug0);

        $subSlug = $container0.'.menu';
        $this->pageSlug = PageModel::where('slug', $subSlug)->exists()
            ? $subSlug
            : $resolved->pageSlug;

        $this->pageData['item'] = $resolved->item;
    }
};
?>

<x-layouts.app>
    @volt('container0.container1.view')
    <div class="page-content content" data-slug="{{ $pageSlug }}" data-side="content">
        <x-page side="content" :slug="$pageSlug" :data="$pageData" />
    </div>
    @endvolt
</x-layouts.app>