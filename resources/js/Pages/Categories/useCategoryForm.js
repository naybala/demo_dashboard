import { useForm } from "@inertiajs/vue3";

export function useCategoryForm(category = null, { onSuccess } = {}) {
  const form = useForm({
    id: category?.id || null,
    name: category?.name || "",
    name_other: category?.name_other || "",
    description: category?.description || "",
    description_other: category?.description_other || "",
    is_show: category ? !!category.is_show : true,
  });

  const submit = () => {
    if (form.id) {
      form.put(`/categories/${form.id}`, {
        onSuccess: () => {
          if (onSuccess) onSuccess();
        },
      });
    } else {
      form.post("/categories", {
        onSuccess: () => {
          form.reset();
          if (onSuccess) onSuccess();
        },
      });
    }
  };

  return { form, submit };
}
