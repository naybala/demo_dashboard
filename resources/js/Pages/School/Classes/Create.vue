<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import { __ } from "@/helpers.js";
import { ref, onMounted } from "vue";

const props = defineProps({
    grades: Array,
    sessions: Array,
    subjects: Array,
    teachers: Array,
    editData: Object,
});

const form = useForm({
    id: props.editData?.id || null,
    grade_id: props.editData?.grade_id || "",
    session_id: props.editData?.session_id || "",
    section: props.editData?.section || "",
    name: props.editData?.name || "",
    capacity: props.editData?.capacity || "",
    teaching_days: props.editData?.teaching_days || "Mon to Fri",
    daily_time: props.editData?.daily_time || "9:00 am - 4:00 pm",
    attendance_mode: props.editData?.attendance_mode || "Daily",
    allow_makeup_attendance: props.editData?.allow_makeup_attendance || "enable",
    head_teacher_id: props.editData?.head_teacher_id || "",
    co_teacher_id: props.editData?.co_teacher_id || "",
    notes: props.editData?.notes || "",
    status: props.editData?.status || "active",
    subjects: props.editData?.subjects?.length > 0 
        ? props.editData.subjects.map(s => ({ subject_id: s.id, teacher_id: s.teacher_id, hours_per_week: s.hours_per_week })) 
        : [{ subject_id: "", teacher_id: "", hours_per_week: "" }],
});

const addSubject = () => {
    form.subjects.push({ subject_id: "", teacher_id: "", hours_per_week: "" });
};

const removeSubject = (index) => {
    form.subjects.splice(index, 1);
};

const submit = () => {
    if (form.id) {
        form.put(`/classes/${form.id}`, {
            onSuccess: () => router.visit('/classes'),
        });
    } else {
        form.post("/classes", {
            onSuccess: () => router.visit('/classes'),
        });
    }
};

const cancel = () => {
    router.visit('/classes');
};
</script>

