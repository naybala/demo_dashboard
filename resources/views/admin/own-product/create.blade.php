<x-master-layout name="Ownproduct" headerName="{{ __('sidebar.own_product') }}">
    <x-form.layout>
        <form action="{{ route('own-products.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <x-file.simple-img-upload name='image' id='image' title="" photoId="avatar-pic"/>
            <x-form.grid>
                
                {{-- name --}}
                <x-form.input-group title='ownProduct.name' name='name' id='name'  />
                {{-- name --}}

                {{-- unit_id --}}
                <x-form.searchable-select title='ownProduct.unit_id' name='unit_id' id='unit_id' :viewData="$viewUnits" />
                {{-- unit_id --}}

                {{-- category --}}
                <x-form.searchable-select title="ownProduct.category_id" name="category_id" id="category-id" :required="true"
                    :viewData="$viewCategories" />
                {{-- category --}}

                {{-- price --}}
                <x-form.input-group title='ownProduct.price' name='price' id='price' class="comma-format"  />
                {{-- price --}}


                {{-- investment --}}
                <x-form.input-group title='ownProduct.investment' name='investment' id='investment' class="comma-format"  />
                {{-- investment --}}


                {{-- profit --}}
                <x-form.input-group title='ownProduct.profit' name='profit' id='profit' class="comma-format"  />
                {{-- profit --}}


            </x-form.grid>
            {{-- Save And Cancel --}}
            <x-form.submit :operate="__('messages.save')" :cancel="__('messages.cancel')" url="own-products.index" />
            {{-- Save And Cancel --}}
        </form>
    </x-form.layout>
         @vite(['resources/js/common/maxFileSize.js'])
</x-master-layout>