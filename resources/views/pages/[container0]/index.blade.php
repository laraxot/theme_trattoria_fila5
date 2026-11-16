<?php

declare(strict_types=1);

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use Livewire\Volt\Component;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;
use Modules\Cms\Models\Page as PageModel;

name('container0.index');
middleware(PageSlugMiddleware::class);

new class extends Component {
    public string $container0 = '';
    public string $pageSlug = '';

    /** @var array<string, mixed> */
    public array $data = [];

    public function mount(string $container0): void
    {
        $this->container0 = $container0;
        // `/privacy` puo' essere salvata come `privacy.index` o come `privacy`: vale la prima che esiste.
        // Nessuna delle due: 404 vero (prima era una pagina vuota con 200, un "soft 404").
        $cands = collect([$container0.'.index', $container0]);
        file_put_contents(storage_path('logs/dbg404.log'), 'mount container0='.$container0.' tenant='.app(\Modules\Tenant\Actions\GetTenantNameAction::class)->execute().' url='.request()->fullUrl().' SERVER_NAME='.var_export($_SERVER['SERVER_NAME']??null,true).' HTTP_HOST='.var_export($_SERVER['HTTP_HOST']??null,true).' appurl='.var_export(config('app.url'),true)."\n", FILE_APPEND);
        foreach ($cands as $c) {
            $e = PageModel::query()->where('slug', $c)->exists();
            file_put_contents(storage_path('logs/dbg404.log'), '  slug='.$c.' exists='.var_export($e,true).' count='.PageModel::count()."\n", FILE_APPEND);
        }
        $slug = $cands->first(static fn (string $candidate): bool => PageModel::query()->where('slug', $candidate)->exists());
        abort_if($slug === null, 404);
        $this->pageSlug = $slug;
        $this->data = ['container0' => $container0];
    }
};
?>

<x-layouts.app>
    @volt('container0.index')
    <div class="page-content content" data-slug="{{ $pageSlug }}" data-side="content">
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    </div>
    @endvolt
</x-layouts.app>
