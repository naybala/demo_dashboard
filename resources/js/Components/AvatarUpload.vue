<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    }
});

const emit = defineEmits(['update:modelValue']);

const fileInput = ref(null);
const previewUrl = ref(props.modelValue);
const isUploading = ref(false);
const error = ref(null);

watch(() => props.modelValue, (newVal) => {
    // Only update preview if it's not a raw path that we just emitted
    if (previewUrl.value && previewUrl.value.startsWith('blob:') && newVal && !newVal.startsWith('http') && !newVal.startsWith('/') && !newVal.startsWith('upload/')) {
        return; // Keep the local blob preview
    }
    if (!isUploading.value && newVal !== previewUrl.value) {
        previewUrl.value = newVal;
    }
});

const triggerFileInput = () => {
    fileInput.value.click();
};

const handleFileSelect = async (event) => {
    const file = event.target.files[0];
    if (!file) return;

    error.value = null;
    isUploading.value = true;

    // Create a local preview immediately
    previewUrl.value = URL.createObjectURL(file);

    try {
        // 1. Get presigned URL from backend
        const response = await axios.post('/upload/presigned-url', {
            count: 1,
            path: 'avatars'
        });

        if (response.data.code === 200 && response.data.links && response.data.links.length > 0) {
            const linkData = response.data.links[0];
            let uploadUrl = linkData.url;
            let uploadHeaders = {
                'Content-Type': file.type
            };

            // In Laravel 10+, S3 temporaryUploadUrl returns an array/object with 'url' and 'headers'
            if (typeof linkData.url === 'object' && linkData.url !== null) {
                uploadUrl = linkData.url.url;
                const extraHeaders = linkData.url.headers || {};
                
                // Remove unsafe headers that browsers refuse to set
                if (extraHeaders['Host']) delete extraHeaders['Host'];
                if (extraHeaders['host']) delete extraHeaders['host'];
                
                uploadHeaders = { ...uploadHeaders, ...extraHeaders };
            }

            // 2. Upload file directly to S3/Cloud Storage
            await axios.put(uploadUrl, file, {
                headers: uploadHeaders
            });

            // 3. Emit the path back to the parent form to be saved
            emit('update:modelValue', linkData.path);
        } else {
            throw new Error('Failed to generate upload URL.');
        }
    } catch (err) {
        console.error('Avatar upload failed:', err);
        error.value = 'Failed to upload image. Please try again.';
        // Revert preview on failure
        previewUrl.value = props.modelValue;
    } finally {
        isUploading.value = false;
        // Reset file input so the same file could be selected again if needed
        if (fileInput.value) {
            fileInput.value.value = '';
        }
    }
};
</script>

<template>
    <div class="flex flex-col items-center">
        <div 
            class="relative w-32 h-32 rounded-full overflow-hidden border-4 border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex items-center justify-center cursor-pointer group transition-all duration-300 hover:border-blue-500 hover:shadow-lg"
            @click="triggerFileInput"
        >
            <img 
                v-if="previewUrl" 
                :src="previewUrl" 
                alt="Avatar" 
                class="w-full h-full object-cover"
                :class="{'opacity-50': isUploading}"
            />
            <div v-else class="text-gray-400 dark:text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>

            <!-- Hover overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>

            <!-- Uploading Spinner -->
            <div v-if="isUploading" class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <svg class="animate-spin h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>

        <input 
            type="file" 
            ref="fileInput" 
            class="hidden" 
            accept="image/*"
            @change="handleFileSelect" 
        />
        
        <p v-if="error" class="mt-2 text-sm text-red-600">{{ error }}</p>
        <p v-else class="mt-2 text-sm text-gray-500 dark:text-gray-400">Click to {{ previewUrl ? 'change' : 'add' }} photo</p>
    </div>
</template>
