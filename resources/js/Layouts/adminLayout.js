import { ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { __ } from "@/helpers.js";

// State
export const isSidebarOpen = ref(
  typeof window !== "undefined" ? window.innerWidth >= 1024 : true,
);
export const isDarkMode = ref(false);
export const showLogoutModal = ref(false);
export const isOnline = ref(
  typeof window !== "undefined" ? window.navigator.onLine : true,
);
export const globalLoading = ref(false);

// Helpers
export const toggleTheme = () => {
  isDarkMode.value = !isDarkMode.value;
  if (isDarkMode.value) {
    document.documentElement.classList.add("dark");
    localStorage.theme = "dark";
  } else {
    document.documentElement.classList.remove("dark");
    localStorage.theme = "light";
  }
};

export const changeLanguage = (lang) => {
  router.get(
    "/change",
    { lang },
    {
      preserveState: false,
    },
  );
};

export const isActive = (href) => {
  const page = usePage();
  if (href === "/dashboard") return page.url === href;
  return page.url.startsWith(href);
};

export const canSee = (item) => {
  const page = usePage();
  const permissions = page.props.permissions || [];
  return !item.permission || permissions.includes(item.permission);
};

export const handleLogoutClick = () => {
  showLogoutModal.value = true;
};

export const confirmLogout = () => {
  router.post("/logout");
  showLogoutModal.value = false;
};

// Lifecycle/Initialization logic
export const initAdminLayout = () => {
  // Theme initialization
  if (
    localStorage.theme === "dark" ||
    (!("theme" in localStorage) &&
      window.matchMedia("(prefers-color-scheme: dark)").matches)
  ) {
    document.documentElement.classList.add("dark");
    isDarkMode.value = true;
  } else {
    document.documentElement.classList.remove("dark");
    isDarkMode.value = false;
  }

  // Connectivity listeners
  const handleOnline = () => (isOnline.value = true);
  const handleOffline = () => (isOnline.value = false);

  window.addEventListener("online", handleOnline);
  window.addEventListener("offline", handleOffline);

  // Global loading state
  const unregisterStart = router.on("start", () => {
    globalLoading.value = true;
  });

  const unregisterFinish = router.on("finish", () => {
    // Small delay to ensure the loading screen is seen and doesn't flicker
    setTimeout(() => {
      globalLoading.value = false;
    }, 300);
  });

  // Auto-close sidebar on navigation for mobile/tablet
  const unregisterFinishSidebar = router.on("finish", () => {
    if (window.innerWidth < 1024) {
      isSidebarOpen.value = false;
    }
  });

  return () => {
    unregisterStart();
    unregisterFinish();
    unregisterFinishSidebar();
    window.removeEventListener("online", handleOnline);
    window.removeEventListener("offline", handleOffline);
  };
};
