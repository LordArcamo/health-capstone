<template>
  <div>
    <!-- Modal Overlay -->
    <div class="fixed inset-0 bg-pink-200 bg-opacity-90 flex items-center justify-center z-50 p-4">
      <!-- Modal Content -->
      <div class="bg-white shadow-lg rounded-lg p-8 max-w-4xl w-full relative">
        <!-- Close Button -->
        <button @click="closeModal" class="absolute top-4 right-4 text-pink-500 hover:text-pink-700 text-xl font-bold">
          ✕
        </button>

        <!-- Check if existingData exists -->
        <div v-if="existingData && !isEditing" class="p-6 bg-pink-50 rounded-lg shadow-md">
          <!-- Header -->
          <h2 class="text-2xl font-extrabold text-pink-600 mb-2">Prenatal Checkup Details</h2>
          <p class="text-sm text-gray-700 mb-6 italic">Details for <span class="font-semibold">{{ existingData.firstName
              }} {{ existingData.middleName }} {{ existingData.lastName }}</span></p>

          <!-- Display Existing Data -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Baby Information -->
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-pink-400">
              <h3 class="text-lg font-semibold text-pink-500 mb-2">Baby Information</h3>
              <p><span class="font-medium text-gray-800">Full Name:</span> {{ existingData.firstName }} {{
                existingData.lastName }}</p>
              <p><span class="font-medium text-gray-800">Sex:</span> {{ existingData.sex }}</p>
              <!-- Add other fields as needed -->
            </div>

            <!-- Delivery Details -->
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-pink-400">
              <h3 class="text-lg font-semibold text-pink-500 mb-2">Delivery Details</h3>
              <p><span class="font-medium text-gray-800">Birth Length:</span> {{ existingData.birthLength }}</p>
              <p><span class="font-medium text-gray-800">Birth Weight:</span> {{ existingData.birthWeight }}</p>
              <p><span class="font-medium text-gray-800">Delivery Date:</span> {{ existingData.deliveryDate }}</p>
              <p><span class="font-medium text-gray-800">Prenatal Delivered:</span> {{ existingData.prenatalDelivered }}
              </p>
              <p><span class="font-medium text-gray-800">Place Delivered:</span> {{ existingData.placeDelivered }}</p>
              <p><span class="font-medium text-gray-800">Mode of Delivery:</span> {{ existingData.modeOfDelivery }}</p>
              <p><span class="font-medium text-gray-800">Attendant Birth:</span> {{ existingData.attendantBirth }}</p>
            </div>

            <!-- Breastfeeding -->
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-pink-400">
              <h3 class="text-lg font-semibold text-pink-500 mb-2">Breastfeeding</h3>
              <p><span class="font-medium text-gray-800">Date Initiated Breastfeeding:</span> {{
                existingData.dateInitiatedBreastfeeding }}</p>
              <p><span class="font-medium text-gray-800">Time Initiated Breastfeeding:</span> {{
                existingData.timeInitiatedBreastfeeding }}</p>
            </div>

            <!-- Postpartum Details -->
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-pink-400">
              <h3 class="text-lg font-semibold text-pink-500 mb-2">Postpartum Details</h3>
              <p><span class="font-medium text-gray-800">Date of Postpartum Visit (within 24 hrs):</span> {{
                existingData.dateOfPostpartumVisitTwentyFourHoursDelivery }}</p>
              <p><span class="font-medium text-gray-800">Date of Postpartum Visit (1 Week):</span> {{
                existingData.dateOfPostpartumVisitOneWeekDelivery }}</p>
              <p><span class="font-medium text-gray-800">Date Vitamin A:</span> {{ existingData.dateVitaminA }}</p>
              <p><span class="font-medium text-gray-800">Date Iron Given:</span> {{ existingData.dateIronGiven }}</p>
              <p><span class="font-medium text-gray-800">No. of Iron Given:</span> {{ existingData.noIronGiven }}</p>
              <p><span class="font-medium text-gray-800">Danger Signs (Mother):</span> {{ existingData.dangerSignsMother
                }}</p>
              <p><span class="font-medium text-gray-800">Danger Signs (Baby):</span> {{ existingData.dangerSignsBaby }}
              </p>
            </div>
          </div>

          <!-- Edit Button -->
          <div class="mt-6 flex justify-end">
            <button @click="enableEdit"
              class="px-6 py-2 bg-pink-500 text-white rounded-lg shadow-md hover:bg-pink-600 transition duration-200">
              Edit Details
            </button>
          </div>
        </div>


        <!-- Form -->
        <div v-else>
          <form @submit.prevent="handleSubmit" class="space-y-6">
            <!-- Step Navigation -->
            <div class="flex justify-between items-center pb-4 border-b border-pink-300">
              <button v-for="(step, index) in steps" :key="index" type="button"
                class="px-4 py-2 rounded-md text-sm font-semibold transition" :class="{
                  'bg-pink-500 text-white shadow': currentStep === index,
                  'bg-pink-100 text-pink-600 hover:bg-pink-200': currentStep !== index,
                }" @click="navigateToStep(index)">
                {{ step.title }}
              </button>

            </div>

            <!-- Step 1: Prenatal Outcome -->
            <div v-if="currentStep === 0" class="mt-6 space-y-4">
              <h2 class="text-lg font-semibold text-pink-600">Prenatal Outcome</h2>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label for="first_name" class="block text-sm font-medium text-pink-700">First Name</label>
                  <input v-model="form.firstName" type="text" id="first_name" placeholder="John"
                    class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                  <p v-if="errors.firstName" class="text-red-500 text-sm">{{ errors.firstName }}</p>
                </div>
                <div>
                  <label for="last_name" class="block text-sm font-medium text-pink-700">Last Name</label>
                  <input v-model="form.lastName" type="text" id="last_name" placeholder="Dela Cruz"
                    class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                  <p v-if="errors.lastName" class="text-red-500 text-sm">{{ errors.lastName }}</p>
                </div>
                <div>
                  <label for="middle_name" class="block text-sm font-medium text-pink-700">Middle Name</label>
                  <input v-model="form.middleName" type="text" id="middle_name" placeholder="Delos Santos"
                    class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                </div>
                <div>
                  <label for="sex" class="block text-sm font-medium text-pink-700">Sex (M / F)</label>
                  <select v-model="form.sex" id="sex"
                    class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500">
                    <option value="">Select</option>
                    <option value="M">Male</option>
                    <option value="F">Female</option>
                  </select>
                  <p v-if="errors.sex" class="text-red-500 text-sm">{{ errors.sex }}</p>
                </div>
              </div>
            </div>

            <!-- Step 2: Delivery Details -->
            <div v-if="currentStep === 1" class="mt-6 space-y-4">
              <h2 class="text-lg font-semibold text-pink-600">Delivery Details</h2>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label for="prenatal_delivered" class="block text-sm font-medium text-pink-700">Prenatal
                    Delivered</label>
                  <input v-model="form.prenatalDelivered" type="text" step="0.01" id="prenatal_delivered"
                    placeholder="Enter Where Prenatal is Delivered"
                    class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                  <p v-if="errors.prenatalDelivered" class="text-red-500 text-sm">{{ errors.prenatalDelivered }}</p>
                </div>
                <div>
                  <label for="place_delivered" class="block text-sm font-medium text-pink-700">Place Delivered</label>
                  <input v-model="form.placeDelivered" type="text" step="0.01" id="place_delivered"
                    placeholder="Enter place delivered"
                    class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                  <p v-if="errors.placeDelivered" class="text-red-500 text-sm">{{ errors.placeDelivered }}</p>
                </div>
                <div>
                  <label for="mode_of_delivery" class="block text-sm font-medium text-pink-700">Mode of Delivery</label>
                  <input v-model="form.modeOfDelivery" type="text" step="0.01" id="mode_of_delivery"
                    placeholder="Enter Mode of Delivery"
                    class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                  <p v-if="errors.modeOfDelivery" class="text-red-500 text-sm">{{ errors.modeOfDelivery }}</p>
                </div>
                <div>
                  <label for="birth_length" class="block text-sm font-medium text-pink-700">Birth Length</label>
                  <input v-model="form.birthLength" type="number" step="0.01" id="birth_length"
                    placeholder="Enter birth length in cm"
                    class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                  <p v-if="errors.birthLength" class="text-red-500 text-sm">{{ errors.birthLength }}</p>
                </div>
                <div>
                  <label for="birth_weight" class="block text-sm font-medium text-pink-700">Birth Weight</label>
                  <input v-model="form.birthWeight" type="number" step="0.01" id="birth_weight"
                    placeholder="Enter birth weight in kg"
                    class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                  <p v-if="errors.birthWeight" class="text-red-500 text-sm">{{ errors.birthWeight }}</p>
                </div>
                <div>
                  <label for="delivery_date" class="block text-sm font-medium text-pink-700">Delivery Date</label>
                  <input v-model="form.deliveryDate" type="date" id="delivery_date" placeholder="Select delivery date"
                    class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                  <p v-if="errors.deliveryDate" class="text-red-500 text-sm">{{ errors.deliveryDate }}</p>
                </div>
                <div>
                  <label for="delivery_time" class="block text-sm font-medium text-pink-700">Delivery Time</label>
                  <input v-model="form.deliveryTime" type="time" id="delivery_time" placeholder="Select delivery time"
                    class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                  <p v-if="errors.deliveryTime" class="text-red-500 text-sm">{{ errors.deliveryTime }}</p>
                </div>

                <div>
                  <label for="attendant_birth" class="block text-sm font-medium text-pink-700">Attendant at
                    Birth</label>
                  <input v-model="form.attendantBirth" type="text" step="0.01" id="attendant_birth"
                    placeholder="Example: Josefina Uy"
                    class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                  <p v-if="errors.attendantBirth" class="text-red-500 text-sm">{{ errors.attendantBirth }}</p>
                </div>
              </div>
            </div>


            <!-- Step 3: Breastfeeding -->
            <div v-if="currentStep === 2" class="mt-6 space-y-4">
              <h2 class="text-lg font-semibold text-pink-600">Breastfeeding</h2>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label for="date_initiated_breastfeeding" class="block text-sm font-medium text-pink-700">Date
                    Initiated
                    Breastfeeding</label>
                  <input v-model="form.dateInitiatedBreastfeeding" type="date" id="date_initiated_breastfeeding"
                    class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                  <p v-if="errors.dateInitiatedBreastfeeding" class="text-red-500 text-sm">{{
                    errors.dateInitiatedBreastfeeding }}</p>
                </div>
                <div>
                  <label for="time_initiated_breastfeeding" class="block text-sm font-medium text-pink-700">Time
                    Initiated
                    Breastfeeding</label>
                  <input v-model="form.timeInitiatedBreastfeeding" type="time" id="time_initiated_breastfeeding"
                    class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                  <p v-if="errors.timeInitiatedBreastfeeding" class="text-red-500 text-sm">{{
                    errors.timeInitiatedBreastfeeding }}</p>
                </div>
              </div>
            </div>

            <!-- Step 4: Postpartum Details -->
            <div v-if="currentStep === 3" class="mt-6 space-y-4">
              <h2 class="text-lg font-semibold text-pink-600">Postpartum Details</h2>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label for="date_of_postpartum_visit_twenty_four_hours_delivery"
                    class="block text-sm font-medium text-pink-700">Date of Postpartum Visit within 24hrs after
                    delivery</label>
                  <input v-model="form.dateOfPostpartumVisitTwentyFourHoursDelivery" type="date"
                    id="date_of_postpartum_visit_twenty_four_hours_delivery"
                    placeholder="Select date Vitamin A was given"
                    class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                  <p v-if="errors.dateOfPostpartumVisitTwentyFourHoursDelivery" class="text-red-500 text-sm">{{
                    errors.dateOfPostpartumVisitTwentyFourHoursDelivery }}</p>
                </div>
                <div>
                  <label for="date_of_postpartum_visit_one_week_delivery"
                    class="block text-sm font-medium text-pink-700">Date of Postpartum Visit wihin 1 Week after
                    delivery</label>
                  <input v-model="form.dateOfPostpartumVisitOneWeekDelivery" type="date"
                    id="date_of_postpartum_visit_one_week_delivery" placeholder="Select date Vitamin A was given"
                    class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                  <p v-if="errors.dateOfPostpartumVisitOneWeekDelivery" class="text-red-500 text-sm">{{
                    errors.dateOfPostpartumVisitOneWeekDelivery }}</p>
                </div>
                <div>
                  <label for="date_vitamin_a" class="block text-sm font-medium text-pink-700">Date Vitamin A
                    Given</label>
                  <input v-model="form.dateVitaminA" type="date" id="date_vitamin_a"
                    placeholder="Select date Vitamin A was given"
                    class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                  <p v-if="errors.dateVitaminA" class="text-red-500 text-sm">{{ errors.dateVitaminA }}</p>
                </div>

                <div>
                  <label for="date_iron_given" class="block text-sm font-medium text-pink-700">Date Iron Given</label>
                  <input v-model="form.dateIronGiven" type="date" id="date_iron_given"
                    placeholder="Select date Vitamin A was given"
                    class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                  <p v-if="errors.dateIronGiven" class="text-red-500 text-sm">{{ errors.dateIronGiven }}</p>
                </div>

                <div>
                  <label for="no_iron_given" class="block text-sm font-medium text-pink-700">No. of Iron Given</label>
                  <input v-model="form.noIronGiven" type="number" id="no_iron_given" placeholder="Example: 100"
                    class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" />
                  <p v-if="errors.noIronGiven" class="text-red-500 text-sm">{{ errors.noIronGiven }}</p>
                </div>

                <div>
                  <label for="danger_signs_mother" class="block text-sm font-medium text-pink-700">Danger Signs
                    (Mother)</label>
                  <textarea v-model="form.dangerSignsMother" id="danger_signs_mother"
                    placeholder="Describe any danger signs for the mother"
                    class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500"></textarea>
                  <p v-if="errors.dangerSignsMother" class="text-red-500 text-sm">{{ errors.dangerSignsMother }}</p>
                </div>

                <div>
                  <label for="danger_signs_baby" class="block text-sm font-medium text-pink-700">Danger Signs
                    (Baby)</label>
                  <textarea v-model="form.dangerSignsBaby" id="danger_signs_baby"
                    placeholder="Describe any danger signs for the baby"
                    class="mt-1 block w-full border-pink-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500"></textarea>
                  <p v-if="errors.dangerSignsBaby" class="text-red-500 text-sm">{{ errors.dangerSignsBaby }}</p>
                </div>

              </div>
            </div>


            <!-- Navigation Buttons -->
            <div class="flex justify-between items-center pt-4 border-t border-pink-300">
              <button type="button"
                class="px-4 py-2 bg-pink-100 text-pink-600 rounded-md shadow hover:bg-pink-200 transition"
                :disabled="currentStep === 0" @click="handlePreviousStep">
                Previous
              </button>
              <button v-if="currentStep < steps.length - 1" type="button"
                class="px-4 py-2 bg-pink-500 text-white rounded-md shadow hover:bg-pink-600 transition"
                @click="handleNextStep">
                Next
              </button>
              <button v-else type="submit"
                class="px-4 py-2 bg-pink-500 text-white rounded-md shadow hover:bg-pink-600 transition">
                Submit
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { router } from '@inertiajs/vue3';

