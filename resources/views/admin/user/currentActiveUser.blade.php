<x-master-layout name="User" headerName="{{ __('sidebar.user') }}">
    <main class="h-full overflow-y-auto">
        <div class="container px-1 md:px-6 mx-auto grid">
            <div class="mt-4">
                <form method="GET" action="{{ route('currentActiveUsers.index') }}" class="flex items-center space-x-2">
                    <label for="webLogin" class="text-sm font-medium text-gray-700">Login From</label>
                    <select name="webLogin" id="webLogin" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="1" {{ request('webLogin') == '1' ? 'selected' : '' }}>Panel</option>
                        <option value="0" {{ request('webLogin') == '0' ? 'selected' : '' }}>Mobile/User-Side</option>
                    </select>
                <x-common.search keyword="{{ request()->keyword }}" />

                </form>
            </div>
        </div>
        <div class="container px-1 md:px-6 mx-auto grid">
            <x-table.wrapper>
                <x-table.header :fields="['user_name', 'email', 'last_used_at']" />
                <x-table.body :checkData="count($data) > 0">
                    @foreach ($data['data'] as $record)
                    <x-table.body-row>
                        <td scope="row" class="px-6 py-4">{{ $record['user_fullname'] }}</td>
                        <td scope="row" class="px-6 py-4">{{ $record['user_email'] }}</td>
                        <td scope="row" class="px-6 py-4">{{ $record['last_used_at'] }}</td>
                        <td scope="row" class="px-6 py-4">
                            <form action="{{ route('forceLogoutByUserId', ['userId' => $record['user_id']]) }}" method="post" class="formActionDelete">
                                @csrf
                                <button type="submit"
                                    class="bg-red-600 text-white px-3 py-1 rounded-md">
                                    Force Logout
                                </button>
                            </form>
                        </td>
                    </x-table.body-row>
                    @endforeach
                </x-table.body>
            </x-table.wrapper>
            <x-pagination.index route="currentActiveUsers.index" :meta="$data['meta']" />
        </div>
    </main>
    @vite('resources/js/common/deleteConfirm.js')
</x-master-layout>
