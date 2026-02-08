<x-master-layout name="Dailyincome" headerName="{{ __('sidebar.daily_income') }}">
    <x-form.layout>
        <form action="{{ route('daily-incomes.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            
            <x-form.grid cols="3">
                <div></div>
                <x-form.date-picker title="dailyIncome.date" name="date" id="date" />
                <div></div>
            </x-form.grid>
            <br><br>
            <x-form.grid cols="3" class="shadow-lg rounded-lg p-4">
                {{-- name --}}
                <x-form.input-group title='dailyIncome.name' name='name' id='name'  />
                {{-- name --}}

                {{-- amount --}}
                <x-form.input-group title='dailyIncome.amount' name='amount' id='amount'  />
                {{-- amount --}}

                {{-- unit_id --}}
                <x-form.input-group title='dailyIncome.unit_id' name='unit_id' id='unit_id'  />
                {{-- unit_id --}}

                {{-- price --}}
                <x-form.input-group title='dailyIncome.price' name='price' id='price'  />
                {{-- price --}}

                {{-- investment --}}
                <x-form.input-group title='dailyIncome.investment' name='investment' id='investment'  />
                {{-- investment --}}

                {{-- profit --}}
                <x-form.input-group title='dailyIncome.profit' name='profit' id='profit'  />
                {{-- profit --}}
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
</x-master-layout>