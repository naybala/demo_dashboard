<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import InputLabel from "@/Components/InputLabel.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import InputError from "@/Components/InputError.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import { useForm, router, page } from "@inertiajs/svelte";

  export let role = null;
  export let getAllPermissions = {};
  export let getCurrentPermissions = [];

  $: permissions = $page.props.permissions || [];

  const form = useForm({
    name: role?.name || "",
    can_access_panel: role?.can_access_panel || false,
    permissions: getCurrentPermissions || [],
  });

  const togglePermission = (permissionName) => {
    if ($form.permissions.includes(permissionName)) {
      $form.permissions = $form.permissions.filter((p) => p !== permissionName);
    } else {
      $form.permissions = [...$form.permissions, permissionName];
    }
  };

  const toggleFeaturePermissions = (featureName, permissions) => {
    const featurePermissionNames = permissions.map((p) => p.name);
    const allSelected = featurePermissionNames.every((p) =>
      $form.permissions.includes(p),
    );

    if (allSelected) {
      $form.permissions = $form.permissions.filter(
        (p) => !featurePermissionNames.includes(p),
      );
    } else {
      $form.permissions = [
        ...new Set([...$form.permissions, ...featurePermissionNames]),
      ];
    }
  };

  const submit = () => {
    if (role) {
      $form.put(`/roles/${role.id}`);
    } else {
      $form.post("/roles");
    }
  };
</script>

<AdminLayout>
  <PageHeader title={role ? "Edit Role" : "Create Role"} />

  <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
    <form on:submit|preventDefault={submit} class="space-y-8">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
        <div>
          <InputLabel for="name" value="Role Name" />
          <TextInput
            id="name"
            type="text"
            class="mt-1 block w-full"
            bind:value={$form.name}
            required
          />
          <InputError message={$form.errors.name} />
        </div>

        <div class="flex items-center pb-2">
          <label class="inline-flex items-center">
            <input
              type="checkbox"
              class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
              bind:checked={$form.can_access_panel}
            />
            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400"
              >Can Access Admin Panel</span
            >
          </label>
        </div>
      </div>

      <div class="space-y-4">
        <h3
          class="text-lg font-medium text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-700 pb-2"
        >
          Permissions
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {#each Object.entries(getAllPermissions) as [feature, permissions]}
            <div
              class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border border-gray-200 dark:border-gray-700"
            >
              <div class="flex justify-between items-center mb-3">
                <h4
                  class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider"
                >
                  {feature}
                </h4>
                <button
                  type="button"
                  on:click={() =>
                    toggleFeaturePermissions(feature, permissions)}
                  class="text-xs text-indigo-600 hover:text-indigo-800 font-medium"
                >
                  Toggle All
                </button>
              </div>
              <div class="space-y-2">
                {#each permissions as permission}
                  <label class="flex items-center text-sm">
                    <input
                      type="checkbox"
                      class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                      checked={$form.permissions.includes(permission.name)}
                      on:change={() => togglePermission(permission.name)}
                    />
                    <span class="ms-2 text-gray-600 dark:text-gray-400"
                      >{permission.name}</span
                    >
                  </label>
                {/each}
              </div>
            </div>
          {/each}
        </div>
        <InputError message={$form.errors.permissions} />
      </div>

      <div
        class="flex items-center justify-end gap-4 border-t border-gray-100 dark:border-gray-700 pt-6"
      >
        <SecondaryButton on:click={() => router.get("/roles")}
          >Cancel</SecondaryButton
        >
        {#if (role && permissions.includes("edit roles")) || (!role && permissions.includes("create roles"))}
          <PrimaryButton type="submit" disabled={$form.processing}>
            {role ? "Update Role" : "Create Role"}
          </PrimaryButton>
        {/if}
      </div>
    </form>
  </div>
</AdminLayout>
