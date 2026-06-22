<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import { useDailyIncomeForm } from "./useDailyIncomeForm";
  import { page } from "@inertiajs/svelte";
  import { __, formatNumber } from "@/helpers.js";

  // Child Components
  import WarehouseSelect from "./Components/WarehouseSelect.svelte";
  import ReadOnlyOrderView from "./Components/ReadOnlyOrderView.svelte";
  import PosWorkspace from "./Components/PosWorkspace.svelte";

  export let dailyIncome = null;
  export let products = [];
  export let warehouses = [];
  export let categories = [];

  $: permissions = $page.props.permissions || [];

  $: warehouseOptions = warehouses.map((w) => ({
    id: w.id,
    label: `${w.name}${w.location ? ` (${w.location})` : ""}`,
    searchKey: w.name,
  }));

  const {
    form,
    removeItem,
    calculateProfit,
    submit,
    totalAmount: totalAmountStore,
    fetchAllStock,
    isStockSufficient,
    handleProductClick,
    updateQuantity,
    clearCart,
    productRegistry,
  } = useDailyIncomeForm(dailyIncome, products);

  $: totalAmount = formatNumber($totalAmountStore, 0);

  // POS State
  let selectedCategory = "all";
  let searchQueryInput = "";
  let searchQuery = "";
  let posProductsList = [];
  let isLoadingProducts = false;
  let searchTimeout;

  function handleSearch() {
    searchQuery = searchQueryInput;
  }

  // Look up product in registry to show details in cart
  const getProductDetails = (productId) => {
    return (
      productRegistry.find((p) => p.id === productId) || {
        name: "Loading product...",
        price: 0,
        image: null,
        unit: "",
      }
    );
  };

  async function fetchPosProducts() {
    if (!$form.warehouse_id) {
      posProductsList = [];
      return;
    }
    isLoadingProducts = true;
    try {
      const url = `/own-products/pos?warehouse_id=${encodeURIComponent($form.warehouse_id)}&category_id=${selectedCategory}&keyword=${encodeURIComponent(searchQuery)}`;
      const response = await fetch(url, {
        headers: { Accept: "application/json" },
      });
      if (response.ok) {
        const result = await response.json();
        posProductsList = result.data || [];
      }
    } catch (e) {
      console.error("Error fetching POS products:", e);
    } finally {
      isLoadingProducts = false;
    }
  }

  // Reactively fetch POS products with debounce when filters change
  $: {
    if (
      $form.warehouse_id !== undefined ||
      selectedCategory !== undefined ||
      searchQuery !== undefined
    ) {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        fetchPosProducts();
      }, 300);
    }
  }

  // Reactively update stock for currently loaded items if warehouse switches
  $: if ($form.warehouse_id) {
    fetchAllStock();
    if (typeof window !== "undefined") {
      sessionStorage.setItem("pos_warehouse_id", $form.warehouse_id);
    }
  } else {
    if (typeof window !== "undefined") {
      sessionStorage.removeItem("pos_warehouse_id");
    }
  }
</script>

<svelte:head>
  <title>
    {__(
      dailyIncome
        ? "dailyIncome.edit_daily_income"
        : "dailyIncome.create_daily_income",
    )}
  </title>
</svelte:head>

<AdminLayout>
  <svelte:fragment slot="header">
    <div class="flex items-center justify-between py-[0.20rem] hidden md:flex">
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
      >
        {dailyIncome
          ? __("dailyIncome.edit_daily_income", "Edit Daily Income")
          : __("dailyIncome.create_daily_income", "Create Daily Income")}
      </h2>
    </div>
  </svelte:fragment>

  <PageHeader
    class="block md:hidden"
    title={dailyIncome
      ? __("dailyIncome.edit_daily_income", "Edit Daily Income")
      : __("dailyIncome.create_daily_income", "Create Daily Income")}
  />

  <div class="w-full">
    {#if dailyIncome}
      <ReadOnlyOrderView
        {dailyIncome}
        {form}
        {warehouses}
        {getProductDetails}
        {totalAmount}
        {permissions}
        {submit}
      />
    {:else if !$form.warehouse_id}
      <WarehouseSelect
        {warehouseOptions}
        bind:value={$form.warehouse_id}
        errors={$form.errors}
        {fetchAllStock}
      />
    {:else}
      <PosWorkspace
        {form}
        bind:selectedCategory
        bind:searchQueryInput
        {posProductsList}
        {isLoadingProducts}
        {categories}
        {warehouses}
        {totalAmount}
        {permissions}
        {isStockSufficient}
        {handleSearch}
        {handleProductClick}
        {updateQuantity}
        {removeItem}
        {clearCart}
        {getProductDetails}
        {calculateProfit}
        {submit}
      />
    {/if}
  </div>
</AdminLayout>
