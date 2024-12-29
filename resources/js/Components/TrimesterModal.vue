<template>
  <div class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50" @click.self="close">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl max-h-screen overflow-y-auto"> 
      
      <!-- Step Indicator -->
      <div class="flex justify-between items-center px-6 py-3 bg-green-100 rounded-t-lg">
        <div class="flex items-center space-x-2 text-green-700 font-semibold">
          <div class="w-5 h-5 rounded-full" :class="step === 1 ? 'bg-green-600' : 'bg-green-300'"></div>
          <span>Step 1: Select Trimester</span>
          <div v-if="step >= 2" class="ml-2 flex items-center space-x-2">
            <div class="w-5 h-5 rounded-full" :class="step === 2 ? 'bg-green-600' : 'bg-green-300'"></div>
            <span>Step 2: Fill Out Form</span>
          </div>
          <div v-if="step === 3" class="ml-2 flex items-center space-x-2">
            <div class="w-5 h-5 rounded-full bg-green-600"></div>
            <span>Step 3: Submission Complete</span>
          </div>
        </div>
      </div> 

      <!-- Header with Back Button -->
      <header class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">
          <span v-if="step === 1">Select Trimester</span>
          <span v-else-if="step === 2">Trimester {{ selectedTrimester }} Form</span>
          <span v-else>Trimester Submitted!</span>
        </h3>
        <button v-if="step === 2" @click="goBack" class="flex items-center text-green-600 hover:text-green-800">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Back
        </button>
      </header>

      <!-- Modal Body -->
      <main class="px-6 py-6 text-gray-700 overflow-y-auto max-h-[70vh]"> 
        <!-- Step 1: Select Trimester -->
        <div v-if="step === 1">
          <p>Select the appropriate trimester options here:</p>
          <select v-model="selectedTrimester" @change="onTrimesterSelect" class="mt-4 border rounded-md w-full p-2">
            <option disabled value="">-- Please choose a trimester --</option>
            <option v-for="(trimester, index) in trimesters" :key="index" :value="trimester">
              {{ trimester }}
            </option>
          </select>
        </div>

        <!-- Step 2: Show Selected Trimester Form -->
        <div v-else-if="step === 2">
          <component 
            :is="formComponent" 
            :prenatalId="prenatalId"
            :trimester="getTrimesterNumber(selectedTrimester)"
            :prefilledData="internalPrefilledData"
            @submit="handleFormSubmit"
            @back="goBack"
          />
        </div>
        <!-- Step 3: Submission Complete Message -->
        <div v-else-if="step === 3" class="text-center">
          <h2 class="text-xl font-semibold text-green-600">Trimester Submitted!</h2>
          <p class="mt-4">Thank you for submitting your trimester information. You can close this window or start again.</p>
        </div>
      </main>

      <!-- Footer Buttons -->
      <footer class="flex justify-end px-6 py-4 border-t border-gray-200">
        <button v-if="step === 1" @click="confirmSelection" :disabled="!selectedTrimester" class="bg-green-600 text-white px-4 py-2 rounded-md mr-2 hover:bg-green-700">
          Next
        </button>
        <!--<button v-if="step === 2" @click="submitForm" type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md mr-2 hover:bg-green-700">
          Submit
        </button>-->
        <button @click="close" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">Close</button>
      </footer>
    </div>
  </div>
</template>

<script setup>
import { ref, defineAsyncComponent, computed, watch, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
  prenatalId: {
    type: Number,
    required: true
  },
  prefilledData: {
    type: Object,
    default: null
  },
  onClose: {
    type: Function,
    required: true
  },
  onConfirm: {
    type: Function,
    required: true
  }
});

const emit = defineEmits(['close']);

const trimesters = ref([
  'Trimester 1',
  'Trimester 2',
  'Trimester 3',
  'Trimester 4',
  'Trimester 5'
]);

const selectedTrimester = ref('');
const step = ref(1);
const formData = ref({});
const internalPrefilledData = ref(null);

const formComponent = computed(() => {
  switch (selectedTrimester.value) {
    case 'Trimester 1':
      return defineAsyncComponent(() => import('@/Components/Trimester/TrimesterOneForm.vue'));
    case 'Trimester 2':
      return defineAsyncComponent(() => import('@/Components/Trimester/TrimesterTwoForm.vue'));
    case 'Trimester 3':
      return defineAsyncComponent(() => import('@/Components/Trimester/TrimesterThreeForm.vue'));
    case 'Trimester 4':
      return defineAsyncComponent(() => import('@/Components/Trimester/TrimesterFourForm.vue'));
    case 'Trimester 5':
      return defineAsyncComponent(() => import('@/Components/Trimester/TrimesterFiveForm.vue'));
    default:
      return null;
  }
});

const getTrimesterNumber = (trimester) => {
  return trimester ? trimester.replace('Trimester ', '') : '';
};

const handleFormSubmit = (formData) => {
  console.log('Form submitted:', formData);
  props.onConfirm(formData);
};

const onTrimesterSelect = () => {
  console.log('Selected trimester:', selectedTrimester.value);
};

const confirmSelection = async () => {
  if (selectedTrimester.value) {
    const trimesterNumber = getTrimesterNumber(selectedTrimester.value);
    try {
      const response = await axios.get(`/prenatal/${props.prenatalId}/trimester/${trimesterNumber}`);
      console.log("API Response:", response.data);
      
      if (response.data.success) {
        internalPrefilledData.value = response.data.data;
        step.value = 2;
      } else {
        console.error("Error:", response.data.message);
        alert("Failed to load trimester data. Please try again.");
      }
    } catch (error) {
      console.error("Error fetching trimester data:", error);
      alert("An unexpected error occurred. Please try again.");
    }
  } else {
    alert("Please select a trimester first.");
  }
};

const goBack = () => {
  step.value = 1;
  internalPrefilledData.value = null;
};

const close = () => {
  emit('close');
};

watch(() => props.prefilledData, (newVal) => {
  internalPrefilledData.value = newVal;
}, { immediate: true });

onMounted(() => {
  console.log("Received prenatalId in TrimesterModal:", props.prenatalId);
});
</script>
