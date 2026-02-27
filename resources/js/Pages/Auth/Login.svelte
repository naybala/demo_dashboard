<script>
  import { useForm } from "@inertiajs/svelte";
  import InputLabel from "@/Components/InputLabel.svelte";
  import TextInput from "@/Components/TextInput.svelte";
  import InputError from "@/Components/InputError.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";

  const form = useForm({
    email: "",
    password: "",
  });

  let showPassword = false;

  const submit = () => {
    $form.post("/login", {
      onFinish: () => $form.reset("password"),
    });
  };

  const togglePasswordVisibility = () => {
    showPassword = !showPassword;
  };
</script>

<div
  class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900"
>
  <div class="container mx-auto text-center mb-8">
    <p class="text-5xl text-gray-500 dark:text-white font-bold">
      Admin Login Page.
    </p>
  </div>

  <div
    class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg"
  >
    <form on:submit|preventDefault={submit}>
      <div>
        <InputLabel for="email" value="User Email" />
        <TextInput
          id="email"
          type="text"
          class="mt-1 block w-full"
          bind:value={$form.email}
          required
          autofocus
          autocomplete="username"
        />
        <InputError class="mt-2" message={$form.errors.email} />
      </div>

      <div class="mt-4 relative">
        <InputLabel for="password" value="User Password" />
        <TextInput
          id="password"
          type={showPassword ? "text" : "password"}
          class="mt-1 block w-full"
          bind:value={$form.password}
          required
          autocomplete="current-password"
        />

        <button
          type="button"
          on:click={togglePasswordVisibility}
          class="absolute right-3 top-[38px] text-gray-500 dark:text-gray-400 focus:outline-none"
          aria-label={showPassword ? "Hide password" : "Show password"}
        >
          {#if showPassword}
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
              />
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
              />
            </svg>
          {:else}
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
              />
            </svg>
          {/if}
        </button>

        <InputError class="mt-2" message={$form.errors.password} />
      </div>

      <div class="flex items-center justify-end mt-4">
        <PrimaryButton class="ms-4" disabled={$form.processing}>
          Login
        </PrimaryButton>
      </div>
    </form>
  </div>
</div>
