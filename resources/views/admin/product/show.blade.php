<x-master-layout name="Product" headerName="{{ __('sidebar.product') }}">
    <x-form.layout>
        <x-show.go-to-edit model="products" :id="$data['id']" />
        <x-form.grid>
            
                {{-- name --}}
                <x-show.text-group title='product.name' :data="$data['name']" />
                {{-- name --}}

                {{-- name_other --}}
                <x-show.text-group title='product.name_other' :data="$data['name_other']" />
                {{-- name_other --}}

                {{-- price --}}
                <x-show.text-group title='product.price' :data="$data['price']" />
                {{-- price --}}

                {{-- description --}}
                <x-show.text-group title='product.description' :data="$data['description']" />
                {{-- description --}}

                {{-- description_other --}}
                <x-show.text-group title='product.description_other' :data="$data['description_other']" />
                {{-- description_other --}}

                {{-- photos --}}
                <x-show.media title="product.photo" type="photo" :links="$data['photos']" />
                {{-- photos --}}
 
        </x-form.grid>
    </x-form.layout>
</x-master-layout>