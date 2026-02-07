<x-master-layout name="Role" headerName="{{ __('sidebar.role') }}">
    <x-form.layout>
        <x-common.url-back-button route="roles.index" />
        <x-show.go-to-edit model="roles" :id="$data['role']['id']" />
        <br><br>
        <x-show.grid :isBackground='true'>
            <x-show.vertical-text-group title="role.role_name" :data="$data['role']['name']" />
            
            <div class="mt-4">
                <h1 class="font-bold mb-4 text-gray-700 dark:text-gray-300 border-b pb-2">{{ __('user.all_permissions') }}</h1>
                <div class="grid grid-cols-2 gap-4">
                    @foreach($data['getAllPermissions'] as $keyName => $permissions)
                        @php 
                            $getCurrentPer = array_filter($data['getCurrentPermissions'], function($per) use($keyName) {
                                return explode(' ', $per)[1] === $keyName;
                            });
                        @endphp
                        <div class="col-span-2 lg:col-span-1 border rounded-md p-4 bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700">
                            <div class="flex items-center mb-3">
                                <span class="capitalize font-bold text-gray-900 dark:text-gray-100">{{ $keyName }}</span>
                            </div>

                            <div class="flex flex-col space-y-2">
                                @foreach($permissions as $permission)
                                    <div class="flex items-center text-sm">
                                        <div class="w-4 h-4 flex items-center justify-center rounded-sm border {{ in_array($permission['name'], $data['getCurrentPermissions']) ? 'bg-blue-600 border-blue-600' : 'bg-gray-200 border-gray-300 dark:bg-gray-700 dark:border-gray-600' }}">
                                            @if(in_array($permission['name'], $data['getCurrentPermissions']))
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            @endif
                                        </div>
                                        <span class="ms-2 text-gray-700 dark:text-gray-300 capitalize">{{ $permission['name'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </x-show.grid>
    </x-form.layout>
</x-master-layout>
