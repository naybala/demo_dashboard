<script setup>
import { usePage } from "@inertiajs/vue3";
import { onMounted, onUnmounted, computed } from "vue";
import { __ } from "@/helpers.js";
import DeleteConfirmationModal from "@/Components/DeleteConfirmationModal.vue";
import Sidebar from "./Parts/Sidebar.vue";
import TopBar from "./Parts/TopBar.vue";
import { navigations } from "./Parts/navigation.js";
import {
  isSidebarOpen,
  isDarkMode,
  showLogoutModal,
  toggleTheme,
  changeLanguage,
  isActive,
  canSee,
  handleLogoutClick,
  confirmLogout,
  initAdminLayout,
} from "./adminLayout.js";
import Toast from "./Parts/Toast.vue";

const page = usePage();

const user = computed(() => page.props.auth.user);
const currentLocale = computed(() => page.props.locale || "en");

let cleanup;
onMounted(() => {
  cleanup = initAdminLayout();
});

onUnmounted(() => {
  if (cleanup) cleanup();
});

const navigation = navigations;
</script>

<template>
  <div class="h-dvh flex bg-gray-100 dark:bg-gray-900 overflow-hidden relative">
    <Sidebar
      :is-sidebar-open="isSidebarOpen"
      :navigation="navigation"
      :user="user"
      :is-active="isActive"
      :can-see="canSee"
      :on-close="() => (isSidebarOpen = false)"
    />

    <!-- Overlay -->
    <Transition
      enter-active-class="transition ease-out duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition ease-in duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isSidebarOpen"
        class="fixed inset-0 bg-gray-900/50 z-40 lg:hidden"
        @click="isSidebarOpen = false"
        role="button"
        tabindex="0"
        aria-label="Close menu"
      ></div>
    </Transition>

    <!-- Main Content -->
    <div
      class="flex-1 flex flex-col min-w-0 overflow-hidden transition-all duration-300"
      :class="isSidebarOpen ? 'lg:pl-64' : ''"
    >
      <Toast />
      <TopBar
        :on-toggle-sidebar="() => (isSidebarOpen = !isSidebarOpen)"
        :current-locale="currentLocale"
        :on-language-change="changeLanguage"
        :is-dark-mode="isDarkMode"
        :on-toggle-theme="toggleTheme"
        :on-logout="handleLogoutClick"
      >
        <template #header>
          <slot name="header" />
        </template>
      </TopBar>

      <!-- Page Content -->
      <div class="relative flex-1 overflow-hidden flex flex-col">
        <main
          class="flex-1 overflow-y-auto shadow-inner custom-scrollbar bg-gray-50 dark:bg-gray-900/50 p-1 md:p-4"
        >
          <div class="mx-auto w-full min-h-full">
            <slot />
          </div>
        </main>
      </div>
    </div>

    <DeleteConfirmationModal
      :show="showLogoutModal"
      :title="__('messages.are_you_sure', 'Are you sure?')"
      :message="__('messages.login_again', 'Are you sure you want to logout?')"
      :confirm-text="__('messages.yes_logout', 'Yes, Logout')"
      :on-confirm="confirmLogout"
      :on-close="() => (showLogoutModal = false)"
    />
  </div>
</template>

<style>
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
  background: #475569;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none; /* IE and Edge */
  scrollbar-width: none; /* Firefox */
}
</style>
