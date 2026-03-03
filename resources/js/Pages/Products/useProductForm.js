import { useForm } from "@inertiajs/svelte";
import { get } from "svelte/store";

export function useProductForm(product = null) {
  const form = useForm({
    name: product?.name || "",
    name_other: product?.name_other || "",
    price: product?.price || "",
    description: product?.description || "",
    description_other: product?.description_other || "",
    categories: product?.category_ids || [],
    is_banner: product?.is_banner || false,
    is_mini_banner: product?.is_mini_banner || false,
    photos: [],
    existing_photos: product?.photo_paths || [],
  });

  const handleFileChange = (e) => {
    const files = Array.from(e.target.files);
    form.update((data) => ({
      ...data,
      photos: [...data.photos, ...files],
    }));
  };

  const removeNewPhoto = (index) => {
    form.update((data) => ({
      ...data,
      photos: data.photos.filter((_, i) => i !== index),
    }));
  };

  const removeExistingPhoto = (path) => {
    form.update((data) => ({
      ...data,
      existing_photos: data.existing_photos.filter((p) => p !== path),
    }));
  };

  const submit = () => {
    const formInstance = get(form);
    if (product) {
      formInstance
        .transform((data) => ({
          ...data,
          _method: "PUT",
        }))
        .post(`/products/${product.id}`);
    } else {
      formInstance.post("/products");
    }
  };

  return {
    form,
    handleFileChange,
    removeNewPhoto,
    removeExistingPhoto,
    submit,
  };
}
