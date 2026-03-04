<script>
  import { usePage } from "@inertiajs/svelte";
  import { onDestroy } from "svelte";
  import { isOnline } from "../adminLayout.js";
  import { __ } from "@/helpers.js";

  const page = usePage();

  let showSuccessToast = false;
  let showErrorToast = false;
  let showConnectionError = false;
  let showConnectionSuccess = false;
  let wasOffline = false;

  let successTimer;
  let errorTimer;
  let connErrorTimer;
  let connSuccessTimer;

  $: if ($page.props.flash?.success) {
    showSuccessToast = true;
    clearTimeout(successTimer);
    successTimer = setTimeout(() => {
      showSuccessToast = false;
    }, 3000);
  }

  $: if ($page.props.flash?.error) {
    showErrorToast = true;
    clearTimeout(errorTimer);
    errorTimer = setTimeout(() => {
      showErrorToast = false;
    }, 4000);
  }

  $: if (!$isOnline) {
    showConnectionError = true;
    wasOffline = true;
    clearTimeout(connErrorTimer);
  } else if ($isOnline && wasOffline) {
    showConnectionError = false;
    showConnectionSuccess = true;
    wasOffline = false;
    clearTimeout(connSuccessTimer);
    connSuccessTimer = setTimeout(() => {
      showConnectionSuccess = false;
    }, 3000);
  }

  onDestroy(() => {
    clearTimeout(successTimer);
    clearTimeout(errorTimer);
    clearTimeout(connErrorTimer);
    clearTimeout(connSuccessTimer);
  });
</script>

<!-- Success Toast -->
{#if showSuccessToast && $page.props.flash?.success}
  <div class="fixed top-5 right-5 z-[100] animate-slide-in">
    <div
      class="flex items-center gap-3 bg-white border border-green-200 shadow-lg rounded-lg px-5 py-4 min-w-[320px]"
    >
      <div class="flex-shrink-0">
        <svg
          class="w-5 h-5 text-green-500"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M5 13l4 4L19 7"
          />
        </svg>
      </div>
      <span class="text-sm font-medium text-gray-800 flex-1"
        >{$page.props.flash.success}</span
      >
      <button
        on:click={() => (showSuccessToast = false)}
        class="text-gray-400 hover:text-gray-600 transition-colors"
      >
        <svg
          class="w-4 h-4"
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
  </div>
{/if}

<!-- Error Toast -->
{#if showErrorToast && $page.props.flash?.error}
  <div
    class="fixed top-5 right-5 z-[100] animate-slide-in"
    style={showSuccessToast ? "top: 5rem;" : ""}
  >
    <div
      class="flex items-center gap-3 bg-white border border-red-200 shadow-lg rounded-lg px-5 py-4 min-w-[320px]"
    >
      <div class="flex-shrink-0">
        <svg
          class="w-5 h-5 text-red-500"
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
      </div>
      <span class="text-sm font-medium text-gray-800 flex-1"
        >{$page.props.flash.error}</span
      >
      <button
        on:click={() => (showErrorToast = false)}
        class="text-gray-400 hover:text-gray-600 transition-colors"
      >
        <svg
          class="w-4 h-4"
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
  </div>
{/if}

<!-- Connection Error Toast -->
{#if showConnectionError}
  <div class="fixed top-5 right-5 z-[100] animate-slide-in">
    <div
      class="flex items-center gap-3 bg-white border border-red-200 shadow-lg rounded-lg px-5 py-4 min-w-[320px]"
    >
      <div class="flex-shrink-0">
        <svg
          class="w-5 h-5 text-red-500"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
          />
        </svg>
      </div>
      <span class="text-sm font-medium text-gray-800 flex-1">
        {__(
          "messages.connection_failed",
          "Network connection failed, Please use internet",
        )}
      </span>
      <button
        on:click={() => (showConnectionError = false)}
        class="text-gray-400 hover:text-gray-600 transition-colors"
      >
        <svg
          class="w-4 h-4"
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
  </div>
{/if}

<!-- Connection Success Toast -->
{#if showConnectionSuccess}
  <div class="fixed top-5 right-5 z-[100] animate-slide-in">
    <div
      class="flex items-center gap-3 bg-white border border-green-200 shadow-lg rounded-lg px-5 py-4 min-w-[320px]"
    >
      <div class="flex-shrink-0">
        <svg
          class="w-5 h-5 text-green-500"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M5 13l4 4L19 7"
          />
        </svg>
      </div>
      <span class="text-sm font-medium text-gray-800 flex-1">
        {__("messages.connection_restored", "Connection restored")}
      </span>
      <button
        on:click={() => (showConnectionSuccess = false)}
        class="text-gray-400 hover:text-gray-600 transition-colors"
      >
        <svg
          class="w-4 h-4"
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
  </div>
{/if}

<style>
  @keyframes slide-in {
    from {
      opacity: 0;
      transform: translateX(100%);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }

  .animate-slide-in {
    animation: slide-in 0.3s ease-out forwards;
  }
</style>
