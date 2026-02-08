<x-master-layout name="Dailyincome" headerName="{{ __('sidebar.daily_income') }}">
    <x-form.layout>
        <x-show.go-to-edit model="daily-incomes" :id="$data['id']" />
        <x-form.grid>
            
                {{-- date --}}
                <x-show.text-group title='dailyincome.date' :data="$data['date']" />
                {{-- date --}}

                {{-- product_id --}}
                <x-show.text-group title='dailyincome.own_product' :data="$data['own_product']" />
                {{-- product_id --}}

                {{-- amount --}}
                <x-show.text-group title='dailyincome.amount' :data="$data['amount']" />
                {{-- amount --}}

                {{-- price --}}
                <x-show.text-group title='dailyincome.price' :data="$data['price']" />
                {{-- price --}}

                {{-- investment --}}
                <x-show.text-group title='dailyincome.investment' :data="$data['investment']" />
                {{-- investment --}}

                {{-- profit --}}
                <x-show.text-group title='dailyincome.profit' :data="$data['profit']" />
                {{-- profit --}}

                {{-- unit_id --}}
                <x-show.text-group title='dailyincome.unit_id' :data="$data['unit_id']" />
                {{-- unit_id --}}

                {{-- note --}}
                <x-show.text-group title='dailyincome.note' :data="$data['note']" />
                {{-- note --}}
 
        </x-form.grid>
    </x-form.layout>
</x-master-layout>