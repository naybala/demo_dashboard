<x-master-layout name="Dashboard" headerName="{{ __('sidebar.dashboard') }}" class="">
    <main class="overflow-y-auto p-4 lg:p-6 space-y-6">
        <!-- Row 1: Three Columns -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-chart.card 
                title="Revenue Source" 
                type="donut" 
                :series="[45, 30, 25]" 
                :labels="['Direct', 'Affiliate', 'Subscription']" 
                height="320"
            />
            <x-chart.card 
                title="User Demographics" 
                type="pie" 
                :series="[40, 35, 25]" 
                :labels="['Mobile', 'Desktop', 'Tablet']" 
                height="320"
            />
            <x-chart.card 
                title="Monthly Traffic Distribution" 
                type="polarArea" 
                :series="[14, 23, 21, 17, 15, 10]" 
                :labels="['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']" 
                height="320"
            />
        </div>

        <!-- Row 2: One Column (Line Chart) -->
        <div class="w-full">
            <x-chart.card 
                title="Sales Growth Performance" 
                type="line" 
                :series="[
                    [
                        'name' => 'Current Year',
                        'data' => [30, 40, 35, 50, 49, 60, 70, 91, 125, 140, 145, 160]
                    ],
                    [
                        'name' => 'Previous Year',
                        'data' => [20, 30, 25, 40, 39, 50, 60, 81, 105, 120, 125, 140]
                    ]
                ]" 
                :labels="['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']" 
            />
        </div>

        <!-- Row 3: One Column (Bar Chart) -->
        <div class="w-full">
            <x-chart.card 
                title="Monthly Active Users" 
                type="bar" 
                :series="[
                    [
                        'name' => 'Users',
                        'data' => [2300, 3100, 4000, 10100, 4000, 3600, 3200, 2300, 3300, 4500, 5200, 6100]
                    ]
                ]" 
                :labels="['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']" 
                :options="[
                    'plotOptions' => [
                        'bar' => [
                            'borderRadius' => 6,
                            'columnWidth' => '45%'
                        ]
                    ]
                ]"
            />
        </div>
    </main>
   
</x-master-layout>
