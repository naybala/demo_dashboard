<script>
  import { fade, scale } from "svelte/transition";
  import { cubicOut } from "svelte/easing";
  import { createEventDispatcher } from "svelte";

  export let show = false;

  const dispatch = createEventDispatcher();

  const close = () => dispatch("close");

  function scaleFade(node, params) {
    const scaleTransition = scale(node, {
      duration: 200,
      easing: cubicOut,
      start: 0.92,
      ...params,
    });

    const fadeTransition = fade(node, {
      duration: 200,
    });

    return {
      duration: 200,
      css: (t, u) => scaleTransition.css(t, u) + fadeTransition.css(t, u),
    };
  }
</script>

{#if show}
  <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" transition:fade />

  <div class="fixed inset-0 flex items-center justify-center z-50">
    <div
      class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-lg"
      transition:scaleFade
      role="dialog"
      aria-modal="true"
    >
      <button
        type="button"
        class="absolute top-4 right-4 text-gray-500"
        on:click={close}
      >
        ✕
      </button>

      <slot />
    </div>
  </div>
{/if}
