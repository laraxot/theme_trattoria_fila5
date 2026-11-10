<?php

declare(strict_types=1);

use Livewire\Volt\Component;
use Modules\Cms\Actions\ResolvePageAction;

new class extends Component {
    public string $container0 = '';

    public string $slug0 = '';

    public string $container1 = '';

    public string $slug1 = '';

    public string $container2 = '';

    public string $pageSlug = '';

    /** @var array<string, mixed> */
    public array $data = [];

    public function mount(string $container0, string $slug0, string $container1 = '', string $slug1 = '', string $container2 = ''): void
    {
        $this->container0 = $container0;
        $this->slug0 = $slug0;
        $this->container1 = $container1;
        $this->slug1 = $slug1;
        $this->container2 = $container2;

        if ($container0 === 'restaurants' && $container1 === 'table' && $container2 === 'order') {
            // /restaurants/{slug}/table/{num}/order: ordinazione dal tavolo nel minisito.
            $this->pageSlug = 'restaurants.table_order';
            $this->data['tableRef'] = $slug1;

            return;
        }

        $resolved = app(ResolvePageAction::class)->execute($container0, $slug0);

        $this->pageSlug = $resolved->pageSlug;

        $item = $resolved->item;
        $predictId = is_object($item) && isset($item->id) ? $item->id : null;

        $this->data = [
            'container0' => $container0,
            'slug0' => $slug0,
            'slug' => $slug0,
            'container1' => $container1,
            'slug1' => $slug1,
            'container2' => $container2,
            'item' => $item,
            'predictId' => $predictId,
        ];
    }
};
?>

<x-layouts.app>
    @volt('view.c2')
    <div class="page-content content" data-slug="{{ $pageSlug }}" data-side="content">
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    </div>
    @endvolt
</x-layouts.app>
