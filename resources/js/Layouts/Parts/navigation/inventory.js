export const inventoryNav = {
  name: "sidebar.inventory",
  label: "Inventory",
  items: [
    {
      name: "sidebar.category",
      label: "Categories",
      href: "/categories",
      icon: "M4 6h16M4 10h16M4 14h16M4 18h16",
      permission: "manage categories",
    },
    {
      name: "sidebar.product",
      label: "Products",
      href: "/products",
      icon: "M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4",
      permission: "manage products",
    },
    {
      name: "sidebar.own_product",
      label: "Own Products",
      href: "/own-products",
      icon: "M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01",
      permission: "manage own-products",
    },
    {
      name: "sidebar.warehouses",
      label: "Warehouses",
      href: "/warehouses",
      icon: "M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6",
      permission: "manage warehouses",
    },
    {
      name: "sidebar.stock_transactions",
      label: "Stock In / Out",
      href: "/stock-transactions",
      icon: "M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4",
      permission: "manage stock-transactions",
    },
    {
      name: "sidebar.inventories",
      label: "Stock Levels",
      href: "/inventories",
      icon: "M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z",
      permission: "manage inventories",
    },
  ],
};
