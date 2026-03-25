<script setup>
import { Head } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
</script>

<template>
  <Head title="Class & Subject" />

  <AdminLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]">
        School Management System
      </h2>
    </template>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
      <div class="mx-auto sm:px-6 lg:px-8">
        <!-- Classes Overview Section -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Classes</h3>
                    <p class="text-sm text-gray-500">13 total</p>
                </div>
            </div>
            
            <div class="flex gap-4 overflow-x-auto pb-4 custom-scrollbar">
                <!-- Grade 12 Card (Active) -->
                <div class="min-w-[300px] border-2 border-blue-500 rounded-xl p-5 bg-white dark:bg-gray-800 shadow-sm cursor-pointer relative">
                    <h4 class="font-bold text-gray-900 dark:text-white mb-4">Grade 12</h4>
                    <div class="flex items-center gap-4 text-xs lg:text-sm text-gray-500 mb-6">
                        <span>Subject <span class="text-gray-900 dark:text-white font-medium ml-1">All</span></span>
                        <span class="text-gray-300">•</span>
                        <span>Section <span class="text-gray-900 dark:text-white font-medium ml-1">3</span></span>
                    </div>
                    <p class="text-xs text-gray-500 font-medium">28/30 students</p>
                </div>
                
                <!-- Grade 10 Card -->
                <div class="min-w-[300px] border border-gray-200 dark:border-gray-700/60 rounded-xl p-5 bg-white dark:bg-gray-800 shadow-sm cursor-pointer hover:border-blue-300 transition-colors">
                    <h4 class="font-bold text-gray-900 dark:text-white mb-4">Grade 10</h4>
                    <div class="flex items-center gap-4 text-xs lg:text-sm text-gray-500 mb-6">
                        <span>Subject <span class="text-gray-900 dark:text-white font-medium ml-1">All</span></span>
                        <span class="text-gray-300">•</span>
                        <span>Section <span class="text-gray-900 dark:text-white font-medium ml-1">B</span></span>
                    </div>
                    <p class="text-xs text-gray-500 font-medium">25/30 students</p>
                </div>

                <!-- Grade 8 Card -->
                <div class="min-w-[300px] border border-gray-200 dark:border-gray-700/60 rounded-xl p-5 bg-white dark:bg-gray-800 shadow-sm cursor-pointer hover:border-blue-300 transition-colors">
                    <h4 class="font-bold text-gray-900 dark:text-white mb-4">Grade 8</h4>
                    <div class="flex items-center gap-4 text-xs lg:text-sm text-gray-500 mb-6">
                        <span>Subject <span class="text-gray-900 dark:text-white font-medium ml-1">All</span></span>
                        <span class="text-gray-300">•</span>
                        <span>Section <span class="text-gray-900 dark:text-white font-medium ml-1">B</span></span>
                    </div>
                    <p class="text-xs text-gray-500 font-medium">0/27 students</p>
                </div>
            </div>
        </div>

        <!-- Grade Detail Section -->
        <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
            <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-3 tracking-tight">Grade 12</h2>
            <div class="flex items-center gap-4 text-sm text-gray-500 mb-10">
                <span class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-gray-400"></span> Section 3</span>
                <span class="flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-gray-400"></span> 228/230 students</span>
            </div>

            <!-- Subjects & Teachers -->
            <div class="mb-6 flex items-center gap-3">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Subjects & Teachers</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Subject Card -->
                <div class="border border-gray-200 dark:border-gray-700/60 rounded-xl p-5 bg-white dark:bg-gray-800 shadow-sm relative overflow-hidden group">
                    <h4 class="font-bold text-gray-900 dark:text-white mb-3 text-sm">Mathematics</h4>
                    <p class="text-xs text-gray-500 mb-6">Dr. Sarah Johnson</p>
                    <div class="flex items-center text-[11px] text-gray-400 gap-1.5 font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        5 hours/week
                    </div>
                </div>

                <!-- Subject Card -->
                <div class="border border-gray-200 dark:border-gray-700/60 rounded-xl p-5 bg-white dark:bg-gray-800 shadow-sm relative overflow-hidden group">
                    <h4 class="font-bold text-gray-900 dark:text-white mb-3 text-sm">Physics</h4>
                    <p class="text-xs text-gray-500 mb-6">Prof. Michael Chen</p>
                    <div class="flex items-center text-[11px] text-gray-400 gap-1.5 font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        4 hours/week
                    </div>
                </div>

                <!-- Subject Card -->
                <div class="border border-gray-200 dark:border-gray-700/60 rounded-xl p-5 bg-white dark:bg-gray-800 shadow-sm relative overflow-hidden group">
                    <h4 class="font-bold text-gray-900 dark:text-white mb-3 text-sm">Chemistry</h4>
                    <p class="text-xs text-gray-500 mb-6">Dr. Emily Brown</p>
                    <div class="flex items-center text-[11px] text-gray-400 gap-1.5 font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        4 hours/week
                    </div>
                </div>

                <!-- Subject Card -->
                <div class="border border-gray-200 dark:border-gray-700/60 rounded-xl p-5 bg-white dark:bg-gray-800 shadow-sm relative overflow-hidden group">
                    <h4 class="font-bold text-gray-900 dark:text-white mb-3 text-sm">Myanmar</h4>
                    <p class="text-xs text-gray-500 mb-6">Dr. Emily Brown</p>
                    <div class="flex items-center text-[11px] text-gray-400 gap-1.5 font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        4 hours/week
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
