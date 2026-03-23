<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import BaseTable from "@/Components/BaseTable.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { usePage, router, Link, Head } from "@inertiajs/vue3";
import DeleteConfirmationModal from "@/Components/DeleteConfirmationModal.vue";
import Pagination from "@/Components/Pagination.vue";
import { __, formatNumber } from "@/helpers.js";
import { ref, computed } from "vue";

const props = defineProps({
  data: {
    type: Array,
    default: () => [],
  },
  meta: {
    type: Object,
    default: () => ({}),
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
});

const page = usePage();
const permissions = computed(() => page.props.permissions || []);

const search = ref(props.filters.keyword || "");
const fromDate = ref(props.filters.from_date || "");
const toDate = ref(props.filters.to_date || "");
const isInstant = ref(props.filters.is_instant || "");
const showDeleteModal = ref(false);
const incomeToDelete = ref(null);

const headers = [
  { key: "date", label: __("table.date", "Date") },
  { key: "voucher_no", label: __("table.voucher_no", "Voucher No") },
  { key: "own_product", label: __("table.product_name", "Product") },
  { key: "amount", label: __("table.amount", "Amount") },
  { key: "price", label: __("table.price", "Price") },
  { key: "profit", label: __("table.profit", "Profit") },
  { key: "is_instant", label: "Is Instant" },
  { key: "actions", label: __("table.action", "Actions") },
];

const handleFilter = () => {
  router.get(
    "/daily-incomes",
    {
      keyword: search.value,
      from_date: fromDate.value,
      to_date: toDate.value,
      is_instant: isInstant.value,
    },
    { preserveState: true, replace: true },
  );
};

const confirmDelete = (income) => {
  incomeToDelete.value = income;
  showDeleteModal.value = true;
};

const deleteIncome = () => {
  router.delete(`/daily-incomes/${incomeToDelete.value.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false;
      incomeToDelete.value = null;
    },
  });
};

const resetFilters = () => {
  search.value = "";
  fromDate.value = "";
  toDate.value = "";
  isInstant.value = "";
  handleFilter();
};
</script>

<template>
  <Head>
    <title>{{ __("sidebar.daily_income") }}</title>
  </Head>

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-[12px] md:text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.13rem]"
      >
        {{ __("sidebar.daily_income", "Daily Incomes") }}
      </h2>
    </template>

    <div
      class="mb-6 bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm"
    >
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 items-end">
        <div>
          <label
            for="search"
            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
            >{{ __("messages.search", "Search") }}</label
          >
          <TextInput
            id="search"
            type="text"
            :placeholder="
              __('placeholder.voucher_or_product', 'Voucher or Product...')
            "
            v-model="search"
            class="w-full"
          />
        </div>
        <div>
          <label
            for="from_date"
            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
            >{{ __("messages.from_date", "From Date") }}</label
          >
          <input
            type="date"
            id="from_date"
            v-model="fromDate"
            class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
          />
        </div>
        <div>
          <label
            for="to_date"
            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
            >{{ __("messages.to_date", "To Date") }}</label
          >
          <input
            type="date"
            id="to_date"
            v-model="toDate"
            class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
          />
        </div>
        <div>
          <label
            for="is_instant"
            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
            >{{ __("dailyIncome.is_instant", "Is Instant") }}</label
          >
          <select
            id="is_instant"
            v-model="isInstant"
            class="w-full py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
          >
            <option value="">All</option>
            <option value="1">Yes</option>
            <option value="0">No</option>
          </select>
        </div>
      </div>
      <div class="flex flex-wrap justify-between mt-3 gap-2">
        <div>
          <Link
            v-if="permissions.includes('excel daily-incomes')"
            href="/daily-incomes/import"
            class="md:ml-auto mr-2"
          >
            <PrimaryButton>
              <svg
                class="w-4 h-4 mr-1 inline-block"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                ></path>
              </svg>
              {{ __("dailyIncome.excel_import", "Excel Import") }}
            </PrimaryButton>
          </Link>
        </div>
        <div class="flex flex-wrap justify-end gap-2">
          <div>
            <PrimaryButton @click="handleFilter">{{
              __("messages.filter", "Filter")
            }}</PrimaryButton>
          </div>
          <div>
            <SecondaryButton @click="resetFilters">{{
              __("messages.reset", "Reset")
            }}</SecondaryButton>
          </div>

          <div v-if="permissions.includes('create daily-incomes')">
            <Link href="/daily-incomes/create" class="md:ml-auto">
              <PrimaryButton>{{
                __("dailyIncome.create_daily_income", "Create Daily Income")
              }}</PrimaryButton>
            </Link>
          </div>
        </div>
      </div>
    </div>

    <BaseTable :headers="headers">
      <tr
        v-for="(income, i) in data"
        :key="income.id"
        class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
      >
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {{ income.date }}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
        >
          {{
            i > 0 && data[i - 1].voucher_no === income.voucher_no
              ? " "
              : income.voucher_no || "N/A"
          }}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"
        >
          {{ income.own_product }}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {{ formatNumber(income.amount, 0) }}
          {{ income.unit }}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {{ formatNumber(income.price, 0) }}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {{ formatNumber(income.profit, 0) }}
        </td>
        <td
          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
        >
          {{ income.is_instant ? "Yes" : "No" }}
        </td>
        <td class="flex gap-2 px-6 py-4">
          <Link :href="`/daily-incomes/${income.id}`">
            <SecondaryButton>{{ __("messages.view", "View") }}</SecondaryButton>
          </Link>
          <Link
            v-if="permissions.includes('edit daily-incomes')"
            :href="`/daily-incomes/${income.id}/edit`"
          >
            <SecondaryButton>{{ __("messages.edit", "Edit") }}</SecondaryButton>
          </Link>
          <SecondaryButton
            v-if="permissions.includes('delete daily-incomes')"
            variant="danger"
            @click="confirmDelete(income)"
          >
            {{ __("messages.delete", "Delete") }}
          </SecondaryButton>
        </td>
      </tr>
    </BaseTable>

    <Pagination :meta="meta" />

    <DeleteConfirmationModal
      :show="showDeleteModal"
      :title="__('dailyIncome.delete_title', 'Delete Daily Income')"
      :message="
        __(
          'dailyIncome.delete_message',
          'Are you sure you want to delete this record? If it belongs to a voucher, the entire voucher will be deleted.',
        )
      "
      @confirm="deleteIncome"
      @close="showDeleteModal = false"
    />
  </AdminLayout>
</template>
