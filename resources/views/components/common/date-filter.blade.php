@props(['fromDate' => null, 'toDate' => null, 'resetUrl' => '#'])

<div class="flex items-end gap-3">
    <div class="flex flex-col">
        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">From Date</label>
        <input type="date" name="from_date" value="{{ $fromDate ?? request()->from_date }}" 
            class="p-2 text-sm border border-gray-300 rounded-md bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>
    <div class="flex flex-col">
        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">To Date</label>
        <input type="date" name="to_date" value="{{ $toDate ?? request()->to_date }}" 
            class="p-2 text-sm border border-gray-300 rounded-md bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    <div class="flex gap-2">
        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-filter mr-2"></i> Filter
        </button>
        <a href="{{ $resetUrl }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
            <i class="fas fa-undo mr-2"></i> Reset
        </a>
    </div>
</div>
