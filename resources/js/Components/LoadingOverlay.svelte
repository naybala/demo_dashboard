<script>
  import { __ } from "@/helpers.js";
  export let show = false;
  export let message = __("messages.loading", "Loading...");
</script>

{#if show}
  <div
    class="absolute inset-0 z-[100] flex items-center justify-center bg-white/60 dark:bg-gray-900/60 backdrop-blur-[30px] transition-all duration-300"
  >
    <div
      class="flex flex-col items-center gap-4 p-8 rounded-2xl bg-white dark:bg-gray-800 shadow-2xl border border-gray-100 dark:border-gray-700"
    >
      <div class="relative w-16 h-16">
        <!-- Outer Ring -->
        <div
          class="absolute inset-0 border-4 border-indigo-100 dark:border-gray-700 rounded-full"
        ></div>
        <!-- Spinning Ring -->
        <div
          class="absolute inset-0 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin shadow-lg"
        ></div>
      </div>
      <div class="flex flex-col items-center gap-1">
        <p class="text-gray-800 dark:text-gray-100 font-bold text-lg">
          {message}
        </p>
        <p class="text-gray-500 dark:text-gray-400 text-xs animate-pulse">
          {__("messages.please_wait", "Please wait a moment")}
        </p>
      </div>
    </div>
  </div>
{/if}

<style>
  @keyframes spin {
    to {
      transform: rotate(360deg);
    }
  }
  .animate-spin {
    animation: spin 3s linear infinite;
  }
</style>
