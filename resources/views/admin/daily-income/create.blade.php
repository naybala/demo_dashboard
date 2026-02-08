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
            <br><br>
            <x-form.grid cols="3" class="shadow-lg rounded-lg p-4">
                {{-- name --}}
                <div id="product-select-wrapper">
                    <x-form.searchable-select title="dailyIncome.name" name="own_product_id" id="own-product-id" :required="true"
                        :viewData="$viewOwnProducts" />
                </div>


                {{-- name --}}

                {{-- amount --}}
                <x-form.input-group title='dailyIncome.amount' name='amount' id='amount' value="1" />


                {{-- amount --}}

                {{-- unit_id --}}
                <input type="hidden" name="unit_id" id="unit_id_hidden">
                <x-form.input-group title='dailyIncome.unit_id' name='unit_name' id='unit_name' :disabled="true" />
                {{-- unit_id --}}

                {{-- price --}}
                <x-form.input-group title='dailyIncome.price' name='price' id='price' :customAttributes="['readonly' => 'readonly']" />
                {{-- price --}}


                {{-- investment --}}
                <x-form.input-group title='dailyIncome.investment' name='investment' id='investment' :customAttributes="['readonly' => 'readonly']" />
                {{-- investment --}}


                {{-- profit --}}
                <x-form.input-group title='dailyIncome.profit' name='profit' id='profit' :customAttributes="['readonly' => 'readonly']" />
                {{-- profit --}}


                <!-- is_instant -->
                <x-form.checkbox title="dailyIncome.is_instant" name="is_instant" id="is-instant" />
                <!-- is_instant -->
            </x-form.grid>


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