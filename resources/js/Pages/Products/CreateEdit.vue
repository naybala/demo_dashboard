<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useProductForm } from "./useProductForm";
import { usePage, router, Head } from "@inertiajs/vue3";
import CurrencyInput from "@/Components/CurrencyInput.vue";
import { computed } from "vue";
import MultiSelectUi from "../../Components/MultiSelectUi.vue";
import { __ } from "@/helpers";
import RichTextEditor from "@/Components/RichTextEditor.vue";
import ProductPhotoGallery from "./Partials/ProductPhotoGallery.vue";

const props = defineProps({
  product: {
    type: Object,
    default: null,
  },
  categories: {
    type: Array,
    default: () => [],
  },
});

const page = usePage();
const permissions = computed(() => page.props.permissions || []);

const { form, handleFileChange, removeNewPhoto, removeExistingPhoto, submit } =
  useProductForm(props.product);
</script>

<template>
  <Head>
    <title>
      {{
        __(props.product ? "product.edit_product" : "product.create_product")
      }}
    </title>
  </Head>

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem] hidden md:block"
      >
        {{ __("sidebar.product", "Products") }}
      </h2>
    </template>
    <PageHeader
      class="block md:hidden"
      :title="
        __(
          props.product ? 'product.edit_product' : 'product.create_product',
          props.product ? 'Edit Product' : 'Create Product',
        )
      "
    />

    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
      <form @submit.prevent="submit" class="space-y-6">
        <!-- Image Upload -->
        <ProductPhotoGallery
          :photos="form.photos"
          :existing_photos="form.existing_photos"
          :handleFileChange="handleFileChange"
          :removeNewPhoto="removeNewPhoto"
          :removeExistingPhoto="removeExistingPhoto"
          :error="form.errors.photos"
        />

        <div
          class="grid grid-cols-1 md:grid-cols-3 gap-6 border border-gray-200 p-3 rounded-2xl"
        >
          <div>
            <InputLabel for="name" :value="__('product.name', 'Name (EN)')" />
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
            <InputLabel
              for="name_other"
              :value="__('product.name_other', 'Name (Other)')"
            />
            <TextInput
              id="name_other"
              type="text"
              class="mt-1 block w-full"
              v-model="form.name_other"
              required
            />
            <InputError :message="form.errors.name_other" />
          </div>

          <div>
            <InputLabel for="price" :value="__('product.price', 'Price')" />
            <CurrencyInput
              id="price"
              class="mt-1 block w-full"
              v-model="form.price"
              required
            />
            <InputError :message="form.errors.price" />
          </div>
        </div>

        <MultiSelectUi
          :options="props.categories"
          :label="__('product.category', 'Categories')"
          v-model="form.categories"
          :error="form.errors.categories"
          :placeholder="__('messages.search_item', 'Select categories...')"
        />

        <div class="flex gap-6">
          <label class="inline-flex items-center">
            <input
              type="checkbox"
              class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
              v-model="form.is_banner"
            />
            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">
              {{ __("product.is_banner", "Is Banner") }}
            </span>
          </label>
          <label class="inline-flex items-center">
            <input
              type="checkbox"
              class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
              v-model="form.is_mini_banner"
            />
            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">
              {{ __("product.is_mini_banner", "Is Mini Banner") }}
            </span>
          </label>
        </div>

        <RichTextEditor
          v-model="form.description"
          uploadedPath="products/quill"
          :label="__('product.description', 'Description (EN)')"
          :error="form.errors.description"
        />

        <RichTextEditor
          v-model="form.description_other"
          uploadedPath="products/quill"
          :label="__('product.description_other', 'Description (Other)')"
          :error="form.errors.description_other"
        />

        <div class="flex items-center justify-end gap-4">
          <SecondaryButton @click="router.get('/products')">
            {{ __("messages.cancel", "Cancel") }}
          </SecondaryButton>
          <template
            v-if="
              (props.product && permissions.includes('edit products')) ||
              (!props.product && permissions.includes('create products'))
            "
          >
            <PrimaryButton type="submit" :disabled="form.processing">
              {{
                props.product
                  ? __("product.edit_product", "Update Product")
                  : __("product.create_product", "Create Product")
              }}
            </PrimaryButton>
          </template>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>
