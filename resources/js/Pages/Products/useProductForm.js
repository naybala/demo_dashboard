import { useForm } from "@inertiajs/svelte";
import { get } from "svelte/store";

export function useProductForm(product = null) {
  const form = useForm({
    name: product?.name || "",
    name_other: product?.name_other || "",
    price: product?.price?.toString().replace(/,/g, "") || "",
    description: product?.description || "",
    description_other: product?.description_other || "",
    categories: product?.category_ids || [],
    is_banner: product?.is_banner || false,
    is_mini_banner: product?.is_mini_banner || false,
    photos: [],
    existing_photos: product?.photo_paths || [],
  });

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

  return { form, submit };
}
