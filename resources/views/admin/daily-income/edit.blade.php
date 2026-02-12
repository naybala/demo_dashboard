<x-master-layout name="Dailyincome" headerName="{{ __('sidebar.daily_income') }}">
    <x-form.layout>
        <form action="{{ route('daily-incomes.update', $data['id']) }}" method="post" enctype="multipart/form-data" id="daily-income-form"
            data-products="{{ $ownProductsData }}">
            @csrf
            @method('PUT')
            
            <x-form.grid cols="3">
                <div></div>
                <x-form.date-picker title="dailyIncome.date" name="date" id="date" :value="$data['date']" />
                <div></div>
            </x-form.grid>
            <br><br>
            <x-form.grid cols="6" class="shadow-lg rounded-lg p-4">
                {{-- name --}}
                <div id="product-select-wrapper">
                    <x-form.searchable-select title="dailyIncome.name" name="own_product_id" id="own-product-id" :required="true"
                        :viewData="$viewOwnProducts" :selectedValue="$data['own_product_id']" />
                </div>

                {{-- name --}}


                {{-- amount --}}
                <x-form.input-group title='dailyIncome.amount' name='amount' id='amount' :value="$data['amount']" class="comma-format" />


                {{-- amount --}}

                {{-- unit_id --}}
                <input type="hidden" name="unit_id" id="unit_id_hidden" value="{{ $data['unit_id'] }}">
                <x-form.input-group title='dailyIncome.unit_id' name='unit_name' id='unit_name' :value="$data['unit']" :disabled="true" />
                {{-- unit_id --}}


                {{-- price --}}
                <x-form.input-group title='dailyIncome.price' name='price' id='price' :value="$data['price']" :customAttributes="['readonly' => 'readonly']" class="comma-format" />
                {{-- price --}}



                {{-- investment --}}
                <x-form.input-group title='dailyIncome.investment' name='investment' id='investment' :value="$data['investment']" :customAttributes="['readonly' => 'readonly']" class="comma-format" />
                {{-- investment --}}



                {{-- profit --}}
                <x-form.input-group title='dailyIncome.profit' name='profit' id='profit' :value="$data['profit']" :customAttributes="['readonly' => 'readonly']" class="comma-format" />
                {{-- profit --}}



                <!-- is_instant -->
                <x-form.checkbox title="dailyIncome.is_instant" name="is_instant" id="is-instant" :checked="$data['is_instant'] == 1"/>
                <!-- is_instant -->
            </x-form.grid>


            <br><br>
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
    @vite('resources/js/admin/dailyIncome/calculate.js')
</x-master-layout>