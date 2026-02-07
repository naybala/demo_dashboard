<x-master-layout name="Dailyincome" headerName="{{ __('sidebar.dailyincome') }}">
    <x-form.layout>
        <x-show.go-to-edit model="daily-incomes" :id="$data['id']" />
        <x-form.grid>
            
                {{-- date --}}
                <x-show.text-group title='dailyincome.date' :data="$data['date']" />
                {{-- date --}}

                {{-- name --}}
                <x-show.text-group title='dailyincome.name' :data="$data['name']" />
                {{-- name --}}

                {{-- product_id --}}
                <x-show.text-group title='dailyincome.product_id' :data="$data['product_id']" />
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