import { router } from "@inertiajs/svelte";
import { writable, get } from "svelte/store";

export function useCategoryActions() {
  const showModal = writable(false);
  const showDeleteModal = writable(false);
  const editingCategory = writable(null);
  const deletingCategory = writable(null);

  const openCreateModal = () => {
    editingCategory.set(null);
    showModal.set(true);
  };

  const openEditModal = (category) => {
    editingCategory.set(category);
    showModal.set(true);
  };

  const openDeleteModal = (category) => {
    deletingCategory.set(category);
    showDeleteModal.set(true);
  };

  const closeDeleteModal = () => {
    showDeleteModal.set(false);
  };

  const confirmDelete = () => {
    const category = get(deletingCategory);
    if (category) {
      router.delete(`/categories/${category.id}`, {
        onSuccess: () => {
          closeDeleteModal();
        },
      });
    }
  };

  return {
    showModal,
    showDeleteModal,
    editingCategory,
    deletingCategory,
    openCreateModal,
    openEditModal,
    openDeleteModal,
    closeDeleteModal,
    confirmDelete,
  };
}
