<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { __ } from "@/helpers.js";
import { ref, computed } from "vue";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
  data: Array,
  meta: Object,
  filters: Object,
});

const search = ref(props.filters.keyword || "");
const filterGrade = ref("All");
const filterSection = ref("All");
const filterSession = ref("25-26");
const page = usePage();
const permissions = computed(() => page.props.permissions || []);

const handleSearch = () => {
  router.get(
    "/classes",
    { keyword: search.value },
    { preserveState: true, replace: true },
  );
};

const deleteClass = (id) => {
  if (
    confirm(
      __(
        "messages.are_you_sure",
        "Are you sure you want to delete this class?",
      ),
    )
  ) {
    router.delete(`/classes/${id}`);
  }
};

const stats = computed(() => {
  // Mock computed stats to match UI design if backend doesn't provide them
  return {
    totalClasses: props.meta.total || 0,
    gradeCovered: "K-12",
    averageSize: "29 students",
    seatsUsed: "842",
    seatsTotal: "960",
    utilization: "88%",
    session: "2025-26",
    unassignedStudents: "18",
  };
});
</script>

<template>
  <Head>
    <title>{{ __("school.classes", "Classes") }}</title>
  </Head>

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]"
      >
        {{ __("school.classes", "Classes") }}
      </h2>
    </template>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
      <div class="mx-auto sm:px-6 lg:px-8">
        <!-- Header Component -->
        <div
          class="mb-6 bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-100 dark:border-gray-700"
        >
          <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4"
          >
            <div>
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
                Classes
              </h3>
              <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                Organizes classes, sections, teachers and student assignments.
              </p>
            </div>
            <div class="flex items-center gap-3">
              <SecondaryButton
                class="flex items-center gap-2 text-sm font-medium bg-gray-200 hover:bg-gray-300 border-none text-gray-700"
              >
                <svg
                  class="w-4 h-4"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"
                  />
                </svg>
                Import
              </SecondaryButton>
              <PrimaryButton
                @click="router.visit('/classes/create')"
                class="flex items-center gap-2 text-sm font-medium bg-blue-600 hover:bg-blue-700"
              >
                <svg
                  class="w-4 h-4"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 4v16m8-8H4"
                  />
                </svg>
                Add Class
              </PrimaryButton>
            </div>
          </div>

          <div
            class="mt-8 flex flex-col md:flex-row md:items-center justify-between gap-4"
          >
            <div class="relative w-full md:w-96">
              <div
                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
              >
                <svg
                  class="h-5 w-5 text-gray-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                  />
                </svg>
              </div>
              <input
                v-model="search"
                @keydown.enter="handleSearch"
                type="text"
                class="pl-10 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white"
                placeholder="Search by class, section or teacher"
              />
            </div>
            <div
              class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-300"
            >
              <div class="flex items-center gap-2">
                <span class="text-gray-400">Grade :</span>
                <select
                  v-model="filterGrade"
                  class="border-none bg-transparent hover:bg-gray-50 focus:ring-0 cursor-pointer font-medium p-0 pr-4"
                >
                  <option>All</option>
                </select>
              </div>
              <div class="flex items-center gap-2">
                <span class="text-gray-400">Section:</span>
                <select
                  v-model="filterSection"
                  class="border-none bg-transparent hover:bg-gray-50 focus:ring-0 cursor-pointer font-medium p-0 pr-4"
                >
                  <option>All</option>
                </select>
              </div>
              <div class="flex items-center gap-2">
                <span class="text-gray-400">Session:</span>
                <select
                  v-model="filterSession"
                  class="border-none bg-transparent hover:bg-gray-50 focus:ring-0 cursor-pointer font-medium p-0 pr-4"
                >
                  <option>25-26</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
          <!-- Left Main Content -->
          <div
            class="flex-1 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden"
          >
            <div
              class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center text-sm"
            >
              <span class="text-gray-500 dark:text-gray-400"
                >Showing {{ data.length }} of {{ meta.total }} classes</span
              >
              <div
                class="flex items-center gap-2 text-gray-600 dark:text-gray-300"
              >
                <span class="text-gray-400">Sort by</span>
                <select
                  class="border-none bg-transparent font-medium p-0 pr-4 hover:bg-gray-50 cursor-pointer"
                >
                  <option>Grade</option>
                </select>
              </div>
            </div>

            <div class="overflow-x-auto">
              <table
                class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
              >
                <thead>
                  <tr class="bg-gray-50 dark:bg-gray-800/50">
                    <th
                      scope="col"
                      class="py-3 px-6 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider"
                    >
                      Grade
                    </th>
                    <th
                      scope="col"
                      class="py-3 px-6 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider"
                    >
                      Section
                    </th>
                    <th
                      scope="col"
                      class="py-3 px-6 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider"
                    >
                      Class teacher
                    </th>
                    <th
                      scope="col"
                      class="py-3 px-6 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider"
                    >
                      Schedule
                    </th>
                    <th
                      scope="col"
                      class="py-3 px-6 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider"
                    >
                      Action
                    </th>
                  </tr>
                </thead>
                <tbody
                  class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700"
                >
                  <tr
                    v-for="(cls, index) in data"
                    :key="cls.id"
                    class="hover:bg-gray-50 dark:hover:bg-gray-700"
                  >
                    <td
                      class="py-4 px-6 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100"
                    >
                      {{ cls.grade_name }}
                    </td>
                    <td
                      class="py-4 px-6 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300"
                    >
                      {{ cls.section }}
                    </td>
                    <td
                      class="py-4 px-6 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300"
                    >
                      {{ cls.head_teacher_name || "Unassigned" }}
                    </td>
                    <td
                      class="py-4 px-6 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium"
                    >
                      {{ cls.teaching_days }}, {{ cls.start_time }}-{{ cls.end_time }}
                    </td>
                    <td class="py-4 px-6 text-sm text-center">
                      <div class="flex items-center justify-center gap-2">
                        <Link
                          v-if="permissions.includes('show classes')"
                          :href="`/classes/${cls.id}`"
                        >
                          <SecondaryButton>{{
                            __("messages.view", "View")
                          }}</SecondaryButton>
                        </Link>
                        <Link
                          v-if="permissions.includes('edit classes')"
                          :href="`/classes/${cls.id}/edit`"
                        >
                          <SecondaryButton>{{
                            __("messages.edit", "Edit")
                          }}</SecondaryButton>
                        </Link>
                        <SecondaryButton
                          v-if="permissions.includes('delete classes')"
                          variant="danger"
                          @click="deleteClass(cls.id)"
                        >
                          {{ __("messages.delete", "Delete") }}
                        </SecondaryButton>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div
              class="px-6 py-4 border-t border-gray-200 dark:border-gray-700"
            >
              <Pagination :meta="meta" />
            </div>
          </div>

          <!-- Right Sidebar -->
          <div class="w-full lg:w-80 flex flex-col gap-6">
            <!-- Class overview -->
            <div
              class="bg-white dark:bg-gray-800 rounded-lg p-5 shadow-sm border border-gray-100 dark:border-gray-700"
            >
              <h4
                class="text-base font-semibold text-gray-900 dark:text-white mb-4"
              >
                Class overview
              </h4>
              <div class="space-y-3">
                <div class="flex justify-between items-center text-sm">
                  <span class="text-gray-500 dark:text-gray-400"
                    >Total classes</span
                  >
                  <span class="font-semibold text-gray-900 dark:text-white">{{
                    stats.totalClasses
                  }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                  <span class="text-gray-500 dark:text-gray-400"
                    >Grade covered</span
                  >
                  <span class="font-semibold text-gray-900 dark:text-white">{{
                    stats.gradeCovered
                  }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                  <span class="text-gray-500 dark:text-gray-400"
                    >Average size</span
                  >
                  <span class="font-semibold text-gray-900 dark:text-white">{{
                    stats.averageSize
                  }}</span>
                </div>
              </div>
            </div>

            <!-- Capacity & allocation -->
            <div
              class="bg-white dark:bg-gray-800 rounded-lg p-5 shadow-sm border border-gray-100 dark:border-gray-700"
            >
              <h4
                class="text-base font-semibold text-gray-900 dark:text-white mb-4"
              >
                Capacity & allocation
              </h4>
              <div class="space-y-3 mb-5">
                <div class="flex justify-between items-center text-sm">
                  <span class="text-gray-500 dark:text-gray-400"
                    >Seats used</span
                  >
                  <span class="font-semibold text-gray-900 dark:text-white"
                    >{{ stats.seatsUsed }}/{{ stats.seatsTotal }}</span
                  >
                </div>
                <div class="flex justify-between items-center text-sm">
                  <span class="text-gray-500 dark:text-gray-400"
                    >Utilization</span
                  >
                  <span class="font-semibold text-gray-900 dark:text-white">{{
                    stats.utilization
                  }}</span>
                </div>
              </div>
              <div class="flex flex-wrap gap-2">
                <button
                  class="px-3 py-1.5 rounded-md text-xs font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900/40 dark:text-blue-300"
                >
                  View large sections
                </button>
                <button
                  class="px-3 py-1.5 rounded-md text-xs font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900/40 dark:text-blue-300"
                >
                  Balance classes
                </button>
              </div>
            </div>

            <!-- Quick Assignment -->
            <div
              class="bg-white dark:bg-gray-800 rounded-lg p-5 shadow-sm border border-gray-100 dark:border-gray-700"
            >
              <h4
                class="text-base font-semibold text-gray-900 dark:text-white mb-4"
              >
                Quick Assignment
              </h4>
              <div class="space-y-3 mb-4">
                <div class="flex justify-between items-center text-sm">
                  <span class="text-gray-500 dark:text-gray-400">Session</span>
                  <span class="font-semibold text-gray-900 dark:text-white">{{
                    stats.session
                  }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                  <span class="text-gray-500 dark:text-gray-400"
                    >Unassigned student</span
                  >
                  <span class="font-semibold text-gray-900 dark:text-white">{{
                    stats.unassignedStudents
                  }}</span>
                </div>
              </div>
              <a
                href="#"
                class="text-sm text-blue-600 hover:text-blue-800 font-medium dark:text-blue-400"
                >Open class assignment</a
              >
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
