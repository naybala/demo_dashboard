import { useForm } from "@inertiajs/svelte";
import { get } from "svelte/store";

export function useCategoryForm(category = null) {
  const form = useForm({
    id: category?.id || null,
    name: category?.name || "",
    name_other: category?.name_other || "",
    description: category?.description || "",
    description_other: category?.description_other || "",
    is_show: category ? !!category.is_show : true,
  });

  const submit = () => {
    const formInstance = get(form);
    if (formInstance.id) {
      formInstance.put(`/categories/${formInstance.id}`, {
        onSuccess: () => {
          // Handle success
        },
      });
    } else {
      formInstance.post("/categories", {
        onSuccess: () => {
          formInstance.reset();
        },
      });
    }
  };

  return { form, submit };
}