<template>
    <Head>
        <title>{{ form.id ? __('school.edit_class', 'Edit Class') : __('school.create_class', 'Create Class') }}</title>
    </Head>

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ form.id ? __('school.edit_class', 'Edit Class') : __('school.create_class', 'Create Class') }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit" class="space-y-8">
                        <!-- Basic Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b pb-2 mb-4">
                                {{ __('school.basic_info', 'Basic Information') }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel for="grade_id" :value="__('school.grade', 'Grade')" />
                                    <select id="grade_id" v-model="form.grade_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                                        <option value="">{{ __('school.select_grade', 'Select Grade') }}</option>
                                        <option v-for="grade in grades" :key="grade.id" :value="grade.id">{{ grade.name }}</option>
                                    </select>
                                    <InputError :message="form.errors.grade_id" class="mt-2" />
                                </div>
                                <div>
                                    <InputLabel for="section" :value="__('school.section', 'Section')" />
                                    <TextInput id="section" type="text" class="mt-1 block w-full" v-model="form.section" placeholder="e.g - A" />
                                    <InputError :message="form.errors.section" class="mt-2" />
                                </div>
                                <div>
                                    <InputLabel for="session_id" :value="__('school.academic_session', 'Academic Session')" />
                                    <select id="session_id" v-model="form.session_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                                        <option value="">{{ __('school.select_session', 'Select Session') }}</option>
                                        <option v-for="session in sessions" :key="session.id" :value="session.id">{{ session.name }}</option>
                                    </select>
                                    <InputError :message="form.errors.session_id" class="mt-2" />
                                </div>
                                <div>
                                    <InputLabel for="capacity" :value="__('school.capacity', 'Capacity')" />
                                    <TextInput id="capacity" type="number" class="mt-1 block w-full" v-model="form.capacity" placeholder="Max student" />
                                    <InputError :message="form.errors.capacity" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Schedule & Attendance -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b pb-2 mb-4">
                                {{ __('school.schedule_attendance', 'Schedule & Attendance') }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel for="teaching_days" :value="__('school.teaching_days', 'Teaching days')" />
                                    <TextInput id="teaching_days" type="text" class="mt-1 block w-full" v-model="form.teaching_days" placeholder="Mon to Fri" />
                                    <InputError :message="form.errors.teaching_days" class="mt-2" />
                                </div>
                                <div>
                                    <InputLabel for="daily_time" :value="__('school.daily_time', 'Daily time')" />
                                    <TextInput id="daily_time" type="text" class="mt-1 block w-full" v-model="form.daily_time" placeholder="e.g 9:00 am - 4:00 pm" />
                                    <InputError :message="form.errors.daily_time" class="mt-2" />
                                </div>
                                <div>
                                    <InputLabel for="attendance_mode" :value="__('school.daily_attendance_mode', 'Daily attendance mode')" />
                                    <select id="attendance_mode" v-model="form.attendance_mode" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                                        <option value="Daily">Daily</option>
                                        <option value="Subject-wise">Subject-wise</option>
                                    </select>
                                    <InputError :message="form.errors.attendance_mode" class="mt-2" />
                                </div>
                                <div>
                                    <InputLabel for="allow_makeup_attendance" :value="__('school.allow_makeup_attendance', 'Allow makeup attendance')" />
                                    <select id="allow_makeup_attendance" v-model="form.allow_makeup_attendance" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                                        <option value="enable">Enable</option>
                                        <option value="disable">Disable</option>
                                    </select>
                                    <InputError :message="form.errors.allow_makeup_attendance" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Assignment -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b pb-2 mb-4">
                                {{ __('school.assignment', 'Assignment') }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <InputLabel for="head_teacher_id" :value="__('school.head_teacher', 'Head teacher')" />
                                    <select id="head_teacher_id" v-model="form.head_teacher_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                                        <option value="">{{ __('school.select_teacher', 'Select teacher') }}</option>
                                        <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">{{ teacher.fullname }}</option>
                                    </select>
                                    <InputError :message="form.errors.head_teacher_id" class="mt-2" />
                                </div>
                                <div>
                                    <InputLabel for="co_teacher_id" :value="__('school.co_teacher', 'Co-teacher')" />
                                    <select id="co_teacher_id" v-model="form.co_teacher_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                                        <option value="">{{ __('school.optional', 'Optional') }}</option>
                                        <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">{{ teacher.fullname }}</option>
                                    </select>
                                    <InputError :message="form.errors.co_teacher_id" class="mt-2" />
                                </div>
                            </div>

                            <div class="space-y-4">
                                <InputLabel :value="__('school.subjects_teachers', 'Subjects & Teachers')" />
                                <div v-for="(subj, index) in form.subjects" :key="index" class="flex flex-wrap items-end gap-4 p-4 border rounded-lg bg-gray-50 dark:bg-gray-700/30">
                                    <div class="flex-1 min-w-[200px]">
                                        <InputLabel :value="__('school.subject', 'Subject')" />
                                        <select v-model="subj.subject_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                                            <option value="">{{ __('school.select_subject', 'Select Subject') }}</option>
                                            <option v-for="s in subjects" :key="s.id" :value="s.id">{{ s.name }}</option>
                                        </select>
                                    </div>
                                    <div class="flex-1 min-w-[200px]">
                                        <InputLabel :value="__('school.teacher', 'Teacher')" />
                                        <select v-model="subj.teacher_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                                            <option value="">{{ __('school.select_teacher', 'Select teacher') }}</option>
                                            <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">{{ teacher.fullname }}</option>
                                        </select>
                                    </div>
                                    <div class="w-32">
                                        <InputLabel :value="__('school.hours_week', 'Hrs/Week')" />
                                        <TextInput type="number" v-model="subj.hours_per_week" class="mt-1 block w-full" placeholder="Hrs" />
                                    </div>
                                    <button @click.prevent="removeSubject(index)" v-if="form.subjects.length > 1" class="mb-1 p-2 text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                                <PrimaryButton type="button" @click="addSubject" class="mt-2">
                                    {{ __('school.add_subject', 'Add Subject & Teacher') }}
                                </PrimaryButton>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <InputLabel for="notes" :value="__('school.notes', 'Notes')" />
                            <textarea id="notes" v-model="form.notes" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm" :placeholder="__('school.notes_placeholder', 'Optional notes for administrators and teacher about this class.')"></textarea>
                            <InputError :message="form.errors.notes" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4 gap-4 border-t pt-4">
                            <SecondaryButton @click="cancel">{{ __('messages.cancel', 'Cancel') }}</SecondaryButton>
                            <PrimaryButton :disabled="form.processing">
                                {{ form.id ? __('school.update_class', 'Update Class') : __('school.create_class', 'Create Class') }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
