<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted, watch } from 'vue';
import Chart from 'chart.js/auto';
import { format } from 'date-fns';
import ViewPatientModalGeneral from '@/Components/Modals/ViewPatientModalGeneral.vue';
import PrenatalModal from '@/Components/Modals/ViewPatientModalPrenatal.vue';

// Props from backend
const props = defineProps({
  totalPatients: Number,
  totalPatient: Array,
  ITRConsultation: Array,
  latestPatients: Array,
  todaysConsultation: Number,
  notifications: Array,
  allDates: Array,
  maleCount: Number,
  femaleCount: Number,
  generalConsultations: Array,
  prenatalConsultations: Array,
  chartData: Object
});

// Reactive states
const totalPatients = ref(props.totalPatients || 0);
const ITRConsultation = ref(props.ITRConsultation || []);
const latestPatients = ref(props.latestPatients || []);
const todaysConsultation = ref(props.todaysConsultation || 0);
const criticalCases = ref(props.criticalCases || 0);
const notifications = ref(props.notifications || []);
const currentPage = ref(1); // Initialize currentPage
const currentLatestPage = ref(1); // Initialize currentLatestPage
const itemsPerPage = ref(5); // Set the number of items per page
const showNotifications = ref(false);
const selectedPatient = ref(null); // Stores the selected patient data
const currentModal = ref(null); // Stores the current modal type (e.g., 'prenatal', 'general')
const showModal = ref(false); // Controls modal visibility

const totalPatientsData = ref([]); // Initialize as empty array

// Ensure totalPatient is converted into an array if it's a Number
if (Array.isArray(props.totalPatient)) {
  totalPatientsData.value = props.totalPatient;
} else {
  // If it's a single number, duplicate it for all months
  totalPatientsData.value = Array(12).fill(props.totalPatient);
}

const searchQueue = ref('');
const unreadNotifications = computed(() =>
  notifications.value.filter(n => !n.isRead).length
);

const formattedDates = props.allDates.map(item => {
  const date = new Date(item.date);
  const month = new Intl.DateTimeFormat('en-US', { month: 'long' }).format(date);
  return {
    formattedDate: month // This will return the month name, like "January"
  };
});

console.log(formattedDates);



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

