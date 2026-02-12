<x-master-layout name="Dashboard" headerName="{{ __('sidebar.dashboard') }}" class="">
    <main class="overflow-y-auto p-4 lg:p-6 space-y-6">
        <!-- Date Filter -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <form action="{{ route('dashboard.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <x-form.label title="Start Date" />
                    <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-var(--color-theme) focus:border-var(--color-theme)">
                </div>
                <div>
                    <x-form.label title="End Date" />
                    <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-var(--color-theme) focus:border-var(--color-theme)">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-[var(--color-theme)] text-white px-4 py-2 rounded-lg hover:opacity-90 transition-opacity">Filter</button>
                    <a href="{{ route('dashboard.index') }}" class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg hover:opacity-90 transition-opacity text-center">Reset</a>
                </div>
            </form>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Own Product Types</p>
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ number_format($stats['total_products']) }}</h4>
            </div>
            <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Price</p>
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ number_format($stats['total_price']) }}</h4>
            </div>
            <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Investment</p>
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ number_format($stats['total_investment']) }}</h4>
            </div>
            <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Profit</p>
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ number_format($stats['total_profit']) }}</h4>
            </div>
        </div>

        <!-- Row 1: Three Columns -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-chart.card 
                title="Own Product By Category" 
                type="donut" 
                :series="$stats['product_distribution']['series']" 
                :labels="$stats['product_distribution']['labels']" 
                height="320"
            />
            <x-chart.card 
                title="Sales By Category" 
                type="pie" 
                :series="$stats['sales_distribution']['series']" 
                :labels="$stats['sales_distribution']['labels']" 
                height="320"
            />
          
        </div>

     
    </main>
   
</x-master-layout>
