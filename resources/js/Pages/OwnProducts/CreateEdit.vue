<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SearchableSelect from "@/Components/SearchableSelect.vue";
import { router, usePage, Head } from "@inertiajs/vue3";
import CurrencyInput from "@/Components/CurrencyInput.vue";
import { useOwnProductForm } from "./useOwnProductForm";
import { __ } from "@/helpers.js";
import { computed } from "vue";

const props = defineProps({
  ownProduct: {
    type: Object,
    default: null,
  },
  categories: {
    type: Array,
    default: () => [],
  },
  units: {
    type: Array,
    default: () => [],
  },
});

const page = usePage();
const permissions = computed(() => page.props.permissions || []);

const categoryOptions = computed(() =>
  props.categories.map((c) => ({ id: c.id, label: c.name })),
);
const unitOptions = computed(() =>
  props.units.map((u) => ({ id: u.id, label: u.name })),
);

const { form, imagePreview, handleImageChange, submit } =
  useOwnProductForm(props.ownProduct);
</script>

<template>
  <Head>
    <title>
      {{
        __(
          props.ownProduct
            ? "ownProduct.edit_own_product"
            : "ownProduct.create_own_product",
        )
      }}
    </title>
  </Head>

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-[12px] md:text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem] hidden md:block"
      >
        {{ __(props.ownProduct ? "sidebar.own_product" : "sidebar.own_product") }}
      </h2>
    </template>

    <PageHeader
      class="block md:hidden"
      :title="
        __(
          props.ownProduct
            ? 'ownProduct.edit_own_product'
            : 'ownProduct.create_own_product',
        )
      "
    />

    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
      <form @submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <div class="flex flex-col items-center justify-center mb-6">
            <div
              class="relative group w-72 h-72 rounded-md overflow-hidden border-2 border-gray-300 dark:border-gray-700"
            >
              <img
                v-if="imagePreview"
                :src="imagePreview"
                alt="preview"
                class="w-72 h-72 object-cover"
              />
              <div
                v-else
                class="w-72 h-72 bg-gray-100 flex items-center justify-center text-gray-400"
              >
                <svg
                  class="w-72 h-72"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                  />
                </svg>
              </div>
              <label
                class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer text-white text-xs font-bold"
              >
                {{ __("ownProduct.add_photo", "Change Image") }}
                <input
                  type="file"
                  class="hidden"
                  @change="handleImageChange"
                  accept="image/*"
                />
              </label>
            </div>
            <InputError :message="form.errors.image" class="mt-2" />
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 col-span-2">
            <div>
              <InputLabel
                for="name"
                :value="__('ownProduct.name', 'Product Name')"
              />
              <TextInput
                id="name"
                type="text"
                class="mt-1 block w-full"
                v-model="form.name"
                required
              />
              <InputError :message="form.errors.name" />
            </div>
            <div>
              <InputLabel for="price" :value="__('ownProduct.price', 'Price')" />
              <CurrencyInput
                id="price"
                class="mt-1 block w-full"
                v-model="form.price"
                required
              />
              <InputError :message="form.errors.price" />
            </div>

            <div>
              <InputLabel
                for="investment"
                :value="__('ownProduct.investment', 'Investment')"
              />
              <CurrencyInput
                id="investment"
                class="mt-1 block w-full"
                v-model="form.investment"
                required
              />
              <InputError :message="form.errors.investment" />
            </div>

            <div>
              <InputLabel
                for="profit"
                :value="__('ownProduct.profit', 'Profit')"
              />
              <CurrencyInput
                id="profit"
                class="mt-1 block w-full"
                v-model="form.profit"
                required
              />
              <InputError :message="form.errors.profit" />
            </div>
            <div>
              <InputLabel
                for="category"
                :value="__('ownProduct.category_id', 'Category')"
              />
              <div class="mt-1">
                <SearchableSelect
                  :options="categoryOptions"
                  v-model:value="form.category_id"
                  :placeholder="
                    __('placeholder.select_category', 'Select Category')
                  "
                  required
                />
              </div>
              <InputError :message="form.errors.category_id" />
            </div>

            <div>
              <InputLabel for="unit" :value="__('ownProduct.unit_id', 'Unit')" />
              <div class="mt-1">
                <SearchableSelect
                  :options="unitOptions"
                  v-model:value="form.unit_id"
                  :placeholder="__('placeholder.select_unit', 'Select Unit')"
                  required
                />
              </div>
              <InputError :message="form.errors.unit_id" />
            </div>
          </div>
        </div>

        <div class="flex items-center justify-end gap-4 mt-6">
          <SecondaryButton @click="router.get('/own-products')">
            {{ __("messages.cancel", "Cancel") }}
          </SecondaryButton>
          <template
            v-if="
              (props.ownProduct && permissions.includes('edit own-products')) ||
              (!props.ownProduct && permissions.includes('create own-products'))
            "
          >
            <PrimaryButton type="submit" :disabled="form.processing">
              {{
                props.ownProduct
                  ? __("ownProduct.edit_own_product", "Update Own Product")
                  : __("ownProduct.create_own_product", "Create Own Product")
              }}
            </PrimaryButton>
          </template>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>
