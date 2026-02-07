<x-master-layout name="Dailyincome" headerName="{{ __('sidebar.dailyincome') }}">
    <x-form.layout>
        <form action="{{ route('daily-incomes.update', $data['id']) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <x-form.grid>
                
                {{-- date --}}
                <x-form.input-group title='dailyincome.date' name='date' id='date' :value="$data['date']" />
                {{-- date --}}

                {{-- name --}}
                <x-form.input-group title='dailyincome.name' name='name' id='name' :value="$data['name']" />
                {{-- name --}}

                {{-- product_id --}}
                <x-form.input-group title='dailyincome.product_id' name='product_id' id='product_id' :value="$data['product_id']" />
                {{-- product_id --}}

                {{-- amount --}}
                <x-form.input-group title='dailyincome.amount' name='amount' id='amount' :value="$data['amount']" />
                {{-- amount --}}

                {{-- price --}}
                <x-form.input-group title='dailyincome.price' name='price' id='price' :value="$data['price']" />
                {{-- price --}}

                {{-- investment --}}
                <x-form.input-group title='dailyincome.investment' name='investment' id='investment' :value="$data['investment']" />
                {{-- investment --}}

                {{-- profit --}}
                <x-form.input-group title='dailyincome.profit' name='profit' id='profit' :value="$data['profit']" />
                {{-- profit --}}

                {{-- unit_id --}}
                <x-form.input-group title='dailyincome.unit_id' name='unit_id' id='unit_id' :value="$data['unit_id']" />
                {{-- unit_id --}}

                {{-- note --}}
                <x-form.input-group title='dailyincome.note' name='note' id='note' :value="$data['note']" />
                {{-- note --}}

            </x-form.grid>
            {{-- Save And Cancel --}}
            <x-form.submit :operate="__('messages.save')" :cancel="__('messages.cancel')" url="daily-incomes.index" />
            {{-- Save And Cancel --}}
        </form>
    </x-form.layout>
</x-master-layout>