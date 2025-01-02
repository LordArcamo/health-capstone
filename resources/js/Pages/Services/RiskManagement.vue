<script setup>
import NewLayout from '@/Layouts/PersonnelLayout.vue';
import { Head } from '@inertiajs/vue3';
import RiskModal from '@/Components/Risk Management/RiskModal.vue';
import RiskTable from '@/Components/Risk Management/RiskTable.vue';
import { ref } from "vue";

const props = defineProps({
  patients: {
    type: Array,
    default: () => [],
  },
  RISK_MANAGEMENT: {
        type: Array,
        default: () => [],
    },
});

const showRiskModal = ref(false);
const currentEntry = ref(null);

const openRiskModal = () => {
  currentEntry.value = null; // Clear form for a new entry
  showRiskModal.value = true;
};

const closeRiskModal = () => {
  showRiskModal.value = false;
};

const saveEntry = (entry) => {
  if (currentEntry.value) {
    // Edit mode
    const index = riskData.value.findIndex((e) => e.name === currentEntry.value.name);
    if (index !== -1) riskData.value[index] = { ...entry };
  } else {
    // Add new entry
    riskData.value.push(entry);
  }
  closeRiskModal();
};
</script>

<template>
  <Head title="Risk Management" />

  <NewLayout>
    <div class="p-6 flex flex-col gap-5 bg-gradient-to-br from-orange-100 via-orange-200 to-orange-300 min-h-screen">
  <!-- Header Section -->
  <div class="flex justify-between items-center py-4 border-b border-orange-400">
    <h1 class="text-4xl font-bold text-orange-800">Risk Management</h1>
    <button
      @click="openRiskModal"
      class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-8 py-3 rounded-lg shadow-lg hover:bg-orange-700 focus:outline-none focus:ring-4 focus:ring-orange-300 focus:ring-offset-2 text-base transition-all sm:text-sm sm:w-auto sm:px-6 sm:py-2"
    >
      + Add Risk Entry
    </button>
  </div>

  <!-- Risk Table Section -->
  <div class="bg-white rounded-lg shadow-md p-6 border border-orange-300">
    <RiskTable :riskPatients="props.RISK_MANAGEMENT" />
  </div>

  <!-- Risk Modal -->
  <RiskModal
    :showModal="showRiskModal"
    :currentEntry="currentEntry"
    :patients="props.patients"
    @close="closeRiskModal"
    @save="saveEntry"
  />
</div>

  </NewLayout>
</template>
