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
                @if ($data['userHasGroup'])
                <input type="hidden" name="user_type" value="{{ $newData['user_type'] }}" />
                @else
                <x-form.enum-select title='user.user_type' name='user_type' id='user_type' enumClass='UserType'
                    :selectedValue="$newData['user_type']" />
                @endif
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

                @if ($data['userHasGroup'])
                <input type="hidden" name="role_marked" value="{{ $newData['role_marked'] }}" />
                @else
                <x-form.compose-single-select title="user.role" name="role_marked" id="role_marked"
                    :selectedValue="$newData['role_marked']" :required="true" :dataArray="$viewRoles"
                    :required="true" />
                @endif

                {{-- status --}}
                <x-form.enum-select title="user.status" name="status" id="status" enumClass="Status" required="true"
                    :selectedValue="$newData['status']" />
                {{-- status --}}


                @if ($data['userHasGroup'])
                <input type="hidden" name="group_id" value="{{ $data['userHasGroup'] }}" />
                @else
                <x-form.simple-select title="user.group" name="group_id" id="group_id">
                    @foreach ($viewGroups as $key => $value)
                    <option @selected($key==$newData['group_id']) value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </x-form.simple-select>
                @endif

            </x-form.grid>
            {{-- Save And Cancel --}}
            <x-form.submit :operate="__('messages.save')" :cancel="__('messages.cancel')" url="users.index" />
            {{-- Save And Cancel --}}
        </form>
    </x-form.layout>
    @vite(['resources/js/common/loginEyes.js', 'resources/js/common/maxFileSize.js'])
</x-master-layout>
