<template>
  <div class="max-h-screen overflow-y-scroll p-6 bg-white shadow rounded-lg">

    <div v-if="prefilledData && !isEditing" class="p-6 bg-purple-50 rounded-lg shadow-md">
  <h2 class="text-xl font-semibold text-purple-700 mb-4">Trimester 1 Details</h2>

  <!-- Grid Layout for Fields -->
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <!-- Date of Visit -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">Date of Visit</p>
      <p class="text-lg font-medium text-purple-800">{{ form.date_of_visit }}</p>
    </div>

    <!-- Weight -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">Weight (kg)</p>
      <p class="text-lg font-medium text-purple-800">{{ form.weight }}</p>
    </div>

    <!-- Blood Pressure -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">Blood Pressure</p>
      <p class="text-lg font-medium text-purple-800">{{ form.bp }}</p>
    </div>

    <!-- Heart Rate -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">Heart Rate</p>
      <p class="text-lg font-medium text-purple-800">{{ form.heart_rate }}</p>
    </div>

    <!-- Age of Gestation (Months) -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">AOG (Months)</p>
      <p class="text-lg font-medium text-purple-800">{{ form.aog_months }}</p>
    </div>

    <!-- Age of Gestation (Days) -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">AOG (Days)</p>
      <p class="text-lg font-medium text-purple-800">{{ form.aog_days }}</p>
    </div>

    <!-- Trimester -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">Trimester</p>
      <p class="text-lg font-medium text-purple-800">{{ form.trimester }}</p>
    </div>

    <!-- Prenatal Check-up -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">In for Prenatal Check-up</p>
      <p class="text-lg font-medium text-purple-800">{{ form.prenatal_checkup ? 'Yes' : 'No' }}</p>
    </div>

    <!-- PE Done -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">PE Done, TT Status Assessed</p>
      <p class="text-lg font-medium text-purple-800">{{ form.pe_done ? 'Yes' : 'No' }}</p>
    </div>

    <!-- Prenatal Record Given -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">Prenatal Record/Book Given and Filled Up</p>
      <p class="text-lg font-medium text-purple-800">{{ form.prenatal_record ? 'Yes' : 'No' }}</p>
    </div>

    <!-- Birth Plan Done -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">Birth Plan Done</p>
      <p class="text-lg font-medium text-purple-800">{{ form.birth_plan_done ? 'Yes' : 'No' }}</p>
    </div>

    <!-- NKFDA -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">NKFDA</p>
      <p class="text-lg font-medium text-purple-800">{{ form.nkfda ? 'Yes' : 'No' }}</p>
    </div>

    <!-- Health Teachings -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">Health Teachings Given (Danger Signs of Pregnancy Imparted)</p>
      <p class="text-lg font-medium text-purple-800">{{ form.health_teachings ? 'Yes' : 'No' }}</p>
    </div>

    <!-- Referred for Urinalysis -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">Referred for Urinalysis, HCT - HGB Count</p>
      <p class="text-lg font-medium text-purple-800">{{ form.referred_for ? 'Yes' : 'No' }}</p>
    </div>

    <!-- Healthy Diet -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">Healthy Diet and Increase Fluid Intake Encouraged</p>
      <p class="text-lg font-medium text-purple-800">{{ form.healthy_diet ? 'Yes' : 'No' }}</p>
    </div>

    <!-- Folic Acid -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">FES04 + Folic Acid Given</p>
      <p class="text-lg font-medium text-purple-800">{{ form.fes04_folic ? `Yes, ${form.folic_acid} tabs` : 'No' }}</p>
    </div>

    <!-- FHB -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">FHB</p>
      <p class="text-lg font-medium text-purple-800">{{ form.fhb }}</p>
    </div>

    <!-- Presentation -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">Presentation</p>
      <p class="text-lg font-medium text-purple-800">{{ form.presentation }}</p>
    </div>

    <!-- Fundal Height -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">Fundal Height</p>
      <p class="text-lg font-medium text-purple-800">{{ form.fundal_height }}</p>
    </div>
  </div>
    </div>



    <form v-else @submit.prevent="submitForm" class="space-y-4">
      <!-- Date of Visit -->
      <div>
        <label for="date_of_visit" class="block text-sm font-medium text-gray-700">Date of Visit</label>
        <input v-model="form.date_of_visit" type="date" id="date_of_visit" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        <!-- <p v-if="errors.date_of_visit" class="text-red-500 text-sm">{{ errors.date_of_visit }}</p> -->
      </div>

      <!-- Weight -->
      <div>
        <label for="weight" class="block text-sm font-medium text-gray-700">Weight (kg)</label>
        <input v-model="form.weight" type="number" step="0.1" id="weight"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        <p v-if="errors.weight" class="text-red-500 text-sm">{{ errors.weight }}</p>
      </div>

      <!-- Blood Pressure and Heart Rate -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="bp" class="block text-sm font-medium text-gray-700">BP</label>
          <input v-model="form.bp" type="text" id="bp" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
          <p v-if="errors.bp" class="text-red-500 text-sm">{{ errors.bp }}</p>
        </div>
        <div>
          <label for="heart_rate" class="block text-sm font-medium text-gray-700">Heart Rate</label>
          <input v-model="form.heart_rate" type="number" id="heart_rate"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
          <p v-if="errors.heart_rate" class="text-red-500 text-sm">{{ errors.heart_rate }}</p>
        </div>
      </div>

      <!-- Age of Gestation -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="aog_months" class="block text-sm font-medium text-gray-700">AOG (Months)</label>
          <input v-model="form.aog_months" type="number" id="aog_months"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>
        <div>
          <label for="aog_days" class="block text-sm font-medium text-gray-700">AOG (Days)</label>
          <input v-model="form.aog_days" type="number" id="aog_days"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>
      </div>

      <!-- Trimester Visit -->
      <div>
        <label for="trimester" class="block text-sm font-medium text-gray-700">Trimester Visit</label>
        <input v-model="form.trimester" type="text" id="trimester"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
      </div>

      <!-- Additional Checkboxes -->
      <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">Check Items</label>
        <div class="flex items-center">
          <input v-model="form.prenatal_checkup" type="checkbox" id="prenatal_checkup"
            class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="prenatal_checkup" class="ml-2 text-sm text-gray-600">In for prenatal check-up</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.pe_done" type="checkbox" id="pe_done"
            class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="pe_done" class="ml-2 text-sm text-gray-600">PE done, TT status assessed</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.prenatal_record" type="checkbox" id="prenatal_record"
            class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="prenatal_record" class="ml-2 text-sm text-gray-600">Prenatal record/book given and filled
            up</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.birth_plan_done" type="checkbox" id="birth_plan_done"
            class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="birth_plan_done" class="ml-2 text-sm text-gray-600">Birth plan done</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.nkfda" type="checkbox" id="nkfda"
            class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="nkfda" class="ml-2 text-sm text-gray-600">NKFDA</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.health_teachings" type="checkbox" id="health_teachings"
            class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="health_teachings" class="ml-2 text-sm text-gray-600">Health teachings given; danger signs of
            pregnancy imparted</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.referred_for" type="checkbox" id="referred_for"
            class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="referred_for" class="ml-2 text-sm text-gray-600">Referred for Urinalysis, HCT - HGB count</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.healthy_diet" type="checkbox" id="healthy_diet"
            class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="healthy_diet" class="ml-2 text-sm text-gray-600">Healthy diet and increase fluid intake
            encouraged</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.fes04_folic" type="checkbox" id="fes04_folic"
            class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="fes04_folic" class="ml-2 text-sm text-gray-600">FES04 + folic acid given - # tabs <input
              v-model="form.folic_acid" type="number" id="folic_acid_tabs"
              class="h-4 w-10 text-xs text-blue-600 border-gray-300 rounded"> -given</input></label>
        </div>
      </div>

      <!-- Observations: FHB, Position, Presentation, Fundal Height -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="fhb" class="block text-sm font-medium text-gray-700">FHB</label>
          <input v-model="form.fhb" 
          @input="capitalizeName('fhb')"
          type="text" id="fhb"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>
        <div>
          <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
          <input v-model="form.position" 
          @input="capitalizeName('position')"
          type="text" id="position"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>
        <div>
          <label for="presentation" class="block text-sm font-medium text-gray-700">Presentation</label>
          <input v-model="form.presentation" 
          @input="capitalizeName('presentation')"
          type="text" id="presentation"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>
        <div>
          <label for="fundal_height" class="block text-sm font-medium text-gray-700">Fundal Height</label>
          <input v-model="form.fundal_height" 
          @input="capitalizeName('fundal_height')"
          type="text" id="fundal_height"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>
      </div>

      <!-- Submit Button -->
      <button @click="submitForm" class="bg-purple-600 text-white px-4 py-2 rounded-md mr-2 hover:bg-purple-700">
        Submit
      </button>
    </form>
  </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';

