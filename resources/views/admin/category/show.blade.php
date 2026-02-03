<x-master-layout name="Category" headerName="{{ __('sidebar.category') }}">
    <x-form.layout>
        <x-show.go-to-edit model="categories" :id="$data['id']" />
        <x-form.grid>
            
                {{-- name --}}
                <x-show.text-group title='category.name' :data="$data['name']" />
                {{-- name --}}

                {{-- name_other --}}
                <x-show.text-group title='category.name_other' :data="$data['name_other']" />
                {{-- name_other --}}

                {{-- description --}}
                <x-show.text-group title='category.description' :data="$data['description']" />
                {{-- description --}}

                {{-- description_other --}}
                <x-show.text-group title='category.description_other' :data="$data['description_other']" />
                {{-- description_other --}}
 
        </x-form.grid>
    </x-form.layout>
</x-master-layout>