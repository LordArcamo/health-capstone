<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed, onMounted, watch } from 'vue';
import Chart from 'chart.js/auto';
import { format } from 'date-fns';
import ViewPatientModalGeneral from '@/Components/Modals/ViewPatientModalGeneral.vue';
import PrenatalModal from '@/Components/Modals/ViewPatientModalPrenatal.vue';

// Props from backend
const props = defineProps({
  totalPatients: Number,
  ITRConsultation: Array,
  latestPatients: Array,
  todayAppointments: Number,
  criticalCases: Number,
  notifications: Array,
});

// Reactive states
const totalPatients = ref(props.totalPatients || 0);
const ITRConsultation = ref(props.ITRConsultation || []);
const latestPatients = ref(props.latestPatients || []);
const todayAppointments = ref(props.todayAppointments || 0);
const criticalCases = ref(props.criticalCases || 0);
const notifications = ref(props.notifications || []);
const currentPage = ref(1); // Initialize currentPage
const itemsPerPage = ref(5); // Set the number of items per page
const showNotifications = ref(false);
const selectedPatient = ref(null); // Stores the selected patient data
const currentModal = ref(null); // Stores the current modal type (e.g., 'prenatal', 'general')
const showModal = ref(false); // Controls modal visibility

const searchQueue = ref('');
const unreadNotifications = computed(() =>
  notifications.value.filter(n => !n.isRead).length
);

// Function to format date
const formatDate = (dateString) => {
  const date = new Date(dateString);
  return format(date, 'MMMM d, yyyy');
};
// Computed property for paginated patients
const paginatedPatients = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredITRConsultation.value.slice(start, end);
});

// Total pages
const totalPages = computed(() => {
  return Math.ceil(filteredITRConsultation.value.length / itemsPerPage.value);
});

// Pagination methods
const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

// Format date for notifications
const formatNotificationDate = (time) => {
  const date = new Date(time);
  return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

// Computed property for filtered patients in queue
const filteredITRConsultation = computed(() => {
  if (!searchQueue.value) return ITRConsultation.value;
  return ITRConsultation.value.filter((patient) =>
    patient.firstName.toLowerCase().includes(searchQueue.value.toLowerCase()) ||
    patient.lastName.toLowerCase().includes(searchQueue.value.toLowerCase()) ||
    patient.visitType.toLowerCase().includes(searchQueue.value.toLowerCase())
  );
});

// Computed property for paginated patients
const paginatedLatestPatients = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return props.latestPatients.slice(start, end);
});

// Function to start checkup
const startCheckup = (patient) => {
  console.log('Patient Data:', patient); // Debugging: Inspect patient data

  // Check if visitType is Prenatal and prenatalConsultationDetailsID exists
  if (patient?.visitType === 'Prenatal' && patient?.prenatalConsultationDetailsID) {
    console.log('Navigating to prenatal route with ID:', patient.prenatalConsultationDetailsID); // Debug
    router.visit('/doctor-checkup/prenatal', {
      method: 'get',
      data: { prenatalConsultationDetailsID: patient.prenatalConsultationDetailsID },
    });
    return; // Exit after handling Prenatal case
  }

  // Check if consultationDetailsID exists (for general case)
  if (patient?.consultationDetailsID) {
    console.log('Navigating to ITR route with ID:', patient.consultationDetailsID); // Debug
    router.visit('/doctor-checkup/itr', {
      method: 'get',
      data: { consultationDetailsID: patient.consultationDetailsID },
    });
    return; // Exit after handling ITR case
  }

  // If no valid data is found
  console.warn('Invalid patient data:', patient);
};

// Function to view patient details
const viewPatientDetails = (patient) => {
  console.log('View Details - Patient Data:', patient);

  if (patient?.visitType === 'Prenatal' && patient?.prenatalConsultationDetailsID) {
    console.log('Opening Prenatal Checkup Modal for ID:', patient.prenatalConsultationDetailsID);
    openModal('prenatal', patient);
    return;
  }

  if (patient?.visitType === 'General' && patient?.consultationDetailsID) {
    console.log('Opening General Patient Modal for ID:', patient.consultationDetailsID);
    openModal('general', patient);
    return;
  }

  console.warn('Invalid patient data for view details:', patient);
};

