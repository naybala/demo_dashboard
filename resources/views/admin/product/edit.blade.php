<x-master-layout name="Product" headerName="{{ __('sidebar.product') }}">
    <x-form.layout>
        <form action="{{ route('products.update', $data['id']) }}" method="post" enctype="multipart/form-data" id="product-form">
            @csrf
            @method('PUT')
            
            <x-file.multi-img-upload name='photo' id='photo' apiPath="products" />
            {{-- Pass existing photos (URLs and Paths) to JS --}}
            <input type="hidden" id="existing-photos" value="{{ json_encode($data['photos']) }}">
            <input type="hidden" id="existing-photo-paths" value="{{ json_encode($data['photo_paths']) }}">
            <input type="hidden" id="is-update" value="true">
            <input type="hidden" id="product-id" value="{{ $data['id'] }}">

            <x-form.grid>
                
                {{-- name --}}
                <x-form.input-group title='product.name' name='name' id='name' :value="$data['name']" />
                {{-- name --}}

                {{-- name_other --}}
                <x-form.input-group title='product.name_other' name='name_other' id='name_other' :value="$data['name_other']" />
                {{-- name_other --}}

                {{-- price --}}
                <x-form.input-group title='product.price' name='price' id='price' :value="$data['price']" class="comma-format" />
                {{-- price --}}


                {{-- categories --}}
                <x-form.searchable-multi-select title="product.category" name="categories" id="categories" :required="true"
                    :viewData="$viewCategories" :selected-value="$data['category_ids']" />
                {{-- categories --}}

                {{-- description --}}
                <x-form.quill-editor title="product.description" name="description" id="description"
                :value="$data['description']" helperText="description" />
                {{-- description --}}

                {{-- description_other --}}
                <x-form.quill-editor title="product.description_other" name="description_other" id="description_other"
                :value="$data['description_other']" helperText="description" />
                {{-- description_other --}}

            </x-form.grid>
            <br><br>
            {{-- Save And Cancel --}}
            <x-form.submit :operate="__('messages.save')" :cancel="__('messages.cancel')" url="products.index" />
            {{-- Save And Cancel --}}
        </form>
    </x-form.layout>
    @vite(['resources/js/admin/product/local-store.js', 'resources/js/admin/product/product-quill.js'])
</x-master-layout>