export const classSubjectManagementNav = {
  name: "sidebar.class_subject_management_section",
  label: "",
  items: [
    {
      name: "sidebar.class_subject_management",
      label: "Class & Subject Management",
      icon: "M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253",
      children: [
        {
          name: "sidebar.class_subject_overview",
          label: "Class & Subject",
          href: "/overview-classes", // To be implemented
          permission: "manage users",
        },
        {
          name: "sidebar.classes_list",
          label: "Classes List",
          href: "/classes",
          permission: "manage classes",
        },
        {
          name: "sidebar.subjects",
          label: "Subjects",
          href: "/subjects",
          permission: "manage subjects",
        },
        {
          name: "sidebar.grades",
          label: "Grades",
          href: "/grades",
          permission: "manage grades",
        },
        {
          name: "sidebar.academic_sessions",
          label: "Academic Sessions",
          href: "/academic-sessions",
          permission: "manage academic-sessions",
        },
      ],
    },
  ],
};
