import { dashboardNav } from "./navigation/dashboard.js";
import { inventoryNav } from "./navigation/inventory.js";
import { salesNav } from "./navigation/sales.js";
import { userManagementNav } from "./navigation/userManagement.js";
import { maintenanceNav } from "./navigation/maintenance.js";
import { schoolNav } from "./navigation/school.js";
import { otherNav } from "./navigation/other.js";

export const navigations = [
  dashboardNav,
  schoolNav,
  inventoryNav,
  salesNav,
  userManagementNav,
  maintenanceNav,
  otherNav,
];
