@props(['items'])

@if ($items instanceof \Illuminate\Contracts\Pagination\Paginator)
    <div {{ $attributes->merge(['class' => 'mt-3']) }}>
        {{ $items->links() }}
    </div>
@endif
