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

  export let user = null;
  export let roles = [];

  $: permissions = $page.props.permissions || [];

  const form = useForm({
    fullname: user?.fullname || "",
    email: user?.email || "",
    password: "",
    password_confirmation: "",
    role_id: user?.role_id || "",
    status: user?.status || 1,
    user_type: user?.user_type || 1,
    phone_number: user?.phone_number || "",
    avatar: null,
  });

  let avatarPreview = user?.avatar || null;
  let fileInput;

  const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      $form.avatar = file;
      avatarPreview = URL.createObjectURL(file);
    }
  };

  const removeAvatar = () => {
    $form.avatar = null;
    avatarPreview = user?.avatar || null;
  };

  const submit = () => {
    if (user) {
      $form
        .transform((data) => ({
          ...data,
          _method: "PUT",
        }))
        .post(`/users/${user.id}`);
    } else {
      $form.post("/users");
    }
  };
</script>

<svelte:head>
  <title>{__(user ? "user.edit_user" : "user.create_user")}</title>
</svelte:head>

<AdminLayout>
  <svelte:fragment slot="header">
    <h2
      class="font-semibold text-[12px] md:text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem] hidden md:block"
    >
      {__("sidebar.user", "User")}
    </h2>
  </svelte:fragment>
  <PageHeader
    title={user
      ? __("user.edit_user", "Edit User")
      : __("user.create_user", "Create User")}
  />

  <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
    <form on:submit|preventDefault={submit} class="space-y-6 max-w-2xl">
      <!-- Avatar Upload -->
      <div>
        <InputLabel value={__("user.avatar", "Avatar")} />
        <div class="mt-2 flex items-center gap-4">
          <div
            class="relative group w-24 h-24 rounded-full overflow-hidden border border-gray-200 bg-gray-50 flex items-center justify-center"
          >
            {#if avatarPreview}
              <img
                src={avatarPreview}
                alt="avatar"
                class="w-full h-full object-cover"
              />
              <button
                type="button"
                on:click={removeAvatar}
                class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity text-white"
              >
                <svg
                  class="w-6 h-6"
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
            {:else}
              <svg
                class="w-12 h-12 text-gray-300"
                fill="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"
                />
              </svg>
            {/if}
          </div>
          <SecondaryButton type="button" on:click={() => fileInput.click()}>
            {__("messages.choose_file", "Choose Photo")}
          </SecondaryButton>
          <input
            type="file"
            class="hidden"
            bind:this={fileInput}
            on:change={handleFileChange}
            accept="image/*"
          />
        </div>
        <InputError message={$form.errors.avatar} />
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <InputLabel for="fullname" value={__("user.fullname", "Full Name")} />
          <TextInput
            id="fullname"
            type="text"
            class="mt-1 block w-full"
            bind:value={$form.fullname}
            required
          />
          <InputError message={$form.errors.fullname} />
        </div>

        <div>
          <InputLabel for="email" value={__("user.email", "Email")} />
          <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full"
            bind:value={$form.email}
            required
          />
          <InputError message={$form.errors.email} />
        </div>

        <div>
          <InputLabel for="role" value={__("user.role", "Role")} />
          <select
            id="role"
            bind:value={$form.role_id}
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            required
          >
            <option value="">{__("user.select_role", "Select Role")}</option>
            {#each roles as role}
              <option value={role.id}>{role.name}</option>
            {/each}
          </select>
          <InputError message={$form.errors.role_id} />
        </div>

        <div>
          <InputLabel for="status" value={__("user.status", "Status")} />
          <select
            id="status"
            bind:value={$form.status}
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            required
          >
            <option value={1}>{__("messages.active", "Active")}</option>
            <option value={2}>{__("messages.inactive", "Inactive")}</option>
          </select>
          <InputError message={$form.errors.status} />
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <InputLabel
            for="password"
            value={user
              ? __("user.new_password_optional", "New Password (Optional)")
              : __("user.password", "Password")}
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
          <InputLabel
            for="password_confirmation"
            value={__("user.confirm_password", "Confirm Password")}
          />
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
          >{__("messages.cancel", "Cancel")}</SecondaryButton
        >
        {#if (user && permissions.includes("edit users")) || (!user && permissions.includes("create users"))}
          <PrimaryButton type="submit" disabled={$form.processing}>
            {user
              ? __("messages.update", "Update User")
              : __("messages.create", "Create User")}
          </PrimaryButton>
        {/if}
      </div>
    </form>
  </div>
</AdminLayout>
