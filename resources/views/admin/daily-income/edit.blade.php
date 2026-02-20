<x-master-layout name="Dailyincome" headerName="{{ __('sidebar.daily_income') }}">
    <x-form.layout>
        <form action="{{ route('daily-incomes.update', $data['id']) }}" method="post" enctype="multipart/form-data" id="daily-income-form"
            data-products="{{ $ownProductsData }}">
            @csrf
            @method('PUT')
            
            <x-form.grid cols="3">
                <x-form.input-group title="dailyIncome.voucher_no" name="voucher_no" :value="$data['voucher_no']" :customAttributes="['readonly'=>'readonly']" class="border-0 font-semibold"/>
                <x-form.date-picker title="dailyIncome.date" name="date" id="date" :value="$data['date']" />
                <div></div>
            </x-form.grid>
            <br>
            <button type="button" id="add-row" class="bg-blue-500 text-white px-3 py-1 rounded">
                + Add More Product
            </button>
            <br>
            <div id="product-rows">
                @foreach($data['items'] as $index => $item)
                <div class="product-row grid grid-cols-2 md:grid-cols-5 gap-2 mb-2 bg-gray-200 p-2 rounded-lg relative" data-index="{{ $index }}">
                    {{-- product --}}
                    <div class="product-select-wrapper col-span-2">
                        <x-form.searchable-select title="dailyIncome.name" name="items[{{ $index }}][product_id]"
                            class="own-product-id" :viewData="$viewOwnProducts" :selectedValue="$item['own_product_id']" />
                    </div>

                    {{-- amount --}}
                    <x-form.input-group title='dailyIncome.amount' name='items[{{ $index }}][amount]'
                        class="amount comma-format" :value="$item['amount']" />

                    {{-- hidden unit --}}
                    <input type="hidden" name="items[{{ $index }}][unit_id]" class="unit-id-hidden" value="{{ $item['unit_id'] }}">

                    {{-- unit name --}}
                    <x-form.input-group title='dailyIncome.unit_id' name='items[{{ $index }}][unit_name]'
                        class="unit-name" :disabled="true" :value="$item['unit']" />

                    {{-- price --}}
                    <x-form.input-group title='dailyIncome.price' name='items[{{ $index }}][price]'
                        class="price comma-format" :customAttributes="['readonly'=>'readonly']" :value="$item['price']" />
                    <div class="hidden">
                        {{-- investment --}}
                        <x-form.input-group title='dailyIncome.investment' name='items[{{ $index }}][investment]'
                            class="investment comma-format" :customAttributes="['readonly'=>'readonly']" :value="$item['investment']" />

                        {{-- profit --}}
                        <x-form.input-group title='dailyIncome.profit' name='items[{{ $index }}][profit]'
                        class="profit comma-format" :customAttributes="['readonly'=>'readonly']" :value="$item['profit']" />
                    </div>
                    <div class="flex items-center justify-center absolute top-0 right-0">
                        <button type="button" class="remove-row bg-red-100 text-red-600 hover:bg-red-200 hover:text-red-700 rounded-full w-8 h-8 flex items-center justify-center transition-all duration-200 shadow-sm" title="Remove Item">
                            <i class="fas fa-trash-alt text-sm"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            <x-form.checkbox title="dailyIncome.is_instant" name="is_instant" id="is-instant" :checked="$data['is_instant'] == 1" />

            <br>
            <div class="">
                <div>
                    <p><span>Total Price:</span> <span id="total-price">{{ $data['total_price'] }}</span></p>
                    <p><span>Total Investment:</span> <span id="total-investment">{{ $data['total_investment'] }}</span></p>
                    <p><span>Total Profit:</span> <span id="total-profit">{{ $data['total_profit'] }}</span></p>
                </div>
            </div>

            <br>
            <x-form.grid cols="1" class="shadow-lg rounded-lg p-4">
                {{-- note --}}
                <x-form.textarea title='dailyIncome.note' name='note' id='note' :value="$data['note']" />
                {{-- note --}}
            </x-form.grid>

            {{-- Save And Cancel --}}
            <x-form.submit :operate="__('messages.save')" :cancel="__('messages.cancel')" url="daily-incomes.index" />
            {{-- Save And Cancel --}}
        </form>
    </x-form.layout>
    @vite(['resources/js/admin/dailyIncome/calculate.js', 'resources/js/admin/dailyIncome/totalCalculate.js'])
</x-master-layout>