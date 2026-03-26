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
  <Head :title="is_editing ? __('school.edit_student', 'Edit Student') : __('school.add_student', 'Add Student')" />

  <AdminLayout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <form @submit.prevent="submit" class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-700">
          
          <div class="p-8 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <div>
              <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ is_editing ? __('school.edit_student', 'Edit Student') : __('school.add_student', 'Add Student') }}
              </h2>
              <p class="text-sm text-gray-500 mt-1 dark:text-gray-400">
                Create a new student profile with basic, contact and academic details.
              </p>
            </div>
            
            <Link
              href="/students"
              class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white flex items-center gap-2"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              Back to list
            </Link>
          </div>

          <div class="p-8 space-y-12">
            <!-- Basic Information -->
            <section>
              <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Basic Information</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Identify the student with core profile fields.</p>
              </div>
              
              <div class="flex flex-col md:flex-row gap-12">
                <div class="flex-1 space-y-6">
                  <div>
                    <InputLabel for="academic_year" value="Academic year" />
                    <TextInput id="academic_year" v-model="form.academic_year" class="mt-1 block w-full bg-gray-50" placeholder="DD/ MM/ YYYY" />
                    <InputError :message="form.errors.academic_year" class="mt-2" />
                  </div>
                  
                  <div>
                    <InputLabel for="class_id" value="Class" />
                    <SearchableSelect
                      id="class_id"
                      v-model="form.class_id"
                      :options="classOptions"
                      placeholder="Select..."
                      class="mt-1 block w-full"
                      :error="form.errors.class_id"
                    />
                    <InputError :message="form.errors.class_id" class="mt-2" />
                  </div>
                  
                  <div>
                    <InputLabel for="full_name" value="Full name *" />
                    <TextInput id="full_name" v-model="form.full_name" class="mt-1 block w-full bg-gray-50" placeholder="Enter full name" required />
                    <InputError :message="form.errors.full_name" class="mt-2" />
                  </div>
                  
                  <div>
                    <InputLabel for="other_name" value="Other name" />
                    <TextInput id="other_name" v-model="form.other_name" class="mt-1 block w-full bg-gray-50" placeholder="Enter other name" />
                    <InputError :message="form.errors.other_name" class="mt-2" />
                  </div>
                  
                  <div>
                    <InputLabel for="email" value="Email Address" />
                    <TextInput id="email" type="email" v-model="form.email" class="mt-1 block w-full bg-gray-50" placeholder="Enter email" />
                    <InputError :message="form.errors.email" class="mt-2" />
                  </div>
                  
                  <div>
                    <InputLabel for="registration_date" value="Registration date" />
                    <TextInput id="registration_date" type="date" v-model="form.registration_date" class="mt-1 block w-full bg-gray-50 text-gray-500" />
                    <InputError :message="form.errors.registration_date" class="mt-2" />
                  </div>
                </div>
                
                <div class="flex-1 md:max-w-xs space-y-6">
                  <div>
                    <InputLabel for="student_code" value="Student ID *" />
                    <TextInput id="student_code" v-model="form.student_code" class="mt-1 block w-full bg-gray-50" placeholder="Auto-generated or enter manually" required />
                    <p class="mt-1 text-xs text-gray-500">Use school ID format, e.g. STU-2001</p>
                    <InputError :message="form.errors.student_code" class="mt-2" />
                  </div>
                  
                  <div>
                    <InputLabel value="Gender" />
                    <div class="mt-2 flex gap-3">
                      <label class="cursor-pointer">
                        <input type="radio" value="male" v-model="form.gender" class="peer sr-only" />
                        <div class="px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-600 peer-checked:bg-blue-600 peer-checked:text-white transition-colors">Male</div>
                      </label>
                      <label class="cursor-pointer">
                        <input type="radio" value="female" v-model="form.gender" class="peer sr-only" />
                        <div class="px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-600 peer-checked:bg-blue-600 peer-checked:text-white transition-colors">Female</div>
                      </label>
                      <label class="cursor-pointer">
                        <input type="radio" value="other" v-model="form.gender" class="peer sr-only" />
                        <div class="px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-600 peer-checked:bg-blue-600 peer-checked:text-white transition-colors">Other</div>
                      </label>
                    </div>
                    <InputError :message="form.errors.gender" class="mt-2" />
                  </div>
                  
                  <div class="pt-4">
                    <InputLabel value="Profile Picture" class="mb-2" />
                    <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center border-2 border-dashed border-gray-300">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                    </div>
                  </div>
                </div>
              </div>
            </section>

            <!-- Personal & Academic Details Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 pt-8 border-t border-gray-100 dark:border-gray-700">
              
              <section>
                <div class="mb-6">
                  <h3 class="text-lg font-bold text-gray-900 dark:text-white">Personal details</h3>
                  <p class="text-sm text-gray-500 dark:text-gray-400">How we reach the student and guardian.</p>
                </div>
                
                <div class="space-y-6">
                  <div>
                    <InputLabel for="dob" value="Date of Birth" />
                    <TextInput id="dob" type="date" v-model="form.dob" class="mt-1 block w-full bg-gray-50 text-gray-500" />
                  </div>
                  <div>
                    <InputLabel for="place_of_birth" value="Place of Birth" />
                    <TextInput id="place_of_birth" v-model="form.place_of_birth" class="mt-1 block w-full bg-gray-50" placeholder="Place of birth" />
                  </div>
                  <div>
                    <InputLabel for="nrc" value="NRC" />
                    <TextInput id="nrc" v-model="form.nrc" class="mt-1 block w-full bg-gray-50" placeholder="NRC" />
                  </div>
                  <div>
                    <InputLabel for="nationality" value="Nationality" />
                    <TextInput id="nationality" v-model="form.nationality" class="mt-1 block w-full bg-gray-50" placeholder="Nationality" />
                  </div>
                  <div>
                    <InputLabel for="religion" value="Religion" />
                    <TextInput id="religion" v-model="form.religion" class="mt-1 block w-full bg-gray-50" placeholder="Religion" />
                  </div>
                  <div>
                    <InputLabel for="address" value="Address" />
                    <textarea id="address" v-model="form.address" class="mt-1 block w-full border-gray-100 bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Address"></textarea>
                  </div>
                </div>
              </section>

              <section>
                <div class="mb-6">
                  <h3 class="text-lg font-bold text-gray-900 dark:text-white">Academic details</h3>
                  <p class="text-sm text-gray-500 dark:text-gray-400">Assign class, section and section for this student.</p>
                </div>
                
                <div class="space-y-6">
                  <div>
                    <InputLabel for="school_attended" value="School Attended" />
                    <TextInput id="school_attended" v-model="form.school_attended" class="mt-1 block w-full bg-gray-50" placeholder="School Attended" />
                  </div>
                  <div>
                    <InputLabel for="grade_attended" value="Grade Attended" />
                    <TextInput id="grade_attended" v-model="form.grade_attended" class="mt-1 block w-full bg-gray-50" placeholder="Grade Attended" />
                  </div>
                  <div>
                    <InputLabel for="year_attended" value="Year Attended" />
                    <TextInput id="year_attended" v-model="form.year_attended" class="mt-1 block w-full bg-gray-50" placeholder="DD/ MM/ YYYY" />
                  </div>
                  <div>
                    <InputLabel for="grade_id" value="Grade" />
                    <SearchableSelect
                      id="grade_id"
                      v-model="form.grade_id"
                      :options="gradeOptions"
                      placeholder="Select Grade..."
                      class="mt-1 block w-full"
                    />
                  </div>
                </div>
              </section>
            </div>

            <!-- Father & Mother Details Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 pt-8 border-t border-gray-100 dark:border-gray-700">
              <section>
                <div class="mb-6">
                  <h3 class="text-lg font-bold text-gray-900 dark:text-white">Father's Information</h3>
                </div>
                
                <div class="space-y-6">
                  <div>
                    <InputLabel for="father_name" value="Father's Name" />
                    <TextInput id="father_name" v-model="form.father_name" class="mt-1 block w-full bg-gray-50" placeholder="Enter name" />
                  </div>
                  <div>
                    <InputLabel for="father_nrc" value="Father's NRC" />
                    <TextInput id="father_nrc" v-model="form.father_nrc" class="mt-1 block w-full bg-gray-50" placeholder="NRC" />
                  </div>
                  <div>
                    <InputLabel for="father_qualification" value="Father's Qualification" />
                    <TextInput id="father_qualification" v-model="form.father_qualification" class="mt-1 block w-full bg-gray-50" placeholder="Qualification" />
                  </div>
                  <div>
                    <InputLabel for="father_job" value="Father's Job" />
                    <TextInput id="father_job" v-model="form.father_job" class="mt-1 block w-full bg-gray-50" placeholder="Job" />
                  </div>
                  <div>
                    <InputLabel for="father_phone" value="Father's Phone" />
                    <TextInput id="father_phone" v-model="form.father_phone" class="mt-1 block w-full bg-gray-50" placeholder="Phone number" />
                  </div>
                  <div>
                    <InputLabel for="father_email" value="Father's Email Address" />
                    <TextInput id="father_email" type="email" v-model="form.father_email" class="mt-1 block w-full bg-gray-50" placeholder="Email address" />
                  </div>
                  <div>
                    <InputLabel for="father_address" value="Father's Address" />
                    <TextInput id="father_address" v-model="form.father_address" class="mt-1 block w-full bg-gray-50" placeholder="Address" />
                  </div>
                  <div>
                    <InputLabel value="Father's Alive or Dead" />
                    <SearchableSelect
                      id="father_alive_status"
                      v-model="form.father_alive_status"
                      :options="aliveStatusOptions"
                      class="mt-1 block w-full"
                    />
                  </div>
                </div>
              </section>

              <section>
                <div class="mb-6">
                  <h3 class="text-lg font-bold text-gray-900 dark:text-white">Mother's Information</h3>
                </div>
                
                <div class="space-y-6">
                  <div>
                    <InputLabel for="mother_name" value="Mother's Name" />
                    <TextInput id="mother_name" v-model="form.mother_name" class="mt-1 block w-full bg-gray-50" placeholder="Enter name" />
                  </div>
                  <div>
                    <InputLabel for="mother_nrc" value="Mother's NRC" />
                    <TextInput id="mother_nrc" v-model="form.mother_nrc" class="mt-1 block w-full bg-gray-50" placeholder="NRC" />
                  </div>
                  <div>
                    <InputLabel for="mother_qualification" value="Mother's Qualification" />
                    <TextInput id="mother_qualification" v-model="form.mother_qualification" class="mt-1 block w-full bg-gray-50" placeholder="Qualification" />
                  </div>
                  <div>
                    <InputLabel for="mother_job" value="Mother's Job" />
                    <TextInput id="mother_job" v-model="form.mother_job" class="mt-1 block w-full bg-gray-50" placeholder="Job" />
                  </div>
                  <div>
                    <InputLabel for="mother_phone" value="Mother's Phone" />
                    <TextInput id="mother_phone" v-model="form.mother_phone" class="mt-1 block w-full bg-gray-50" placeholder="Phone number" />
                  </div>
                  <div>
                    <InputLabel for="mother_email" value="Mother's Email Address" />
                    <TextInput id="mother_email" type="email" v-model="form.mother_email" class="mt-1 block w-full bg-gray-50" placeholder="Email address" />
                  </div>
                  <div>
                    <InputLabel for="mother_address" value="Mother's Address" />
                    <TextInput id="mother_address" v-model="form.mother_address" class="mt-1 block w-full bg-gray-50" placeholder="Address" />
                  </div>
                  <div>
                    <InputLabel value="Mother's Alive or Dead" />
                    <SearchableSelect
                      id="mother_alive_status"
                      v-model="form.mother_alive_status"
                      :options="aliveStatusOptions"
                      class="mt-1 block w-full"
                    />
                  </div>
                </div>
              </section>
            </div>
            
            <!-- Guardian Details Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 pt-8 border-t border-gray-100 dark:border-gray-700">
              <section>
                <div class="mb-6">
                  <h3 class="text-lg font-bold text-gray-900 dark:text-white">Guardian's Information</h3>
                </div>
                
                <div class="space-y-6">
                  <div>
                    <InputLabel for="guardian_name" value="Guardian's Name" />
                    <TextInput id="guardian_name" v-model="form.guardian_name" class="mt-1 block w-full bg-gray-50" placeholder="Enter name" />
                  </div>
                  <div>
                    <InputLabel for="guardian_nrc" value="Guardian's NRC" />
                    <TextInput id="guardian_nrc" v-model="form.guardian_nrc" class="mt-1 block w-full bg-gray-50" placeholder="NRC" />
                  </div>
                  <div>
                    <InputLabel for="guardian_qualification" value="Guardian's Qualification" />
                    <TextInput id="guardian_qualification" v-model="form.guardian_qualification" class="mt-1 block w-full bg-gray-50" placeholder="Qualification" />
                  </div>
                  <div>
                    <InputLabel for="guardian_job" value="Guardian's Job" />
                    <TextInput id="guardian_job" v-model="form.guardian_job" class="mt-1 block w-full bg-gray-50" placeholder="Job" />
                  </div>
                  <div>
                    <InputLabel for="guardian_phone" value="Guardian's Phone" />
                    <TextInput id="guardian_phone" v-model="form.guardian_phone" class="mt-1 block w-full bg-gray-50" placeholder="Phone number" />
                  </div>
                  <div>
                    <InputLabel for="guardian_email" value="Guardian's Email Address" />
                    <TextInput id="guardian_email" type="email" v-model="form.guardian_email" class="mt-1 block w-full bg-gray-50" placeholder="Email address" />
                  </div>
                  <div>
                    <InputLabel for="guardian_address" value="Guardian's Address" />
                    <TextInput id="guardian_address" v-model="form.guardian_address" class="mt-1 block w-full bg-gray-50" placeholder="Address" />
                  </div>
                  <div>
                    <InputLabel value="Guardian's Alive or Dead" />
                    <SearchableSelect
                      id="guardian_alive_status"
                      v-model="form.guardian_alive_status"
                      :options="aliveStatusOptions"
                      class="mt-1 block w-full"
                    />
                  </div>
                </div>
              </section>
              
              <section class="flex flex-col justify-end pb-4 pt-10">
                <div class="flex justify-end gap-3 mt-auto">
                    <SecondaryButton @click="router.get('/students')">{{ __("messages.cancel") }}</SecondaryButton>
                    <PrimaryButton type="submit" :disabled="form.processing" class="bg-blue-600 hover:bg-blue-700">{{ is_editing ? 'Update Student' : 'Save Student' }}</PrimaryButton>
                </div>
              </section>
            </div>

          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
