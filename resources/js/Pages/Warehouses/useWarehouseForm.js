import { useForm } from "@inertiajs/svelte";
import { get } from "svelte/store";

export function useWarehouseForm(warehouse = null, { onSuccess } = {}) {
  const form = useForm({
    id: warehouse?.id || null,
    name: warehouse?.name || "",
    location: warehouse?.location || "",
    description: warehouse?.description || "",
  });

  const submit = () => {
    const formInstance = get(form);
    if (formInstance.id) {
      formInstance.put(`/warehouses/${formInstance.id}`, {
        onSuccess: () => {
          if (onSuccess) onSuccess();
        },
      });
    } else {
      formInstance.post("/warehouses", {
        onSuccess: () => {
          formInstance.reset();
          if (onSuccess) onSuccess();
        },
      });
    }
  };

  return { form, submit };
}
