<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import SearchableSelect from "@/Components/SearchableSelect.vue";
import { useForm, Head, Link, router } from "@inertiajs/vue3";
import { __ } from "@/helpers.js";
import { computed } from "vue";

const props = defineProps({
  student: {
    type: Object,
    default: null,
  },
  grades: Array,
  classes: Array,
  academic_sessions: Array,
  is_editing: {
    type: Boolean,
    default: false,
  },
});

const classOptions = computed(() =>
  props.classes.map((c) => ({ id: c.id, label: c.name })),
);

const gradeOptions = computed(() =>
  props.grades.map((g) => ({ id: g.id, label: g.name })),
);

const aliveStatusOptions = [
  { id: "alive", label: __("school.alive", "Alive") },
  { id: "dead", label: __("school.dead", "Dead") },
];

const form = useForm({
  academic_year: props.student?.academic_year || "",
  class_id: props.student?.class_id || "",
  full_name: props.student?.full_name || "",
  other_name: props.student?.other_name || "",
  email: props.student?.email || "",
  registration_date: props.student?.registration_date || "",
  student_code: props.student?.student_code || "",
  gender: props.student?.gender || "male",
  dob: props.student?.dob || "",
  place_of_birth: props.student?.place_of_birth || "",
  nrc: props.student?.nrc || "",
  nationality: props.student?.nationality || "",
  religion: props.student?.religion || "",
  address: props.student?.address || "",
  school_attended: props.student?.school_attended || "",
  grade_attended: props.student?.grade_attended || "",
  year_attended: props.student?.year_attended || "",
  grade_id: props.student?.grade_id || "",
  father_name: props.student?.father_name || "",
  father_nrc: props.student?.father_nrc || "",
  father_qualification: props.student?.father_qualification || "",
  father_job: props.student?.father_job || "",
  father_phone: props.student?.father_phone || "",
  father_email: props.student?.father_email || "",
  father_address: props.student?.father_address || "",
  father_alive_status: props.student?.father_alive_status || "alive",
  mother_name: props.student?.mother_name || "",
  mother_nrc: props.student?.mother_nrc || "",
  mother_qualification: props.student?.mother_qualification || "",
  mother_job: props.student?.mother_job || "",
  mother_phone: props.student?.mother_phone || "",
  mother_email: props.student?.mother_email || "",
  mother_address: props.student?.mother_address || "",
  mother_alive_status: props.student?.mother_alive_status || "alive",
  guardian_name: props.student?.guardian_name || "",
  guardian_nrc: props.student?.guardian_nrc || "",
  guardian_qualification: props.student?.guardian_qualification || "",
  guardian_job: props.student?.guardian_job || "",
  guardian_phone: props.student?.guardian_phone || "",
  guardian_email: props.student?.guardian_email || "",
  guardian_address: props.student?.guardian_address || "",
  guardian_alive_status: props.student?.guardian_alive_status || "alive",
});

const submit = () => {
  if (props.is_editing) {
    form.put(`/students/${props.student.id}`);
  } else {
    form.post("/students");
  }
};
</script>

