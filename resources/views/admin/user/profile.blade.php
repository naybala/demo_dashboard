<x-master-layout name="User" headerName="{{ __('sidebar.user') }}">
    <x-form.layout>
        {{-- <x-common.url-back-button /> --}}
        <br><br>

        {{-- Avatar Img --}}
        <x-show.profile-photo :src="$data['avatar']" width="w-20" height="h-20" :created_at="$data['created_at']" />
            <br>
        {{-- Avatar Img --}}
        <x-show.grid :isBackground='true'>

            {{-- FullName --}}
            <x-show.text-group title="user.fullname" :data="$data['fullname']" />
            {{-- FullName --}}
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
            <x-show.text-group title='user.role' :data="$data['role_marked']" />
            {{-- role_marked --}}

            {{-- province_ids --}}
            {{-- <x-show.text-group title='user.province_ids' :data="$data['province']" /> --}}
            {{-- province_ids --}}

            {{-- status --}}
            <x-show.text-group title="user.status" :data="$data['status_text']" />
            {{-- status --}}
           

        </x-show.grid>
    </x-form.layout>
</x-master-layout>
