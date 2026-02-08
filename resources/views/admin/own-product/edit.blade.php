<x-master-layout name="Ownproduct" headerName="{{ __('sidebar.own_product') }}">
    <x-form.layout>
        <form action="{{ route('own-products.update', $data['id']) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <x-form.grid>
             
                {{-- name --}}
                <x-form.input-group title='ownProduct.name' name='name' id='name' :value="$data['name']" />
                {{-- name --}}

                {{-- unit_id --}}
                <x-form.searchable-select title='ownProduct.unit_id' name='unit_id' id='unit_id' :viewData="$viewUnits" :selectedValue="$data['unit_id']" />
                {{-- unit_id --}}

                {{-- category --}}
                <x-form.searchable-select title="ownProduct.category_id" name="category_id" id="category-id" :required="true"
                    :viewData="$viewCategories" :selectedValue="$data['category_id']" />
                {{-- category --}}

                {{-- price --}}
                <x-form.input-group title='ownProduct.price' name='price' id='price' :value="$data['price']" class="comma-format" />
                {{-- price --}}


                {{-- investment --}}
                <x-form.input-group title='ownProduct.investment' name='investment' id='investment' :value="$data['investment']" class="comma-format" />
                {{-- investment --}}


                {{-- profit --}}
                <x-form.input-group title='ownProduct.profit' name='profit' id='profit' :value="$data['profit']" class="comma-format" />
                {{-- profit --}}


            </x-form.grid>
            {{-- Save And Cancel --}}
            <x-form.submit :operate="__('messages.save')" :cancel="__('messages.cancel')" url="own-products.index" />
            {{-- Save And Cancel --}}
        </form>
    </x-form.layout>
</x-master-layout>