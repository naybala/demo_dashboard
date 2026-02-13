<x-master-layout name="Dailyincome" headerName="{{ __('sidebar.daily_income') }}">
    <x-form.layout>
        <form action="{{ route('daily-incomes.store') }}" method="post" enctype="multipart/form-data" id="daily-income-form"
            data-products="{{ $ownProductsData }}">

            @csrf
    
            <x-form.grid cols="3">
                <div></div>
                <x-form.date-picker title="dailyIncome.date" name="date" id="date" value="{{ date('Y-m-d') }}" />
                <div></div>
            </x-form.grid>
            <br>
            <button type="button" id="add-row" class="bg-blue-500 text-white px-3 py-1 rounded">
                + Add More Product
            </button>
            <br>
           <div id="product-rows">
                <div class="product-row grid grid-cols-7 gap-2 mb-2">
                    {{-- product --}}
                    <div class="product-select-wrapper">
                        <x-form.searchable-select title="dailyIncome.name" name="own_product_id[]"
                            class="own-product-id" :viewData="$viewOwnProducts" />
                    </div>

                    {{-- amount --}}
                    <x-form.input-group title='dailyIncome.amount' name='amount[]'
                        class="amount comma-format" value="1" />

                    {{-- hidden unit --}}
                    <input type="hidden" name="unit_id[]" class="unit-id-hidden">

                    {{-- unit name --}}
                    <x-form.input-group title='dailyIncome.unit_id' name='unit_name[]'
                        class="unit-name" :disabled="true" />

                    {{-- price --}}
                    <x-form.input-group title='dailyIncome.price' name='price[]'
                        class="price comma-format" :customAttributes="['readonly'=>'readonly']" />

                    {{-- investment --}}
                    <x-form.input-group title='dailyIncome.investment' name='investment[]'
                        class="investment comma-format" :customAttributes="['readonly'=>'readonly']" />

                    {{-- profit --}}
                    <x-form.input-group title='dailyIncome.profit' name='profit[]'
                        class="profit comma-format" :customAttributes="['readonly'=>'readonly']" />
                    <div class="flex items-center justify-center">
                        <button type="button" class="remove-row bg-red-100 text-red-600 hover:bg-red-200 hover:text-red-700 rounded-full w-8 h-8 flex items-center justify-center transition-all duration-200 shadow-sm" title="Remove Item">
                            <i class="fas fa-trash-alt text-sm"></i>
                        </button>
                    </div>

                </div>
            </div>
            <x-form.checkbox title="dailyIncome.is_instant" name="is_instant" id="is-instant" />


            <br>
            <div class="">
                <div>
                    <p><span>Total Price:</span> <span id="total-price">0</span></p>
                    <p><span>Total Investment:</span> <span id="total-investment">0</span></p>
                    <p><span>Total Profit:</span> <span id="total-profit">0</span></p>
                </div>
                
            </div>
            
            <br>
            <x-form.grid cols="1" class="shadow-lg rounded-lg p-4">
                {{-- note --}}
                <x-form.textarea title='dailyIncome.note' name='note' id='note'  />
                {{-- note --}}
            </x-form.grid>
            {{-- Save And Cancel --}}
            <x-form.submit :operate="__('messages.save')" :cancel="__('messages.cancel')" url="daily-incomes.index" />
            {{-- Save And Cancel --}}
        </form>
    </x-form.layout>
    @vite(['resources/js/admin/dailyIncome/calculate.js', 'resources/js/admin/dailyIncome/totalCalculate.js'])
</x-master-layout>