export default {
  props: {
    prenatalConsultationDetailsID: {
      type: Number,
      required: true,
    },
    trimester: {
      type: String,
      required: true,
    },
    prefilledData: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      isSubmitting: false,
      form: {
        date_of_visit: this.getTodayDate(),
        weight: '',
        bp: '',
        heart_rate: '',
        aog_months: '',
        aog_days: '',
        trimester: '1',
        prenatal_checkup: false,
        pe_done: false,
        prenatal_record: false,
        birth_plan_done: false,
        nkfda: false,
        health_teachings: false,
        referred_for: false,
        healthy_diet: false,
        fes04_folic: false,
        folic_acid: '',
        fhb: '',
        position: '',
        presentation: '',
        fundal_height: ''
      },
      errors: {},
      isEditing: false,
    };
  },
  watch: {
    prefilledData: {
      handler(newData) {
        if (newData) {
          this.populateForm(newData);
        }
      },
      immediate: true
    }
  },
  methods: {
    getTodayDate() {
      const today = new Date();
      return today.toISOString().split('T')[0]; // "YYYY-MM-DD"
    },
        capitalizeName(field) {
      if (this.form[field]) {
        this.form[field] = this.form[field]
          .split(" ") // Split the input by spaces
          .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()) // Capitalize the first letter
          .join(" "); // Join the words back together
      }
    },
    formatLabel(key) {
      // Convert camelCase or snake_case keys to readable labels
      return key
        .replace(/([A-Z])/g, " $1") // Add space before uppercase letters
        .replace(/_/g, " ") // Replace underscores with spaces
        .replace(/\b\w/g, (char) => char.toUpperCase()); // Capitalize first letters
    },
    enableEditing() {
      this.isEditing = true;
    },
    populateForm(data) {
      if (!data) return;

      if (data.generalTrimester) {
        const gt = data.generalTrimester;
        this.form = {
          ...this.form,
          generalTrimesterID: gt.generalTrimesterID || null, // Include the ID
          date_of_visit: gt.date_of_visit || '',
          weight: gt.weight || '',
          bp: gt.bp || '',
          heart_rate: gt.heart_rate || '',
          aog_months: gt.aog_months || '',
          aog_days: gt.aog_days || '',
          trimester: gt.trimester || '1',
        };
      }

      const checkbox1Data = data.checkbox1 || {};
      this.form = {
        ...this.form,
        prenatal_checkup: checkbox1Data.prenatal_checkup || false,
        pe_done: checkbox1Data.pe_done || false,
        prenatal_record: checkbox1Data.prenatal_record || false,
        birth_plan_done: checkbox1Data.birth_plan_done || false,
        nkfda: checkbox1Data.nkfda || false,
        health_teachings: checkbox1Data.health_teachings || false,
        referred_for: checkbox1Data.referred_for || false,
        healthy_diet: checkbox1Data.healthy_diet || false,
        fes04_folic: checkbox1Data.fes04_folic || false,
        folic_acid: checkbox1Data.folic_acid || '',
        fhb: checkbox1Data.fhb || '',
        position: checkbox1Data.position || '',
        presentation: checkbox1Data.presentation || '',
        fundal_height: checkbox1Data.fundal_height || '',
      };
    },


    validateForm() {
      this.errors = {}; // Reset errors
      let isValid = true;

      if (!this.form.date_of_visit) {
        this.errors.date_of_visit = 'Date of Visit is required.';
        isValid = false;
      }
      if (!this.form.weight) {
        this.errors.weight = 'Weight is required.';
        isValid = false;
      }
      if (!this.form.bp) {
        this.errors.bp = 'Blood Pressure is required.';
        isValid = false;
      }
      if (!this.form.heart_rate) {
        this.errors.heart_rate = 'Heart Rate is required.';
        isValid = false;
      }
      if (!this.form.heart_rate) {
        this.errors.heart_rate = 'Heart Rate is required.';
        isValid = false;
      }
      if (!this.form.fhb) {
        this.fhb = 'FHB is required.';
        isValid = false;
      }
      if (!this.form.position) {
        this.errors.position = 'Position is required.';
        isValid = false;
      }
      if (!this.form.presentation) {
        this.errors.presentation = 'Presentation is required.';
        isValid = false;
      }
      if (!this.form.fundal_height) {
        this.errors.fundal_height = 'Fundal Height is required.';
        isValid = false;
      }

      return isValid;
    },

    submitForm() {
      if (this.isSubmitting) return; // Prevent duplicate submissions
      this.isSubmitting = true;

      if (this.validateForm()) {
        const formData = { ...this.form, prenatalConsultationDetailsID: this.prenatalConsultationDetailsID, id: this.id };

        Inertia.post('/trimester1/store', formData, {
          onStart: () => {
            this.loading = true;
          },
          onFinish: () => {
            this.loading = false;
            this.isSubmitting = false;
          },
          onSuccess: () => {
            alert('Data saved successfully!');
            this.resetForm();
          },
          onError: (response) => {
            console.error('Error:', response.errors);
            this.errors = response.errors;
            this.isSubmitting = false;
          },
        });
      } else {
        alert('Please complete all required fields.');
        this.isSubmitting = false;
      }
    },

    resetForm() {
      this.form = {
        date_of_visit: '',
        weight: '',
        bp: '',
        heart_rate: '',
        aog_months: '',
        aog_days: '',
        trimester: '',
        prenatal_checkup: false,
        pe_done: false,
        prenatal_record: false,
        birth_plan_done: false,
        nkfda: false,
        health_teachings: false,
        referred_for: false,
        healthy_diet: false,
        fes04_folic: false,
        folic_acid: '',
        fhb: '',
        position: '',
        presentation: '',
        fundal_height: '',
      };
      this.errors = {}; // Reset error messages
      this.successMessage = ''; // Reset success message
    }
  },
  mounted() {
    if (this.prefilledData && Object.keys(this.prefilledData).length) {
      this.form = { ...this.form, ...this.prefilledData };
    }
  },
};
</script>

<style scoped>
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type="number"] {
  -moz-appearance: textfield;
}
</style>
