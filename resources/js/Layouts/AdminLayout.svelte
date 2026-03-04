<script>
  import { page } from "@inertiajs/svelte";
  import { onMount } from "svelte";
  import { __ } from "@/helpers.js";
  import DeleteConfirmationModal from "@/Components/DeleteConfirmationModal.svelte";
  import Sidebar from "./Parts/Sidebar.svelte";
  import TopBar from "./Parts/TopBar.svelte";
  import { navigations } from "./Parts/navigation.js";
  import {
    isSidebarOpen,
    isDarkMode,
    showLogoutModal,
    toggleTheme,
    changeLanguage,
    isActive,
    canSee,
    handleLogoutClick,
    confirmLogout,
    initAdminLayout,
    isChangingLanguage,
  } from "./adminLayout.js";
  import Toast from "./Parts/Toast.svelte";

  export let user = $page.props.auth.user;
  $: currentLocale = $page.props.locale || "en";

  onMount(initAdminLayout);

  $: navigation = navigations;
</script>

<div class="h-dvh flex bg-gray-100 dark:bg-gray-900 overflow-hidden relative">
  {#if $isChangingLanguage}
    <div
      class="fixed inset-0 z-[110] flex items-center justify-center bg-white/50 dark:bg-gray-900/50 backdrop-blur-sm transition-all duration-300"
    >
      <div class="flex flex-col items-center gap-3">
        <div
          class="w-12 h-12 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"
        ></div>
        <p class="text-indigo-600 dark:text-indigo-400 font-medium text-sm">
          {currentLocale === "en"
            ? "Changing Language..."
            : "ဘာသာစကား ပြောင်းလဲနေသည်..."}
        </p>
      </div>
    </div>
  {/if}

  <Sidebar
    isSidebarOpen={$isSidebarOpen}
    {navigation}
    {user}
    {isActive}
    {canSee}
    onClose={() => isSidebarOpen.set(false)}
  />

  <!-- Overlay -->
  {#if $isSidebarOpen}
    <div
      class="fixed inset-0 bg-gray-900/50 z-40 lg:hidden"
      on:click={() => isSidebarOpen.set(false)}
      on:keydown={(e) => e.key === "Escape" && isSidebarOpen.set(false)}
      role="button"
      tabindex="0"
      aria-label="Close menu"
    ></div>
  {/if}

  <!-- Main Content -->
  <div
    class={`flex-1 flex flex-col min-w-0 overflow-hidden transition-all duration-300 ${$isSidebarOpen ? "lg:pl-64" : ""}`}
  >
    <Toast />
    <TopBar
      onToggleSidebar={() => isSidebarOpen.update((v) => !v)}
      {currentLocale}
      onLanguageChange={changeLanguage}
      isDarkMode={$isDarkMode}
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
    show={$showLogoutModal}
    title={__("messages.are_you_sure", "Are you sure?")}
    message={__("messages.login_again", "Are you sure you want to logout?")}
    confirmText={__("messages.yes_logout", "Yes, Logout")}
    onConfirm={confirmLogout}
    onClose={() => showLogoutModal.set(false)}
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
