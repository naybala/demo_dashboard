<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import InputLabel from "@/Components/InputLabel.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import InputError from "@/Components/InputError.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import { useForm, router, page } from "@inertiajs/svelte";
  import { __ } from "@/helpers.js";

  export let permission = null;

  $: permissions = $page.props.permissions || [];

  const form = useForm({
    name: permission?.name || "",
  });

  const submit = () => {
    if (permission) {
      $form.put(`/permissions/${permission.id}`);
    } else {
      $form.post("/permissions");
    }
  };
</script>

<svelte:head>
  <title
    >{__(
      permission
        ? "permission.edit_permission"
        : "permission.create_permission",
    )}</title
  >
</svelte:head>

<AdminLayout>
  <svelte:fragment slot="header">
    <h2
      class="font-semibold text-[12px] md:text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem] hidden md:block"
    >
      {__("sidebar.permission", "Permission")}
    </h2>
  </svelte:fragment>
  <PageHeader
    title={permission
      ? __("permission.edit_permission", "Edit Permission")
      : __("permission.create_permission", "Create Permission")}
  />

  <div
    class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 max-w-2xl mx-auto"
  >
    <form on:submit|preventDefault={submit} class="space-y-6">
      <div>
        <InputLabel
          for="name"
          value={__("permission.permission_name", "Permission Name")}
        />
        <TextInput
          id="name"
          type="text"
          class="mt-1 block w-full"
          bind:value={$form.name}
          required
          placeholder="e.g. create users"
        />
        <p class="mt-2 text-xs text-gray-500">
          Tip: Use space for feature separation (e.g. "view users", "edit
          roles").
        </p>
        <InputError message={$form.errors.name} />
      </div>

      <div
        class="flex items-center justify-end gap-4 border-t border-gray-100 dark:border-gray-700 pt-6"
      >
        <SecondaryButton on:click={() => router.get("/permissions")}
          >{__("messages.cancel", "Cancel")}</SecondaryButton
        >
        {#if (permission && permissions.includes("edit permissions")) || (!permission && permissions.includes("create permissions"))}
          <PrimaryButton type="submit" disabled={$form.processing}>
            {permission
              ? __("messages.update", "Update Permission")
              : __("messages.create", "Create Permission")}
          </PrimaryButton>
        {/if}
      </div>
    </form>
  </div>
</AdminLayout>
