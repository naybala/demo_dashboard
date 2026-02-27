import { router } from "@inertiajs/svelte";

export function useCategoryActions() {
  const deleteCategory = (id) => {
    if (confirm("Are you sure you want to delete this category?")) {
      router.delete(`/categories/${id}`);
    }
  };

  return { deleteCategory };
}
