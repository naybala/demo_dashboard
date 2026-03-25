<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { __ } from "@/helpers.js";

const props = defineProps({
    classInfo: Object,
});

const editClass = () => {
    router.visit(`/classes/${props.classInfo.id}/edit`);
};

const backToIndex = () => {
    router.visit('/classes');
};
</script>

<template>
    <Head>
        <title>{{ classInfo.grade_name }} - {{ classInfo.section }}</title>
    </Head>

    <AdminLayout>
        <template #header>
            <div class="flex justify-between items-center w-full">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ classInfo.grade_name }} - {{ classInfo.section }}
                </h2>
                <div class="flex gap-3">
                    <SecondaryButton @click="backToIndex">{{ __('messages.back', 'Back') }}</SecondaryButton>
                    <PrimaryButton @click="editClass">{{ __('messages.edit', 'Edit Class') }}</PrimaryButton>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Class Header Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-sm mb-8 border border-gray-100 dark:border-gray-700">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ classInfo.grade_name }}</h1>
                            <div class="flex flex-wrap items-center gap-4 text-gray-500 dark:text-gray-400">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                    {{ classInfo.section }}
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                    {{ classInfo.students_count || 0 }}/{{ classInfo.capacity }} students
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    {{ classInfo.academic_year || classInfo.session_name }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('school.head_teacher', 'Head Teacher') }}</p>
                            <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ classInfo.head_teacher_name || 'Not assigned' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Subjects & Schedule Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Subjects & Teachers List -->
                    <div class="lg:col-span-2">
                        <div class="flex items-center gap-2 mb-6">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('school.subjects_teachers', 'Subjects & Teachers') }}</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div v-for="subj in classInfo.subjects" :key="subj.id" class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-100 dark:border-gray-700">
                                <h4 class="font-bold text-gray-900 dark:text-white mb-1">{{ subj.name }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">{{ subj.teacher_name || 'To be assigned' }}</p>
                                <div class="flex items-center gap-2 text-xs text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    {{ subj.hours_per_week }} hours/week
                                </div>
                            </div>
                        </div>

                        <!-- Weekly Timetable Placeholder -->
                        <div class="mt-12 bg-gray-50 dark:bg-gray-900/30 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl p-12 text-center">
                            <div class="mx-auto w-16 h-16 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center shadow-sm mb-4 text-gray-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ __('school.weekly_timetable', 'Weekly Timetable') }}</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-sm mx-auto">Generate a balanced weekly schedule for this class based on core subjects and teaching hours.</p>
                            <PrimaryButton>{{ __('school.create_timetable', 'Create Timetable') }}</PrimaryButton>
                        </div>
                    </div>

                    <!-- Sidebar Info -->
                    <div class="space-y-6">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-100 dark:border-gray-700">
                            <h4 class="font-bold text-gray-900 dark:text-white mb-4">{{ __('school.schedule', 'Schedule') }}</h4>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">{{ __('school.teaching_days', 'Teaching Days') }}</p>
                                    <p class="text-gray-900 dark:text-white">{{ classInfo.teaching_days }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">{{ __('school.daily_time', 'Daily Time') }}</p>
                                    <p class="text-gray-900 dark:text-white">{{ classInfo.daily_time }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">{{ __('school.attendance_mode', 'Attendance Mode') }}</p>
                                    <p class="text-gray-900 dark:text-white">{{ classInfo.attendance_mode }}</p>
                                </div>
                            </div>
                        </div>

                        <div v-if="classInfo.notes" class="bg-amber-50 dark:bg-amber-950/20 p-6 rounded-xl border border-amber-100 dark:border-amber-900/40">
                            <h4 class="font-bold text-amber-900 dark:text-amber-400 mb-2">{{ __('school.notes', 'Notes') }}</h4>
                            <p class="text-sm text-amber-800 dark:text-amber-500/80 leading-relaxed">{{ classInfo.notes }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
