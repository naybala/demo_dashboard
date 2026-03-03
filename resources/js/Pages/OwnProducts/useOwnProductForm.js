import { useForm } from "@inertiajs/svelte";
import { writable, get } from "svelte/store";

export function useOwnProductForm(ownProduct = null) {
  const form = useForm({
    name: ownProduct?.name || "",
    category_id: ownProduct?.category_id || "",
    unit_id: ownProduct?.unit_id || "",
    price: ownProduct?.price || "",
    investment: ownProduct?.investment || "",
    profit: ownProduct?.profit || "",
    image: null,
  });

  const imagePreview = writable(ownProduct?.image || null);

  const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      form.update((data) => ({ ...data, image: file }));
      imagePreview.set(URL.createObjectURL(file));
    }
  };

  const submit = () => {
    const formInstance = get(form);
    if (ownProduct) {
      formInstance
        .transform((data) => ({
          ...data,
          _method: "PUT",
        }))
        .post(`/own-products/${ownProduct.id}`);
    } else {
      formInstance.post("/own-products");
    }
  };

  return {
    form,
    imagePreview,
    handleImageChange,
    submit,
  };
}
