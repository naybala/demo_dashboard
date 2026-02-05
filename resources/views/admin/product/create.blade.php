<x-master-layout name="Product" headerName="{{ __('sidebar.product') }}">
    <x-form.layout>
        <form action="{{ route('products.store') }}" method="post" enctype="multipart/form-data" id="product-form">
            @csrf
            <x-file.multi-img-upload name='photo' id='photo' apiPath="products" />
            
            <x-form.grid>
                {{-- name --}}
                <x-form.input-group title='product.name' name='name' id='name'  required="true"/>
                {{-- name --}}

                {{-- name_other --}}
                <x-form.input-group title='product.name_other' name='name_other' id='name_other'  required="true"/>
                {{-- name_other --}}

                {{-- price --}}
                <x-form.input-group title='product.price' name='price' id='price'  required="true"/>
                {{-- price --}}

                {{-- description --}}
                <x-form.input-group title='product.description' name='description' id='description' />
                {{-- description --}}

                {{-- description_other --}}
                <x-form.input-group title='product.description_other' name='description_other' id='description_other'  />
                {{-- description_other --}}

            </x-form.grid>
            {{-- Save And Cancel --}}
            <x-form.submit :operate="__('messages.save')" :cancel="__('messages.cancel')" url="products.index" />
            {{-- Save And Cancel --}}
        </form>
    </x-form.layout>
     @vite(['resources/js/admin/product/local-store.js'])
</x-master-layout>