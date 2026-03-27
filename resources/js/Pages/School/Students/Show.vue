<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
  student: Object,
});

const documentLabels = {
  academic_transcripts: "Academic Transcripts",
  degree_certificates: "Degree Certificates",
  letters_of_recommendation: "Letters of Recommendation",
  statement_of_purpose: "Statement of Purpose",
  signature: "Signature",
};

const isImage = (url) => {
  if (!url) return false;
  const extension = url.split(".").pop().toLowerCase().split("?")[0];
  return ["jpg", "jpeg", "png", "gif", "webp", "svg"].includes(extension);
};
</script>

<template>
  <Head :title="'Student Details - ' + (student?.full_name || 'View')" />

  <AdminLayout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
          class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-700"
        >
          <div
            class="p-8 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-800"
          >
            <div>
              <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Student Details
              </h2>
              <p class="text-sm text-gray-500 mt-1 dark:text-gray-400">
                Review profile, assignment, attendance and fees for this
                student.
              </p>
            </div>

            <Link
              href="/students"
              class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white flex items-center gap-2"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M10 19l-7-7m0 0l7-7m-7 7h18"
                />
              </svg>
              Back to profile
            </Link>
          </div>

          <div class="p-8 space-y-12">
            <!-- Basic Information -->
            <section>
              <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                  Basic Information
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  Core profile fields for the student.
                </p>
              </div>

              <div class="flex flex-col md:flex-row gap-12">
                <div class="flex-1 space-y-6">
                  <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                      Choose student Info
                    </div>
                    <div class="font-medium text-gray-900 dark:text-white">
                      {{ student?.student_info || "-" }}
                    </div>
                  </div>
                  <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                      Academic year
                    </div>
                    <div class="font-medium text-gray-900 dark:text-white">
                      {{ student?.academic_year || "-" }}
                    </div>
                  </div>
                  <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                      Class
                    </div>
                    <div class="font-medium text-gray-900 dark:text-white">
                      {{ student?.class?.name || "-" }}
                    </div>
                  </div>
                  <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                      Full Name
                    </div>
                    <div class="font-medium text-gray-900 dark:text-white">
                      {{ student?.full_name || "-" }}
                    </div>
                  </div>
                  <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                      Other Name
                    </div>
                    <div class="font-medium text-gray-900 dark:text-white">
                      {{ student?.other_name || "-" }}
                    </div>
                  </div>
                  <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                      Email Address
                    </div>
                    <div class="font-medium text-gray-900 dark:text-white">
                      {{ student?.email || "-" }}
                    </div>
                  </div>
                  <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                      Registration Date
                    </div>
                    <div class="font-medium text-gray-900 dark:text-white">
                      {{ student?.registration_date || "-" }}
                    </div>
                  </div>
                </div>

                <div class="flex-1 md:max-w-xs space-y-6">
                  <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                      Student ID
                    </div>
                    <div class="font-medium text-gray-900 dark:text-white">
                      {{ student?.student_code || "-" }}
                    </div>
                    <div class="text-xs text-gray-400 mt-1">
                      Auto-generated, but can edited if needed.
                    </div>
                  </div>
                  <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                      Gender
                    </div>
                    <div
                      class="inline-block px-4 py-1 rounded-full text-sm font-medium bg-blue-600 text-white capitalize"
                    >
                      {{ student?.gender || "-" }}
                    </div>
                  </div>
                  <div class="pt-4">
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                      Profile Picture
                    </div>
                    <div
                      class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center border border-gray-200 shadow-sm overflow-hidden"
                    >
                      <img
                        v-if="student?.avatar"
                        :src="student.avatar"
                        class="w-full h-full object-cover rounded-full"
                      />
                      <svg
                        v-else
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-10 w-10 text-gray-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                      </svg>
                    </div>
                  </div>
                </div>
              </div>
            </section>

            <!-- Personal & Academic Details -->
            <div
              class="grid grid-cols-1 md:grid-cols-2 gap-12 pt-8 border-t border-gray-100 dark:border-gray-700"
            >
              <section class="space-y-6">
                <div class="mb-6">
                  <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    Personal details
                  </h3>
                  <p class="text-sm text-gray-500 dark:text-gray-400">
                    How we reach the student and guardian.
                  </p>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Date of Birth
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.dob || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Place of Birth
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.place_of_birth || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    NRC
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.nrc || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Nationality
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.nationality || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Religion
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.religion || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Address
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.address || "-" }}
                  </div>
                </div>
              </section>

              <section class="space-y-6">
                <div class="mb-6">
                  <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    Academic details
                  </h3>
                  <p class="text-sm text-gray-500 dark:text-gray-400">
                    Placement, transport and other academic - related details.
                  </p>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    School Attended
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.school_attended || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Grade Attended
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.grade_attended || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Year Attended
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.year_attended || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Grade
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.grade?.name || "-" }}
                  </div>
                </div>
              </section>
            </div>

            <!-- Father & Mother Details -->
            <div
              class="grid grid-cols-1 md:grid-cols-2 gap-12 pt-8 border-t border-gray-100 dark:border-gray-700"
            >
              <section class="space-y-6">
                <div class="mb-6">
                  <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    Father's Information
                  </h3>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Father's Name
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.father_name || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Father's NRC
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.father_nrc || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Father's Qualification
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.father_qualification || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Father's Job
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.father_job || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Father's Phone
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.father_phone || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Father's Email Address
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.father_email || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Father's Address
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.father_address || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Father's Alive or Dead
                  </div>
                  <div
                    class="font-medium text-gray-900 dark:text-white capitalize"
                  >
                    {{ student?.father_alive_status || "-" }}
                  </div>
                </div>
              </section>

              <section class="space-y-6">
                <div class="mb-6">
                  <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    Mother's Information
                  </h3>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Mother's Name
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.mother_name || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Mother's NRC
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.mother_nrc || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Mother's Qualification
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.mother_qualification || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Mother's Job
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.mother_job || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Mother's Phone
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.mother_phone || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Mother's Email Address
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.mother_email || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Mother's Address
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.mother_address || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Mother's Alive or Dead
                  </div>
                  <div
                    class="font-medium text-gray-900 dark:text-white capitalize"
                  >
                    {{ student?.mother_alive_status || "-" }}
                  </div>
                </div>
              </section>
            </div>

            <!-- Guardian Details -->
            <div
              class="grid grid-cols-1 md:grid-cols-2 gap-12 pt-8 border-t border-gray-100 dark:border-gray-700"
            >
              <section class="space-y-6">
                <div class="mb-6">
                  <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    Guardian's Information
                  </h3>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Guardian's Name
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.guardian_name || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Guardian's NRC
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.guardian_nrc || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Guardian's Qualification
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.guardian_qualification || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Guardian's Job
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.guardian_job || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Guardian's Phone
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.guardian_phone || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Guardian's Email Address
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.guardian_email || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Guardian's Address
                  </div>
                  <div class="font-medium text-gray-900 dark:text-white">
                    {{ student?.guardian_address || "-" }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                    Guardian's Alive or Dead
                  </div>
                  <div
                    class="font-medium text-gray-900 dark:text-white capitalize"
                  >
                    {{ student?.guardian_alive_status || "-" }}
                  </div>
                </div>
              </section>
            </div>

            <!-- Additional Documents -->
            <div
              v-if="
                student?.additional_documents &&
                Object.values(student.additional_documents).some((v) => v)
              "
              class="pt-8 border-t border-gray-100 dark:border-gray-700 mt-12"
            >
              <section>
                <div class="mb-6">
                  <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    Additional Documents
                  </h3>
                  <p class="text-sm text-gray-500 dark:text-gray-400">
                    Uploaded academic and legal documents.
                  </p>
                </div>

                <div
                  class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                >
                  <div
                    v-for="(url, type) in student.additional_documents"
                    :key="type"
                    class="p-4 bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700 rounded-xl flex flex-col gap-4 group transition-all hover:border-blue-500 hover:shadow-sm"
                  >
                    <div class="flex items-center justify-between">
                      <div class="flex items-center gap-3">
                        <div
                          class="p-2 bg-blue-100 dark:bg-blue-900/30 text-blue-600 rounded-lg"
                        >
                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                          </svg>
                        </div>
                        <div
                          class="text-sm font-semibold text-gray-900 dark:text-white"
                        >
                          {{ documentLabels[type] || type }}
                        </div>
                      </div>

                      <a
                        :href="url"
                        target="_blank"
                        class="text-xs font-bold text-blue-600 hover:text-blue-700 uppercase tracking-wider flex items-center gap-1"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          class="h-3 w-3"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke="currentColor"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                          />
                        </svg>
                        View
                      </a>
                    </div>

                    <!-- Image Preview -->
                    <div
                      v-if="isImage(url)"
                      class="relative aspect-video bg-white dark:bg-gray-900 rounded-lg border border-gray-100 dark:border-gray-700 overflow-hidden group/preview"
                    >
                      <img
                        :src="url"
                        class="w-full h-full object-contain p-2"
                        :alt="documentLabels[type]"
                      />
                      <div
                        class="absolute inset-0 bg-black/5 opacity-0 group-hover/preview:opacity-100 transition-opacity flex items-center justify-center"
                      >
                        <a
                          :href="url"
                          target="_blank"
                          class="p-2 bg-white/90 dark:bg-gray-800/90 rounded-full shadow-lg text-gray-900 dark:text-white transform scale-90 group-hover/preview:scale-100 transition-transform"
                        >
                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"
                            />
                          </svg>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
