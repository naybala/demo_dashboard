import { router } from "@inertiajs/svelte";
import { writable, get } from "svelte/store";

export function useProductActions() {
  const showDeleteModal = writable(false);
  const productToDelete = writable(null);

  const confirmDelete = (product) => {
    productToDelete.set(product);
    showDeleteModal.set(true);
  };

  const deleteProduct = () => {
    const product = get(productToDelete);
    if (product) {
      router.delete("/products", {
        data: { id: product.id },
        onSuccess: () => {
          showDeleteModal.set(false);
          productToDelete.set(null);
        },
      });
    }
  };

  const closeDeleteModal = () => {
    showDeleteModal.set(false);
    productToDelete.set(null);
  };

  return {
    showDeleteModal,
    productToDelete,
    confirmDelete,
    deleteProduct,
    closeDeleteModal,
  };
}
