<script setup>
import { defineProps, defineEmits, computed, ref, watch } from "vue";

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
      vaccinationId: null
    }),
  },
  history: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(["close"]);
const loading = ref(false);
const error = ref(null);

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
    "Patient Name",
    "Patient Age",
    "Vaccine Category",
    "Purok",
    "Barangay",
    "Date of Visit",
    "Visit Age",
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
    `${props.patient.firstName} ${props.patient.middleName || ""} ${props.patient.lastName} ${props.patient.suffix || ""}`.trim(),
    props.patient.age || "N/A",
    props.patient.vaccineCategory || "N/A",
    props.patient.purok || "N/A",
    props.patient.barangay || "N/A",
    formatDate(record.dateOfVisit),
    props.patient?.vaccineCategory === "Under 1 Year"
      ? `${record.ageInMonths ?? "N/A"} months`
      : `${record.ageInYears ?? "N/A"} years`,
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
    ...rows.map((row) => row.map((cell) => `"${cell}"`).join(",")), // Add rows
  ].join("\n");

  // Create a blob and download
  const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.setAttribute(
    "download",
    `${props.patient.firstName}_${props.patient.lastName}_vaccination_report.csv`
  );
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};
// Fetch vaccination history
const fetchHistory = async () => {
  if (!props.patient.vaccinationId) return;

  loading.value = true;
  error.value = null;

  try {
    const response = await fetch(`/appointments/history/${props.patient.vaccinationId}`);
    const data = await response.json();

    if (response.ok) {
      props.history = data.history;
    } else {
      error.value = data.message || 'Failed to fetch history';
    }
  } catch (err) {
    error.value = 'Failed to fetch vaccination history';
    console.error(err);
  } finally {
    loading.value = false;
  }
};

// Watch for changes in patient's vaccinationId
watch(() => props.patient.vaccinationId, (newId) => {
  if (newId) {
    fetchHistory();
  }
}, { immediate: true });
</script>

<template>
  <div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-8 rounded-lg w-full max-w-6xl shadow-2xl">
      <!-- Header -->
      <div class="pb-4 border-b border-gray-300">
        <h2 class="text-3xl font-bold mb-4 text-green-600">
          Vaccination History
        </h2>

        <!-- Patient Details -->
        <div class="mb-4 bg-green-50 border border-green-300 rounded-lg p-4">
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
              <p class="text-gray-700">
                <strong>Vaccine Type:</strong> {{ history?.[0]?.vaccineType || 'N/A' }}
              </p>
              <p class="text-gray-700">
                <strong>Total Records:</strong> {{ history?.length || 0 }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-8">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-700"></div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-red-600 text-center py-8">
        {{ error }}
      </div>

      <!-- Scrollable Table -->
      <div v-else class="overflow-y-auto max-h-[400px] border-t border-b border-gray-300">
        <table class="w-full text-left border-collapse">
          <thead class="bg-green-600 text-white sticky top-0">
            <tr>
              <th class="px-4 py-3 text-left font-semibold">Date of Visit</th>
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
            <tr v-if="!history?.length" class="text-center">
              <td colspan="8" class="px-4 py-3 text-gray-500">
                No vaccination history available
              </td>
            </tr>
            <tr
              v-else
              v-for="record in history"
              :key="record.vacAppointmentId"
              class="odd:bg-gray-50 even:bg-white hover:bg-green-100 transition-colors"
            >
              <td class="px-4 py-3">{{ formatDate(record.dateOfVisit) }}</td>
              <td class="px-4 py-3">{{ record.weight || "N/A" }}</td>
              <td class="px-4 py-3">{{ record.height || "N/A" }}</td>
              <td class="px-4 py-3">{{ record.temperature || "N/A" }}</td>
              <td class="px-4 py-3">{{ record.antigenGiven || "N/A" }}</td>
              <td class="px-4 py-3">{{ record.injectedBy || "N/A" }}</td>
              <td v-if="isUnderOneYear" class="px-4 py-3">
                {{ record.exclusivelyBreastfed || "N/A" }}
              </td>
              <td class="px-4 py-3">{{ formatDate(record.nextAppointment) }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal Footer -->
      <div class="pt-4 flex justify-between items-center">
        <button
          @click="closeModal"
          class="bg-gray-500 text-white px-6 py-3 rounded-md hover:bg-gray-600 transition"
        >
          Close
        </button>
        <button
          @click="downloadReport"
          :disabled="!history?.length"
          :class="[history?.length ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-gray-300 text-gray-500 cursor-not-allowed']"
          class="px-6 py-3 rounded-md transition"
        >
          Download Report
        </button>
      </div>
    </div>
  </div>
</template>
