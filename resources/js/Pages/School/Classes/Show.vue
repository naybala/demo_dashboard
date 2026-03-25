<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
  classInfo: Object,
});

const cls = computed(() => props.classInfo?.data || props.classInfo || {});
</script>

<template>
  <Head>
    <title>View Class</title>
  </Head>

  <AdminLayout>
    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
      <div
        class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white dark:bg-gray-800 p-8 rounded-lg shadow-sm font-sans"
      >
        <!-- Header -->
        <div
          class="mb-10 flex flex-col md:flex-row md:justify-between items-start"
        >
          <div class="mb-4 md:mb-0">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
              View Class
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              Define class detail, schedule and class teacher. You can assign
              students later from the students or student modules.
            </p>
          </div>
          <button
            @click="router.visit('/classes')"
            class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition-colors whitespace-nowrap"
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
                d="M10 19l-7-7m0 0l7-7m-7 7h18"
              />
            </svg>
            Back to profile
          </button>
        </div>

        <!-- Basic Information -->
        <div class="mb-12">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
            Basic Information
          </h3>
          <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 font-medium">
            Identify the student with core profile fields.
          </p>

          <div
            class="grid grid-cols-[160px_20px_1fr] gap-y-4 items-center text-sm w-full max-w-lg"
          >
            <div class="text-gray-600 dark:text-gray-400">Grade</div>
            <div class="text-gray-400">-</div>
            <div class="text-gray-900 dark:text-white font-medium">
              {{ cls.grade_name || "-" }}
            </div>

            <div class="text-gray-600 dark:text-gray-400">Section</div>
            <div class="text-gray-400">-</div>
            <div class="text-gray-900 dark:text-white font-medium">
              {{ cls.section || "-" }}
            </div>

            <div class="text-gray-600 dark:text-gray-400">Capacity</div>
            <div class="text-gray-400">-</div>
            <div class="text-gray-900 dark:text-white font-medium">
              {{ cls.capacity || "-" }}
            </div>

            <div class="text-gray-600 dark:text-gray-400">Academic session</div>
            <div class="text-gray-400">-</div>
            <div class="text-gray-900 dark:text-white font-medium">
              {{ cls.session_name || "-" }}
            </div>
          </div>
        </div>

        <!-- Schedule & Attendance -->
        <div class="mb-12">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
            Schedule & Attendance
          </h3>
          <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 font-medium">
            Set teaching days, timings and default attendance configuration.
          </p>

          <div
            class="grid grid-cols-[160px_20px_1fr] gap-y-4 items-center text-sm w-full max-w-lg"
          >
            <div class="text-gray-600 dark:text-gray-400">Teaching days</div>
            <div class="text-gray-400">-</div>
            <div class="text-gray-900 dark:text-white font-medium">
              {{ cls.teaching_days || "-" }}
            </div>

            <div class="text-gray-600 dark:text-gray-400">
              Daily attendance mood
            </div>
            <div class="text-gray-400">-</div>
            <div class="text-gray-900 dark:text-white font-medium">
              {{ cls.attendance_mode || "-" }}
            </div>

            <div class="text-gray-600 dark:text-gray-400">Start time</div>
            <div class="text-gray-400">-</div>
            <div class="text-gray-900 dark:text-white font-medium">
              {{ cls.start_time || "-" }}
            </div>

            <div class="text-gray-600 dark:text-gray-400">End time</div>
            <div class="text-gray-400">-</div>
            <div class="text-gray-900 dark:text-white font-medium">
              {{ cls.end_time || "-" }}
            </div>
          </div>
        </div>

        <!-- Assignment -->
        <div class="pb-16">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
            Assignment
          </h3>
          <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 font-medium">
            Assign class teacher and link this class to subjects and fee
            category.
          </p>

          <div
            class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12 w-full max-w-4xl text-sm"
          >
            <!-- Left Column -->
            <div class="space-y-6">
              <div class="grid grid-cols-[160px_20px_1fr] items-center">
                <div class="text-gray-600 dark:text-gray-400">Head teacher</div>
                <div class="text-gray-400">-</div>
                <div class="text-gray-900 dark:text-white font-medium">
                  {{ cls.head_teacher_name || "-" }}
                </div>
              </div>

              <div>
                <div class="text-gray-600 dark:text-gray-400 mb-3">
                  Subjects
                </div>
                <div class="space-y-3 pl-1">
                  <div
                    v-for="(subj, idx) in cls.subjects"
                    :key="idx"
                    class="grid grid-cols-[160px_20px_1fr] items-center"
                  >
                    <div
                      class="flex items-center gap-2 text-gray-900 dark:text-white font-medium"
                    >
                      <div
                        class="w-3 h-3 rounded-full border-2 border-gray-400 flex items-center justify-center shrink-0"
                      >
                        <div class="w-1.5 h-1.5 bg-gray-600 rounded-full"></div>
                      </div>
                      <span class="truncate">{{ subj.name }}</span>
                    </div>
                    <div class="text-gray-400">-</div>
                    <div class="text-gray-600 dark:text-gray-300">
                      {{ subj.teacher_name || "-" }}
                    </div>
                  </div>
                  <div
                    v-if="!cls.subjects || cls.subjects.length === 0"
                    class="text-gray-500 italic"
                  >
                    No subjects assigned
                  </div>
                </div>
              </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4">
              <div class="grid grid-cols-[170px_20px_1fr] items-center">
                <div class="text-gray-600 dark:text-gray-400">Co-teacher</div>
                <div class="text-gray-400">-</div>
                <div class="text-gray-900 dark:text-white font-medium">
                  {{ cls.co_teacher_name || "-" }}
                </div>
              </div>
              <div class="grid grid-cols-[170px_20px_1fr] items-center">
                <div class="text-gray-600 dark:text-gray-400">
                  Daily attendance mood
                </div>
                <div class="text-gray-400">-</div>
                <div class="text-gray-900 dark:text-white font-medium">
                  {{ cls.attendance_mode || "-" }}
                </div>
              </div>
              <div class="grid grid-cols-[170px_20px_1fr] items-center">
                <div class="text-gray-600 dark:text-gray-400">
                  Allow makeup attendance
                </div>
                <div class="text-gray-400">-</div>
                <div
                  class="text-gray-900 dark:text-white font-medium capitalize"
                >
                  {{ cls.allow_makeup_attendance || "-" }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
