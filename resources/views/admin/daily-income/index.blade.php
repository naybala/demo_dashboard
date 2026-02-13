<x-master-layout name="Dailyincome" headerName="{{ __('sidebar.daily_income') }}">
   <main class="h-full overflow-y-auto">
        <div class="container px-1 md:px-6 mx-auto grid">
            <div class="mt-5 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                <form method="GET" action="{{ route('daily-incomes.index') }}" class="flex flex-wrap items-end gap-4">
                    {{-- Search Keyword --}}
                    <div class="flex-grow min-w-[300px]">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="keyword" value="{{ request()->keyword }}" 
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 sm:text-sm" 
                                placeholder="Search products, vouchers...">
                        </div>
                    </div>
                    
                    {{-- Date Range & Actions --}}
                    <x-common.date-filter 
                        :fromDate="request()->from_date" 
                        :toDate="request()->to_date" 
                        :resetUrl="route('daily-incomes.index')" 
                    />

                    <div class="ml-auto">
                        <x-common.create-button route="daily-incomes.create" permission="create daily-incomes" />
                    </div>
                </form>
            </div>
            <x-table.wrapper>
                <x-table.header :fields="['date', 'voucher_no', 'own_product', 'amount', 'price', 'investment', 'profit', 'unit']" />
                <x-table.body :checkData="count($data['data'])>0">
                    @php $lastVoucher = null; @endphp
                    @foreach ($data['data'] as $record)
                        <x-table.body-row>
                            <x-table.body-column :field="$record['date']" limit="20" />
                            @if ($lastVoucher != $record['voucher_no'])
                                <x-table.body-column :field="$record['voucher_no'] ?? '-'" limit="40" />
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