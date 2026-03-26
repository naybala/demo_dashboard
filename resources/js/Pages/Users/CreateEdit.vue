<script setup>
import { ref, computed } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import SearchableSelect from "@/Components/SearchableSelect.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import { __ } from "@/helpers.js";

const props = defineProps({
  user: Object,
  types: Array,
  genders: Array,
  roles: Array,
  classes: Array,
});

const isEditing = !!props.user;

const currentStep = ref(1);
const totalSteps = 8;

const typeOptions = computed(() =>
  props.types?.map((type) => ({ id: type.value, label: type.label })) || []
);

const genderOptions = computed(() =>
  props.genders?.map((gender) => ({ id: gender.value, label: gender.label })) || []
);

const roleOptions = computed(() =>
  props.roles?.map((role) => ({ id: role.name, label: role.name })) || []
);

const classOptions = computed(() =>
  props.classes?.map((c) => ({ id: c.id, label: c.name })) || []
);

const form = useForm({
  class_id: props.user?.class_id || "",
  fullname: props.user?.fullname || "",
  staff_id: props.user?.staff_id || "",
  email: props.user?.email || "",
  password: "",
  password_confirmation: "",
  user_type: props.user?.user_type || (props.types?.[0]?.value || ""),
  gender: props.user?.gender || "",
  dob: props.user?.dob || "",
  phone_number: props.user?.phone_number || "",
  role: props.user?.roles?.[0]?.name || "",
  status: props.user?.status || "active",
  profile: {
    marital_status: props.user?.profile?.marital_status || "",
    place_of_birth: props.user?.profile?.place_of_birth || "",
    nrc: props.user?.profile?.nrc || "",
    religion: props.user?.profile?.religion || "",
    nationality: props.user?.profile?.nationality || "",
    possessive_grade: props.user?.profile?.possessive_grade || "",
    professional_subject: props.user?.profile?.professional_subject || "",
    current_address: props.user?.profile?.current_address || "",
    permanent_address: props.user?.profile?.permanent_address || "",
    education_background: {
      degree: props.user?.profile?.education_background?.degree || "",
      certificate:
        props.user?.profile?.education_background?.certificate || "",
      institution:
        props.user?.profile?.education_background?.institution || "",
      year: props.user?.profile?.education_background?.year || "",
      specialization:
        props.user?.profile?.education_background?.specialization || "",
    },
    work_experience: {
      years: props.user?.profile?.work_experience?.years || "",
      department: props.user?.profile?.department_name || "",
      position: props.user?.profile?.position || "",
      duration: props.user?.profile?.work_experience?.duration || "",
      location: props.user?.profile?.work_experience?.location || "",
    },
  },
  spouse: {
    name: props.user?.spouse?.name || "",
    nrc: props.user?.spouse?.nrc || "",
    job: props.user?.spouse?.job || "",
    alive_status: props.user?.spouse?.alive_status || "alive",
    phone: props.user?.spouse?.phone || "",
    address: props.user?.spouse?.address || "",
  },
  father: {
    name: props.user?.father?.name || "",
    nrc: props.user?.father?.nrc || "",
    phone: props.user?.father?.phone || "",
    address: props.user?.father?.address || "",
  },
  mother: {
    name: props.user?.mother?.name || "",
    nrc: props.user?.mother?.nrc || "",
    phone: props.user?.mother?.phone || "",
    address: props.user?.mother?.address || "",
  },
});

const nextStep = () => {
  if (currentStep.value < totalSteps) {
    currentStep.value++;
  }
};

const prevStep = () => {
  if (currentStep.value > 1) {
    currentStep.value--;
  }
};

const submit = () => {
  if (isEditing) {
    form.put(`/users/${props.user.id}`);
  } else {
    form.post("/users");
  }
};
</script>

