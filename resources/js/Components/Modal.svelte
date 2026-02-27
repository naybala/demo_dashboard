<script>
  import { createEventDispatcher, onMount, onDestroy } from "svelte";
  const dispatch = createEventDispatcher();

  export let show = false;
  export let maxWidth = "2xl";
  export let closeable = true;

  $: if (show) {
    document.body.style.overflow = "hidden";
  } else {
    document.body.style.overflow = null;
  }

  const close = () => {
    if (closeable) {
      dispatch("close");
    }
  };

  const handleKeydown = (e) => {
    if (e.key === "Escape" && show) {
      close();
    }
  };

  onMount(() => {
    window.addEventListener("keydown", handleKeydown);
  });

  onDestroy(() => {
    window.removeEventListener("keydown", handleKeydown);
    document.body.style.overflow = null;
  });

  const maxWidthClass = {
    sm: "sm:max-w-sm",
    md: "sm:max-w-md",
    lg: "sm:max-w-lg",
    xl: "sm:max-w-xl",
    "2xl": "sm:max-w-2xl",
  }[maxWidth];
</script>

{#if show}
  <div
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50 flex items-center justify-center"
  >
    <div
      class="fixed inset-0 transform transition-all"
      on:click={close}
      on:keydown={handleKeydown}
      role="button"
      tabindex="0"
      aria-label="Close modal"
    >
      <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>

    <div
      class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full {maxWidthClass} sm:mx-auto"
    >
      <slot />
    </div>
  </div>
{/if}
