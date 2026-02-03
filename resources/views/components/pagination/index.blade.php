@props(['meta','route'])
<p class="italic" style="word-spacing: 8px;">Showing <span class="font-bold">{{ $meta['per_page'] }}</span> of Total <span class="font-bold">{{ $meta['total'] }}</span> Items</p>
<x-pagination.wrapper>
    <x-pagination.number :route="$route" />
    <x-pagination.tabs :meta="$meta"></x-pagination.tabs>
</x-pagination.wrapper>