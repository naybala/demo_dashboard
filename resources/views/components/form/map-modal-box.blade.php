<button type="button"
    class="px-3 py-2 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
    aria-haspopup="dialog" aria-expanded="false" aria-controls="map-modal-box"
    data-hs-overlay="#map-modal-box">
    Choose in Google Map
</button>

<div id="map-modal-box"
    class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
    role="dialog" tabindex="-1" aria-labelledby="map-modal-box-label">
    <div
        class="hs-overlay-open:mt-0 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-10 opacity-0 transition-all max-w-full max-h-full h-full sm:hs-overlay-open:mt-10 sm:mt-0 sm:max-w-xl sm:max-h-none sm:h-auto sm:mx-auto">
        <div
            class="flex flex-col bg-white pointer-events-auto max-w-full max-h-full h-full sm:max-w-lg sm:max-h-none sm:h-auto sm:border sm:rounded-xl sm:shadow-sm dark:bg-neutral-800 sm:dark:border-neutral-700">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                <h3 id="map-modal-box-label" class="font-bold text-gray-800 dark:text-white">
                    Google Map
                </h3>
                <button type="button"
                    class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400 dark:focus:bg-neutral-600"
                    aria-label="Close" data-hs-overlay="#map-modal-box">
                    <span class="sr-only">Close</span>
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <div id="modal-map" style="width: 100%;height:50vh;"></div>
            </div>
            <div
                class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700 mt-auto sm:mt-0">
                <button type="button"
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    data-hs-overlay="#map-modal-box">
                    Close
                </button>
                <button type="button" id="lat-lng-add"
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                    data-hs-overlay="#map-modal-box">
                    Add to Input Box
                </button>
            </div>
        </div>
    </div>
</div>