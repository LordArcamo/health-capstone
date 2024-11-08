<template>
   <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100">
    <div class="bg-white shadow-md rounded-lg p-8 max-w-4xl w-full">
    <h2 class="text-2xl font-bold mb-6 text-center">Postpartum Form </h2>

    <!-- Step 1: Child Information Section -->
    <div v-if="step === 1">
      <h3 class="text-2xl font-bold mb-6">Child Information</h3>
      <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="flex flex-col">
          <label class="font-regular" for="middleName">First Name</label>
          <input v-model="form.firstName" type="text" class="input-field" />
        </div>
        <div class="flex flex-col">
          <label class="font-regular" for="middleName">Last Name</label>
          <input v-model="form.lastName"  type="text" class="input-field" />
        </div>
        <div class="flex flex-col">
          <label class="font-regular" for="middleName">Middle Name</label>
          <input v-model="form.middleName" type="text" class="input-field" />
        </div>
        <div class="flex flex-col">
          <label class="font-regular" for="sex">Gender</label>
          <select v-model="form.sex" class="input-field" required>
                <option>Male</option>
                <option>Female</option>
              </select>
        </div>
        <div class="flex flex-col">
          <label class="font-regular" for="birthLength">Birth Length</label>
          <input v-model="form.birthLength" type="number" class="input-field" />
        </div>
        <div class="flex flex-col">
          <label class="font-regular" for="birthWeight">Birth Weight</label>
          <input v-model="form.birthWeight" type="number" class="input-field" />
        </div>
      </div>
      <div class="flex justify-center">
        <button @click="nextStep" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Next</button>
      </div>
    </div>

    <!-- Step 2: Remaining Information Section -->
    <div v-if="step === 2">
      <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="flex flex-col">
          <label class="font-regular" for="prenatalDelivered">Prenatal Delivered</label>
          <input v-model="form.prenatalDelivered" type="text" class="input-field" />
        </div>
        <div class="flex flex-col">
          <label class="font-regular" for="placeDelivered">Place Delivered</label>
          <input v-model="form.placeDelivered" type="text" class="input-field" />
        </div>
        <div class="flex flex-col">
          <label class="font-regular" for="modeOfDelivery">Mode of Delivery</label>
          <input v-model="form.modeOfDelivery" type="text" class="input-field" />
        </div>
        <div class="flex flex-col">
          <label class="font-regular" for="attendantAtBirth">Attendant at Birth</label>
          <input v-model="form.attendantAtBirth" type="text" class="input-field" />
        </div>
        <div class="flex flex-col">
          <label class="font-regular" for="deliveryDate">Delivery Date</label>
          <input v-model="form.deliveryDate" type="date" class="input-field" />
        </div>
        <div class="flex flex-col">
          <label class="font-regular" for="deliveryTime">Delivery Time</label>
          <input v-model="form.deliveryTime" type="time" class="input-field" />
        </div>
        <div class="flex flex-col">
          <label class="font-regular" for="dangerSignsMother">Danger Signs (Mother)</label>
          <input v-model="form.dangerSignsMother" type="text" class="input-field" />
        </div>
        <div class="flex flex-col">
          <label class="font-regular" for="dangerSignsBaby">Danger Signs (Baby)</label>
          <input v-model="form.dangerSignsBaby" type="text" class="input-field" />
        </div>
      </div>
      <div class="flex flex-row justify-between">
        <button @click="prevStep" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 mr-2">Back</button>
        <button @click="submitForm" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit</button>
      </div>
    </div>
  </div>
   </div>

</template>

<script>
export default {
  props: ['onSubmit'],
  data() {
    return {
      step: 1,
      form: {
        lastName: '',
        firstName: '',
        middleName: '',
        sex: '',
        birthLength: '',
        birthWeight: '',
        prenatalDelivered: '',
        placeDelivered: '',
        modeOfDelivery: '',
        attendantAtBirth: '',
        deliveryDate: '',
        deliveryTime: '',
        dangerSignsMother: '',
        dangerSignsBaby: '',
      }
    };
  },
  methods: {
    nextStep() {
      this.step = 2;
    },
    prevStep() {
      this.step = 1;
    },
    submitForm() {
      console.log('Submitting form with data:', this.form);
      alert('Form submitted');
      this.onSubmit(this.form); // Pass form data to parent
    },
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
