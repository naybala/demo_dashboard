<x-master-layout name="Category" headerName="{{ __('sidebar.category') }}">
    <x-form.layout>
        <form action="{{ route('categories.update', $data['id']) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <x-form.grid>
                
                {{-- name --}}
                <x-form.input-group title='category.name' name='name' id='name' :value="$data['name']" />
                {{-- name --}}

                {{-- name_other --}}
                <x-form.input-group title='category.name_other' name='name_other' id='name_other' :value="$data['name_other']" />
                {{-- name_other --}}

                {{-- description --}}
                <x-form.input-group title='category.description' name='description' id='description' :value="$data['description']" />
                {{-- description --}}

                {{-- description_other --}}
                <x-form.input-group title='category.description_other' name='description_other' id='description_other' :value="$data['description_other']" />
                {{-- description_other --}}

            </x-form.grid>
            {{-- Save And Cancel --}}
            <x-form.submit :operate="__('messages.save')" :cancel="__('messages.cancel')" url="categories.index" />
            {{-- Save And Cancel --}}
        </form>
    </x-form.layout>
</x-master-layout>