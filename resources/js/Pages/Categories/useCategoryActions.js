import { router } from "@inertiajs/vue3";
import { ref } from "vue";

export function useCategoryActions() {
  const showModal = ref(false);
  const showDeleteModal = ref(false);
  const editingCategory = ref(null);
  const deletingCategory = ref(null);

  const openCreateModal = () => {
    editingCategory.value = null;
    showModal.value = true;
  };

  const openEditModal = (category) => {
    editingCategory.value = category;
    showModal.value = true;
  };

  const openDeleteModal = (category) => {
    deletingCategory.value = category;
    showDeleteModal.value = true;
  };

  const closeDeleteModal = () => {
    showDeleteModal.value = false;
  };

  const confirmDelete = () => {
    const category = deletingCategory.value;
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
