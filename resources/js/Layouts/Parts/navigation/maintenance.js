export const maintenanceNav = {
  name: "sidebar.maintenance",
  label: "Maintenance",
  items: [
    {
      name: "sidebar.unit",
      label: "Units",
      href: "/units",
      icon: "M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10",
      permission: "manage units",
    },
    {
      name: "sidebar.audit",
      label: "Activity Logs",
      href: "/audits",
      icon: "M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z",
      permission: "manage audits",
    },
  ],
};
