<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import InputLabel from "@/Components/InputLabel.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import InputError from "@/Components/InputError.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import { useForm, router, page } from "@inertiajs/svelte";

  export let unit = null;

  $: permissions = $page.props.permissions || [];

  const form = useForm({
    name: unit?.name || "",
  });

  const submit = () => {
    if (unit) {
      $form.put(`/units/${unit.id}`);
    } else {
      $form.post("/units");
    }
  };
</script>

<AdminLayout>
  <PageHeader title={unit ? "Edit Unit" : "Create Unit"} />

  <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 max-w-xl">
    <form on:submit|preventDefault={submit} class="space-y-6">
      <div>
        <InputLabel for="name" value="Unit Name" />
        <TextInput
          id="name"
          type="text"
          class="mt-1 block w-full"
          bind:value={$form.name}
          required
        />
        <InputError message={$form.errors.name} />
      </div>

      <div class="flex items-center justify-end gap-4">
        <SecondaryButton on:click={() => router.get("/units")}
          >Cancel</SecondaryButton
        >
        {#if (unit && permissions.includes("edit units")) || (!unit && permissions.includes("create units"))}
          <PrimaryButton type="submit" disabled={$form.processing}>
            {unit ? "Update Unit" : "Create Unit"}
          </PrimaryButton>
        {/if}
      </div>
    </form>
  </div>
</AdminLayout>
