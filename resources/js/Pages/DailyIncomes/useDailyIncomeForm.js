import { useForm } from "@inertiajs/vue3";
import { computed, reactive } from "vue";

export function useDailyIncomeForm(dailyIncome = null, products = []) {
  // Registry of products to handle async-fetched ones
  const productRegistry = reactive([...products]);

  const updateRegistry = (product) => {
    if (product && !productRegistry.find((p) => p.id === product.id)) {
      productRegistry.push(product);
    }
  };

  const form = useForm({
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
    })) || [
      { own_product_id: "", amount: "", price: "", investment: "", profit: "" },
    ],
  });

  const addItem = () => {
    form.items.push({
      own_product_id: "",
      amount: "",
      price: "",
      investment: "",
      profit: "",
    });
  };

  const removeItem = (index) => {
    if (form.items.length > 1) {
      form.items.splice(index, 1);
    }
  };

  const calculateProfit = (index) => {
    const item = form.items[index];
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
    }
  };

  const handleProductChange = (index, selectedProduct = null) => {
    if (selectedProduct) updateRegistry(selectedProduct);

    const item = form.items[index];
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
    }
  };

  const submit = () => {
    if (dailyIncome) {
      form.put(`/daily-incomes/${dailyIncome.id}`);
    } else {
      form.post("/daily-incomes");
    }
  };

  // Computed for total amount
  const totalAmount = computed(() => {
    return form.items.reduce(
      (sum, item) => sum + (parseFloat(item.price) || 0),
      0,
    );
  });

  return {
    form,
    addItem,
    removeItem,
    handleProductChange,
    calculateProfit,
    submit,
    totalAmount,
  };
}
