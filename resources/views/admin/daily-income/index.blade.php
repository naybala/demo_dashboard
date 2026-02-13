<x-master-layout name="Dailyincome" headerName="{{ __('sidebar.daily_income') }}">
   <main class="h-full overflow-y-auto">
        <div class="container px-1 md:px-6 mx-auto grid">
            <div class="container md:flex justify-start md:justify-between items-start mx-auto mt-5">
                <x-common.search keyword="{{ request()->keyword }}" />
                <x-common.create-button route="daily-incomes.create" permission="create daily-incomes" />
            </div>
            <x-table.wrapper>
                <x-table.header :fields="['date', 'voucher_no', 'own_product', 'amount', 'price', 'investment', 'profit', 'unit']" />
                <x-table.body :checkData="count($data['data'])>0">
                    @php $lastVoucher = null; @endphp
                    @foreach ($data['data'] as $record)
                        <x-table.body-row>
                            <x-table.body-column :field="$record['date']" limit="20" />
                            @if ($lastVoucher != $record['voucher_no'])
                                <x-table.body-column :field="$record['voucher_no'] ?? '-'" limit="20" />
                                @php $lastVoucher = $record['voucher_no']; @endphp
                            @else
                                <x-table.body-column field="" limit="20" />
                            @endif
                            <x-table.body-column :field="$record['own_product']" limit="20" />
                            <x-table.body-column :field="$record['amount']" limit="20" />
                            <x-table.body-column :field="$record['price']" limit="20" />
                            <x-table.body-column :field="$record['investment']" limit="20" />
                            <x-table.body-column :field="$record['profit']" limit="20" />
                            <x-table.body-column :field="$record['unit']" limit="20" />

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