<script setup>
import { useForm, usePage, Head } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import Logo from "../../../../public/images/logo.png";
import { ref, computed } from "vue";

const page = usePage();
const flash = computed(() => page.props.flash || {});

const form = useForm({
  email: "",
  password: "",
});

const showPassword = ref(false);

const submit = () => {
  form.post("/login", {
    onFinish: () => form.reset("password"),
  });
};

const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value;
};
</script>

<template>
  <Head>
    <title>Login - Tha Dar Aung</title>
  </Head>

  <div
    class="min-h-screen flex bg-[#e8f3f1] font-sans items-center justify-center p-4 sm:p-6"
  >
    <div
      class="w-full max-w-[1100px] flex flex-col lg:flex-row bg-white rounded-[32px] lg:rounded-[40px] shadow-2xl overflow-hidden relative border-4 lg:border-8 border-white"
    >
      <!-- Left Side: Login Form -->
      <div
        class="w-full lg:w-[45%] px-6 py-10 sm:px-10 sm:py-12 flex flex-col justify-center relative z-10 bg-white"
      >
        <!-- Decorative Circles -->
        <div
          class="absolute -top-10 -left-10 sm:-top-16 sm:-left-16 w-32 h-32 sm:w-48 sm:h-48 bg-[#f9a472] rounded-full opacity-80"
        ></div>
        <div
          class="absolute -bottom-8 right-8 sm:-bottom-12 sm:right-12 w-20 h-20 sm:w-32 sm:h-32 bg-[#fbd4c0] rounded-full opacity-60"
        ></div>
        <div
          class="absolute top-5 right-5 sm:top-8 sm:right-8 w-8 h-8 sm:w-12 sm:h-12 bg-[#2c7db6] rounded-full"
        ></div>

        <div class="relative z-20 max-w-sm mx-auto w-full">
          <!-- Logo for mobile/tablet — shown above form on small screens -->
          <div class="flex items-center gap-3 mb-6 lg:hidden">
            <img
              :src="Logo"
              alt="Logo"
              class="w-12 h-12 rounded-xl object-cover"
            />
            <div>
              <p
                class="text-lg font-serif font-bold text-[#1b1b1b] leading-tight"
              >
                IBEC Private School
              </p>
              <p class="text-sm text-gray-500">Admin Portal</p>
            </div>
          </div>

          <h1
            class="hidden lg:block text-3xl xl:text-4xl font-serif text-[#1b1b1b] leading-tight mb-2"
          >
            IBEC Private School
          </h1>
          <h2
            class="hidden lg:block text-3xl xl:text-4xl font-serif text-[#1b1b1b] leading-tight mb-10"
          >
            Admin Portal
          </h2>

          <div
            v-if="flash.error"
            class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-600 text-sm font-medium animate-shake"
          >
            {{ flash.error }}
          </div>

          <form @submit.prevent="submit" class="space-y-5">
            <div>
              <label
                for="email"
                class="block text-sm font-medium text-gray-700 mb-2"
                >Username</label
              >
              <input
                id="email"
                type="text"
                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#2cb699] focus:ring-2 focus:ring-[#2cb699]/20 outline-none transition-all duration-300"
                v-model="form.email"
                required
              />
              <InputError class="mt-1" :message="form.errors.email" />
            </div>

            <div class="relative">
              <label
                for="password"
                class="block text-sm font-medium text-gray-700 mb-2"
                >Password</label
              >
              <div class="relative">
                <input
                  id="password"
                  :type="showPassword ? 'text' : 'password'"
                  class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#2cb699] focus:ring-2 focus:ring-[#2cb699]/20 outline-none transition-all duration-300 pr-12"
                  v-model="form.password"
                  required
                />
                <button
                  type="button"
                  @click="togglePasswordVisibility"
                  class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                >
                  <svg
                    v-if="showPassword"
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
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
                  <svg
                    v-else
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
                    />
                  </svg>
                </button>
              </div>
              <InputError class="mt-1" :message="form.errors.password" />
            </div>

            <div class="flex justify-end">
              <button
                type="button"
                class="text-xs font-semibold text-gray-800 hover:text-[#2cb699] transition-colors"
              >
                Contact : 09xxxxxxxx
              </button>
            </div>

            <div class="pt-2">
              <button
                type="submit"
                :disabled="form.processing"
                class="w-full flex items-center bg-[#2c58b6] text-white rounded-xl overflow-hidden hover:bg-[#25519d] transition-all group disabled:opacity-50"
              >
                <span
                  class="flex-grow py-4 text-sm font-bold tracking-widest uppercase"
                  >Login</span
                >
                <span
                  class="bg-[#f9a472] p-4 group-hover:bg-[#e89361] transition-colors"
                >
                  <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 5l7 7-7 7"
                    />
                  </svg>
                </span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Right Side: Illustration (desktop only) -->
      <div
        class="hidden lg:flex w-[55%] bg-[#2cb699] relative items-center justify-center p-12"
      >
        <div class="text-center">
          <div
            class="bg-white/20 p-8 rounded-[40px] backdrop-blur-sm border border-white/30 inline-block mb-6 transform hover:scale-105 transition-transform duration-500"
          >
            <img :src="Logo" alt="Logo" class="w-32 h-32" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500;600;700&display=swap");

.font-serif {
  font-family: "Playfair Display", serif;
}

.font-sans {
  font-family: "Inter", sans-serif;
}

@keyframes shake {
  0%,
  100% {
    transform: translateX(0);
  }
  10%,
  30%,
  50%,
  70%,
  90% {
    transform: translateX(-5px);
  }
  20%,
  40%,
  60%,
  70%,
  80% {
    transform: translateX(5px);
  }
}

.animate-shake {
  animation: shake 0.6s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
}
</style>
