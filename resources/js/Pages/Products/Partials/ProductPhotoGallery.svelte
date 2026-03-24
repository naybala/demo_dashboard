<script>
  import InputLabel from "@/Components/InputLabel.svelte";
  import InputError from "@/Components/InputError.svelte";
  import { __ } from "@/helpers";

  export let photos = [];
  export let existing_photos = [];
  export let handleFileChange;
  export let removeNewPhoto;
  export let removeExistingPhoto;
  export let error = "";

  let fileInput;
</script>

<div>
  <InputLabel value={__("product.photo", "Product Photos")} />
  <div class="mt-2 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
    {#each existing_photos as path}
      <div
        class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200"
      >
        <img src={path} alt="product" class="w-full h-full object-cover" />
        <button
          type="button"
          on:click={() => removeExistingPhoto(path)}
          class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity"
        >
          <svg
            class="w-4 h-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            ><path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            /></svg
          >
        </button>
      </div>
    {/each}

    {#each photos as file, i}
      <div
        class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 bg-gray-50 flex items-center justify-center"
      >
        <img
          src={URL.createObjectURL(file)}
          alt="preview"
          class="w-full h-full object-cover"
          on:load={(e) => URL.revokeObjectURL(e.target.src)}
        />
        <button
          type="button"
          on:click={() => removeNewPhoto(i)}
          class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 shadow-sm"
        >
          <svg
            class="w-4 h-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            ><path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            /></svg
          >
        </button>
      </div>
    {/each}

    <button
      type="button"
      on:click={() => fileInput.click()}
      class="aspect-square rounded-lg border-2 border-dashed border-gray-300 flex flex-col items-center justify-center text-gray-500 hover:border-indigo-500 hover:text-indigo-500 transition-colors"
    >
      <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
        ><path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M12 4v16m8-8H4"
        /></svg
      >
      <span class="mt-1 text-xs">{__("product.add_photo", "Add Photo")}</span>
    </button>
    <input
      type="file"
      multiple
      class="hidden"
      bind:this={fileInput}
      on:change={handleFileChange}
      accept="image/*"
    />
  </div>
  {#if error}
    <InputError message={error} />
  {/if}
</div>
