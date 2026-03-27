<script setup>
import { ref, watch } from "vue";
import axios from "axios";

const props = defineProps({
  modelValue: {
    type: String,
    default: "",
  },
  label: {
    type: String,
    default: "Document",
  },
  required: {
    type: Boolean,
    default: false,
  },
  path: {
    type: String,
    default: "documents",
  },
  acceptedTypes: {
    type: String,
    default: ".pdf,.doc,.docx",
  },
  maxSize: {
    type: String,
    default: "10MB",
  },
  showView: {
    type: Boolean,
    default: true,
  },
});

const emit = defineEmits(["update:modelValue"]);

const fileInput = ref(null);
const isUploading = ref(false);
const error = ref(null);
const fileName = ref("");
const localPreviewUrl = ref("");

const triggerFileInput = () => {
  fileInput.value.click();
};

const handleFileSelect = async (event) => {
  const file = event.target.files[0];
  if (!file) return;

  error.value = null;
  isUploading.value = true;
  fileName.value = file.name;

  // Create local preview for immediate viewing
  localPreviewUrl.value = URL.createObjectURL(file);

  try {
    // 1. Get presigned URL from backend
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

      // 2. Upload file directly to S3
      await axios.put(uploadUrl, file, {
        headers: uploadHeaders,
      });

      // 3. Emit path
      emit("update:modelValue", linkData.path);
    } else {
      throw new Error("Failed to generate upload URL.");
    }
  } catch (err) {
    console.error("Document upload failed:", err);
    error.value = "Failed to upload document. Please try again.";
  } finally {
    isUploading.value = false;
    if (fileInput.value) {
      fileInput.value.value = "";
    }
  }
};

const viewFile = (e) => {
  e.stopPropagation();
  const url = props.modelValue.startsWith("http")
    ? props.modelValue
    : localPreviewUrl.value;

  if (url) {
    window.open(url, "_blank");
  } else {
    error.value = "File preview not available yet.";
  }
};
</script>

<template>
  <div class="space-y-2">
    <div class="flex items-center justify-between">
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        {{ label }} <span v-if="required" class="text-red-500 font-bold">*</span>
      </label>
    </div>

    <div
      class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 transition-all duration-300 hover:border-blue-500 hover:bg-blue-50/50 dark:hover:bg-blue-900/10 cursor-pointer group"
      @click="triggerFileInput"
      :class="{ 'border-blue-500 bg-blue-50/50': isUploading || modelValue }"
    >
      <input
        type="file"
        ref="fileInput"
        class="hidden"
        :accept="acceptedTypes"
        @change="handleFileSelect"
      />

      <div class="flex flex-col items-center justify-center text-center">
        <!-- Icon -->
        <div class="mb-3 p-3 bg-gray-100 dark:bg-gray-700 rounded-full text-gray-400 group-hover:text-blue-500 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30 transition-colors">
          <svg v-if="!isUploading && !modelValue" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
          </svg>
          <svg v-else-if="isUploading" class="animate-spin h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>

        <div v-if="!isUploading && !modelValue">
          <p class="text-sm font-medium text-gray-900 dark:text-white">
            <span class="text-blue-600">Click to upload</span> or drag and drop
          </p>
          <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
            Accepted: {{ acceptedTypes }}<br />
            Max size: {{ maxSize }}
          </p>
        </div>

        <div v-else-if="isUploading">
          <p class="text-sm font-medium text-blue-600">Uploading...</p>
          <p class="mt-1 text-xs text-gray-500">{{ fileName }}</p>
        </div>

        <div v-else>
          <p class="text-sm font-medium text-green-600">Document Uploaded Successfully</p>
          <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 truncate max-w-xs">
            {{ modelValue.split("/").pop() }}
          </p>
          <div v-if="modelValue" class="mt-3 flex items-center justify-center gap-4">
            <button
              v-if="showView"
              type="button"
              @click="viewFile"
              class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                />
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                />
              </svg>
              View File
            </button>
            <span class="text-gray-300">|</span>
            <button
              type="button"
              class="text-xs font-semibold text-gray-600 hover:text-gray-700"
            >
              Change File
            </button>
          </div>
        </div>
      </div>
    </div>

    <p v-if="error" class="text-xs text-red-600">{{ error }}</p>
  </div>
</template>
