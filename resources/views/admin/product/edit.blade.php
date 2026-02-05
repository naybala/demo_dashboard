<x-master-layout name="Product" headerName="{{ __('sidebar.product') }}">
    <x-form.layout>
        <form action="{{ route('products.update', $data['id']) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <x-form.grid>
                
                {{-- name --}}
                <x-form.input-group title='product.name' name='name' id='name' :value="$data['name']" />
                {{-- name --}}

                {{-- name_other --}}
                <x-form.input-group title='product.name_other' name='name_other' id='name_other' :value="$data['name_other']" />
                {{-- name_other --}}

                {{-- price --}}
                <x-form.input-group title='product.price' name='price' id='price' :value="$data['price']" />
                {{-- price --}}

                {{-- description --}}
                <x-form.input-group title='product.description' name='description' id='description' :value="$data['description']" />
                {{-- description --}}

                {{-- description_other --}}
                <x-form.input-group title='product.description_other' name='description_other' id='description_other' :value="$data['description_other']" />
                {{-- description_other --}}

            </x-form.grid>
            {{-- Save And Cancel --}}
            <x-form.submit :operate="__('messages.save')" :cancel="__('messages.cancel')" url="products.index" />
            {{-- Save And Cancel --}}
        </form>
    </x-form.layout>
</x-master-layout>