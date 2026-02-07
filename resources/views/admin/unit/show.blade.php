<x-master-layout name="Unit" headerName="{{ __('sidebar.unit') }}">
    <x-form.layout>
        <x-show.go-to-edit model="units" :id="$data['id']" />
        <x-form.grid>
            
                {{-- name --}}
                <x-show.text-group title='unit.name' :data="$data['name']" />
                {{-- name --}}

                {{-- description --}}
                <x-show.text-group title='unit.description' :data="$data['description']" />
                {{-- description --}}
 
        </x-form.grid>
    </x-form.layout>
</x-master-layout>