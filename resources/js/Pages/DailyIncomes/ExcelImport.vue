<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useForm, Link, Head } from "@inertiajs/vue3";
import { __ } from "@/helpers.js";
import { ref } from "vue";

const fileInput = ref(null);
const form = useForm({
  file: null,
});

const submit = () => {
  form.post("/daily-incomes/import", {
    preserveScroll: true,
    onSuccess: () => {
      form.reset("file");
      if (fileInput.value) fileInput.value.value = "";
    },
    forceFormData: true,
  });
};

const handleFileChange = (e) => {
  const file = e.target.files[0];
  form.file = file;
};

const downloadSample = () => {
  window.location.href = "/daily-incomes/sample-excel";
};
</script>

<template>
  <Head>
    <title>Excel Import - Daily Incomes</title>
  </Head>

  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2
          class="font-semibold text-[12px] md:text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.13rem]"
        >
          Excel Import - Daily Incomes
        </h2>
      </div>
    </template>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div
        class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700"
      >
        <Link
          href="/daily-incomes"
          class="text-indigo-600 hover:text-indigo-900 border border-indigo-600 rounded px-3 py-1 text-sm bg-white"
        >
          &larr; Back to Incomes
        </Link>
        <br /><br />
        <div class="mb-6">
          <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
            Instructions
          </h3>
          <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
            To import daily incomes, please download the sample Excel file. Fill
            it with your data and ensure that the "Product Name" matches exactly
            what is recorded in the system. The product pricing (price,
            investment, profit) will be automatically calculated based on the
            Product Name.
          </p>
          <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 font-semibold">
            Note: To group multiple products into a single voucher, make sure
            they all share the exact same "Date", "Is Instant", and "Note"
            values in the Excel sheet.
          </p>

          <SecondaryButton type="button" @click="downloadSample">
            <svg
              class="w-4 h-4 mr-2"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
              ></path>
            </svg>
            Download Sample Excel
          </SecondaryButton>
        </div>

        <hr class="my-6 border-gray-200 dark:border-gray-700" />

        <form @submit.prevent="submit" class="max-w-md">
          <div class="mb-4">
            <label
              for="excelFile"
              class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
            >
              Upload completed file (.xlsx, .xls, .csv):
            </label>
            <input
              id="excelFile"
              ref="fileInput"
              type="file"
              accept=".xlsx,.xls,.csv"
              @change="handleFileChange"
              class="block w-full text-sm text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-md file:border-0
                file:text-sm file:font-semibold
                file:bg-indigo-50 file:text-indigo-700
                hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-indigo-300
              "
              required
            />
            <p
              v-if="form.errors.file"
              class="mt-2 text-sm text-red-600 dark:text-red-400"
            >
              {{ form.errors.file }}
            </p>
          </div>

          <div class="flex items-center gap-4 mt-6">
            <PrimaryButton :disabled="form.processing">
              {{ form.processing ? "Importing..." : "Import Excel" }}
            </PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
