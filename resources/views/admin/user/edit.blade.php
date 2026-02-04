<x-master-layout name="User" headerName="{{ __('sidebar.user') }}">
    <x-form.layout>
        @php
        $newData = $data['data'];
        @endphp
        <form action="{{ route('users.update', $newData['id']) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="id" value="{{ $newData['id'] }}" />
            {{-- Profile Photo --}}
            <x-file.simple-img-upload title="user.avatar" name="avatar" id="avatar-photo" photoId="avatar-photo-pic"
                imageSrc="{{ $newData['avatar'] }}" />
            {{-- Profile Photo --}}
            <x-form.grid>

                {{-- fullname --}}
                <x-form.input-group title='user.fullname' name='fullname' id='fullname' :value="$newData['fullname']" />
                {{-- fullname --}}

                {{-- user_type --}}
                <x-form.enum-select title='user.user_type' name='user_type' id='user_type' enumClass='Users\UserType'
                    :selectedValue="$newData['user_type']" />
                {{-- user_type --}}

                {{-- email --}}
                <x-form.input-group type="email" title='user.user_email' name='email' id='email'
                    :value="$newData['email']" />
                {{-- email --}}

                {{-- password --}}
                <x-form.input-group title='user.password' type="password" name='password' id='password' :playEye="true"
                    placeholder="password" :required="true" />
                {{-- password --}}

                {{-- phone_number --}}
                <x-form.input-group title='user.phone_number' name='phone_number' id='phone_number'
                    :value="$newData['phone_number']" />
                {{-- phone_number --}}

                {{-- Role Single Select --}}
                 <x-form.searchable-select title="user.role" name="role_marked" id="role_marked" :required="true" :selectedValue="$newData['role_marked']"
                    :viewData="$viewRoles" />    

                {{-- status --}}
                <x-form.enum-select title="user.status" name="status" id="status" enumClass="Common\Status" required="true"
                    :selectedValue="$newData['status']" />
                {{-- status --}}

            </x-form.grid>
            {{-- Save And Cancel --}}
            <x-form.submit :operate="__('messages.save')" :cancel="__('messages.cancel')" url="users.index" />
            {{-- Save And Cancel --}}
        </form>
    </x-form.layout>
    @vite(['resources/js/common/loginEyes.js', 'resources/js/common/maxFileSize.js'])
</x-master-layout>
