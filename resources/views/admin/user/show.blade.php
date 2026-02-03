<x-master-layout name="User" headerName="{{ __('sidebar.user') }}">
    <x-form.layout>
        <x-show.go-to-edit model="users" :id="$data['id']" />
        <x-form.grid>

            {{-- Profile Photo --}}
            <x-show.profile-photo :src="$data['avatar']" width="w-20" height="h-20" :created_at="$data['created_at']" />
            <br>
            {{-- Profile Photo --}}

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

            {{-- group_marked --}}
            <x-show.text-group title='user.group_marked' :data="$data['group']" />
            {{-- group_marked --}}

            {{-- province_ids --}}
            <x-show.text-group title='user.province_ids' :data="$data['provinces']" />
            {{-- province_ids --}}

            {{-- status --}}
            <x-show.text-group title="user.status" :data="$data['status_text']" />
            {{-- status --}}

            {{-- remember_token --}}
            {{-- <x-show.text-group title='user.remember_token' :data="$data['remember_token']" /> --}}
            {{-- remember_token --}}

        </x-form.grid>
    </x-form.layout>
</x-master-layout>