<template>
  <Head
    :title="
      isEditing
        ? __('user.edit_teacher', 'Edit Teacher/Staff')
        : __('user.create_teacher', 'Create Teacher/Staff')
    "
  />

  <AdminLayout>
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
      >
        {{
          isEditing
            ? __("user.edit_teacher", "Edit Teacher/Staff")
            : __("user.create_teacher", "Create Teacher/Staff")
        }}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700">
          
          <div class="p-8 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
              {{ isEditing ? 'Edit Staff' : 'Add New Staff' }}
            </h2>
            
            <div class="flex justify-between items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
              <span>Section {{ currentStep }} of {{ totalSteps }}</span>
              <span>{{ Math.round((currentStep / totalSteps) * 100) }}% Complete</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
              <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" :style="`width: ${(currentStep / totalSteps) * 100}%`"></div>
            </div>
            <!-- Section Title underneath progress bar representing the current active section-->
            <h3 class="mt-6 text-lg font-semibold text-blue-600 dark:text-blue-400">
              <span v-if="currentStep === 1">Basic Information</span>
              <span v-else-if="currentStep === 2">Personal Details</span>
              <span v-else-if="currentStep === 3">Educational Background</span>
              <span v-else-if="currentStep === 4">Professional Qualifications</span>
              <span v-else-if="currentStep === 5">Work Experience</span>
              <span v-else-if="currentStep === 6">Spouse's Information</span>
              <span v-else-if="currentStep === 7">Father's Information</span>
              <span v-else-if="currentStep === 8">Mother's Information</span>
            </h3>
          </div>

          <form @submit.prevent="submit" class="p-8 space-y-6">
            
            <div v-show="currentStep === 1" class="space-y-6">
              <div>
                <InputLabel for="class_id" value="Class" />
                <SearchableSelect
                  id="class_id"
                  v-model="form.class_id"
                  :options="classOptions"
                  placeholder="Select"
                  class="mt-1 block w-full"
                  :error="form.errors.class_id"
                />
                <InputError :message="form.errors.class_id" class="mt-2" />
              </div>

              <div>
                <InputLabel for="fullname" value="Name *" />
                <TextInput
                  id="fullname"
                  v-model="form.fullname"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Enter your name"
                  required
                />
                <InputError :message="form.errors.fullname" class="mt-2" />
              </div>

              <div>
                <InputLabel for="staff_id" value="Staff ID *" />
                <TextInput
                  id="staff_id"
                  v-model="form.staff_id"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Staff ID"
                  required
                />
                <InputError :message="form.errors.staff_id" class="mt-2" />
              </div>

              <div>
                <InputLabel for="home_grade" value="Home Grade" />
                <TextInput
                  id="home_grade"
                  v-model="form.profile.possessive_grade"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="KG"
                />
              </div>

              <div>
                <InputLabel for="gender" value="Gender" />
                <SearchableSelect
                  id="gender"
                  v-model="form.gender"
                  :options="genderOptions"
                  placeholder="Select Gender"
                  class="mt-1 block w-full"
                  :error="form.errors.gender"
                />
                <InputError :message="form.errors.gender" class="mt-2" />
              </div>
              
              <div class="grid grid-cols-2 gap-4">
                 <div>
                    <InputLabel for="user_type" value="User Type *" />
                    <SearchableSelect
                      id="user_type"
                      v-model="form.user_type"
                      :options="typeOptions"
                      placeholder="Select User Type"
                      class="mt-1 block w-full"
                      :error="form.errors.user_type"
                      required
                    />
                    <InputError :message="form.errors.user_type" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="role" value="Role *" />
                    <SearchableSelect
                      id="role"
                      v-model="form.role"
                      :options="roleOptions"
                      placeholder="Select Role"
                      class="mt-1 block w-full"
                      :error="form.errors.role"
                      required
                    />
                    <InputError :message="form.errors.role" class="mt-2" />
                </div>
              </div>
            </div>

            <div v-show="currentStep === 2" class="space-y-6">
              <div>
                <InputLabel for="dob" value="Date of Birth" />
                <TextInput
                  id="dob"
                  v-model="form.dob"
                  type="date"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="DD/MM/YY"
                />
                <InputError :message="form.errors.dob" class="mt-2" />
              </div>

              <div>
                <InputLabel for="place_of_birth" value="Place of Birth" />
                <TextInput
                  id="place_of_birth"
                  v-model="form.profile.place_of_birth"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Place of Birth"
                />
              </div>

              <div>
                <InputLabel for="nrc" value="NRC" />
                <TextInput
                  id="nrc"
                  v-model="form.profile.nrc"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="NRC"
                />
              </div>

              <div>
                <InputLabel for="nationality" value="Nationality" />
                <TextInput
                  id="nationality"
                  v-model="form.profile.nationality"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Myanmar"
                />
              </div>

              <div>
                <InputLabel for="religion" value="Religion" />
                <TextInput
                  id="religion"
                  v-model="form.profile.religion"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Buddhist"
                />
              </div>

              <div>
                <InputLabel for="marital_status" value="Marital Status" />
                <TextInput
                  id="marital_status"
                  v-model="form.profile.marital_status"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Single"
                />
              </div>
            </div>

            <div v-show="currentStep === 3" class="space-y-6">
              <div>
                <InputLabel for="degree" value="Degree Earned" />
                <TextInput
                  id="degree"
                  v-model="form.profile.education_background.degree"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Degree Earned"
                />
              </div>

              <div>
                <InputLabel for="certificate" value="Certificate" />
                <TextInput
                  id="certificate"
                  v-model="form.profile.education_background.certificate"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Certificate"
                />
              </div>

              <div>
                <InputLabel for="institution" value="Institution Name" />
                <TextInput
                  id="institution"
                  v-model="form.profile.education_background.institution"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Institution Name"
                />
              </div>

              <div>
                <InputLabel for="grad_year" value="Year of Graduation" />
                <TextInput
                  id="grad_year"
                  v-model="form.profile.education_background.year"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Year of Graduation"
                />
              </div>

              <div>
                <InputLabel for="specialization" value="Specialization" />
                <TextInput
                  id="specialization"
                  v-model="form.profile.education_background.specialization"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Specialization"
                />
              </div>
            </div>

            <div v-show="currentStep === 4" class="space-y-6">
              <div>
                <InputLabel for="professional_subject" value="Professional Subject" />
                <TextInput
                  id="professional_subject"
                  v-model="form.profile.professional_subject"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Professional Subject"
                />
              </div>

              <div>
                <InputLabel for="current_address" value="Current Address" />
                <TextInput
                  id="current_address"
                  v-model="form.profile.current_address"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Current Address"
                />
              </div>

              <div>
                <InputLabel for="permanent_address" value="Permanent Address" />
                <TextInput
                  id="permanent_address"
                  v-model="form.profile.permanent_address"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Permanent Address"
                />
              </div>

              <div>
                <InputLabel for="phone_number" value="Phone Number" />
                <TextInput
                  id="phone_number"
                  v-model="form.phone_number"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Phone Number"
                />
                <InputError :message="form.errors.phone_number" class="mt-2" />
              </div>
              
              <div>
                <InputLabel for="email" value="Email" />
                <TextInput
                  id="email"
                  v-model="form.email"
                  type="email"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Email"
                />
                <InputError :message="form.errors.email" class="mt-2" />
              </div>

              <div>
                <InputLabel for="password" :value="'Password' + (!isEditing ? ' *' : '')" />
                <TextInput
                  id="password"
                  v-model="form.password"
                  type="password"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Password"
                  :required="!isEditing"
                />
                <InputError :message="form.errors.password" class="mt-2" />
              </div>
              
              <div v-if="!isEditing">
                <InputLabel for="password_confirmation" value="Confirm Password *" />
                <TextInput
                  id="password_confirmation"
                  v-model="form.password_confirmation"
                  type="password"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Confirm Password"
                  required
                />
              </div>
            </div>

            <div v-show="currentStep === 5" class="space-y-6">
              <div>
                <InputLabel for="work_years" value="Work Experience (Years)" />
                <TextInput
                  id="work_years"
                  v-model="form.profile.work_experience.years"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="e.g. 3 yrs"
                />
              </div>

              <div>
                <InputLabel for="department_name" value="Department Name" />
                <TextInput
                  id="department_name"
                  v-model="form.profile.work_experience.department"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Department Name"
                />
              </div>

              <div>
                <InputLabel for="position" value="Position" />
                <TextInput
                  id="position"
                  v-model="form.profile.work_experience.position"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Position"
                />
              </div>

              <div>
                <InputLabel for="duration" value="Duration" />
                <TextInput
                  id="duration"
                  v-model="form.profile.work_experience.duration"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Duration"
                />
              </div>

              <div>
                <InputLabel for="location" value="Location" />
                <TextInput
                  id="location"
                  v-model="form.profile.work_experience.location"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Location"
                />
              </div>
            </div>

            <div v-show="currentStep === 6" class="space-y-6">
              <div>
                <InputLabel for="partner_name" value="Partner's Name" />
                <TextInput
                  id="partner_name"
                  v-model="form.spouse.name"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Partner's Name"
                />
              </div>

              <div>
                <InputLabel for="partner_nrc" value="Partner's NRC" />
                <TextInput
                  id="partner_nrc"
                  v-model="form.spouse.nrc"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Partner's NRC"
                />
              </div>

              <div>
                <InputLabel for="partner_job" value="Partner's Job" />
                <TextInput
                  id="partner_job"
                  v-model="form.spouse.job"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Partner's Job"
                />
              </div>

              <div>
                <InputLabel for="partner_alive" value="Partner's Alive or Dead" />
                <SearchableSelect
                  id="partner_alive"
                  v-model="form.spouse.alive_status"
                  :options="[
                    { id: 'alive', label: __('user.alive', 'Alive') },
                    { id: 'dead', label: __('user.dead', 'Dead') },
                  ]"
                  class="mt-1 block w-full"
                  :error="form.errors['spouse.alive_status']"
                />
              </div>

              <div>
                <InputLabel for="partner_phone" value="Partner's Phone" />
                <TextInput
                  id="partner_phone"
                  v-model="form.spouse.phone"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Partner's Phone"
                />
              </div>

              <div>
                <InputLabel for="partner_address" value="Partner's Address" />
                <TextInput
                  id="partner_address"
                  v-model="form.spouse.address"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Partner's Address"
                />
              </div>
            </div>

            <div v-show="currentStep === 7" class="space-y-6">
              <div>
                <InputLabel for="father_name" value="Father's Name" />
                <TextInput
                  id="father_name"
                  v-model="form.father.name"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Father's Name"
                />
              </div>

              <div>
                <InputLabel for="father_nrc" value="Father's NRC" />
                <TextInput
                  id="father_nrc"
                  v-model="form.father.nrc"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Father's NRC"
                />
              </div>

              <div>
                <InputLabel for="father_phone" value="Father's Phone" />
                <TextInput
                  id="father_phone"
                  v-model="form.father.phone"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Father's Phone"
                />
              </div>

              <div>
                <InputLabel for="father_address" value="Father's Address" />
                <TextInput
                  id="father_address"
                  v-model="form.father.address"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Father's Address"
                />
              </div>
            </div>

            <div v-show="currentStep === 8" class="space-y-6">
              <div>
                <InputLabel for="mother_name" value="Mother's Name" />
                <TextInput
                  id="mother_name"
                  v-model="form.mother.name"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Mother's Name"
                />
              </div>

              <div>
                <InputLabel for="mother_nrc" value="Mother's NRC" />
                <TextInput
                  id="mother_nrc"
                  v-model="form.mother.nrc"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Mother's NRC"
                />
              </div>

              <div>
                <InputLabel for="mother_phone" value="Mother's Phone" />
                <TextInput
                  id="mother_phone"
                  v-model="form.mother.phone"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Mother's Phone"
                />
              </div>

              <div>
                <InputLabel for="mother_address" value="Mother's Address" />
                <TextInput
                  id="mother_address"
                  v-model="form.mother.address"
                  type="text"
                  class="mt-1 block w-full bg-gray-50"
                  placeholder="Mother's Address"
                />
              </div>
            </div>

            <div class="mt-10 flex items-center justify-between pt-6 border-t border-gray-100 dark:border-gray-700">
              <SecondaryButton 
                type="button" 
                @click="prevStep" 
                class="flex items-center gap-2"
                :disabled="currentStep === 1"
                :class="{ 'opacity-50 cursor-not-allowed': currentStep === 1 }"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Previous
              </SecondaryButton>
              
              <div class="text-sm text-gray-500 font-medium">Step {{ currentStep }} of {{ totalSteps }}</div>
              
              <PrimaryButton 
                v-if="currentStep < totalSteps" 
                type="button" 
                @click="nextStep"
                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700"
              >
                Next
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </PrimaryButton>
              
              <PrimaryButton 
                v-if="currentStep === totalSteps"
                type="submit"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
                class="bg-blue-600 hover:bg-blue-700"
              >
                {{ isEditing ? 'Update Staff Member' : 'Create Staff Member' }}
              </PrimaryButton>
            </div>

            <div v-if="Object.keys(form.errors).length > 0" class="mt-4 p-4 bg-red-50 text-red-600 text-sm rounded-lg">
              There are some errors in the form. Please check the fields and correct them.
            </div>

          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
