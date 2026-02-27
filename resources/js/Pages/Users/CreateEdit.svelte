<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import InputLabel from "@/Components/InputLabel.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import InputError from "@/Components/InputError.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import { useForm, router } from "@inertiajs/svelte";

  export let user = null;
  export let roles = [];

  const form = useForm({
    name: user?.name || "",
    username: user?.username || "",
    password: "",
    password_confirmation: "",
    role: user?.role_name || "",
    active: user?.active ?? true,
  });

  const submit = () => {
    if (user) {
      $form.put(`/users/${user.id}`);
    } else {
      $form.post("/users");
    }
  };
</script>

<AdminLayout>
  <PageHeader title={user ? "Edit User" : "Create User"} />

  <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
    <form on:submit|preventDefault={submit} class="space-y-6 max-w-2xl">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <InputLabel for="name" value="Full Name" />
          <TextInput
            id="name"
            type="text"
            class="mt-1 block w-full"
            bind:value={$form.name}
            required
          />
          <InputError message={$form.errors.name} />
        </div>

        <div>
          <InputLabel for="username" value="Username" />
          <TextInput
            id="username"
            type="text"
            class="mt-1 block w-full"
            bind:value={$form.username}
            required
          />
          <InputError message={$form.errors.username} />
        </div>

        <div>
          <InputLabel for="role" value="Role" />
          <select
            id="role"
            bind:value={$form.role}
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            required
          >
            <option value="">Select Role</option>
            {#each roles as role}
              <option value={role.name}>{role.name}</option>
            {/each}
          </select>
          <InputError message={$form.errors.role} />
        </div>

        <div class="flex items-center pt-8">
          <label class="inline-flex items-center">
            <input
              type="checkbox"
              class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
              bind:checked={$form.active}
            />
            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400"
              >Account Active</span
            >
          </label>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <InputLabel
            for="password"
            value={user ? "New Password (Optional)" : "Password"}
          />
          <TextInput
            id="password"
            type="password"
            class="mt-1 block w-full"
            bind:value={$form.password}
            required={!user}
          />
          <InputError message={$form.errors.password} />
        </div>

        <div>
          <InputLabel for="password_confirmation" value="Confirm Password" />
          <TextInput
            id="password_confirmation"
            type="password"
            class="mt-1 block w-full"
            bind:value={$form.password_confirmation}
            required={!user}
          />
          <InputError message={$form.errors.password_confirmation} />
        </div>
      </div>

      <div
        class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100 dark:border-gray-700"
      >
        <SecondaryButton on:click={() => router.get("/users")}
          >Cancel</SecondaryButton
        >
        <PrimaryButton type="submit" disabled={$form.processing}>
          {user ? "Update User" : "Create User"}
        </PrimaryButton>
      </div>
    </form>
  </div>
</AdminLayout>
