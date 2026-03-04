<script>
  import { page, router } from "@inertiajs/svelte";
  import { __ } from "@/helpers.js";
  import { onMount } from "svelte";
  import DeleteConfirmationModal from "@/Components/DeleteConfirmationModal.svelte";
  import Sidebar from "./Parts/Sidebar.svelte";
  import TopBar from "./Parts/TopBar.svelte";
  import { navigations } from "./Parts/navigation.js";

  export let user = $page.props.auth.user;

  $: permissions = $page.props.permissions || [];
  // Initialize synchronously to avoid flash — safe since Inertia is client-side only (no SSR)
  let isSidebarOpen =
    typeof window !== "undefined" ? window.innerWidth >= 1024 : true;
  let isDarkMode = false;
  let currentLocale = $page.props.locale || "en";

  let showLogoutModal = false;

  onMount(() => {
    // Theme initialization
    if (
      localStorage.theme === "dark" ||
      (!("theme" in localStorage) &&
        window.matchMedia("(prefers-color-scheme: dark)").matches)
    ) {
      document.documentElement.classList.add("dark");
      isDarkMode = true;
    } else {
      document.documentElement.classList.remove("dark");
      isDarkMode = false;
    }

    // Auto-close sidebar on navigation for mobile/tablet
    const unregisterFinish = router.on("finish", () => {
      if (window.innerWidth < 1024) {
        isSidebarOpen = false;
      }
    });

    return () => {
      unregisterFinish();
    };
  });

  const toggleTheme = () => {
    isDarkMode = !isDarkMode;
    if (isDarkMode) {
      document.documentElement.classList.add("dark");
      localStorage.theme = "dark";
    } else {
      document.documentElement.classList.remove("dark");
      localStorage.theme = "light";
    }
  };

  const changeLanguage = (lang) => {
    router.get("/change", { lang }, { preserveState: false });
  };

  const isActive = (href) => {
    if (href === "/dashboard") return $page.url === href;
    return $page.url.startsWith(href);
  };

  const handleLogoutClick = () => {
    showLogoutModal = true;
  };

  const confirmLogout = () => {
    router.post("/logout");
    showLogoutModal = false;
  };

  // Returns true if the nav item has no permission requirement OR the user has it
  const canSee = (item) =>
    !item.permission || permissions.includes(item.permission);

  $: navigation = navigations;
</script>

<div class="h-dvh flex bg-gray-100 dark:bg-gray-900 overflow-hidden">
  <Sidebar
    {isSidebarOpen}
    {navigation}
    {user}
    {isActive}
    {canSee}
    onClose={() => (isSidebarOpen = false)}
  />

  <!-- Overlay -->
  {#if isSidebarOpen}
    <div
      class="fixed inset-0 bg-gray-900/50 z-40 lg:hidden"
      on:click={() => (isSidebarOpen = false)}
      on:keydown={(e) => e.key === "Escape" && (isSidebarOpen = false)}
      role="button"
      tabindex="0"
      aria-label="Close menu"
    ></div>
  {/if}

  <!-- Main Content -->
  <div
    class={`flex-1 flex flex-col min-w-0 overflow-hidden transition-all duration-300 ${isSidebarOpen ? "lg:pl-64" : ""}`}
  >
    <TopBar
      onToggleSidebar={() => (isSidebarOpen = !isSidebarOpen)}
      {currentLocale}
      onLanguageChange={changeLanguage}
      {isDarkMode}
      onToggleTheme={toggleTheme}
      onLogout={handleLogoutClick}
    >
      <svelte:fragment slot="header">
        <slot name="header" />
      </svelte:fragment>
    </TopBar>

    <!-- Page Content -->
    <main
      class="flex-1 overflow-y-auto p-1 md:p-4 shadow-inner custom-scrollbar bg-gray-50 dark:bg-gray-900/50"
    >
      <div class="mx-auto w-full">
        <slot />
      </div>
    </main>
  </div>

  <DeleteConfirmationModal
    show={showLogoutModal}
    title={__("messages.are_you_sure", "Are you sure?")}
    message={__("messages.login_again", "Are you sure you want to logout?")}
    confirmText={__("messages.yes_logout", "Yes, Logout")}
    onConfirm={confirmLogout}
    onClose={() => (showLogoutModal = false)}
  />
</div>

<style>
  .custom-scrollbar::-webkit-scrollbar {
    width: 6px;
  }
  .custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
  }
  .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
  }
  .dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #475569;
  }
  .custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
  }

  .no-scrollbar::-webkit-scrollbar {
    display: none;
  }
  .no-scrollbar {
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
  }
</style>
