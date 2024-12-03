<script setup>
import { defineProps, defineEmits, computed } from "vue";

// Define the props and emits
const props = defineProps({
  patient: {
    type: Object,
    required: true,
    default: () => ({
      name: "Unknown",
      age: "Unknown",
      vaccineType: "Unknown",
      address: "Unknown",
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
    record.dateOfVisit || "N/A",
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
    record.nextAppointment || "N/A",
  ]);

  // Create CSV content
  const csvContent = [
    headers.join(","),
    ...rows.map((row) => row.join(",")),
  ].join("\n");

  // Create a blob and download link
  const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.setAttribute("download", `${props.patient.name}_history_report.csv`);

  // Trigger download
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);

  alert("History report downloaded successfully!");
};
</script>

<template>
  <div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-8 rounded-lg w-full max-w-6xl shadow-2xl">
      <!-- Header -->
      <h2 class="text-3xl font-bold mb-6 text-green-600">
        History for {{ patient.name || "Unknown" }}
      </h2>

      <!-- Patient Details -->
      <div class="mb-6 bg-green-50 border border-green-300 rounded-lg p-4">
        <p class="text-gray-700">
          <strong>Name:</strong> {{ patient.name || "N/A" }}
        </p>
        <p class="text-gray-700">
          <strong>Age:</strong> {{ patient.age || "N/A" }}
        </p>
        <p class="text-gray-700">
          <strong>Vaccine Type:</strong> {{ patient.vaccineType || "N/A" }}
        </p>
        <p class="text-gray-700">
          <strong>Address:</strong> {{ patient.address || "N/A" }}
        </p>
      </div>

      <!-- History Table -->
      <div v-if="history.length > 0">
        <table class="w-full text-left border-collapse rounded-lg overflow-hidden">
          <thead class="bg-green-600 text-white">
            <tr>
              <th class="px-6 py-3 font-semibold">Date of Visit</th>
              <th class="px-6 py-3 font-semibold">Age</th>
              <th class="px-6 py-3 font-semibold">Weight</th>
              <th class="px-6 py-3 font-semibold">Height</th>
              <th class="px-6 py-3 font-semibold">Temperature</th>
              <th class="px-6 py-3 font-semibold">Antigen Given</th>
              <th class="px-6 py-3 font-semibold">Injected By</th>
              <th v-if="isUnderOneYear" class="px-6 py-3 font-semibold">
                Exclusively Breastfed
              </th>
              <th class="px-6 py-3 font-semibold">Next Appointment</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="record in history"
              :key="record.id"
              class="odd:bg-gray-50 even:bg-white hover:bg-green-100 transition-colors"
            >
              <td class="px-6 py-3">{{ record.dateOfVisit || "N/A" }}</td>
              <td class="px-6 py-3">
                <template v-if="isUnderOneYear">
                  {{ record.ageInMonths || "N/A" }} months
                </template>
                <template v-else>
                  {{ record.ageInYears || "N/A" }} years
                </template>
              </td>
              <td class="px-6 py-3">{{ record.weight || "N/A" }}</td>
              <td class="px-6 py-3">{{ record.height || "N/A" }}</td>
              <td class="px-6 py-3">{{ record.temperature || "N/A" }}</td>
              <td class="px-6 py-3">{{ record.antigenGiven || "N/A" }}</td>
              <td class="px-6 py-3">{{ record.injectedBy || "N/A" }}</td>
              <td v-if="isUnderOneYear" class="px-6 py-3">
                {{ record.exclusivelyBreastfed || "N/A" }}
              </td>
              <td class="px-6 py-3">{{ record.nextAppointment || "N/A" }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- No History Fallback -->
      <div v-else class="text-center text-gray-500 mt-8">
        <p>No history records available for this patient.</p>
      </div>

      <!-- Modal Footer -->
      <div class="flex justify-between items-center mt-8">
        <button
          @click="closeModal"
          class="bg-gray-500 text-white px-6 py-3 rounded-md hover:bg-gray-600 transition"
        >
          Close
        </button>
        <button
          @click="downloadReport"
          class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition"
        >
          Download Report
        </button>
      </div>
    </div>
  </div>
</template>
