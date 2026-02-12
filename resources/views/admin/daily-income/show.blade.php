<x-master-layout name="Dailyincome" headerName="{{ __('sidebar.daily_income') }}">
    <x-form.layout>
        <x-show.go-to-edit model="daily-incomes" :id="$data['id']" />
        <x-form.grid>
            
        <x-show.text-group title='dailyIncome.date' :data="$data['date']" />
        <x-show.text-group title='dailyIncome.voucher_no' :data="$data['voucher_no'] ?? '-'" />
        <x-show.text-group title='dailyIncome.note' :data="$data['note']" />
        
        <div class="col-span-1 md:col-span-2 mt-4">
            <h3 class="text-lg font-semibold mb-2">Products</h3>
            <x-table.wrapper>
                <x-table.header :fields="['#', 'own_product', 'amount', 'price', 'investment', 'profit', 'unit']" />
                <x-table.body :checkData="count($data['items'])>0">
                    @foreach ($data['items'] as $index => $item)
                        <x-table.body-row>
                            <x-table.body-column :field="$index + 1" />
                            <x-table.body-column :field="$item['own_product']" />
                            <x-table.body-column :field="$item['amount']" />
                            <x-table.body-column :field="$item['price']" />
                            <x-table.body-column :field="$item['investment']" />
                            <x-table.body-column :field="$item['profit']" />
                            <x-table.body-column :field="$item['unit']" />
                        </x-table.body-row>
                    @endforeach
                </x-table.body>
            </x-table.wrapper>
        </div>
 
        </x-form.grid>
    </x-form.layout>
</x-master-layout>