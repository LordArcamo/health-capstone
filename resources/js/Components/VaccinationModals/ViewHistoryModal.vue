<script setup>
import { defineProps, defineEmits, computed } from "vue";

// Define the props and emits
const props = defineProps({
  patient: {
    type: Object,
    required: true,
    default: () => ({
      firstName: "Unknown",
      middleName: "Unknown",
      lastName: "Unknown",
      suffix: "Unknown",
      age: "Unknown",
      vaccineType: "Unknown",
      barangay: "Unknown",
      purok: "Unknown",
      vaccineCategory: "Unknown",
    }),
  },
  history: {
    type: Array,
    default: () => [], // Default to an empty array if no history is provided
  },
});

const emit = defineEmits(["close"]); // Emit event to close the modal

// Close the modal
const closeModal = () => {
  emit("close");
};

// Computed property to check if the patient is "Under 1 Year"
const isUnderOneYear = computed(() => {
  return props.patient?.vaccineCategory === "Under 1 Year";
});

// Function to format date
const formatDate = (dateString) => {
  if (!dateString) return "N/A";
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'numeric',
    day: 'numeric'
  });
};

// Function to download the patient's history as a CSV file
const downloadReport = () => {
  if (!props.history || props.history.length === 0) {
    alert("No history records available to generate the report.");
    return;
  }

  // Prepare CSV headers
  const headers = [
    "Date of Visit",
    "Age",
    "Weight",
    "Height",
    "Temperature",
    "Antigen Given",
    "Injected By",
    "Exclusively Breastfed",
    "Next Appointment",
  ];

  // Prepare rows
  const rows = props.history.map((record) => [
    formatDate(record.dateOfVisit),
    props.patient?.vaccineCategory === "Under 1 Year"
      ? `${record.ageInMonths || "N/A"} months`
      : `${record.ageInYears || "N/A"} years`,
    record.weight || "N/A",
    record.height || "N/A",
    record.temperature || "N/A",
    record.antigenGiven || "N/A",
    record.injectedBy || "N/A",
    props.patient?.vaccineCategory === "Under 1 Year"
      ? record.exclusivelyBreastfed || "N/A"
      : "N/A",
    formatDate(record.nextAppointment),
  ]);

  // Create CSV content
  const csvContent = [
    headers.join(","), // Add headers
    ...rows.map((row) => row.map((cell) => `"${cell}"`).join(",")), // Escape CSV content
  ].join("\n");

  // Create a blob and download link
  const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.setAttribute(
    "download",
    `${props.patient.firstName}_${props.patient.lastName}_history_report.csv`
  );

  // Trigger download
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);

  alert("History report downloaded successfully!");
};
</script>

<template>
  <div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-8 rounded-lg w-full max-w-6xl shadow-2xl max-h-[90vh] overflow-y-auto">
      <!-- Header -->
      <div class="sticky top-0 bg-white z-10 pb-4">
        <h2 class="text-3xl font-bold mb-6 text-green-600">
          Vaccination History
        </h2>

        <!-- Patient Details -->
        <div class="mb-6 bg-green-50 border border-green-300 rounded-lg p-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <p class="text-gray-700">
                <strong>Name:</strong> {{ patient.firstName }} {{ patient.middleName }} {{ patient.lastName }} {{ patient.suffix }}
              </p>
              <p class="text-gray-700">
                <strong>Age:</strong> {{ patient.age }}
              </p>
            </div>
            <div>
              <p class="text-gray-700">
                <strong>Category:</strong> {{ patient.vaccineCategory }}
              </p>
              <p class="text-gray-700">
                <strong>Address:</strong> Purok {{ patient.purok }}, {{ patient.barangay }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- History Table -->
      <div v-if="history.length > 0" class="overflow-x-auto">
        <table class="w-full text-left border-collapse rounded-lg overflow-hidden">
          <thead class="bg-green-600 text-white">
            <tr>
              <th class="px-4 py-3 text-left font-semibold">Date of Visit</th>
              <th class="px-4 py-3 text-left font-semibold">Age</th>
              <th class="px-4 py-3 text-left font-semibold">Weight</th>
              <th class="px-4 py-3 text-left font-semibold">Height</th>
              <th class="px-4 py-3 text-left font-semibold">Temperature</th>
              <th class="px-4 py-3 text-left font-semibold">Antigen Given</th>
              <th class="px-4 py-3 text-left font-semibold">Injected By</th>
              <th v-if="isUnderOneYear" class="px-4 py-3 text-left font-semibold">
                Exclusively Breastfed
              </th>
              <th class="px-4 py-3 text-left font-semibold">Next Appointment</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr
              v-for="record in history"
              :key="record.id"
              class="odd:bg-gray-50 even:bg-white hover:bg-green-100 transition-colors"
            >
              <td class="px-4 py-3 text-left">{{ formatDate(record.dateOfVisit) }}</td>
              <td class="px-4 py-3 text-left">
                <template v-if="isUnderOneYear">
                  {{ record.ageInMonths || "N/A" }} months
                </template>
                <template v-else>
                  {{ record.ageInYears || "N/A" }} years
                </template>
              </td>
              <td class="px-4 py-3 text-left">{{ record.weight || "N/A" }}</td>
              <td class="px-4 py-3 text-left">{{ record.height || "N/A" }}</td>
              <td class="px-4 py-3 text-left">{{ record.temperature || "N/A" }}</td>
              <td class="px-4 py-3 text-left">{{ record.antigenGiven || "N/A" }}</td>
              <td class="px-4 py-3 text-left">{{ record.injectedBy || "N/A" }}</td>
              <td v-if="isUnderOneYear" class="px-4 py-3 text-left">
                {{ record.exclusivelyBreastfed || "N/A" }}
              </td>
              <td class="px-4 py-3 text-left">{{ formatDate(record.nextAppointment) }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- No History Fallback -->
      <div v-else class="text-center text-gray-500 mt-8">
        <p>No vaccination history available for this patient.</p>
      </div>

      <!-- Modal Footer -->
      <div class="flex justify-between items-center mt-8 sticky bottom-0 bg-white pt-4">
        <button
          @click="closeModal"
          class="bg-gray-500 text-white px-6 py-3 rounded-md hover:bg-gray-600 transition"
        >
          Close
        </button>
        <button
          @click="downloadReport"
          :disabled="!history.length"
          :class="[
            'px-6 py-3 rounded-md transition',
            history.length
              ? 'bg-blue-600 text-white hover:bg-blue-700'
              : 'bg-gray-300 text-gray-500 cursor-not-allowed'
          ]"
        >
          Download Report
        </button>
      </div>
    </div>
  </div>
</template>
