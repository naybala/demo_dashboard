<script>
  import { createEventDispatcher } from "svelte";
  import InputLabel from "./InputLabel.svelte";
  import InputError from "./InputError.svelte";

  export let options = []; // [{ id: 1, name: 'Category 1' }, ...]
  export let value = []; // [1, 2, ...]
  export let label = "Categories";
  export let error = null;

  const dispatch = createEventDispatcher();

  const toggleOption = (id) => {
    if (value.includes(id)) {
      value = value.filter((v) => v !== id);
    } else {
      value = [...value, id];
    }
    dispatch("change", value);
  };
</script>

<div
  class="border border-gray-200 dark:border-gray-700 p-4 rounded-2xl bg-white dark:bg-gray-800"
>
  <InputLabel value={label} />
  <div class="mt-2 flex flex-wrap gap-2">
    {#each options as option}
      <button
        type="button"
        on:click={() => toggleOption(option.id)}
        class={`px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 border ${
          value.includes(option.id)
            ? "bg-indigo-600 text-white border-indigo-600 shadow-sm"
            : "bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100 hover:border-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600"
        }`}
      >
        {option.name}
      </button>
    {/each}
  </div>
  {#if error}
    <div class="mt-2">
      <InputError message={error} />
    </div>
  {/if}
</div>
