import { router } from "@inertiajs/vue3";
import { ref } from "vue";

export function useOwnProductActions() {
  const showDeleteModal = ref(false);
  const productToDelete = ref(null);

  const confirmDelete = (product) => {
    productToDelete.value = product;
    showDeleteModal.value = true;
  };

  const deleteProduct = () => {
    const product = productToDelete.value;
    if (product) {
      router.delete(`/own-products/${product.id}`, {
        onSuccess: () => {
          showDeleteModal.value = false;
          productToDelete.value = null;
        },
      });
    }
  };

  const closeDeleteModal = () => {
    showDeleteModal.value = false;
    productToDelete.value = null;
  };

  return {
    showDeleteModal,
    productToDelete,
    confirmDelete,
    deleteProduct,
    closeDeleteModal,
  };
}
