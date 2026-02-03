@props(['title','required'=>false,'name','id'])
<x-form.control>
    <x-form.label :title="$title" :required="$required" />

    <div
        x-data="{
            search: '',
            users: [],
            selectedUser: null,
            loading: false,
            isOpen: false,
            timeout: null,
            async fetchUsers() {
                const response = await fetch('/panel/users?search=' + this.search);
                this.users = await response.json();
                this.loading = false;
            },
            selectUser(user) {
                this.search = user.fullname+ ' - ' +user.phone_number;
                this.selectedUser = user;
                this.isOpen = false;
            }
        }"
        x-init="
            $watch('search', value => {
                if(isOpen){
                    loading = true;
                }
                if(value.length > 1) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        fetchUsers();
                    }, 1000);
                } else {
                    loading = false;
                }
            })
        "
        class="relative"
        @click.away="isOpen = false"
    >
        <div class="relative">
            <input 
                type="text" 
                x-model="search" 
                @focus="isOpen = true"
                placeholder="Type and Select users..."
                class="w-full p-2.5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            >
            <input type="hidden" name="{{ $name }}" :value="selectedUser?.id">
            <div 
                x-show="loading"
                class="absolute right-3 top-1/2 transform -translate-y-1/2"
            >
                <svg class="select-box-spinner h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>
        
        <div
            x-show="isOpen && users.length > 0"
            class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto dark:bg-gray-700 dark:border-gray-600"
        >
            <template x-for="user in users" :key="user.id">
                <div 
                    @click="selectUser(user)"
                    x-text="user.fullname+ ' - ' +user.phone_number"
                    class="px-4 py-2 text-xs cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                ></div>
            </template>
        </div>
    </div>
    <p id="{{ $name }}-error" class="text-red-500 text-xs italic ajax-error-shower"></p>
</x-form.control>