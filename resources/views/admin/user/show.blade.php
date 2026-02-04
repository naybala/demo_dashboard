<x-master-layout name="User" headerName="{{ __('sidebar.user') }}">
    <x-form.layout>
        <x-show.go-to-edit model="users" :id="$data['id']" />
            {{-- Profile Photo --}}
            <x-show.profile-photo :src="$data['avatar']" width="w-42" height="h-42" :created_at="$data['created_at']" />
            <br>
            {{-- Profile Photo --}}
        <x-form.grid>
            {{-- fullname --}}
            <x-show.text-group title='user.fullname' :data="$data['fullname']" />
            {{-- fullname --}}

            {{-- user_type --}}
            <x-show.text-group title='user.user_type' :data="$data['user_type_label']" />
            {{-- user_type --}}

            {{-- email --}}
            <x-show.text-group title='user.email' :data="$data['email']" />
            {{-- email --}}

            {{-- phone_number --}}
            <x-show.text-group title='user.phone_number' :data="$data['phone_number']" />
            {{-- phone_number --}}

            {{-- role_marked --}}
            <x-show.text-group title='user.role_marked' :data="$data['role_marked']" />
            {{-- role_marked --}}

            {{-- status --}}
            <x-show.text-group title="user.status" :data="$data['status_text']" />
            {{-- status --}}

        </x-form.grid>
    </x-form.layout>
</x-master-layout>
