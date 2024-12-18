<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import RiskModal from '@/Components/Risk Management/RiskModal.vue';
import RiskTable from '@/Components/Risk Management/RiskTable.vue';
import { ref } from "vue";

const riskData = ref([
  {
    name: "John Doe",
    foodIntake: "High Salt/Fat",
    physicalActivity: "No",
    diabetes: "Yes",
  },
  {
    name: "Jane Smith",
    foodIntake: "High Fiber",
    physicalActivity: "Yes",
    diabetes: "No",
  },
  {
    name: "Alice Brown",
    foodIntake: "Moderate Salt/Fat",
    physicalActivity: "No",
    diabetes: "Yes",
  },
]);

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
    <div class="p-6 flex flex-col gap-5 ">
      <!-- Header Section -->
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Risk Management</h1>
        <button
          @click="openRiskModal"
          class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition"
        >
          Add Risk Entry
        </button>
      </div>

      <!-- Risk Table -->
      <div class="shadow-md rounded-lg">
        <RiskTable :data="riskData" />
      </div>

      <!-- Risk Modal -->
      <RiskModal
        :showModal="showRiskModal"
        @close="closeRiskModal"
        @save="saveEntry"
      />
    </div>
  </NewLayout>
</template>
