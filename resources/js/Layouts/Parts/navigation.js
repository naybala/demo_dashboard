import { __ } from "@/helpers.js";

export const navigations = [
  {
    name: __("sidebar.dashboard", "Dashboard"),
    href: "/dashboard",
    icon: "M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6",
  },
  {
    name: __("sidebar.inventory", "Inventory"),
    items: [
      {
        name: __("sidebar.category", "Categories"),
        href: "/categories",
        icon: "M4 6h16M4 10h16M4 14h16M4 18h16",
        permission: "manage categories",
      },
      {
        name: __("sidebar.product", "Products"),
        href: "/products",
        icon: "M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4",
        permission: "manage products",
      },
      {
        name: __("sidebar.own_product", "Own Products"),
        href: "/own-products",
        icon: "M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01",
        permission: "manage own-products",
      },
    ],
  },
  {
    name: __("sidebar.sales", "Sales"),
    items: [
      {
        name: __("sidebar.daily_income", "Daily Incomes"),
        href: "/daily-incomes",
        icon: "M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z",
        permission: "manage daily-incomes",
      },
    ],
  },
  {
    name: __("sidebar.user_management", "User Management"),
    items: [
      {
        name: __("sidebar.user", "Users"),
        href: "/users",
        icon: "M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z",
        permission: "manage users",
      },
      {
        name: __("sidebar.role", "Roles"),
        href: "/roles",
        icon: "M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z",
        permission: "manage roles",
      },
    ],
  },
  {
    name: __("sidebar.maintenance", "Maintenance"),
    items: [
      {
        name: __("sidebar.unit", "Units"),
        href: "/units",
        icon: "M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10",
        permission: "manage units",
      },
      {
        name: __("sidebar.audit", "Activity Logs"),
        href: "/audits",
        icon: "M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z",
        permission: "manage audits",
      },
    ],
  },
];