// Total pages for latest patients
const totalLatestPages = computed(() => {
  return Math.ceil(props.latestPatients.length / itemsPerPage.value);
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

const nextLatestPage = () => {
  if (currentLatestPage.value < totalLatestPages.value) {
    currentLatestPage.value++;
  }
};

const prevLatestPage = () => {
  if (currentLatestPage.value > 1) {
    currentLatestPage.value--;
  }
};

// Format date for notifications
const formatNotificationDate = (time) => {
  const date = new Date(time);
  return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};
// Computed property for filtered patients in queue
const filteredITRConsultation = computed(() => {
  if (!searchQueue.value.trim()) return ITRConsultation.value;

  // Normalize the search input: trim spaces and collapse multiple spaces
  const normalizedSearch = searchQueue.value.trim().replace(/\s+/g, ' ').toLowerCase();

  return ITRConsultation.value.filter((patient) => {
    const firstName = patient.firstName.toLowerCase();
    const lastName = patient.lastName.toLowerCase();
    const visitType = patient.visitType.toLowerCase();

    return (
      firstName.includes(normalizedSearch) ||
      lastName.includes(normalizedSearch) ||
      visitType.includes(normalizedSearch)
    );
  });
});

// Computed property for paginated patients
const paginatedLatestPatients = computed(() => {
  const start = (currentLatestPage.value - 1) * itemsPerPage.value;
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

console.log('ITRConsultation:', props.ITRConsultation);
console.log('Latest Patients:', props.latestPatients);


const markAsCancelled = (patient) => {
  const data = patient.visitType === 'Prenatal' 
    ? { prenatalConsultationDetailsID: patient.prenatalConsultationDetailsID }
    : { consultationDetailsID: patient.consultationDetailsID };

  router.post('/doctor/mark-as-cancelled', data, {
    onSuccess: () => {
      // Refresh the data after successful cancellation
      refreshData();
    }
  });
};

onMounted(() => {
    console.log("Chart Data:", props.chartData);

    if (!props.chartData || !props.chartData.datasets) {
        console.error("Chart data is missing or incorrectly formatted!");
        return;
    }

    const ctx = document.getElementById('criticalCasesBarChart')?.getContext('2d');
    if (!ctx) {
        console.error("Canvas not found for chart!");
        return;
    }

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: props.chartData.labels,
            datasets: props.chartData.datasets, 
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false, 
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let dataset = context.dataset;
                            let monthIndex = context.dataIndex;
                            let diagnosisName = dataset.diagnosis[monthIndex] || 'N/A'; 
                            let count = context.raw; 

                            return `${diagnosisName}: ${count}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                    },
                    barPercentage: 1.3, 
                    categoryPercentage: 1.0, 
                },
                y: {
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1,
                        precision: 1.0,
                    },
                },
            },
            elements: {
                bar: {
                    barThickness: 50, 
                    maxBarThickness: 80, 
                },
            },
        },
    });
});

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
      labels: ['Male', 'Female'],
      datasets: [
        {
          label: 'Number of Patients',
          data: [props.maleCount, props.femaleCount],
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
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], 
      datasets: [
        {
          label: 'General Consultations',
          data: props.generalConsultations, // Replace with actual data
          borderColor: '#42A5F5',
          backgroundColor: 'rgba(66, 165, 245, 0.2)',
          borderWidth: 2,
          tension: 0.3, // Smooth curve
        },
        {
          label: 'Prenatal Consultations', // New line graph
          data: props.prenatalConsultations, // Replace with actual data
          borderColor: '#FF5252', // Different color for distinction
          backgroundColor: 'rgba(255, 82, 82, 0.2)',
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
            stepSize: 1,
            precision: 1.0
          },
        },
      },
    },
  });


  // // Critical Cases (Line Chart)
  // const criticalCasesCtx = document.getElementById('criticalCasesLineChart').getContext('2d');
  // new Chart(criticalCasesCtx, {
  //   type: 'line',
  //   data: {
  //     labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
  //     , // Replace with actual months or dates
  //     datasets: [
  //       {
  //         label: 'Heart Attack',
  //         data: [5, 10, 8, 12, 15], // Replace with actual data for Heart Attack cases
  //         borderColor: '#FF5252', // Red color
  //         backgroundColor: 'rgba(255, 82, 82, 0.2)',
  //         borderWidth: 2,
  //         tension: 0.3, // Smooth curve
  //       },
  //       {
  //         label: 'Stroke',
  //         data: [3, 5, 7, 6, 9], // Replace with actual data for Stroke cases
  //         borderColor: '#FF9800', // Orange color
  //         backgroundColor: 'rgba(255, 152, 0, 0.2)',
  //         borderWidth: 2,
  //         tension: 0.3, // Smooth curve
  //       },
  //       {
  //         label: 'Diabetes',
  //         data: [2, 4, 5, 3, 7], // Replace with actual data for Diabetes cases
  //         borderColor: '#FFD700', // Green color
  //         backgroundColor: 'rgba(76, 175, 80, 0.2)',
  //         borderWidth: 2,
  //         tension: 0.3, // Smooth curve
  //       },
  //       // You can add more datasets for other critical cases as needed
  //     ],
  //   },
  //   options: {
  //     responsive: true,
  //     plugins: {
  //       legend: {
  //         display: true,
  //         position: 'top',
  //       },
  //     },
  //     scales: {
  //       x: {
  //         grid: {
  //           display: false,
  //         },
  //       },
  //       y: {
  //         ticks: {
  //           beginAtZero: true,
  //         },
  //       },
  //     },
  //   },
  // });

  //New Patients (Bar Chart)
  const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
  const monthCounts = months.map(month => {
    return formattedDates.filter(item => item.formattedDate === month).length;
  });

  const totalPatientsCtx = document.getElementById('totalPatientsChart').getContext('2d');

  new Chart(totalPatientsCtx, {
    type: 'bar',
    data: {
      labels: months, // Month names as labels
      datasets: [
        {
          label: 'Total Patients',
          data: monthCounts, // The count of patients per month
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
          beginAtZero: true,
          suggestedMax: Math.max(...totalPatientsData.value) + 10,
          ticks: {
            stepSize: 10,
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

// Reset pages when search changes
watch(searchQueue, () => {
  currentPage.value = 1;
  currentLatestPage.value = 1;
});

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
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 my-10 px-8">
        <!-- Total Patients -->
        <div
          class="bg-gradient-to-br from-green-100 to-green-300 text-green-800 hover:shadow-lg p-6 rounded-xl shadow-md">
          <div class="flex flex-col items-start gap-4">
            <div class="flex justify-between w-full">
              <h2 class="font-semibold text-lg">Total Patients</h2>
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

        <!-- <div
          class="bg-gradient-to-br from-yellow-100 to-yellow-300 text-yellow-800 hover:shadow-lg p-6 rounded-xl shadow-md">
          <div class="flex flex-col items-start gap-4">
            <div class="flex justify-between w-full">
              <h2 class="font-semibold text-lg">Patients in Queue</h2>
            </div>
            <div class="flex items-center">
              <font-awesome-icon :icon="['fas', 'users']" class="mr-2 text-2xl text-yellow-600" />
              <p class="text-3xl font-bold">{{ ITRConsultation.length }}</p>
            </div>
          </div>
        </div> -->


        <div class="bg-gradient-to-br from-blue-100 to-blue-300 text-blue-800 hover:shadow-lg p-6 rounded-xl shadow-md">
          <div class="flex flex-col items-start gap-4">
            <div class="flex justify-between w-full">
              <h2 class="font-semibold text-lg">Today's Consultation</h2>
              <Link href="/services/consultation"
                class="bg-blue-500 text-white rounded px-4 py-2 shadow hover:opacity-90"
                aria-label="View today's consultations">
              View
              </Link>
            </div>
            <div class="flex items-center">
              <font-awesome-icon :icon="['fas', 'calendar-day']" class="mr-2 text-2xl text-blue-600" />
              <p class="text-3xl font-bold">{{ todaysConsultation }}</p>
            </div>
          </div>
        </div>


        <!-- Critical Cases -->
        <!-- <div class="bg-gradient-to-br from-red-100 to-red-300 text-red-800 hover:shadow-lg p-6 rounded-xl shadow-md">
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
        </div> -->

      </div>

      <!-- Patients Section -->
      <div class="px-8 mb-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


          <div class="bg-white rounded-2xl shadow-xl p-8">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-3xl font-bold text-gray-800">🩺 Patients in Queue</h2>
              <span class="text-sm text-gray-500 italic">{{ paginatedPatients.length }} patient(s) in queue</span>
            </div>

            <!-- Search Input -->
            <div class="relative mb-6">
              <input type="text" v-model="searchQueue" placeholder="🔍 Search for a patient..."
                class="w-full px-5 py-3 border border-gray-300 rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400 transition duration-200 placeholder-gray-400" />
              <svg xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18a7.5 7.5 0 006.15-3.35z" />
              </svg>
            </div>

            <!-- Patient Queue List -->
            <div v-if="paginatedPatients.length" class="space-y-4">
              <div v-for="(patient, index) in paginatedPatients" :key="index" :class="[
                'flex items-center justify-between border rounded-xl p-4 shadow-sm transition',
                patient.status === 'Cancelled' ? 'bg-red-100 border-red-300' : 'bg-gray-50 border-gray-200 hover:bg-gray-100'
              ]">

                <!-- Left: Queue Number and Patient Info -->
                <div class="flex items-center gap-4">
                  <!-- Queue Number -->
                  <div
                    class="flex items-center justify-center w-12 h-12 bg-green-100 text-green-800 font-bold text-lg rounded-full">
                    {{ ((currentPage - 1) * itemsPerPage) + index + 1 }}
                  </div>

                  <!-- Patient Info -->
                  <div>
                    <h3 class="text-lg font-semibold text-gray-700">
                      {{ patient.firstName }} {{ patient.lastName }}
                    </h3>
                    <p class="text-sm text-gray-500">Age: {{ patient.age }} | Reason: {{ patient.visitType }}</p>
                    <p class="text-xs text-gray-400">🕒 Queued In: {{ patient.consultationTime }}</p>

                    <!-- Patient Status -->
                    <p v-if="patient.status === 'Cancelled'" class="text-xs text-red-600 font-semibold mt-1">❌ Cancelled
                    </p>
                  </div>
                </div>

                <!-- Right: Action Buttons -->
                <div class="flex gap-2">
                  <!-- Start Checkup Button -->
                  <button @click="startCheckup(patient)" :disabled="patient.status === 'Cancelled'" :class="[
                    'px-5 py-2 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition',
                    patient.status === 'Cancelled' ? 'bg-gray-400 cursor-not-allowed' :
                      patient.visitType === 'Prenatal' ? 'bg-pink-500 hover:bg-pink-600' : 'bg-green-500 hover:bg-green-600'
                  ]">
                    {{ patient.visitType === 'Prenatal' ? 'Start Prenatal' : 'Start Checkup' }}
                  </button>

                  <!-- Mark as Cancelled Button -->
                  <button @click="markAsCancelled(patient)" :disabled="patient.status === 'Cancelled'"
                    class="px-4 py-2 bg-red-500 text-white text-sm font-semibold rounded-lg shadow-md hover:bg-red-600 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                    Cancel
                  </button>
                </div>
              </div>
            </div>

            <!-- No Patients Message -->
            <p v-else class="text-center text-gray-500 mt-4">No patients in queue.</p>

            <!-- Pagination Controls -->
            <div v-if="paginatedPatients.length" class="flex justify-center items-center mt-6 space-x-3">
              <button @click="prevPage" :disabled="currentPage === 1"
                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 disabled:bg-gray-200 disabled:cursor-not-allowed">
                Previous
              </button>

              <span class="text-gray-600">Page {{ currentPage }} of {{ totalPages }}</span>

              <button @click="nextPage" :disabled="currentPage === totalPages"
                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 disabled:bg-gray-200 disabled:cursor-not-allowed">
                Next
              </button>
            </div>
          </div>



          <div class="bg-white rounded-2xl shadow-xl p-8 mb-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-3xl font-bold text-gray-800">🩺 Latest Consultations</h2>
              <span class="text-sm text-gray-500 italic">{{ paginatedLatestPatients.length }} consultation(s)</span>
            </div>

            <!-- Consultation Table -->
            <div class="grid grid-cols-1 gap-4">
              <div v-for="patient in paginatedLatestPatients"
                :key="patient.visitType === 'Prenatal' ? patient.prenatalConsultationDetailsID : patient.consultationDetailsID"
                class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-xl shadow-md hover:shadow-lg transition duration-300">

                <!-- Patient Info -->
                <div class="flex justify-between items-center">
                  <div>
                    <h3 class="text-xl font-semibold text-gray-800">
                      {{ patient.firstName }} {{ patient.lastName }}
                    </h3>
                    <p class="text-sm text-gray-600">
                      📅 {{ formatDate(patient.consultationDate) }} | 🕒 {{ patient.consultationTime }}
                    </p>
                    <p class="mt-1">
                      <span :class="[
                        'inline-block px-3 py-1 rounded-full text-xs font-medium',
                        patient.visitType === 'Prenatal' ? 'bg-pink-100 text-pink-600' : 'bg-blue-100 text-blue-600'
                      ]">
                        {{ patient.visitType }}
                      </span>
                    </p>
                  </div>

                  <!-- Action Buttons -->
                  <div class="flex space-x-3">
                    <button @click="viewPatientDetails(patient)"
                      class="flex items-center gap-2 px-4 py-2 bg-indigo-500 text-white rounded-lg shadow hover:bg-indigo-600 transition">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12H9m0 0H5m4 0h4m0 0h4m0 0a9 9 0 11-9 9 9 9 0 019-9z" />
                      </svg>
                      View Details
                    </button>

                    <button @click="printPatientDetails(patient)"
                      class="flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg shadow hover:bg-green-600 transition">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 9V2h12v7m-6 5v6m0 0h4m-4 0H8" />
                      </svg>
                      Print Record
                    </button>
                  </div>
                </div>
              </div>

              <!-- Empty State -->
              <div v-if="latestPatients.length === 0"
                class="text-gray-500 text-center py-8 bg-gray-50 rounded-lg shadow">
                No completed consultations yet.
              </div>
            </div>

            <!-- Pagination Controls -->
            <div v-if="paginatedLatestPatients.length" class="flex justify-center items-center mt-6 space-x-3">
              <button @click="prevLatestPage" :disabled="currentLatestPage === 1"
                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 disabled:bg-gray-200 disabled:cursor-not-allowed">
                Previous
              </button>

              <span class="text-gray-600 font-semibold">Page {{ currentLatestPage }} of {{ totalLatestPages }}</span>

              <button @click="nextLatestPage" :disabled="currentLatestPage === totalLatestPages"
                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 disabled:bg-gray-200 disabled:cursor-not-allowed">
                Next 
              </button>
            </div>

            <!-- Modals -->
            <PrenatalModal v-if="currentModal === 'prenatal'" :show-modal="showModal"
              :selected-patient="selectedPatient" @close="closeModal" />
            <ViewPatientModalGeneral v-if="currentModal === 'general'" :show-modal="showModal"
              :selected-patient="selectedPatient" @close="closeModal" />
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
            <canvas id="criticalCasesBarChart"></canvas>
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
