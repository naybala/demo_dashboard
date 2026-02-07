<x-master-layout name="Audit" headerName="{{ __('sidebar.audit') }}">
    <x-form.layout>
        <x-common.url-back-button route="audits.index" />
        <br><br>
        <x-show.grid :isBackground='true'>
            {{-- Model --}}
            <x-show.vertical-text-group title="audit.model" :data="$data['model']" />
            {{-- Model --}}
            {{-- Event --}}
            <x-show.vertical-text-group title="audit.event" :data="$data['event']" />
            {{-- Event --}}
            {{-- Old Data --}}
            <x-show.vertical-text-group title="audit.old_data" :data="$data['old_data']" />
            {{-- Old Data --}}
            {{-- New Data --}}
            <x-show.vertical-text-group title="audit.new_data" :data="$data['new_data']" />
            {{-- New Data --}}
            {{-- Created By --}}
            <x-show.vertical-text-group title="audit.created_by" :data="$data['created_by']" />
            {{-- Created By --}}
            {{-- Created At --}}
            <x-show.vertical-text-group title="audit.created_at" :data="$data['created_at']" />
            {{-- Created At --}}
        </x-show.grid>
    </x-form.layout>
</x-master-layout>