export default {
  name: 'PostpartumForm',
  emits: ['close'],
  props: {
    patient: {
      type: Object,
      required: true
    },
    existingData: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      isEditing: false,
      currentStep: 0,
      steps: [
        { title: 'Basic Info' },
        { title: 'Delivery Details' },
        { title: 'Breastfeeding' },
        { title: 'Postpartum Details' },
      ],
      form: {
        prenatalId: this.patient?.prenatalId || null,
        lastName: '',
        firstName: '',
        middleName: '',
        sex: '',
        prenatalDelivered: '',
        placeDelivered: '',
        modeOfDelivery: '',
        birthLength: '',
        birthWeight: '',
        deliveryDate: '',
        deliveryTime: '',
        attendantBirth: '',
        dateInitiatedBreastfeeding: '',
        timeInitiatedBreastfeeding: '',
        dateOfPostpartumVisitTwentyFourHoursDelivery: '',
        dateOfPostpartumVisitOneWeekDelivery: '',
        dateVitaminA: '',
        dateIronGiven: '',
        noIronGiven: '',
        dangerSignsMother: '',
        dangerSignsBaby: '',
      },
      errors: {},
    };
  },
  watch: {
    existingData: {
      immediate: true,
      handler(newData) {
        if (newData) {
          this.form = { ...this.form, ...newData };
        }
      },
    },
  },
  methods: {
    enableEdit() {
      this.isEditing = true;
      this.form = { ...this.existingData }; // Pre-fill form with existing data
    },
    navigateToStep(index) {
      if (index > this.currentStep && !this.validateStep()) {
        // Prevent moving to the next step if validation fails
        return;
      }
      this.currentStep = index;
    },
    validateStep() {
      this.errors = {};

      // Step 1: Basic Info validations
      if (this.currentStep === 0) {
        if (!this.form.firstName) this.errors.firstName = 'First name is required.';
        if (!this.form.lastName) this.errors.lastName = 'Last name is required.';
        if (!this.form.sex) this.errors.sex = 'Please select a valid sex.';
      }

      // Step 2: Delivery Details validations
      if (this.currentStep === 1) {
        if (!this.form.birthLength) this.errors.birthLength = 'Birth length is required.';
        if (!this.form.birthWeight) this.errors.birthWeight = 'Birth weight is required.';
        if (!this.form.deliveryDate) this.errors.deliveryDate = 'Delivery date is required.';
        if (!this.form.deliveryTime) this.errors.deliveryTime = 'Delivery time is required.';
        if (!this.form.prenatalDelivered) this.errors.prenatalDelivered = 'Prenatal delivered is required.';
        if (!this.form.placeDelivered) this.errors.placeDelivered = 'Place delivered is required.';
        if (!this.form.modeOfDelivery) this.errors.modeOfDelivery = 'Mode of Delivery is required.';
        if (!this.form.attendantBirth) this.errors.attendantBirth = 'Who is the attendant of the birth?';
      }

      // Step 3: Breastfeeding validations
      if (this.currentStep === 2) {
        if (!this.form.dateInitiatedBreastfeeding) {
          this.errors.dateInitiatedBreastfeeding = 'Date breastfeeding initiated is required.';
        }
        if (!this.form.timeInitiatedBreastfeeding) {
          this.errors.timeInitiatedBreastfeeding = 'Time breastfeeding initiated is required.';
        }
      }

      // Step 4: Postpartum Details validations
      if (this.currentStep === 3) {
        if (!this.form.dateIronGiven) this.errors.dateIronGiven = 'Date Iron given is required.';
        if (!this.form.noIronGiven) this.errors.noIronGiven = 'Number of Iron is required.';
        if (!this.form.dateOfPostpartumVisitTwentyFourHoursDelivery) this.errors.dateOfPostpartumVisitTwentyFourHoursDelivery = 'Date of Postpartum Visit within 24hrs after delivery is required.';
        if (!this.form.dateOfPostpartumVisitOneWeekDelivery) this.errors.dateOfPostpartumVisitOneWeekDelivery = 'Date of Postpartum Visit within 1 Week after delivery is required.';
        if (!this.form.dateVitaminA) this.errors.dateVitaminA = 'Date Vitamin A given is required.';
        if (!this.form.dangerSignsMother) {
          this.errors.dangerSignsMother = 'Please describe any danger signs for the mother.';
        }
        if (!this.form.dangerSignsBaby) {
          this.errors.dangerSignsBaby = 'Please describe any danger signs for the baby.';
        }
      }

      return Object.keys(this.errors).length === 0;
    },
    handleNextStep() {
      if (this.validateStep()) {
        this.currentStep++;
      }
    },
    handlePreviousStep() {
      if (this.currentStep > 0) {
        this.currentStep--;
      }
    },
    handleSubmit() {
      if (!this.validateStep()) {
        return;
      }

      const formData = {
        prenatalId: this.form.prenatalId,
        lastName: this.form.lastName,
        firstName: this.form.firstName,
        middleName: this.form.middleName,
        sex: this.form.sex,
        prenatalDelivered: this.form.prenatalDelivered,
        placeDelivered: this.form.placeDelivered,
        modeOfDelivery: this.form.modeOfDelivery,
        birthLength: parseFloat(this.form.birthLength) || 0,
        birthWeight: parseFloat(this.form.birthWeight) || 0,
        deliveryDate: this.form.deliveryDate,
        deliveryTime: this.form.deliveryTime,
        attendantBirth: this.form.attendantBirth,
        dateInitiatedBreastfeeding: this.form.dateInitiatedBreastfeeding,
        timeInitiatedBreastfeeding: this.form.timeInitiatedBreastfeeding,
        dateOfPostpartumVisitTwentyFourHoursDelivery: this.form.dateOfPostpartumVisitTwentyFourHoursDelivery,
        dateOfPostpartumVisitOneWeekDelivery: this.form.dateOfPostpartumVisitOneWeekDelivery,
        dateVitaminA: this.form.dateVitaminA,
        dateIronGiven: this.form.dateIronGiven,
        noIronGiven: parseInt(this.form.noIronGiven) || 0,
        dangerSignsMother: this.form.dangerSignsMother,
        dangerSignsBaby: this.form.dangerSignsBaby,
      };

      console.log('Submitting form data:', formData); // Debug log

      if (this.existingData) {
        router.put(`/postpartum/${this.existingData.postpartumId}`, formData, {
          onSuccess: () => {
            this.$emit('close');
          },
          onError: (errors) => {
            console.error('Error updating form:', errors);
            this.errors = errors;
          },
          preserveState: true,
        });
      } else {
        router.post('/postpartum', formData, {
          onSuccess: () => {
            this.$emit('close');
          },
          onError: (errors) => {
            console.error('Error submitting form:', errors);
            this.errors = errors;
          },
          preserveState: true,
        });
      }
    },

    closeModal() {
      this.$emit('close');
    },
  },
};
</script>
