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
import { onMounted, ref, computed } from "vue";
import MultiSelectUi from "../../Components/MultiSelectUi.vue";
import { __ } from "@/helpers";

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

const fileInput = ref(null);

onMounted(async () => {
  const [{ default: Quill }] = await Promise.all([
    import("quill"),
    import("quill/dist/quill.snow.css"),
  ]);

  const toolbarOptions = [
    ["bold", "italic", "underline", "strike"],
    ["blockquote", "code-block"],
    [{ header: 1 }, { header: 2 }],
    [{ list: "ordered" }, { list: "bullet" }],
    [{ script: "sub" }, { script: "super" }],
    [{ indent: "-1" }, { indent: "+1" }],
    [{ direction: "rtl" }],
    [{ size: ["small", false, "large", "huge"] }],
    [{ header: [1, 2, 3, 4, 5, 6, false] }],
    [{ color: [] }, { background: [] }],
    [{ font: [] }],
    [{ align: [] }],
    ["image", "clean"],
  ];

  const quillEn = new Quill("#editor-en", {
    theme: "snow",
    modules: { toolbar: toolbarOptions },
  });

  const quillOther = new Quill("#editor-other", {
    theme: "snow",
    modules: { toolbar: toolbarOptions },
  });

  quillEn.root.innerHTML = form.description;
  quillOther.root.innerHTML = form.description_other;

  quillEn.on("text-change", () => {
    form.description = quillEn.root.innerHTML;
  });

  quillOther.on("text-change", () => {
    form.description_other = quillOther.root.innerHTML;
  });
});

const getObjectURL = (file) => URL.createObjectURL(file);
const revokeObjectURL = (url) => URL.revokeObjectURL(url);
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
        <div>
          <InputLabel :value="__('product.photo', 'Product Photos')" />
          <div class="mt-2 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <div
              v-for="path in form.existing_photos"
              :key="path"
              class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200"
            >
              <img
                :src="path"
                alt="product"
                class="w-full h-full object-cover"
              />
              <button
                type="button"
                @click="removeExistingPhoto(path)"
                class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity"
              >
                <svg
                  class="w-4 h-4"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                  />
                </svg>
              </button>
            </div>

            <div
              v-for="(file, i) in form.photos"
              :key="i"
              class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 bg-gray-50 flex items-center justify-center"
            >
              <img
                :src="getObjectURL(file)"
                alt="preview"
                class="w-full h-full object-cover"
                @load="revokeObjectURL($event.target.src)"
              />
              <button
                type="button"
                @click="removeNewPhoto(i)"
                class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 shadow-sm"
              >
                <svg
                  class="w-4 h-4"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                  />
                </svg>
              </button>
            </div>

            <button
              type="button"
              @click="fileInput.click()"
              class="aspect-square rounded-lg border-2 border-dashed border-gray-300 flex flex-col items-center justify-center text-gray-500 hover:border-indigo-500 hover:text-indigo-500 transition-colors"
            >
              <svg
                class="w-8 h-8"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 4v16m8-8H4"
                />
              </svg>
              <span class="mt-1 text-xs">{{
                __("product.add_photo", "Add Photo")
              }}</span>
            </button>
            <input
              type="file"
              multiple
              class="hidden"
              ref="fileInput"
              @change="handleFileChange"
              accept="image/*"
            />
          </div>
          <InputError :message="form.errors.photos" />
        </div>

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

        <div>
          <InputLabel :value="__('product.description', 'Description (EN)')" />
          <div
            class="mt-1 bg-white dark:bg-gray-900 rounded-md border border-gray-300 dark:border-gray-700 min-h-[200px]"
            id="editor-en"
          ></div>
          <InputError :message="form.errors.description" />
        </div>

        <div>
          <InputLabel
            :value="__('product.description_other', 'Description (Other)')"
          />
          <div
            class="mt-1 bg-white dark:bg-gray-900 rounded-md border border-gray-300 dark:border-gray-700 min-h-[200px]"
            id="editor-other"
          ></div>
          <InputError :message="form.errors.description_other" />
        </div>

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

<style>
/* Add any Quill-specific styles if needed */
.ql-editor {
  min-height: 200px;
}
</style>
