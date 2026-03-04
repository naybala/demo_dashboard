<script>
  import AdminLayout from "@/Layouts/AdminLayout.svelte";
  import PageHeader from "@/Components/PageHeader.svelte";
  import { Link } from "@inertiajs/svelte";
  import SecondaryButton from "@/Components/SecondaryButton.svelte";
  import PrimaryButton from "@/Components/PrimaryButton.svelte";
  import { __ } from "@/helpers.js";

  export let unit = {};
</script>

<svelte:head>
  <title>{__("sidebar.unit", "Unit")}: {unit.name}</title>
</svelte:head>

<AdminLayout>
  <PageHeader title={`${__("sidebar.unit", "Unit")}: ${unit.name}`}>
    <Link href="/units">
      <SecondaryButton
        >{__("unit.back_to_list", "Back to List")}</SecondaryButton
      >
    </Link>
  </PageHeader>

  <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 max-w-xl">
    <div class="space-y-4">
      <div>
        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">
          {__("unit.unit_name", "Unit Name")}
        </h4>
        <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
          {unit.name}
        </p>
      </div>
    </div>

    <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
      <Link href={`/units/${unit.id}/edit`}>
        <PrimaryButton>{__("unit.edit_unit", "Edit Unit")}</PrimaryButton>
      </Link>
    </div>
  </div>
</AdminLayout>
