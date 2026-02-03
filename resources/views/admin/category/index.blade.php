<x-master-layout name="Category" headerName="{{ __('sidebar.category') }}">
   <main class="h-full overflow-y-auto">
        <div class="container px-1 md:px-6 mx-auto grid">
            <div class="container md:flex justify-start md:justify-between items-start mx-auto mt-5">
                <x-common.search keyword="{{ request()->keyword }}" />
                <x-common.create-button route="categories.create" permission="create categories" />
            </div>
            <x-table.wrapper>
                <x-table.header :fields="['name','name_other','description','description_other']" />
                <x-table.body :checkData="count($data['data'])>0">
                    @foreach ($data['data'] as $record)
                        <x-table.body-row>
                            <x-table.body-column :field="$record['name']" limit="20" />
                            <x-table.body-column :field="$record['name_other']" limit="20" />
                            <x-table.body-column :field="$record['description']" limit="20" />
                            <x-table.body-column :field="$record['description_other']" limit="20" />
                            <x-table.action-new :id="$record['id']" field="categories" />
                        </x-table.body-row>
                    @endforeach
                </x-table.body>
            </x-table.wrapper>
            <x-pagination.index route="categories.index" :meta="$data['meta']" />
        </div>
    </main>
    @vite('resources/js/common/deleteConfirm.js') 
</x-master-layout>
