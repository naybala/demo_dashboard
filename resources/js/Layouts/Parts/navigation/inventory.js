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
  ],
};