const openModal = (modalType, patient) => {
  selectedPatient.value = patient;
  currentModal.value = modalType;
  showModal.value = true;
};

const closeModal = () => {
  selectedPatient.value = null;
  currentModal.value = null;
  showModal.value = false;
};


// Handle marking notification as read
const markAsRead = (notification) => {
  if (!notification.isRead) {
    notification.isRead = true;
  }
};

// Dynamic date
const currentDate = ref('');
const updateDate = () => {
  const options = { year: 'numeric', month: 'long', day: 'numeric', timeZone: 'Asia/Manila' };
  currentDate.value = new Date().toLocaleDateString('en-PH', options);
};

// Initialize Charts
const initCharts = () => {
  // Patient Demographics (Bar Chart)
  const demographicsCtx = document.getElementById('demographicsChart').getContext('2d');
  new Chart(demographicsCtx, {
    type: 'bar',
    data: {
      labels: ['Male', 'Female', 'Other'],
      datasets: [
        {
          label: 'Number of Patients',
          data: [50, 70, 10], // Replace with actual data
          backgroundColor: ['#42A5F5', '#66BB6A', '#FFA726'],
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: true,
          position: 'top',
        },
      },
      scales: {
        x: {
          grid: {
            display: false,
          },
        },
        y: {
          ticks: {
            beginAtZero: true,
          },
        },
      },
    },
  });

  // Appointments Overview (Line Chart)
  const appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
  new Chart(appointmentsCtx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
      , // Replace with actual months
      datasets: [
        {
          label: 'Appointments',
          data: [30, 50, 70, 60, 90], // Replace with actual data
          borderColor: '#42A5F5',
          backgroundColor: 'rgba(66, 165, 245, 0.2)',
          borderWidth: 2,
          tension: 0.3, // Smooth curve
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: true,
          position: 'top',
        },
      },
      scales: {
        x: {
          grid: {
            display: false,
          },
        },
        y: {
          ticks: {
            beginAtZero: true,
          },
        },
      },
    },
  });

  // Critical Cases (Line Chart)
  const criticalCasesCtx = document.getElementById('criticalCasesLineChart').getContext('2d');
  new Chart(criticalCasesCtx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
      , // Replace with actual months or dates
      datasets: [
        {
          label: 'Heart Attack',
          data: [5, 10, 8, 12, 15], // Replace with actual data for Heart Attack cases
          borderColor: '#FF5252', // Red color
          backgroundColor: 'rgba(255, 82, 82, 0.2)',
          borderWidth: 2,
          tension: 0.3, // Smooth curve
        },
        {
          label: 'Stroke',
          data: [3, 5, 7, 6, 9], // Replace with actual data for Stroke cases
          borderColor: '#FF9800', // Orange color
          backgroundColor: 'rgba(255, 152, 0, 0.2)',
          borderWidth: 2,
          tension: 0.3, // Smooth curve
        },
        {
          label: 'Diabetes',
          data: [2, 4, 5, 3, 7], // Replace with actual data for Diabetes cases
          borderColor: '#4CAF50', // Green color
          backgroundColor: 'rgba(76, 175, 80, 0.2)',
          borderWidth: 2,
          tension: 0.3, // Smooth curve
        },
        // You can add more datasets for other critical cases as needed
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: true,
          position: 'top',
        },
      },
      scales: {
        x: {
          grid: {
            display: false,
          },
        },
        y: {
          ticks: {
            beginAtZero: true,
          },
        },
      },
    },
  });

  const totalPatientsCtx = document.getElementById('totalPatientsChart').getContext('2d');
  new Chart(totalPatientsCtx, {
    type: 'bar',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
      , // Replace with the months or dates you have
      datasets: [
        {
          label: 'Total Patients',
          data: [200, 250, 300, 275, 350], // Replace with the actual total patients data for each month
          backgroundColor: '#66BB6A', // Green color for the bar
          borderColor: '#388E3C', // Dark green for border color
          borderWidth: 1,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: false, // Hides the legend since we only have one dataset
        },
      },
      scales: {
        x: {
          grid: {
            display: false, // Hides the grid lines for x-axis
          },
          ticks: {
            font: {
              size: 12,
            },
          },
        },
        y: {
          grid: {
            display: true, // Keeps the grid lines for y-axis
          },
          ticks: {
            beginAtZero: true, // Ensures the y-axis starts at 0
            stepSize: 50, // Adjust the step size for better readability
            font: {
              size: 12,
            },
          },
        },
      },
    },
  });
};

