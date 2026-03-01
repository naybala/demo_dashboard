import { useForm } from "@inertiajs/svelte";
import { get, derived } from "svelte/store";

export function useDailyIncomeForm(dailyIncome = null, products = []) {
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
      const product = products.find((p) => p.id === item.own_product_id);

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

  const handleProductChange = (index) => {
    form.update((data) => {
      const items = [...data.items];
      const item = { ...items[index] };
      const product = products.find((p) => p.id === item.own_product_id);

      if (product) {
        if (!item.amount || parseInt(item.amount) === 0) {
          item.amount = "1";
        }

        const amount = parseInt(item.amount) || 0;
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
