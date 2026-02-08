<x-master-layout name="Ownproduct" headerName="{{ __('sidebar.ownproduct') }}">
    <x-form.layout>
        <x-show.go-to-edit model="own-products" :id="$data['id']" />
        <x-form.grid>
            
                {{-- name --}}
                <x-show.text-group title='ownproduct.name' :data="$data['name']" />
                {{-- name --}}

                {{-- unit_id --}}
                <x-show.text-group title='ownproduct.unit_id' :data="$data['unit_id']" />
                {{-- unit_id --}}

                {{-- category_id --}}
                <x-show.text-group title='ownproduct.category_id' :data="$data['category_id']" />
                {{-- category_id --}}

                {{-- price --}}
                <x-show.text-group title='ownproduct.price' :data="$data['price']" />
                {{-- price --}}

                {{-- investment --}}
                <x-show.text-group title='ownproduct.investment' :data="$data['investment']" />
                {{-- investment --}}

                {{-- profit --}}
                <x-show.text-group title='ownproduct.profit' :data="$data['profit']" />
                {{-- profit --}}
 
        </x-form.grid>
    </x-form.layout>
</x-master-layout>