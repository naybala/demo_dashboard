<script>
  import { page, router } from "@inertiajs/svelte";
  import { Link } from "@inertiajs/svelte";
  export let user = $page.props.auth.user;
  let isMobileMenuOpen = false;

  const navigation = [
    {
      name: "Dashboard",
      href: "/dashboard",
      icon: "M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6",
    },
    {
      name: "Inventory",
      items: [
        {
          name: "Categories",
          href: "/categories",
          icon: "M4 6h16M4 10h16M4 14h16M4 18h16",
        },
        {
          name: "Products",
          href: "/products",
          icon: "M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4",
        },
        {
          name: "Own Products",
          href: "/own-products",
          icon: "M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01",
        },
      ],
    },
    {
      name: "Sales",
      items: [
        {
          name: "Daily Incomes",
          href: "/daily-incomes",
          icon: "M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z",
        },
      ],
    },
    {
      name: "User Management",
      items: [
        {
          name: "Users",
          href: "/users",
          icon: "M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z",
        },
        {
          name: "Roles",
          href: "/roles",
          icon: "M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z",
        },
      ],
    },
    {
      name: "Maintenance",
      items: [
        {
          name: "Units",
          href: "/units",
          icon: "M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10",
        },
        {
          name: "Activity Logs",
          href: "/audits",
          icon: "M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z",
        },
      ],
    },
  ];

  const isActive = (href) => {
    if (href === "/dashboard") return $page.url === href;
    return $page.url.startsWith(href);
  };

  const logout = () => {
    router.post("/logout");
  };
</script>

<div class="h-dvh flex bg-gray-100 dark:bg-gray-900 overflow-hidden">
  <!-- Sidebar -->
  <aside
    class={`fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-xl transform transition-transform duration-300 ease-in-out md:static md:translate-x-0 ${isMobileMenuOpen ? "translate-x-0" : "-translate-x-full"}`}
  >
    <div class="h-full flex flex-col">
      <div
        class="p-6 border-b dark:border-gray-700 flex items-center justify-between shrink-0"
      >
        <h1
          class="text-xl font-black tracking-tight text-indigo-600 dark:text-indigo-400"
        >
          DASHBOARD
        </h1>
        <button
          on:click={() => (isMobileMenuOpen = false)}
          class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none"
        >
          <svg
            class="w-6 h-6"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
      </div>

      <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-6 no-scrollbar">
        {#each navigation as section}
          <div>
            {#if section.items}
              <h3
                class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"
              >
                {section.name}
              </h3>
              <div class="space-y-1">
                {#each section.items as item}
                  <Link
                    href={item.href}
                    class={`flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 group ${
                      isActive(item.href)
                        ? "bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400"
                        : "text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white"
                    }`}
                  >
                    <svg
                      class={`mr-3 h-5 w-5 transition-colors ${isActive(item.href) ? "text-indigo-600 dark:text-indigo-400" : "text-gray-400 group-hover:text-gray-500"}`}
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d={item.icon}
                      />
                    </svg>
                    {item.name}
                  </Link>
                {/each}
              </div>
            {:else}
              <Link
                href={section.href}
                class={`flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 group ${
                  isActive(section.href)
                    ? "bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400"
                    : "text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white"
                }`}
              >
                <svg
                  class={`mr-3 h-5 w-5 transition-colors ${isActive(section.href) ? "text-indigo-600 dark:text-indigo-400" : "text-gray-400 group-hover:text-gray-500"}`}
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d={section.icon}
                  />
                </svg>
                {section.name}
              </Link>
            {/if}
          </div>
        {/each}
      </nav>

      <div
        class="p-4 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 shrink-0"
      >
        <div class="flex items-center gap-3">
          <div
            class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-700 dark:text-indigo-300 font-bold text-xs shrink-0"
          >
            {(user?.name || user?.username || "A").charAt(0).toUpperCase()}
          </div>
          <div class="flex-1 min-w-0">
            <p
              class="text-sm font-medium text-gray-900 dark:text-white truncate"
            >
              {user?.name || user?.username || "Admin"}
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
              {user?.email}
            </p>
          </div>
        </div>
      </div>
    </div>
  </aside>

  <!-- Mobile Overlay -->
  {#if isMobileMenuOpen}
    <div
      class="fixed inset-0 bg-gray-900/50 z-40 md:hidden"
      on:click={() => (isMobileMenuOpen = false)}
      on:keydown={(e) => e.key === "Escape" && (isMobileMenuOpen = false)}
      role="button"
      tabindex="0"
      aria-label="Close menu"
    ></div>
  {/if}

  <!-- Main Content -->
  <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
    <!-- Top Nav -->
    <header
      class="bg-white dark:bg-gray-800 shadow-sm px-4 py-3 flex justify-between items-center border-b dark:border-gray-700 shrink-0 z-30"
    >
      <button
        on:click={() => (isMobileMenuOpen = true)}
        class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none p-2"
        aria-label="Open menu"
      >
        <svg
          class="h-6 w-6"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M4 6h16M4 12h16m-7 6h7"
          />
        </svg>
      </button>

      <div class="flex-1 px-4">
        <!-- Breadcrumbs could go here -->
      </div>

      <div class="flex items-center space-x-4">
        <button
          on:click={logout}
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
          <span class="hidden sm:inline">Logout</span>
        </button>
      </div>
    </header>

    <!-- Page Content -->
    <main
      class="flex-1 overflow-y-auto p-4 md:p-6 custom-scrollbar bg-gray-50 dark:bg-gray-900/50"
    >
      <div class="max-w-7xl mx-auto w-full">
        <slot />
      </div>
    </main>
  </div>
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
