<x-master-layout name="Dailyincome" headerName="{{ __('sidebar.dailyincome') }}">
   <main class="h-full overflow-y-auto">
        <div class="container px-1 md:px-6 mx-auto grid">
            <div class="container md:flex justify-start md:justify-between items-start mx-auto mt-5">
                <x-common.search keyword="{{ request()->keyword }}" />
                <x-common.create-button route="daily-incomes.create" permission="create daily-incomes" />
            </div>
            <x-table.wrapper>
                <x-table.header :fields="['date', 'name', 'product_id', 'amount', 'price', 'investment', 'profit', 'unit_id', 'note']" />
                <x-table.body :checkData="count($data['data'])>0">
                    @foreach ($data['data'] as $record)
                        <x-table.body-row>
                            <x-table.body-column :field="$record['date']" limit="20" />
                            <x-table.body-column :field="$record['name']" limit="20" />
                            <x-table.body-column :field="$record['product_id']" limit="20" />
                            <x-table.body-column :field="$record['amount']" limit="20" />
                            <x-table.body-column :field="$record['price']" limit="20" />
                            <x-table.body-column :field="$record['investment']" limit="20" />
                            <x-table.body-column :field="$record['profit']" limit="20" />
                            <x-table.body-column :field="$record['unit_id']" limit="20" />
                            <x-table.body-column :field="$record['note']" limit="20" />
                            <x-table.action-new :id="$record['id']" field="daily-incomes" />
                        </x-table.body-row>
                    @endforeach
                </x-table.body>
            </x-table.wrapper>
            <x-pagination.index route="daily-incomes.index" :meta="$data['meta']" />
        </div>
    </main>
    @vite('resources/js/common/deleteConfirm.js') 
</x-master-layout>