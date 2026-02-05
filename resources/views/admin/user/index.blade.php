<x-master-layout name="User" headerName="{{ __('sidebar.user') }}">
    <main class="h-full overflow-y-auto">
        <div class="container px-1 md:px-6 mx-auto grid">
            <div class="container md:flex justify-start md:justify-between items-start mx-auto mt-5">
                <x-common.search keyword="{{ request()->keyword }}" />
                <x-common.create-button route="users.create" permission="create users" />
                <x-common.index-toast field="role_name" />
            </div>

            <x-table.wrapper>

                <x-table.header :fields="['avatar','user_name', 'email', 'role', 'status']" />
                <x-table.body :checkData="count($data['data']) > 0">
                    @foreach ($data['data'] as $record)
                    <x-table.body-row>
                        <td scope="row" class="px-6 py-4">
                            <img src="{{ asset($record['avatar']) }}" alt="" class="w-10 h-10 rounded-full">
                        </td>
                        <td scope="row" class="px-6 py-4">{{ $record['fullname'] }}</td>
                        <td scope="row" class="px-6 py-4">{{ $record['email'] }}</td>
                        <td scope="row" class="px-6 py-4">{{ $record['role_marked'] }}</td>
                        <td class="px-3 py-0.5 mx-auto">
                            <button
                                class="text-white p-1 text-xs rounded-md {{$record['status'] ? 'bg-green-500' : 'bg-red-500'}} ms-4">{{ $record['status'] ? 'Active' : 'Inactive' }}</button>
                        </td>
                        <x-table.action-new :id="$record['id']" field="users" />
                    </x-table.body-row>
                    @endforeach
                </x-table.body>
            </x-table.wrapper>
            <x-pagination.index route="users.index" :meta="$data['meta']" />
        </div>
    </main>
    @vite('resources/js/common/deleteConfirm.js')
</x-master-layout>
