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

    public string $slug2 = '';

    public string $container3 = '';

    public string $slug3 = '';

    public string $pageSlug = '';

    /** @var array<string, mixed> */
    public array $data = [];

    public function mount(string $container0, string $slug0, string $container1 = '', string $slug1 = '', string $container2 = '', string $slug2 = '', string $container3 = '', string $slug3 = ''): void
    {
        $this->container0 = $container0;
        $this->slug0 = $slug0;
        $this->container1 = $container1;
        $this->slug1 = $slug1;
        $this->container2 = $container2;
        $this->slug2 = $slug2;
        $this->container3 = $container3;
        $this->slug3 = $slug3;

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
            'slug2' => $slug2,
            'container3' => $container3,
            'slug3' => $slug3,
            'item' => $item,
            'predictId' => $predictId,
        ];
    }
};
?>

<x-layouts.app>
    @volt('view.c3s3')
    <div class="page-content content" data-slug="{{ $pageSlug }}" data-side="content">
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    </div>
    @endvolt
</x-layouts.app>
