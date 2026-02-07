<x-master-layout name="Unit" headerName="{{ __('sidebar.unit') }}">
    <x-form.layout>
        <form action="{{ route('units.update', $data['id']) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <x-form.grid>
                
                {{-- name --}}
                <x-form.input-group title='unit.name' name='name' id='name' :value="$data['name']" />
                {{-- name --}}

                {{-- description --}}
                <x-form.input-group title='unit.description' name='description' id='description' :value="$data['description']" />
                {{-- description --}}

            </x-form.grid>
            {{-- Save And Cancel --}}
            <x-form.submit :operate="__('messages.save')" :cancel="__('messages.cancel')" url="units.index" />
            {{-- Save And Cancel --}}
        </form>
    </x-form.layout>
</x-master-layout>