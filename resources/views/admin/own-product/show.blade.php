<x-master-layout name="Ownproduct" headerName="{{ __('sidebar.own_product') }}">
    <x-form.layout>
        <x-show.go-to-edit model="own-products" :id="$data['id']" />
        <x-form.grid cols="3">
            <div></div>
            <x-show.profile-photo :src="$data['image']" width="w-44" height="h-44" rounded="rounded-full"/>
            <div></div>
        </x-form.grid>
        <x-form.grid>
            <x-show.text-group title='ownProduct.name' :data="$data['name']" />
            <x-show.text-group title='ownProduct.unit_id' :data="$data['unit_id']" />
            <x-show.text-group title='ownProduct.category_id' :data="$data['category_id']" />
            <x-show.text-group title='ownProduct.price' :data="$data['price']" />
            <x-show.text-group title='ownProduct.investment' :data="$data['investment']" />
            <x-show.text-group title='ownProduct.profit' :data="$data['profit']" />
        </x-form.grid>
    </x-form.layout>
</x-master-layout>