import { useForm } from "@inertiajs/svelte";
import { get, derived } from "svelte/store";

export function useDailyIncomeForm(dailyIncome = null, products = []) {
  // Registry of products to handle async-fetched ones
  let productRegistry = [...products];

  const updateRegistry = (product) => {
    if (product && !productRegistry.find((p) => p.id === product.id)) {
      productRegistry.push(product);
    }
  };

  const form = useForm({
    warehouse_id: dailyIncome?.warehouse_id || "",
    date: dailyIncome?.date || new Date().toISOString().split("T")[0],
    is_instant: dailyIncome?.is_instant ?? true,
    note: dailyIncome?.note || "",
    items: dailyIncome?.items?.map((item) => ({
      own_product_id: item.own_product_id,
      amount: Math.round(
        parseFloat(item.amount.toString().replace(/,/g, "")),
      ).toString(),
      price: item.price.toString().replace(/,/g, ""),
      investment: item.investment.toString().replace(/,/g, ""),
      profit: item.profit.toString().replace(/,/g, ""),
      id: item.id,
      stock_left: null,
    })) || [
      { own_product_id: "", amount: "", price: "", investment: "", profit: "", stock_left: null },
    ],
  });

  const addItem = () => {
    form.update((data) => ({
      ...data,
      items: [
        ...data.items,
        {
          own_product_id: "",
          amount: "",
          price: "",
          investment: "",
          profit: "",
          stock_left: null,
        },
      ],
    }));
  };

  const removeItem = (index) => {
    form.update((data) => {
      if (data.items.length > 1) {
        return {
          ...data,
          items: data.items.filter((_, i) => i !== index),
        };
      }
      return data;
    });
  };

  const calculateProfit = (index) => {
    form.update((data) => {
      const items = [...data.items];
      const item = { ...items[index] };
      const product = productRegistry.find((p) => p.id === item.own_product_id);

      if (product) {
        const amount = parseInt(item.amount.toString().replace(/,/g, "")) || 0;
        const unitPrice =
          parseFloat(product.price.toString().replace(/,/g, "")) || 0;
        const unitInv =
          parseFloat(product.investment.toString().replace(/,/g, "")) || 0;

        item.price = (amount * unitPrice).toFixed(2);
        item.investment = (amount * unitInv).toFixed(2);
        item.profit = (amount * (unitPrice - unitInv)).toFixed(2);
        items[index] = item;
      }
      return { ...data, items };
    });
  };

  const fetchStockLeft = async (index) => {
    const data = get(form);
    const item = data.items[index];
    const warehouseId = data.warehouse_id;
    const ownProductId = item.own_product_id;

    if (!warehouseId || !ownProductId) {
      form.update((d) => {
        const items = [...d.items];
        items[index] = { ...items[index], stock_left: null };
        return { ...d, items };
      });
      return;
    }

    try {
      const response = await fetch(`/inventories/check-stock?warehouse_id=${encodeURIComponent(warehouseId)}&own_product_id=${encodeURIComponent(ownProductId)}`);
      const result = await response.json();
      form.update((d) => {
        const items = [...d.items];
        items[index] = { ...items[index], stock_left: result.quantity };
        return { ...d, items };
      });
    } catch (err) {
      console.error("Failed to fetch stock:", err);
    }
  };

  const fetchAllStock = async () => {
    const data = get(form);
    const warehouseId = data.warehouse_id;
    if (!warehouseId) {
      form.update((d) => {
        const items = d.items.map(item => ({ ...item, stock_left: null }));
        return { ...d, items };
      });
      return;
    }

    for (let i = 0; i < data.items.length; i++) {
      if (data.items[i].own_product_id) {
        await fetchStockLeft(i);
      }
    }
  };

  const handleProductChange = (index, selectedProduct = null) => {
    if (selectedProduct) updateRegistry(selectedProduct);

    form.update((data) => {
      const items = [...data.items];
      const item = { ...items[index] };
      const product =
        selectedProduct ||
        productRegistry.find((p) => p.id === item.own_product_id);

      if (product) {
        if (!item.amount || parseInt(item.amount) === 0) {
          item.amount = "1";
        }

        const amount = parseInt(item.amount.toString().replace(/,/g, "")) || 0;
        const unitPrice =
          parseFloat(product.price.toString().replace(/,/g, "")) || 0;
        const unitInv =
          parseFloat(product.investment.toString().replace(/,/g, "")) || 0;

        item.price = (amount * unitPrice).toFixed(2);
        item.investment = (amount * unitInv).toFixed(2);
        item.profit = (amount * (unitPrice - unitInv)).toFixed(2);
        items[index] = item;
      }
      return { ...data, items };
    });

    fetchStockLeft(index);
  };

  const submit = () => {
    const formInstance = get(form);
    if (dailyIncome) {
      formInstance.put(`/daily-incomes/${dailyIncome.id}`);
    } else {
      formInstance.post("/daily-incomes");
    }
  };

  // Derived store for total amount
  const totalAmount = derived(form, ($form) => {
    return $form.items.reduce(
      (sum, item) => sum + (parseFloat(item.price) || 0),
      0,
    );
  });

  // Derived store to validate stock levels
  const isStockSufficient = derived(form, ($form) => {
    if (!$form.warehouse_id) return true;
    return $form.items.every((item) => {
      if (!item.own_product_id || item.stock_left === null) return true;
      const amount = parseInt(item.amount.toString().replace(/,/g, "")) || 0;
      return amount <= item.stock_left;
    });
  });

  // Fetch initial stock levels on mount
  setTimeout(() => {
    fetchAllStock();
  }, 100);

  return {
    form,
    addItem,
    removeItem,
    handleProductChange,
    calculateProfit,
    submit,
    totalAmount,
    fetchAllStock,
    isStockSufficient,
  };
}
