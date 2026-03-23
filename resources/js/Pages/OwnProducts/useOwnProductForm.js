import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

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

  const imagePreview = ref(ownProduct?.image || null);

  const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      form.image = file;
      imagePreview.value = URL.createObjectURL(file);
    }
  };

  const submit = () => {
    if (ownProduct) {
      form
        .transform((data) => ({
          ...data,
          _method: "PUT",
        }))
        .post(`/own-products/${ownProduct.id}`);
    } else {
      form.post("/own-products");
    }
  };

  return {
    form,
    imagePreview,
    handleImageChange,
    submit,
  };
}
