import { useForm } from "@inertiajs/vue3";

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
    form.photos = [...form.photos, ...files];
  };

  const removeNewPhoto = (index) => {
    form.photos = form.photos.filter((_, i) => i !== index);
  };

  const removeExistingPhoto = (path) => {
    form.existing_photos = form.existing_photos.filter((p) => p !== path);
  };

  const submit = () => {
    if (product) {
      form
        .transform((data) => ({
          ...data,
          _method: "PUT",
        }))
        .post(`/products/${product.id}`);
    } else {
      form.post("/products");
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