<template>
  <Head :title="is_editing ? __('school.edit_student') : __('school.add_student')" />

  <AdminLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          {{ is_editing ? __("school.edit_student") : __("school.add_student") }}
        </h2>
        <Link
          href="/students"
          class="text-sm text-gray-600 dark:text-gray-400 hover:underline"
        >
          &larr; {{ __("messages.back_to_list") }}
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <form @submit.prevent="submit" class="space-y-8 bg-white dark:bg-gray-800 p-8 rounded-lg shadow">
          
          <!-- Basic Information -->
          <section>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b pb-2 mb-4">
              {{ __("school.basic_information") }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <InputLabel for="academic_year" :value="__('school.academic_year')" />
                <TextInput id="academic_year" v-model="form.academic_year" class="mt-1 block w-full" />
                <InputError :message="form.errors.academic_year" />
              </div>
              <div>
                <InputLabel for="class_id" :value="__('school.class')" />
                  <SearchableSelect
                    id="class_id"
                    v-model="form.class_id"
                    :options="classOptions"
                    :placeholder="__('student.select_class', 'Select Class')"
                    class="mt-1 block w-full"
                    :error="form.errors.class_id"
                  />
                <InputError :message="form.errors.class_id" />
              </div>
              <div>
                <InputLabel for="full_name" :value="__('school.full_name')" />
                <TextInput id="full_name" v-model="form.full_name" class="mt-1 block w-full" required />
                <InputError :message="form.errors.full_name" />
              </div>
              <div>
                <InputLabel for="other_name" :value="__('school.other_name')" />
                <TextInput id="other_name" v-model="form.other_name" class="mt-1 block w-full" />
                <InputError :message="form.errors.other_name" />
              </div>
              <div>
                <InputLabel for="email" :value="__('school.email_address')" />
                <TextInput id="email" type="email" v-model="form.email" class="mt-1 block w-full" />
                <InputError :message="form.errors.email" />
              </div>
              <div>
                <InputLabel for="registration_date" :value="__('school.registration_date')" />
                <TextInput id="registration_date" type="date" v-model="form.registration_date" class="mt-1 block w-full" />
                <InputError :message="form.errors.registration_date" />
              </div>
              <div>
                <InputLabel for="student_code" :value="__('school.student_id')" />
                <TextInput id="student_code" v-model="form.student_code" class="mt-1 block w-full" required />
                <InputError :message="form.errors.student_code" />
              </div>
              <div>
                <InputLabel :value="__('school.gender')" />
                <div class="mt-2 flex gap-4">
                  <label class="flex items-center">
                    <input type="radio" value="male" v-model="form.gender" class="rounded border-gray-300 text-indigo-600 shadow-sm" />
                    <span class="ml-2">{{ __("school.male") }}</span>
                  </label>
                  <label class="flex items-center">
                    <input type="radio" value="female" v-model="form.gender" class="rounded border-gray-300 text-indigo-600 shadow-sm" />
                    <span class="ml-2">{{ __("school.female") }}</span>
                  </label>
                  <label class="flex items-center">
                    <input type="radio" value="other" v-model="form.gender" class="rounded border-gray-300 text-indigo-600 shadow-sm" />
                    <span class="ml-2">{{ __("school.other") }}</span>
                  </label>
                </div>
                <InputError :message="form.errors.gender" />
              </div>
            </div>
          </section>

          <!-- Personal Details -->
          <section>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b pb-2 mb-4">
              {{ __("school.personal_details") }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <InputLabel for="dob" :value="__('school.date_of_birth')" />
                <TextInput id="dob" type="date" v-model="form.dob" class="mt-1 block w-full" />
                <InputError :message="form.errors.dob" />
              </div>
              <div>
                <InputLabel for="place_of_birth" :value="__('school.place_of_birth')" />
                <TextInput id="place_of_birth" v-model="form.place_of_birth" class="mt-1 block w-full" />
                <InputError :message="form.errors.place_of_birth" />
              </div>
              <div>
                <InputLabel for="nrc" :value="__('school.nrc')" />
                <TextInput id="nrc" v-model="form.nrc" class="mt-1 block w-full" />
                <InputError :message="form.errors.nrc" />
              </div>
              <div>
                <InputLabel for="nationality" :value="__('school.nationality')" />
                <TextInput id="nationality" v-model="form.nationality" class="mt-1 block w-full" />
                <InputError :message="form.errors.nationality" />
              </div>
              <div>
                <InputLabel for="religion" :value="__('school.religion')" />
                <TextInput id="religion" v-model="form.religion" class="mt-1 block w-full" />
                <InputError :message="form.errors.religion" />
              </div>
              <div>
                <InputLabel for="address" :value="__('school.address')" />
                <textarea id="address" v-model="form.address" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"></textarea>
                <InputError :message="form.errors.address" />
              </div>
            </div>
          </section>

          <!-- Academic Details -->
          <section>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b pb-2 mb-4">
              {{ __("school.academic_details") }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <InputLabel for="school_attended" :value="__('school.school_attended')" />
                <TextInput id="school_attended" v-model="form.school_attended" class="mt-1 block w-full" />
                <InputError :message="form.errors.school_attended" />
              </div>
              <div>
                <InputLabel for="grade_attended" :value="__('school.grade_attended')" />
                <TextInput id="grade_attended" v-model="form.grade_attended" class="mt-1 block w-full" />
                <InputError :message="form.errors.grade_attended" />
              </div>
              <div>
                <InputLabel for="year_attended" :value="__('school.year_attended')" />
                <TextInput id="year_attended" v-model="form.year_attended" class="mt-1 block w-full" />
                <InputError :message="form.errors.year_attended" />
              </div>
              <div>
                <InputLabel for="grade_id" :value="__('school.grade')" />
                  <SearchableSelect
                    id="grade_id"
                    v-model="form.grade_id"
                    :options="gradeOptions"
                    :placeholder="__('student.select_grade', 'Select Grade')"
                    class="mt-1 block w-full"
                    :error="form.errors.grade_id"
                  />
                <InputError :message="form.errors.grade_id" />
              </div>
            </div>
          </section>

          <!-- Parents Info (Father & Mother) -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <!-- Father -->
            <section>
              <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b pb-2 mb-4">
                {{ __("school.father_information") }}
              </h3>
              <div class="space-y-4">
                <div>
                  <InputLabel for="father_name" :value="__('school.father_name')" />
                  <TextInput id="father_name" v-model="form.father_name" class="mt-1 block w-full" />
                </div>
                <div>
                  <InputLabel for="father_nrc" :value="__('school.father_nrc')" />
                  <TextInput id="father_nrc" v-model="form.father_nrc" class="mt-1 block w-full" />
                </div>
                <div>
                  <InputLabel for="father_qualification" :value="__('school.father_qualification')" />
                  <TextInput id="father_qualification" v-model="form.father_qualification" class="mt-1 block w-full" />
                </div>
                <div>
                  <InputLabel for="father_job" :value="__('school.father_job')" />
                  <TextInput id="father_job" v-model="form.father_job" class="mt-1 block w-full" />
                </div>
                <div>
                  <InputLabel for="father_phone" :value="__('school.father_phone')" />
                  <TextInput id="father_phone" v-model="form.father_phone" class="mt-1 block w-full" />
                </div>
                <div>
                  <InputLabel for="father_email" :value="__('school.father_email')" />
                  <TextInput id="father_email" type="email" v-model="form.father_email" class="mt-1 block w-full" />
                </div>
                <div>
                  <InputLabel for="father_address" :value="__('school.father_address')" />
                  <textarea id="father_address" v-model="form.father_address" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"></textarea>
                </div>
                <div>
                  <InputLabel :value="__('school.father_alive_status')" />
                  <SearchableSelect
                    id="father_alive_status"
                    v-model="form.father_alive_status"
                    :options="aliveStatusOptions"
                    class="mt-1 block w-full"
                    :error="form.errors.father_alive_status"
                  />
                </div>
              </div>
            </section>

            <!-- Mother -->
            <section>
              <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b pb-2 mb-4">
                {{ __("school.mother_information") }}
              </h3>
              <div class="space-y-4">
                <div>
                  <InputLabel for="mother_name" :value="__('school.mother_name')" />
                  <TextInput id="mother_name" v-model="form.mother_name" class="mt-1 block w-full" />
                </div>
                <div>
                  <InputLabel for="mother_nrc" :value="__('school.mother_nrc')" />
                  <TextInput id="mother_nrc" v-model="form.mother_nrc" class="mt-1 block w-full" />
                </div>
                <div>
                  <InputLabel for="mother_qualification" :value="__('school.mother_qualification')" />
                  <TextInput id="mother_qualification" v-model="form.mother_qualification" class="mt-1 block w-full" />
                </div>
                <div>
                  <InputLabel for="mother_job" :value="__('school.mother_job')" />
                  <TextInput id="mother_job" v-model="form.mother_job" class="mt-1 block w-full" />
                </div>
                <div>
                  <InputLabel for="mother_phone" :value="__('school.mother_phone')" />
                  <TextInput id="mother_phone" v-model="form.mother_phone" class="mt-1 block w-full" />
                </div>
                <div>
                  <InputLabel for="mother_email" :value="__('school.mother_email')" />
                  <TextInput id="mother_email" type="email" v-model="form.mother_email" class="mt-1 block w-full" />
                </div>
                <div>
                  <InputLabel for="mother_address" :value="__('school.mother_address')" />
                  <textarea id="mother_address" v-model="form.mother_address" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"></textarea>
                </div>
                <div>
                  <InputLabel :value="__('school.mother_alive_status')" />
                  <SearchableSelect
                    id="mother_alive_status"
                    v-model="form.mother_alive_status"
                    :options="aliveStatusOptions"
                    class="mt-1 block w-full"
                    :error="form.errors.mother_alive_status"
                  />
                </div>
              </div>
            </section>
          </div>

          <!-- Guardian Information -->
          <section>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b pb-2 mb-4">
              {{ __("school.guardian_information") }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <InputLabel for="guardian_name" :value="__('school.guardian_name')" />
                <TextInput id="guardian_name" v-model="form.guardian_name" class="mt-1 block w-full" />
              </div>
              <div>
                <InputLabel for="guardian_nrc" :value="__('school.guardian_nrc')" />
                <TextInput id="guardian_nrc" v-model="form.guardian_nrc" class="mt-1 block w-full" />
              </div>
              <div>
                <InputLabel for="guardian_qualification" :value="__('school.guardian_qualification')" />
                <TextInput id="guardian_qualification" v-model="form.guardian_qualification" class="mt-1 block w-full" />
              </div>
              <div>
                <InputLabel for="guardian_job" :value="__('school.guardian_job')" />
                <TextInput id="guardian_job" v-model="form.guardian_job" class="mt-1 block w-full" />
              </div>
              <div>
                <InputLabel for="guardian_phone" :value="__('school.guardian_phone')" />
                <TextInput id="guardian_phone" v-model="form.guardian_phone" class="mt-1 block w-full" />
              </div>
              <div>
                <InputLabel for="guardian_email" :value="__('school.guardian_email')" />
                <TextInput id="guardian_email" type="email" v-model="form.guardian_email" class="mt-1 block w-full" />
              </div>
              <div>
                <InputLabel for="guardian_address" :value="__('school.guardian_address')" />
                <textarea id="guardian_address" v-model="form.guardian_address" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"></textarea>
              </div>
              <div>
                <InputLabel :value="__('school.guardian_alive_status')" />
                <SearchableSelect
                  id="guardian_alive_status"
                  v-model="form.guardian_alive_status"
                  :options="aliveStatusOptions"
                  class="mt-1 block w-full"
                  :error="form.errors.guardian_alive_status"
                />
              </div>
            </div>
          </section>

          <div class="flex justify-end gap-4">
            <SecondaryButton @click="router.get('/students')">{{ __("messages.cancel") }}</SecondaryButton>
            <PrimaryButton :disabled="form.processing">{{ __("messages.save") }}</PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
