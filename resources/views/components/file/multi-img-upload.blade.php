@props(['apiPath'])
<div> 
    <!-- File Input -->
    <x-form.label title="product.photo" :required="true" />
    <input type="hidden" id="apiPath" value="{{ $apiPath }}" />
    <input type="hidden" id="photo" />

    <input type="file" id="fileInput" multiple accept="image/*" class="hidden">
    <button type="button" id="multi-photo-btn" onclick="document.getElementById('fileInput').click()"
        class="bg-blue-500 text-white px-4 py-2 rounded flex items-center gap-2">
        Select Images
    </button>

    <!-- Preview Images -->
    <div id="previewContainer" class="flex flex-wrap gap-2 mt-3"></div>

    <!-- Cropping Modal -->
    <div id="cropModal"
        class="hidden fixed inset-0 bg-gray-700 bg-opacity-75 flex justify-center items-center z-[50000]">
        <div class="bg-white p-5 rounded-lg shadow-lg w-96">
            <div class="flex justify-between">
                <h2 class="text-lg font-bold">Crop Image</h2>
                <button type="button" id="closeModal" class="text-red-500 text-xl">✕</button>
            </div>
            <div class="mt-3">
                <img id="cropperImage" class="max-w-full">
            </div>
            <div class="flex justify-end mt-3">
                <button type="button" id="cropImageBtn" class="bg-blue-500 text-white px-4 py-2 rounded">
                    Crop & Save
                </button>
            </div>
        </div>
    </div>
</div>
