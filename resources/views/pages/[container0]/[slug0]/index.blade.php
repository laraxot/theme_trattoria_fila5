<?php

declare(strict_types=1);

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use Livewire\Volt\Component;
use Modules\Cms\Actions\ResolvePageAction;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;
use Modules\Cms\Models\Page as PageModel;

name('container0.view');
middleware(PageSlugMiddleware::class);

new class extends Component {
    public string $container0 = '';

    public string $slug0 = '';

    public string $pageSlug = '';

    /** @var array<string, mixed> */
    public array $data = [];

    public function mount(string $container0, string $slug0 = ''): void
    {
        $this->container0 = $container0;
        $this->slug0 = $slug0;

        file_put_contents(storage_path('logs/dbg404.log'), 'mount2 container0='.$container0.' slug0='.$slug0.' url='.request()->fullUrl()."\n", FILE_APPEND);
        $resolved = app(ResolvePageAction::class)->execute($container0, $slug0);
        file_put_contents(storage_path('logs/dbg404.log'), '  resolved pageSlug='.$resolved->pageSlug.' item='.(is_object($resolved->item)?get_class($resolved->item):'null').' slugExists='.var_export(PageModel::query()->where('slug',$resolved->pageSlug)->exists(),true).' count='.PageModel::count()."\n", FILE_APPEND);

        // Pagina CMS inesistente (ne' `contenitore.slug` ne' `contenitore.view`): 404 invece di una pagina vuota.
        abort_if($resolved->item === null && ! PageModel::query()->where('slug', $resolved->pageSlug)->exists(), 404);

        $this->pageSlug = $resolved->pageSlug;

        $item = $resolved->item;

        $this->data = [
            'container0' => $container0,
            'slug0' => $slug0,
            'slug' => $slug0,
            'item' => $item,
        ];
    }
};
?>

<x-layouts.app>
    @volt('container0.view')
    <div class="page-content content" data-slug="{{ $pageSlug }}" data-side="content">
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    </div>
    @endvolt
</x-layouts.app>
