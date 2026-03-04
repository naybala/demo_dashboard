<script>
  import { Link } from "@inertiajs/svelte";
  import { crossfade } from "svelte/transition";
  import { cubicOut } from "svelte/easing";
  import { __ } from "@/helpers.js";

  const [send, receive] = crossfade({
    duration: 400,
    easing: cubicOut,
  });

  export let isSidebarOpen = true;
  export let navigation = [];
  export let user = null;
  export let isActive;
  export let canSee;
  export let onClose;
</script>

<aside
  class={`fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-xl transform transition-transform duration-300 ease-in-out ${isSidebarOpen ? "translate-x-0" : "-translate-x-full"}`}
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
        on:click={onClose}
        class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none"
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
        {#if section.items}
          {#if section.items.some(canSee)}
            <div>
              <h3
                class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"
              >
                {__(section.name, section.label)}
              </h3>
              <div class="space-y-1">
                {#each section.items.filter(canSee) as item}
                  <Link
                    href={item.href}
                    class={`flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 group relative overflow-hidden ${
                      isActive(item.href)
                        ? "bg-indigo-50/80 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 shadow-sm"
                        : "text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white"
                    }`}
                  >
                    {#if isActive(item.href)}
                      <div
                        class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-600 dark:bg-indigo-500 rounded-r-full"
                        in:receive={{ key: "active-indicator" }}
                        out:send={{ key: "active-indicator" }}
                      ></div>
                    {/if}
                    <svg
                      class={`mr-3 h-5 w-5 transition-all duration-300 ${isActive(item.href) ? "text-indigo-600 dark:text-indigo-400 scale-110" : "text-gray-400 group-hover:text-gray-500 group-hover:scale-110"}`}
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
                    <span
                      class="transition-transform duration-300 {isActive(
                        item.href,
                      )
                        ? 'translate-x-1'
                        : 'group-hover:translate-x-1'}"
                    >
                      {__(item.name, item.label)}
                    </span>
                  </Link>
                {/each}
              </div>
            </div>
          {/if}
        {:else}
          <div>
            <Link
              href={section.href}
              class={`flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 group relative overflow-hidden ${
                isActive(section.href)
                  ? "bg-indigo-50/80 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 shadow-sm"
                  : "text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white"
              }`}
            >
              {#if isActive(section.href)}
                <div
                  class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-600 dark:bg-indigo-500 rounded-r-full"
                  in:receive={{ key: "active-indicator" }}
                  out:send={{ key: "active-indicator" }}
                ></div>
              {/if}
              <svg
                class={`mr-3 h-5 w-5 transition-all duration-300 ${isActive(section.href) ? "text-indigo-600 dark:text-indigo-400 scale-110" : "text-gray-400 group-hover:text-gray-500 group-hover:scale-110"}`}
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
              <span
                class="transition-transform duration-300 {isActive(section.href)
                  ? 'translate-x-1'
                  : 'group-hover:translate-x-1'}"
              >
                {__(section.name, section.label)}
              </span>
            </Link>
          </div>
        {/if}
      {/each}
    </nav>

    <div
      class="p-4 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 shrink-0"
    >
      <div class="flex items-center gap-3">
        <div
          class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-700 dark:text-indigo-300 font-bold text-sm shrink-0 overflow-hidden border-2 border-white dark:border-gray-700 shadow-sm"
        >
          {#if user?.avatar}
            <img
              src={user.avatar}
              alt={user.fullname}
              class="w-full h-full object-cover"
            />
          {:else}
            {(user?.fullname || user?.name || "A").charAt(0).toUpperCase()}
          {/if}
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-bold text-gray-900 dark:text-white truncate">
            {user?.fullname || user?.name || "Admin"}
          </p>
          <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
            {user?.email}
          </p>
        </div>
      </div>
    </div>
  </div>
</aside>
