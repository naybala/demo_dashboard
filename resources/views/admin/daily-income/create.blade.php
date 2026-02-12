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
                    <div type="button" class="remove-row text-red-500 cursor-pointer "><i class="fas fa-trash"></i></div>

                </div>
            </div>
            <x-form.checkbox title="dailyIncome.is_instant" name="is_instant" id="is-instant" />


            <br><br>
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
    @vite('resources/js/admin/dailyIncome/calculate.js')
</x-master-layout>