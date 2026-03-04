<script>
  import LanguageSwitcher from "./LanguageSwitcher.svelte";
  import ThemeToggle from "./ThemeToggle.svelte";

  export let onToggleSidebar;
  export let currentLocale = "en";
  export let onLanguageChange;
  export let isDarkMode = false;
  export let onToggleTheme;
  export let onLogout;
</script>

<header
  class="bg-white dark:bg-gray-800 shadow-sm px-4 py-3 flex justify-between items-center border-b dark:border-gray-700 shrink-0 z-30"
>
  <button
    on:click={onToggleSidebar}
    class="text-gray-500 hover:text-gray-700 focus:outline-none p-2"
    aria-label="Open menu"
  >
    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path
        stroke-linecap="round"
        stroke-linejoin="round"
        stroke-width="2"
        d="M4 6h16M4 12h16m-7 6h7"
      />
    </svg>
  </button>

  <!-- Page Header Slot -->
  <div class="px-1 md:px-6 py-[10px]">
    <slot name="header" />
  </div>

  <div class="flex-1 px-4">
    <!-- Breadcrumbs could go here -->
  </div>

  <div class="flex items-center space-x-2 sm:space-x-4">
    <LanguageSwitcher {currentLocale} {onLanguageChange} />

    <ThemeToggle {isDarkMode} onToggle={onToggleTheme} />

    <button
      on:click={onLogout}
      class="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors p-2 rounded-lg"
    >
      <svg
        class="w-5 h-5"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
        />
      </svg>
      <span class="hidden lg:inline">Logout</span>
    </button>
  </div>
</header>
