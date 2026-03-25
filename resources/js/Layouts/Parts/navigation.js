import { dashboardNav } from "./navigation/dashboard.js";
import { salesNav } from "./navigation/sales.js";
import { maintenanceNav } from "./navigation/maintenance.js";
import { otherNav } from "./navigation/other.js";

import { studentManagementNav } from "./navigation/studentManagement.js";
import { teacherStaffManagementNav } from "./navigation/teacherStaffManagement.js";
import { classSubjectManagementNav } from "./navigation/classSubjectManagement.js";
import { examManagementNav } from "./navigation/examManagement.js";
import { userManagementNav } from "./navigation/userManagement.js";

export const navigations = [
  dashboardNav,
  studentManagementNav,
  teacherStaffManagementNav,
  classSubjectManagementNav,
  examManagementNav,
  salesNav,
  maintenanceNav,
  otherNav,
  userManagementNav,
];
