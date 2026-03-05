import { writable, get } from "svelte/store";
import { router, page } from "@inertiajs/svelte";
import { __ } from "@/helpers.js";

// State
export const isSidebarOpen = writable(
  typeof window !== "undefined" ? window.innerWidth >= 1024 : true,
);
export const isDarkMode = writable(false);
export const showLogoutModal = writable(false);
export const isOnline = writable(
  typeof window !== "undefined" ? window.navigator.onLine : true,
);
export const globalLoading = writable(false);

// Helpers
export const toggleTheme = () => {
  isDarkMode.update((dark) => {
    const next = !dark;
    if (next) {
      document.documentElement.classList.add("dark");
      localStorage.theme = "dark";
    } else {
      document.documentElement.classList.remove("dark");
      localStorage.theme = "light";
    }
    return next;
  });
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
  const $page = get(page);
  if (href === "/dashboard") return $page.url === href;
  return $page.url.startsWith(href);
};

export const canSee = (item) => {
  const $page = get(page);
  const permissions = $page.props.permissions || [];
  return !item.permission || permissions.includes(item.permission);
};

export const handleLogoutClick = () => {
  showLogoutModal.set(true);
};

export const confirmLogout = () => {
  router.post("/logout");
  showLogoutModal.set(false);
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
    isDarkMode.set(true);
  } else {
    document.documentElement.classList.remove("dark");
    isDarkMode.set(false);
  }

  // Connectivity listeners
  const handleOnline = () => isOnline.set(true);
  const handleOffline = () => isOnline.set(false);

  window.addEventListener("online", handleOnline);
  window.addEventListener("offline", handleOffline);

  // Global loading state
  const unregisterStart = router.on("start", () => {
    globalLoading.set(true);
  });

  const unregisterFinish = router.on("finish", () => {
    // Small delay to ensure the loading screen is seen and doesn't flicker
    setTimeout(() => {
      globalLoading.set(false);
    }, 400);
  });

  // Auto-close sidebar on navigation for mobile/tablet
  const unregisterFinishSidebar = router.on("finish", () => {
    if (window.innerWidth < 1024) {
      isSidebarOpen.set(false);
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
