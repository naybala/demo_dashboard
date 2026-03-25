<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import SearchableSelect from "@/Components/SearchableSelect.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import { __ } from "@/helpers.js";
import { ref, computed } from "vue";

const props = defineProps({
  user: Object,
  types: Array,
  genders: Array,
  roles: Array,
});

const isEditing = !!props.user;

const typeOptions = computed(() =>
  props.types.map((type) => ({ id: type.value, label: type.label })),
);

const genderOptions = computed(() =>
  props.genders.map((gender) => ({ id: gender.value, label: gender.label })),
);

const roleOptions = computed(() =>
  props.roles.map((role) => ({ id: role.name, label: role.name })),
);

const form = useForm({
  fullname: props.user?.fullname || "",
  staff_id: props.user?.staff_id || "",
  email: props.user?.email || "",
  password: "",
  user_type: props.user?.user_type || "",
  gender: props.user?.gender || "",
  dob: props.user?.dob || "",
  phone_number: props.user?.phone_number || "",
  role: props.user?.roles?.[0]?.name || "",
  profile: {
    marital_status: props.user?.profile?.marital_status || "",
    place_of_birth: props.user?.profile?.place_of_birth || "",
    nrc: props.user?.profile?.nrc || "",
    religion: props.user?.profile?.religion || "",
    nationality: props.user?.profile?.nationality || "",
    professional_subject: props.user?.profile?.professional_subject || "",
    possessive_grade: props.user?.profile?.possessive_grade || "",
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
});

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
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <form @submit.prevent="submit" class="space-y-8">
          <!-- Section 1: Account Information -->
          <div
            class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-8 border border-transparent dark:border-gray-700"
          >
            <h3
              class="text-xl font-bold text-gray-900 dark:text-white mb-6 border-b pb-4"
            >
              {{ __("user.account_info", "Account Information") }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <InputLabel
                  for="fullname"
                  :value="__('user.fullname', 'Full Name') + ' *'"
                />
                <TextInput
                  id="fullname"
                  v-model="form.fullname"
                  type="text"
                  class="mt-1 block w-full"
                  required
                />
                <InputError :message="form.errors.fullname" class="mt-2" />
              </div>
              <div>
                <InputLabel
                  for="staff_id"
                  :value="__('user.staff_id', 'Staff ID') + ' *'"
                />
                <TextInput
                  id="staff_id"
                  v-model="form.staff_id"
                  type="text"
                  class="mt-1 block w-full"
                  required
                />
                <InputError :message="form.errors.staff_id" class="mt-2" />
              </div>
              <div>
                <InputLabel
                  for="user_type"
                  :value="__('user.type', 'User Type') + ' *'"
                />
                <SearchableSelect
                  id="user_type"
                  v-model="form.user_type"
                  :options="typeOptions"
                  :placeholder="__('user.choose_type', 'Select User Type')"
                  class="mt-1 block w-full"
                  :error="form.errors.user_type"
                  required
                />
                <InputError :message="form.errors.user_type" class="mt-2" />
              </div>
              <div>
                <InputLabel for="email" :value="__('user.email', 'Email')" />
                <TextInput
                  id="email"
                  v-model="form.email"
                  type="email"
                  class="mt-1 block w-full"
                />
                <InputError :message="form.errors.email" class="mt-2" />
              </div>
              <div>
                <InputLabel
                  for="password"
                  :value="
                    __('user.password', 'Password') + (isEditing ? '' : ' *')
                  "
                />
                <TextInput
                  id="password"
                  v-model="form.password"
                  type="password"
                  class="mt-1 block w-full"
                  :required="!isEditing"
                />
                <InputError :message="form.errors.password" class="mt-2" />
              </div>
              <div>
                <InputLabel for="role" :value="__('user.role', 'Role')" />
                <SearchableSelect
                  id="role"
                  v-model="form.role"
                  :options="roleOptions"
                  :placeholder="__('user.choose_role', 'Select Role')"
                  class="mt-1 block w-full"
                  :error="form.errors.role"
                />
                <InputError :message="form.errors.role" class="mt-2" />
              </div>
              <div>
                <InputLabel for="gender" :value="__('user.gender', 'Gender')" />
                <SearchableSelect
                  id="gender"
                  v-model="form.gender"
                  :options="genderOptions"
                  :placeholder="__('user.choose_gender', 'Select Gender')"
                  class="mt-1 block w-full"
                  :error="form.errors.gender"
                />
                <InputError :message="form.errors.gender" class="mt-2" />
              </div>
              <div>
                <InputLabel
                  for="dob"
                  :value="__('user.dob', 'Date of Birth')"
                />
                <TextInput
                  id="dob"
                  v-model="form.dob"
                  type="date"
                  class="mt-1 block w-full"
                />
                <InputError :message="form.errors.dob" class="mt-2" />
              </div>
              <div>
                <InputLabel
                  for="phone_number"
                  :value="__('user.phone', 'Phone Number')"
                />
                <TextInput
                  id="phone_number"
                  v-model="form.phone_number"
                  type="text"
                  class="mt-1 block w-full"
                />
                <InputError :message="form.errors.phone_number" class="mt-2" />
              </div>
            </div>
          </div>

          <!-- Section 2: Personal Profile -->
          <div
            class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-8 border border-transparent dark:border-gray-700"
          >
            <h3
              class="text-xl font-bold text-gray-900 dark:text-white mb-6 border-b pb-4"
            >
              {{ __("user.personal_profile", "Personal Profile") }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <InputLabel
                  for="marital_status"
                  :value="__('user.marital_status', 'Marital Status')"
                />
                <TextInput
                  id="marital_status"
                  v-model="form.profile.marital_status"
                  type="text"
                  class="mt-1 block w-full"
                />
              </div>
              <div>
                <InputLabel
                  for="place_of_birth"
                  :value="__('user.pob', 'Place of Birth')"
                />
                <TextInput
                  id="place_of_birth"
                  v-model="form.profile.place_of_birth"
                  type="text"
                  class="mt-1 block w-full"
                />
              </div>
              <div>
                <InputLabel for="nrc" :value="__('user.nrc', 'NRC')" />
                <TextInput
                  id="nrc"
                  v-model="form.profile.nrc"
                  type="text"
                  class="mt-1 block w-full"
                />
              </div>
              <div>
                <InputLabel
                  for="religion"
                  :value="__('user.religion', 'Religion')"
                />
                <TextInput
                  id="religion"
                  v-model="form.profile.religion"
                  type="text"
                  class="mt-1 block w-full"
                />
              </div>
              <div>
                <InputLabel
                  for="nationality"
                  :value="__('user.nationality', 'Nationality')"
                />
                <TextInput
                  id="nationality"
                  v-model="form.profile.nationality"
                  type="text"
                  class="mt-1 block w-full"
                />
              </div>
              <div>
                <InputLabel
                  for="professional_subject"
                  :value="__('user.prof_subject', 'Professional Subject')"
                />
                <TextInput
                  id="professional_subject"
                  v-model="form.profile.professional_subject"
                  type="text"
                  class="mt-1 block w-full"
                />
              </div>
              <div class="md:col-span-3">
                <InputLabel
                  for="current_address"
                  :value="__('user.curr_address', 'Current Address')"
                />
                <textarea
                  id="current_address"
                  v-model="form.profile.current_address"
                  rows="2"
                  class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                ></textarea>
              </div>
            </div>
          </div>

          <!-- Section 3: Educational Background -->
          <div
            class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-8 border border-transparent dark:border-gray-700"
          >
            <h3
              class="text-xl font-bold text-gray-900 dark:text-white mb-6 border-b pb-4"
            >
              {{ __("user.edu_background", "Educational Background") }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <InputLabel
                  for="edu_degree"
                  :value="__('user.degree', 'Degree Earned')"
                />
                <TextInput
                  id="edu_degree"
                  v-model="form.profile.education_background.degree"
                  type="text"
                  class="mt-1 block w-full"
                />
              </div>
              <div>
                <InputLabel
                  for="edu_certificate"
                  :value="__('user.certificate', 'Certificate')"
                />
                <TextInput
                  id="edu_certificate"
                  v-model="form.profile.education_background.certificate"
                  type="text"
                  class="mt-1 block w-full"
                />
              </div>
              <div>
                <InputLabel
                  for="edu_institution"
                  :value="__('user.institution', 'Institution Name')"
                />
                <TextInput
                  id="edu_institution"
                  v-model="form.profile.education_background.institution"
                  type="text"
                  class="mt-1 block w-full"
                />
              </div>
              <div>
                <InputLabel
                  for="edu_year"
                  :value="__('user.grad_year', 'Year of Graduation')"
                />
                <TextInput
                  id="edu_year"
                  v-model="form.profile.education_background.year"
                  type="text"
                  class="mt-1 block w-full"
                />
              </div>
              <div>
                <InputLabel
                  for="edu_specialization"
                  :value="__('user.specialization', 'Specialization')"
                />
                <TextInput
                  id="edu_specialization"
                  v-model="form.profile.education_background.specialization"
                  type="text"
                  class="mt-1 block w-full"
                />
              </div>
            </div>
          </div>

          <!-- Section 4: Work Experience -->
          <div
            class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-8 border border-transparent dark:border-gray-700"
          >
            <h3
              class="text-xl font-bold text-gray-900 dark:text-white mb-6 border-b pb-4"
            >
              {{ __("user.work_exp", "Work Experience") }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <InputLabel
                  for="work_years"
                  :value="__('user.work_exp_yrs', 'Experience (Years)')"
                />
                <TextInput
                  id="work_years"
                  v-model="form.profile.work_experience.years"
                  type="text"
                  class="mt-1 block w-full"
                />
              </div>
              <div>
                <InputLabel
                  for="work_dept"
                  :value="__('user.dept_name', 'Department Name')"
                />
                <TextInput
                  id="work_dept"
                  v-model="form.profile.work_experience.department"
                  type="text"
                  class="mt-1 block w-full"
                />
              </div>
              <div>
                <InputLabel
                  for="work_pos"
                  :value="__('user.position', 'Position')"
                />
                <TextInput
                  id="work_pos"
                  v-model="form.profile.work_experience.position"
                  type="text"
                  class="mt-1 block w-full"
                />
              </div>
              <div>
                <InputLabel
                  for="work_dur"
                  :value="__('user.duration', 'Duration')"
                />
                <TextInput
                  id="work_dur"
                  v-model="form.profile.work_experience.duration"
                  type="text"
                  class="mt-1 block w-full"
                />
              </div>
              <div>
                <InputLabel
                  for="work_loc"
                  :value="__('user.location', 'Location')"
                />
                <TextInput
                  id="work_loc"
                  v-model="form.profile.work_experience.location"
                  type="text"
                  class="mt-1 block w-full"
                />
              </div>
            </div>
          </div>

          <!-- Section 5: Spouse's Information -->
          <div
            class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-8 border border-transparent dark:border-gray-700"
          >
            <h3
              class="text-xl font-bold text-gray-900 dark:text-white mb-6 border-b pb-4"
            >
              {{ __("user.spouse_info", "Spouse's Information") }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <InputLabel
                  for="spouse_name"
                  :value="__('user.partner_name', 'Partner Name')"
                />
                <TextInput
                  id="spouse_name"
                  v-model="form.spouse.name"
                  type="text"
                  class="mt-1 block w-full"
                />
              </div>
              <div>
                <InputLabel
                  for="spouse_nrc"
                  :value="__('user.partner_nrc', 'Partner NRC')"
                />
                <TextInput
                  id="spouse_nrc"
                  v-model="form.spouse.nrc"
                  type="text"
                  class="mt-1 block w-full"
                />
              </div>
              <div>
                <InputLabel
                  for="spouse_job"
                  :value="__('user.partner_job', 'Partner Job')"
                />
                <TextInput
                  id="spouse_job"
                  v-model="form.spouse.job"
                  type="text"
                  class="mt-1 block w-full"
                />
              </div>
              <div>
                <InputLabel
                  for="spouse_alive"
                  :value="__('user.partner_alive', 'Alive Status')"
                />
                <SearchableSelect
                  id="spouse_alive"
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
                <InputLabel
                  for="spouse_phone"
                  :value="__('user.partner_phone', 'Partner Phone')"
                />
                <TextInput
                  id="spouse_phone"
                  v-model="form.spouse.phone"
                  type="text"
                  class="mt-1 block w-full"
                />
              </div>
            </div>
          </div>

          <!-- Form Actions -->
          <div
            class="flex items-center justify-end gap-4 mt-12 bg-gray-50 dark:bg-gray-800/50 p-6 rounded-xl border border-gray-100 dark:border-gray-700"
          >
            <Link href="/users">
              <SecondaryButton>{{
                __("messages.cancel", "Cancel")
              }}</SecondaryButton>
            </Link>
            <PrimaryButton
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
            >
              {{
                isEditing
                  ? __("messages.update", "Update")
                  : __("messages.create", "Create")
              }}
            </PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
