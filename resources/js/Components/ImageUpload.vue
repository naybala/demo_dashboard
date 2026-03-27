<script setup>
import { ref, watch } from "vue";
import axios from "axios";
import { __ } from "@/helpers.js";

const props = defineProps({
  modelValue: {
    type: String,
    default: "",
  },
  path: {
    type: String,
    default: "images",
  },
  label: {
    type: String,
    default: "",
  },
  aspectRatio: {
    type: String,
    default: "aspect-video",
  },
});

const emit = defineEmits(["update:modelValue"]);

const fileInput = ref(null);
const previewUrl = ref(props.modelValue);
const isUploading = ref(false);
const error = ref(null);

watch(
  () => props.modelValue,
  (newVal) => {
    if (
      previewUrl.value &&
      previewUrl.value.startsWith("blob:") &&
      newVal &&
      !newVal.startsWith("http") &&
      !newVal.startsWith("/") &&
      !newVal.startsWith("upload/")
    ) {
      return;
    }
    if (!isUploading.value && newVal !== previewUrl.value) {
      previewUrl.value = newVal;
    }
  },
);

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
    const response = await axios.post("/upload/presigned-url", {
      count: 1,
      path: props.path,
    });

    if (
      response.data.code === 200 &&
      response.data.links &&
      response.data.links.length > 0
    ) {
      const linkData = response.data.links[0];
      let uploadUrl = linkData.url;
      let uploadHeaders = {
        "Content-Type": file.type,
      };

      if (typeof linkData.url === "object" && linkData.url !== null) {
        uploadUrl = linkData.url.url;
        const extraHeaders = linkData.url.headers || {};
        if (extraHeaders["Host"]) delete extraHeaders["Host"];
        if (extraHeaders["host"]) delete extraHeaders["host"];
        uploadHeaders = { ...uploadHeaders, ...extraHeaders };
      }

      await axios.put(uploadUrl, file, {
        headers: uploadHeaders,
      });

      emit("update:modelValue", linkData.path);
    } else {
      throw new Error("Failed to generate upload URL.");
    }
  } catch (err) {
    console.error("Image upload failed:", err);
    error.value = "Failed to upload image. Please try again.";
    previewUrl.value = props.modelValue;
  } finally {
    isUploading.value = false;
    if (fileInput.value) {
      fileInput.value.value = "";
    }
  }
};
</script>

<template>
  <div class="w-full">
    <label v-if="label" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
      {{ label }}
    </label>
    
    <div
      :class="[
        'relative w-full rounded-xl overflow-hidden border-2 border-dashed border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 flex flex-col items-center justify-center cursor-pointer group transition-all duration-300 hover:border-blue-500 hover:bg-gray-100 dark:hover:bg-gray-800',
        aspectRatio
      ]"
      @click="triggerFileInput"
    >
      <img
        v-if="previewUrl"
        :src="previewUrl"
        alt="Preview"
        class="w-full h-full object-contain"
        :class="{ 'opacity-50': isUploading }"
      />
      
      <div v-if="!previewUrl && !isUploading" class="flex flex-col items-center p-6 text-center">
        <div class="p-3 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-full mb-3 group-hover:scale-110 transition-transform">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-8 w-8"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="1.5"
              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
            />
          </svg>
        </div>
        <p class="text-sm font-semibold text-gray-900 dark:text-white">
          {{ __("messages.click_to_upload", "Click to upload image") }}
        </p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
          PNG, JPG, GIF up to 10MB
        </p>
      </div>

      <!-- Hover overlay for existing image -->
      <div
        v-if="previewUrl && !isUploading"
        class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300"
      >
        <div class="flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-md rounded-full border border-white/30 text-white text-sm font-medium">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
          </svg>
          {{ __("messages.change_image", "Change Image") }}
        </div>
      </div>

      <!-- Uploading Spinner -->
      <div
        v-if="isUploading"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm flex flex-col items-center justify-center text-white"
      >
        <svg
          class="animate-spin h-10 w-10 mb-3"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
        >
          <circle
            class="opacity-25"
            cx="12"
            cy="12"
            r="10"
            stroke="currentColor"
            stroke-width="4"
          ></circle>
          <path
            class="opacity-75"
            fill="currentColor"
            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
          ></path>
        </svg>
        <span class="text-sm font-medium tracking-wide">Uploading...</span>
      </div>
    </div>

    <input
      type="file"
      ref="fileInput"
      class="hidden"
      accept="image/*"
      @change="handleFileSelect"
    />

    <p v-if="error" class="mt-2 text-sm text-red-600 animate-pulse">{{ error }}</p>
  </div>
</template>
