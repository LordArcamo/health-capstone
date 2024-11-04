<template>
   <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100">
    <div class="bg-white shadow-md rounded-lg p-8 max-w-4xl w-full">
    <h2 class="text-2xl font-bold mb-6 text-center">Postpartum Form </h2>

    <!-- Step 1: Child Information Section -->
    <div v-if="currentStep === 1">
      <h3 class="text-2xl font-bold mb-6">Child Information</h3>
      <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="flex flex-col">
          <label class="font-regular" for="last_name">Last Name</label>
          <input v-model="form.last_name" id="last_name" type="text" class="input-field" />
        </div>
        <div class="flex flex-col">
          <label class="font-regular" for="first_name">First Name</label>
          <input v-model="form.first_name" id="first_name" type="text" class="input-field" />
        </div>
        <div class="flex flex-col">
          <label class="font-regular" for="middle_name">Middle Name</label>
          <input v-model="form.middle_name" id="middle_name" type="text" class="input-field" />
        </div>
        <div class="flex flex-col">
          <label class="font-regular" for="sex">Sex (M/F)</label>
          <input v-model="form.sex" id="sex" type="text" class="input-field" />
        </div>
        <div class="flex flex-col">
          <label class="font-regular" for="birth_length">Birth Length</label>
          <input v-model="form.birth_length" id="birth_length" type="text" class="input-field" />
        </div>
        <div class="flex flex-col">
          <label class="font-regular" for="birth_weight">Birth Weight</label>
          <input v-model="form.birth_weight" id="birth_weight" type="text" class="input-field" />
        </div>
      </div>
      <button @click="nextStep" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Next</button>
    </div>

    <!-- Step 2: Remaining Information Section -->
    <div v-if="currentStep === 2">
      <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="flex flex-col">
          <label class="font-regular" for="prenatal_delivered">Prenatal Delivered</label>
          <input v-model="form.prenatal_delivered" id="prenatal_delivered" type="text" class="input-field" />
        </div>
        <div class="flex flex-col">
          <label class="font-regular" for="place_delivered">Place Delivered</label>
          <input v-model="form.place_delivered" id="place_delivered" type="text" class="input-field" />
        </div>
        <div class="flex flex-col">
          <label class="font-regular" for="mode_of_delivery">Mode of Delivery</label>
          <input v-model="form.mode_of_delivery" id="mode_of_delivery" type="text" class="input-field" />
        </div>
        <div class="flex flex-col">
          <label class="font-regular" for="attendant_at_birth">Attendant at Birth</label>
          <input v-model="form.attendant_at_birth" id="attendant_at_birth" type="text" class="input-field" />
        </div>
        <div class="flex flex-col">
          <label class="font-regular" for="delivery_date">Delivery Date</label>
          <input v-model="form.delivery_date" id="delivery_date" type="date" class="input-field" />
        </div>
        <div class="flex flex-col">
          <label class="font-regular" for="delivery_time">Delivery Time</label>
          <input v-model="form.delivery_time" id="delivery_time" type="time" class="input-field" />
        </div>
        <div class="flex flex-col col-span-2">
          <label class="font-regular" for="danger_signs_mother">Danger Signs (Mother)</label>
          <input v-model="form.danger_signs_mother" id="danger_signs_mother" type="text" class="input-field" />
        </div>
      </div>
      
      <button @click="prevStep" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 mr-2">Back</button>
      <button @click="submitForm" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit</button>
    </div>
  </div>
   </div>

</template>

<script>
export default {
  data() {
    return {
      currentStep: 1,
      form: {
        last_name: '',
        first_name: '',
        middle_name: '',
        sex: '',
        birth_length: '',
        birth_weight: '',
        prenatal_delivered: '',
        place_delivered: '',
        mode_of_delivery: '',
        attendant_at_birth: '',
        delivery_date: '',
        delivery_time: '',
        danger_signs_mother: '',
        danger_signs_baby: '',
      }
    };
  },
  methods: {
    nextStep() {
      this.currentStep = 2;
    },
    prevStep() {
      this.currentStep = 1;
    },
    submitForm() {
      console.log(this.form);
      // Submit form data (e.g., using Inertia or other method)
    }
  }
};
</script>

<style scoped>
.input-field {
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
}
</style>
