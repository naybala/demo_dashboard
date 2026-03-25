<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { __ } from "@/helpers.js";
import { ref } from "vue";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    data: Array,
    meta: Object,
    filters: Object,
});

const search = ref(props.filters.keyword || "");

const handleSearch = () => {
    router.get("/classes", { keyword: search.value }, { preserveState: true, replace: true });
};

const resetSearch = () => {
    search.value = "";
    handleSearch();
};
</script>

<template>
    <Head>
        <title>{{ __('school.classes', 'Classes') }}</title>
    </Head>

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-[0.20rem]">
                {{ __('school.classes', 'Classes') }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Summary Header -->
                <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg text-blue-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('school.classes', 'Classes') }}</h3>
                            <p class="text-gray-500 dark:text-gray-400">{{ meta.total }} total</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="relative w-64">
                            <TextInput v-model="search" @keydown.enter="handleSearch" class="w-full pl-10" :placeholder="__('messages.search', 'Search...')" />
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </div>
                        </div>
                        <PrimaryButton @click="router.visit('/classes/create')">
                            {{ __('school.add_class', 'Add Class') }}
                        </PrimaryButton>
                    </div>
                </div>

                <!-- Classes Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <div v-for="cls in data" :key="cls.id" @click="router.visit(`/classes/${cls.id}`)" class="cursor-pointer group bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm hover:shadow-md transition-all hover:border-blue-500 dark:hover:border-blue-400">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                    {{ cls.grade_name }}
                                </h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Subject {{ cls.subjects?.length || 0 }}</span>
                                    <span class="text-gray-300 dark:text-gray-600">•</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Section {{ cls.section }}</span>
                                </div>
                            </div>
                            <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 dark:text-gray-400">{{ cls.students_count || 0 }}/{{ cls.capacity }} students</span>
                                <div class="w-32 h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-500" :style="{ width: Math.min((cls.students_count || 0) / cls.capacity * 100, 100) + '%' }"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <Pagination :meta="meta" />
            </div>
        </div>
    </AdminLayout>
</template>