// Watch for changes in props
watch(() => props.latestPatients, (newVal) => {
  latestPatients.value = newVal || [];
}, { deep: true });

watch(() => props.ITRConsultation, (newVal) => {
  ITRConsultation.value = newVal || [];
}, { deep: true });

// Watch for changes in notifications
watch(() => props.notifications, (newVal) => {
  const oldNotifications = notifications.value;
  notifications.value = newVal || [];

  // Check for new notifications
  if (oldNotifications && newVal) {
    const newNotifications = newVal.filter(notification =>
      !notification.isRead &&
      !oldNotifications.some(old => old.id === notification.id)
    );

    // Show notification for each new patient
    newNotifications.forEach(notification => {
      //   // Create notification sound
      //   const audio = new Audio('/notification.mp3'); // Make sure to add this sound file
      //   audio.play().catch(e => console.log('Audio play failed:', e));

      // Show browser notification if permitted
      if (Notification.permission === "granted") {
        new Notification("New Patient in Queue", {
          body: notification.message,
          icon: "/icon.png" // Add your icon
        });
      }
    });
  }
}, { deep: true });

// Function to refresh data
const refreshData = () => {
  router.reload({ only: ['latestPatients', 'ITRConsultation', 'notifications'] });
};

onMounted(() => {
  updateDate();
  setInterval(updateDate, 1000);
  initCharts();

  // Request notification permission
  if (Notification.permission !== "granted" && Notification.permission !== "denied") {
    Notification.requestPermission();
  }

  // Set up periodic refresh
  setInterval(refreshData, 30000); // Check every 30 seconds

  // Initial refresh
  refreshData();
});
</script>
<template>

  <Head title="Initao RHU Dashboard" />

  <MainLayout>
    <div class="w-full min-h-screen">
      <!-- Branding Section -->
      <div
        class="flex items-center justify-between bg-gradient-to-r from-blue-500 to-green-400 px-8 py-6 text-white shadow-md ">
        <div>
          <h1 class="text-3xl font-semibold">Initao RHU Dashboard</h1>
          <p class="text-lg">Empowering community health with data-driven insights.</p>
        </div>
        <div class="text-right">
          <p class="text-lg font-semibold">Date: {{ currentDate }}</p>
        </div>
      </div>

      <!-- Stats Section -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 my-10 px-8">
        <!-- Total Patients -->
        <div
          class="bg-gradient-to-br from-green-100 to-green-300 text-green-800 hover:shadow-lg p-6 rounded-xl shadow-md">
          <div class="flex flex-col items-start gap-4">
            <div class="flex justify-between w-full">
              <h2 class="font-semibold text-lg">Total Patients Checked Up</h2>
              <Link href="/services/patients" class="bg-green-500 text-white rounded px-4 py-2 shadow hover:opacity-90"
                aria-label="View all checked-up patients">
              View
              </Link>
            </div>
            <div class="flex items-center">
              <font-awesome-icon :icon="['fas', 'user-check']" class="mr-2 text-2xl text-green-600" />
              <p class="text-3xl font-bold">{{ totalPatients }}</p>
            </div>
          </div>
        </div>

        <div
          class="bg-gradient-to-br from-yellow-100 to-yellow-300 text-yellow-800 hover:shadow-lg p-6 rounded-xl shadow-md">
          <div class="flex flex-col items-start gap-4">
            <div class="flex justify-between w-full">
              <h2 class="font-semibold text-lg">Patients in Queue</h2>
              <Link href="/services/queue" class="bg-yellow-500 text-white rounded px-4 py-2 shadow hover:opacity-90"
                aria-label="View all patients in queue">
              View
              </Link>
            </div>
            <div class="flex items-center">
              <font-awesome-icon :icon="['fas', 'users']" class="mr-2 text-2xl text-yellow-600" />
              <p class="text-3xl font-bold">{{ ITRConsultation.length }}</p>
            </div>
          </div>
        </div>


        <div class="bg-gradient-to-br from-blue-100 to-blue-300 text-blue-800 hover:shadow-lg p-6 rounded-xl shadow-md">
          <div class="flex flex-col items-start gap-4">
            <div class="flex justify-between w-full">
              <h2 class="font-semibold text-lg">Today's Consultation</h2>
              <Link href="/services/appointments"
                class="bg-blue-500 text-white rounded px-4 py-2 shadow hover:opacity-90"
                aria-label="View today's consultations">
              View
              </Link>
            </div>
            <div class="flex items-center">
              <font-awesome-icon :icon="['fas', 'calendar-day']" class="mr-2 text-2xl text-blue-600" />
              <p class="text-3xl font-bold">{{ todayAppointments }}</p>
            </div>
          </div>
        </div>


        <!-- Critical Cases -->
        <div class="bg-gradient-to-br from-red-100 to-red-300 text-red-800 hover:shadow-lg p-6 rounded-xl shadow-md">
          <div class="flex flex-col items-start gap-4">
            <div class="flex justify-between w-full">
              <h2 class="font-semibold text-lg">Critical Cases</h2>
              <Link href="/services/critical" class="bg-red-500 text-white rounded px-4 py-2 shadow hover:opacity-90"
                aria-label="View critical cases">
              View
              </Link>
            </div>
            <div class="flex items-center">
              <font-awesome-icon :icon="['fas', 'heartbeat']" class="mr-2 text-2xl text-red-600" />
              <p class="text-3xl font-bold">{{ criticalCases }}</p>
            </div>
          </div>
        </div>

      </div>

      <!-- Patients Section -->
      <div class="px-8 mb-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


          <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Patients in Queue</h2>
            <input type="text" v-model="searchQueue" placeholder="Search for a patient..."
              class="w-full border border-gray-300 rounded-lg px-4 py-2 mb-4" />
            <div v-if="paginatedPatients.length" class="space-y-4 overflow-hidden">
              <div v-for="(patient, index) in paginatedPatients" :key="index"
                class="p-4 bg-gray-50 rounded-lg shadow-md hover:bg-gray-100 transition">
                <h3 class="text-lg font-semibold text-gray-700">
                  {{ patient.firstName }} {{ patient.lastName }}
                </h3>
                <p class="text-sm text-gray-500">Age: {{ patient.age }}</p>
                <p class="text-sm text-gray-500">Reason: {{ patient.visitType }}</p>
                <button @click="startCheckup(patient)" :class="[
                  'mt-3 text-white font-semibold py-2 px-4 rounded-lg transition',
                  patient.visitType === 'Prenatal' ? 'bg-pink-500 hover:bg-pink-600' : 'bg-green-500 hover:bg-green-600'
                ]">
                  {{ patient.visitType === 'Prenatal' ? 'Start Prenatal Checkup' : 'Start General Checkup' }}
                </button>

              </div>
            </div>
            <p v-else class="text-center text-gray-500">No patients in queue.</p>

            <!-- Pagination Controls -->
            <div v-if="paginatedPatients.length" class="flex justify-center items-center mt-4 space-x-2">
              <button @click="prevPage" :disabled="currentPage === 1"
                class="px-4 py-2 bg-green-600 text-white-800 rounded-lg hover:bg-green-400 disabled:bg-green-200 disabled:text-black-400">
                Previous
              </button>
              <span class="text-gray-700">Page {{ currentPage }} of {{ totalPages }}</span>
              <button @click="nextPage" :disabled="currentPage === totalPages"
                class="px-4 py-2 bg-green-600 text-white-800 rounded-lg hover:bg-green-400 disabled:bg-green-200 disabled:text-black-400">
                Next
              </button>
            </div>
          </div>



          <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Latest Consultations</h2>
            <div class="overflow-hidden">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Visit Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="patient in paginatedLatestPatients"
                    :key="patient.visitType === 'Prenatal' ? patient.prenatalConsultationDetailsID : patient.consultationDetailsID">
                    <td class="px-6 py-4 whitespace-nowrap">
                      {{ patient.firstName }} {{ patient.lastName }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      {{ patient.visitType }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      {{ formatDate(patient.consultationDate) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      {{ patient.consultationTime }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <button  @click="viewPatientDetails(patient)"
                        class=" textcolor text-black-600 hover:text-indigo-900">
                        View Details
                      </button>
                    </td>
                  </tr>
                  <tr v-if="latestPatients.length === 0">
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                      No completed consultations yet
                    </td>
                  </tr>
                </tbody>
              </table>
              <!-- Prenatal Modal -->
              <PrenatalModal v-if="currentModal === 'prenatal'" :show-modal="showModal"
                :selected-patient="selectedPatient" @close="closeModal" />

              <!-- General Modal -->
              <ViewPatientModalGeneral v-if="currentModal === 'general'" :show-modal="showModal"
                :selected-patient="selectedPatient" @close="closeModal" />
            </div>

            <!-- Pagination Controls -->
            <div v-if="paginatedLatestPatients.length" class="flex justify-center items-center mt-4 space-x-2">
              <button @click="prevLatestPage" :disabled="currentLatestPage === 1"
                class="px-4 py-2 bg-green-600 text-white-800 rounded-lg hover:bg-green-400 disabled:bg-green-200 disabled:text-black-400">
                Previous
              </button>
              <span class="text-black-700">Page {{ currentLatestPage }} of {{ totalLatestPages }}</span>
              <button @click="nextLatestPage" :disabled="currentLatestPage === totalLatestPages"
                class="px-4 py-2 bg-green-600 text-white-800 rounded-lg hover:bg-green-400 disabled:bg-green-200 disabled:text-black-400">
                Next
              </button>
            </div>
          </div>

        </div>
      </div>

      <!-- Charts Section -->
      <div class="px-8 mb-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Patient Demographics</h2>
            <canvas id="demographicsChart"></canvas>
          </div>
          <!-- Critical Cases Line Chart -->
          <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Critical Cases Overview</h2>
            <canvas id="criticalCasesLineChart"></canvas>
          </div>

          <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Consultation Overview</h2>
            <canvas id="appointmentsChart"></canvas>
          </div>

          <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Total Patients</h2>
            <canvas id="totalPatientsChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Notifications Section -->
      <div v-if="showNotifications" class="fixed inset-0 bg-black bg-opacity-50 flex justify-end z-50">
        <div class="w-96 bg-white h-screen overflow-hidden">
          <div class="p-4 bg-blue-500 text-white flex justify-between items-center">
            <h3 class="text-lg font-semibold">Notifications</h3>
            <button @click="showNotifications = false" class="text-2xl">&times;</button>
          </div>
          <div class="p-4 h-[calc(100vh-4rem)] overflow-y-auto no-scrollbar">
            <div v-if="notifications.length === 0" class="text-gray-500 text-center py-4">
              No new notifications
            </div>
            <template v-else>
              <div v-for="notification in notifications" :key="notification.id"
                class="mb-4 p-4 bg-white border rounded-lg shadow-sm hover:shadow-md transition-shadow cursor-pointer"
                :class="{ 'bg-blue-50': !notification.isRead }" @click="markAsRead(notification)">
                <div class="flex items-start">
                  <div class="flex-1">
                    <p class="font-medium text-gray-800">{{ notification.message }}</p>
                    <p class="text-sm text-gray-500">{{ formatNotificationDate(notification.time) }}</p>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </div>
      </div>

      <button @click="showNotifications = !showNotifications"
        class="fixed bottom-4 right-4 bg-yellow-500 text-white font-semibold px-5 py-3 rounded-full shadow-lg hover:bg-yellow-600">
        Notifications ({{ unreadNotifications }})
      </button>
      <button @click="showNotifications = !showNotifications"
        class="relative p-2 text-white hover:bg-blue-600 rounded-full transition-colors fixed top-4 right-4">
        <font-awesome-icon :icon="['fas', 'bell']" class="text-xl" />
        <span v-if="unreadNotifications > 0"
          class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
          {{ unreadNotifications }}
        </span>
      </button>
    </div>
  </MainLayout>
</template>
<style scoped>
button {
  color: white;
}

.textcolor {
  color: green;
}

.bg-gradient-to-r {
  background: linear-gradient(to right, var(--tw-gradient-stops));
}

/* Hide scrollbar for Chrome, Safari and Opera */
.no-scrollbar::-webkit-scrollbar {
  display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.no-scrollbar {
  -ms-overflow-style: none;
  /* IE and Edge */
  scrollbar-width: none;
  /* Firefox */
}
</style>
