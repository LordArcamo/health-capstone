<template>
  <div>
    <!-- Modal Overlay -->
    <div
      class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 p-4"
    >
      <!-- Modal Content -->
      <div class="bg-white shadow-md rounded-lg p-8 max-w-4xl w-full relative">
        <!-- Close Button -->
        <button
          @click="closeModal"
          class="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
        >
          ✕
        </button>

        <!-- Form -->
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Step Navigation -->
          <div class="flex justify-between items-center pb-4 border-b">
            <button
              v-for="(step, index) in steps"
              :key="index"
              type="button"
              class="px-4 py-2 rounded-md text-sm font-semibold"
              :class="{
                'bg-blue-600 text-white': currentStep === index,
                'bg-gray-200 text-gray-800': currentStep !== index,
              }"
              @click="currentStep = index"
            >
              {{ step.title }}
            </button>
          </div>

          <!-- Dynamic Step Content -->
          <div v-if="currentStep === 0" class="mt-6 space-y-4">
            <h2 class="text-lg font-semibold">Prenatal Outcome</h2>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                <input
                  v-model="form.last_name"
                  type="text"
                  id="last_name"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                />
              </div>
              <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                <input
                  v-model="form.first_name"
                  type="text"
                  id="first_name"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                />
              </div>
              <div>
                <label for="middle_name" class="block text-sm font-medium text-gray-700">Middle Name</label>
                <input
                  v-model="form.middle_name"
                  type="text"
                  id="middle_name"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                />
              </div>
              <div>
                <label for="sex" class="block text-sm font-medium text-gray-700">Sex (M / F)</label>
                <select
                  v-model="form.sex"
                  id="sex"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                >
                  <option value="">Select</option>
                  <option value="M">Male</option>
                  <option value="F">Female</option>
                </select>
              </div>
            </div>
          </div>

          <div v-if="currentStep === 1" class="mt-6 space-y-4">
            <h2 class="text-lg font-semibold">Delivery Details</h2>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label for="birth_length" class="block text-sm font-medium text-gray-700">Birth Length</label>
                <input
                  v-model="form.birth_length"
                  type="number"
                  step="0.01"
                  id="birth_length"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                />
              </div>
              <div>
                <label for="birth_weight" class="block text-sm font-medium text-gray-700">Birth Weight</label>
                <input
                  v-model="form.birth_weight"
                  type="number"
                  step="0.01"
                  id="birth_weight"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                />
              </div>
              <div>
                <label for="delivery_date" class="block text-sm font-medium text-gray-700">Delivery Date</label>
                <input
                  v-model="form.delivery_date"
                  type="date"
                  id="delivery_date"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                />
              </div>
              <div>
                <label for="delivery_time" class="block text-sm font-medium text-gray-700">Delivery Time</label>
                <input
                  v-model="form.delivery_time"
                  type="time"
                  id="delivery_time"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                />
              </div>
            </div>
          </div>

          <div v-if="currentStep === 2" class="mt-6 space-y-4">
      <h2 class="text-lg font-semibold">Breastfeeding</h2>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="date_initiated_breastfeeding" class="block text-sm font-medium text-gray-700">Date Initiated Breastfeeding</label>
          <input v-model="form.date_initiated_breastfeeding" type="date" id="date_initiated_breastfeeding" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>
        <div>
          <label for="time_initiated_breastfeeding" class="block text-sm font-medium text-gray-700">Time Initiated Breastfeeding</label>
          <input v-model="form.time_initiated_breastfeeding" type="time" id="time_initiated_breastfeeding" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>
      </div>
    </div>

    <div v-if="currentStep === 3" class="mt-6 space-y-4">
      <h2 class="text-lg font-semibold">Postpartum Details</h2>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="date_vitamin_a" class="block text-sm font-medium text-gray-700">Date Vitamin A Given</label>
          <input v-model="form.date_vitamin_a" type="date" id="date_vitamin_a" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>
        <div>
          <label for="danger_signs_mother" class="block text-sm font-medium text-gray-700">Danger Signs (Mother)</label>
          <textarea v-model="form.danger_signs_mother" id="danger_signs_mother" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
        </div>
      </div>
    </div>


          <!-- Navigation Buttons -->
          <div class="flex justify-between items-center pt-4 border-t">
            <button
              type="button"
              class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md"
              :disabled="currentStep === 0"
              @click="currentStep--"
            >
              Previous
            </button>
            <button
              v-if="currentStep < steps.length - 1"
              type="button"
              class="px-4 py-2 bg-blue-600 text-white rounded-md"
              @click="currentStep++"
            >
              Next
            </button>
            <button
              v-else
              type="submit"
              class="px-4 py-2 bg-green-600 text-white rounded-md"
            >
              Submit
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    patient: {
      type: Object,
      required: true,
    },
    onSubmit: {
      type: Function,
      required: true,
    },
  },
  data() {
    return {
      steps: [
        { title: "Prenatal Outcome" },
        { title: "Delivery Details" },
        { title: "Breastfeeding" },
        { title: "Postpartum Details" },
      ],
      currentStep: 0,
      form: {
        last_name: '',
        first_name: '',
        middle_name: '',
        sex: '',
        birth_length: '',
        birth_weight: '',
        delivery_date: '',
        delivery_time: '',
        date_initiated_breastfeeding: '',
        time_initiated_breastfeeding: '',
        date_vitamin_a: '',
        danger_signs_mother: '',
      },
    };
  },
  methods: {
    handleSubmit() {
      this.onSubmit(this.form); // Emit form data to parent
      this.closeModal(); // Close modal
    },
    closeModal() {
      this.$emit('close'); // Notify parent to close modal
    },
  },
};
</script>
