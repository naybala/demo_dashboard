<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { __ } from "@/helpers.js";
import Logo from "../../../../public/images/logo.png";

const props = defineProps({
  isSidebarOpen: {
    type: Boolean,
    default: true,
  },
  navigation: {
    type: Array,
    default: () => [],
  },
  user: {
    type: Object,
    default: null,
  },
  isActive: {
    type: Function,
    required: true,
  },
  canSee: {
    type: Function,
    required: true,
  },
  onClose: {
    type: Function,
    required: true,
  },
});

const page = usePage();
</script>

<template>
  <aside
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-xl transform transition-transform duration-300 ease-in-out"
    :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
  >
    <div class="h-full flex flex-col">
      <div
        class="p-[1.12rem] border-b dark:border-gray-700 flex items-center justify-start shrink-0 shadow-xl rounded-l-3xl border ms-1"
      >
        <img
          :src="Logo"
          alt="Logo"
          class="w-10 h-10 rounded-xl object-cover me-2"
        />
        <h1
          class="text-xl font-black tracking-tight text-indigo-600 dark:text-indigo-400"
        >
          Tha Dar Aung
        </h1>
        <button
          @click="onClose"
          class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none"
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
      </div>

      <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-6 no-scrollbar">
        <template v-for="section in navigation" :key="section.name">
          <div v-if="section.items">
            <template v-if="section.items.some(canSee)">
              <h3
                class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"
              >
                {{ __(page.props.locale && section.name, section.label) }}
              </h3>
              <div class="space-y-1">
                <template
                  v-for="item in section.items.filter(canSee)"
                  :key="item.href"
                >
                  <Link
                    :href="item.href"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 group relative"
                    :class="
                      isActive(item.href)
                        ? 'bg-indigo-50/80 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 shadow-sm'
                        : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white'
                    "
                  >
                    <div
                      v-if="isActive(item.href)"
                      class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-600 dark:bg-indigo-500 rounded-r-full"
                    ></div>
                    <svg
                      class="mr-3 h-5 w-5 transition-all duration-300"
                      :class="
                        isActive(item.href)
                          ? 'text-indigo-600 dark:text-indigo-400 scale-110'
                          : 'text-gray-400 group-hover:text-gray-500 group-hover:scale-110'
                      "
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        :d="item.icon"
                      />
                    </svg>
                    <span
                      class="transition-transform duration-300"
                      :class="
                        isActive(item.href)
                          ? 'translate-x-1'
                          : 'group-hover:translate-x-1'
                      "
                    >
                      {{ __(page.props.locale && item.name, item.label) }}
                    </span>
                  </Link>
                </template>
              </div>
            </template>
          </div>
          <div v-else>
            <Link
              :href="section.href"
              class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 group relative"
              :class="
                isActive(section.href)
                  ? 'bg-indigo-50/80 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 shadow-sm'
                  : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white'
              "
            >
              <div
                v-if="isActive(section.href)"
                class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-600 dark:bg-indigo-500 rounded-r-full"
              ></div>
              <svg
                class="mr-3 h-5 w-5 transition-all duration-300"
                :class="
                  isActive(section.href)
                    ? 'text-indigo-600 dark:text-indigo-400 scale-110'
                    : 'text-gray-400 group-hover:text-gray-500 group-hover:scale-110'
                "
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  :d="section.icon"
                />
              </svg>
              <span
                class="transition-transform duration-300"
                :class="
                  isActive(section.href)
                    ? 'translate-x-1'
                    : 'group-hover:translate-x-1'
                "
              >
                {{ __(page.props.locale && section.name, section.label) }}
              </span>
            </Link>
          </div>
        </template>
      </nav>

      <div
        class="p-4 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 shrink-0"
      >
        <div class="flex items-center gap-3">
          <div
            class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-700 dark:text-indigo-300 font-bold text-sm shrink-0 overflow-hidden border-2 border-white dark:border-gray-700 shadow-sm"
          >
            <img
              v-if="user?.avatar"
              :src="user.avatar"
              :alt="user.fullname"
              class="w-full h-full object-cover"
            />
            <template v-else>
              {{ (user?.fullname || user?.name || "A").charAt(0).toUpperCase() }}
            </template>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-bold text-gray-900 dark:text-white truncate">
              {{ user?.fullname || user?.name || "Admin" }}
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
              {{ user?.email }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </aside>
</template>
