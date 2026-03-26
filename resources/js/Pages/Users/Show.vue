<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SectionCard from "@/Components/SectionCard.vue";
import { Head, Link } from "@inertiajs/vue3";
import { __ } from "@/helpers.js";

const props = defineProps({
  user: Object,
});

const profile = props.user.profile || {};
const spouse = props.user.spouse || {};
const father = props.user.father || {};
const mother = props.user.mother || {};

const getGenderName = (gender) => {
  if (gender == 1) return "Male";
  if (gender == 2) return "Female";
  return "Other";
};
</script>

<template>
  <Head :title="__('user.teacher_details', 'Teacher Details')" />

  <AdminLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2
          class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
        >
          {{ __("user.teacher_details", "Teacher Details") }}
        </h2>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto sm:px-6 lg:px-8 space-y-8">
        <!-- Main Profile Card -->
        <div
          class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden border border-transparent dark:border-gray-700"
        >
          <div class="p-8">
            <div
              class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 pb-4 border-b border-gray-100 dark:border-gray-700"
            >
              <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                  {{ __("user.teacher_details", "Teacher Details") }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                  {{
                    __(
                      "user.review_profile_msg",
                      "Review profile, academic, qualification and duties for this user.",
                    )
                  }}
                </p>
              </div>
              <Link
                href="/users"
                class="mt-4 md:mt-0 flex items-center text-sm text-gray-500 hover:text-gray-700"
              >
                <svg
                  class="w-4 h-4 mr-2"
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
                {{ __("messages.back_to_profile", "Back to List") }}
              </Link>
            </div>

            <!-- Sections Container -->
            <div class="pt-2 flex items-center mx-auto">
              <label
                class="text-xs text-gray-400 uppercase tracking-wider mr-4"
                >{{ __("user.profile_pic", "Profile Picture :") }}</label
              >
              <div
                class="h-40 w-40 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center overflow-hidden border border-gray-200 dark:border-gray-600"
              >
                <img
                  v-if="user.avatar"
                  :src="user.avatar"
                  class="h-full w-full object-cover"
                />
                <svg
                  v-else
                  class="h-8 w-8 text-gray-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.5"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                  />
                </svg>
              </div>
            </div>
            <br /><br />
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-12">
              <!-- Basic Information -->
              <SectionCard
                :title="__('user.basic_info', 'Basic Information')"
                :description="
                  __(
                    'user.basic_info_desc',
                    'Core profile fields for the user.',
                  )
                "
              >
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.fullname", "Full name") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ user.fullname }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{
                      __("user.staff_id_label", "Teacher / Staff ID")
                    }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ user.staff_id }}
                  </p>
                  <p class="text-[10px] text-gray-400">
                    {{
                      __(
                        "user.staff_id_note",
                        "Auto-generated, but can edited if needed.",
                      )
                    }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.class", "Class") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ profile.possessive_grade || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.gender", "Gender") }}</label
                  >
                  <div class="mt-1">
                    <p
                      class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                    >
                      {{ getGenderName(user.gender) || "-" }}
                    </p>
                  </div>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.home_grade", "Home Grade") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ profile.possessive_grade || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.marital_status", "Marital Status") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ profile.marital_status || "-" }}
                  </p>
                </div>
              </SectionCard>

              <!-- Personal Information -->
              <SectionCard
                :title="__('user.personal_info', 'Personal Information')"
                :description="
                  __(
                    'user.personal_info_desc',
                    'How to reach the student and guardian.',
                  )
                "
              >
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.dob", "Date of Birth") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ user.dob || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.pob", "Place of Birth") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ profile.place_of_birth || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.nrc", "NRC") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ profile.nrc || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.religion", "Religion") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ profile.religion || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.nationality", "Nationality") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ profile.nationality || "-" }}
                  </p>
                </div>
              </SectionCard>

              <!-- Educational Background -->
              <SectionCard
                :title="__('user.edu_background', 'Educational Background')"
                :description="
                  __(
                    'user.edu_background_desc',
                    'How to reach the student and guardian.',
                  )
                "
              >
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.degree", "Degree Earned") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ profile.education_background?.degree || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.certificate", "Certificate") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ profile.education_background?.certificate || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.institution", "Institution Name") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ profile.education_background?.institution || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.grad_year", "Year of Graduation") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ profile.education_background?.year || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.specialization", "Specialization") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ profile.education_background?.specialization || "-" }}
                  </p>
                </div>
              </SectionCard>

              <!-- Professional Qualifications -->
              <SectionCard
                :title="
                  __('user.prof_qualifications', 'Professional Qualifications')
                "
                :description="
                  __(
                    'user.prof_qual_desc',
                    'How to reach the student and guardian.',
                  )
                "
              >
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{
                      __("user.prof_subject", "Professional Subject")
                    }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ profile.professional_subject || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.possess_grade", "Possessive Grade") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ profile.possessive_grade || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.fam_member", "Family's Member") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    -
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.curr_address", "Current Address") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ profile.current_address || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.perm_address", "Permanent Address") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ profile.permanent_address || "-" }}
                  </p>
                </div>
              </SectionCard>

              <!-- Work Experience -->
              <SectionCard
                :title="__('user.work_exp', 'Work Experience')"
                :description="
                  __(
                    'user.work_exp_desc',
                    'How to reach the student and guardian.',
                  )
                "
              >
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.work_exp_yrs", "Work Experience") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ profile.work_experience?.years || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.dept_name", "Department Name") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ profile.department_name || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.position", "Position") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ profile.position || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.duration", "Duration") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ profile.work_experience?.duration || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.location", "Location") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ profile.work_experience?.location || "-" }}
                  </p>
                </div>
              </SectionCard>

              <!-- Spouse's Information -->
              <SectionCard
                :title="__('user.spouse_info', 'Spouse\'s Information')"
                :description="
                  __(
                    'user.spouse_info_desc',
                    'How to reach the student and guardian.',
                  )
                "
              >
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.partner_name", "Partner's Name") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ spouse.name || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.partner_nrc", "Partner's NRC") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ spouse.nrc || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.partner_job", "Partner's Job") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ spouse.job || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{
                      __("user.partner_alive", "Partner's Alive or Dead")
                    }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ spouse.alive_status || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.partner_phone", "Partner's Phone") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ spouse.phone || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{
                      __("user.partner_address", "Partner's Address")
                    }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ spouse.address || "-" }}
                  </p>
                </div>
              </SectionCard>

              <!-- Father Information -->
              <SectionCard
                :title="__('user.father_info', 'Father\'s Information')"
                :description="
                  __(
                    'user.father_info_desc',
                    'How to reach the student and guardian.',
                  )
                "
              >
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.father_name", "Father's Name") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ father.name || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.father_nrc", "Father's NRC") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ father.nrc || "-" }}
                  </p>
                </div>

                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{
                      __("user.father_alive", "Father's Alive or Dead")
                    }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ father.alive_status || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.father_phone", "Father's Phone") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ father.phone || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.father_address", "Father's Address") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ father.address || "-" }}
                  </p>
                </div>
              </SectionCard>
              <!-- Mother Information -->
              <SectionCard
                :title="__('user.mother_info', 'Mother\'s Information')"
                :description="
                  __(
                    'user.mother_info_desc',
                    'How to reach the student and guardian.',
                  )
                "
              >
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.mother_name", "Mother's Name") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ mother.name || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.mother_nrc", "Mother's NRC") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ mother.nrc || "-" }}
                  </p>
                </div>

                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{
                      __("user.mother_alive", "Mother's Alive or Dead")
                    }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ mother.alive_status || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.mother_phone", "Mother's Phone") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ mother.phone || "-" }}
                  </p>
                </div>
                <div>
                  <label
                    class="text-xs text-gray-400 uppercase tracking-wider"
                    >{{ __("user.mother_address", "Mother's Address") }}</label
                  >
                  <p
                    class="text-sm font-medium text-gray-900 dark:text-white mt-1"
                  >
                    {{ mother.address || "-" }}
                  </p>
                </div>
              </SectionCard>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
