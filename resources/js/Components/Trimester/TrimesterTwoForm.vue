<template>
  <div class="max-h-screen overflow-y-scroll p-6 bg-white shadow rounded-lg">


    <div v-if="prefilledData && !isEditing" class="p-6 bg-purple-50 rounded-lg shadow-md">
  <h2 class="text-xl font-semibold text-purple-700 mb-4">Trimester 2 Details</h2>

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
      <p class="text-sm text-purple-500">Trimester Visit</p>
      <p class="text-lg font-medium text-purple-800">{{ form.trimester }}</p>
    </div>

    <!-- Prenatal Check-up -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">Prenatal record/book updated</p>
      <p class="text-lg font-medium text-purple-800">{{ form.prenatal_record ? 'Yes' : 'No' }}</p>
    </div>

    <!-- PE Done -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">Reminded on importance of FBD</p>
      <p class="text-lg font-medium text-purple-800">{{ form.reminded_importance ? 'Yes' : 'No' }}</p>
    </div>

    <!-- Prenatal Record Given -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">Health teachings given</p>
      <p class="text-lg font-medium text-purple-800">{{ form.health_teachings ? 'Yes' : 'No' }}</p>
    </div>

    <!-- Birth Plan Done -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">Reminded on the Dangers Signs of Pregnancy</p>
      <p class="text-lg font-medium text-purple-800">{{ form.reminded_dangers ? 'Yes' : 'No' }}</p>
    </div>

    <!-- NKFDA -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">Healthy diet and increase fluid intake encouraged</p>
      <p class="text-lg font-medium text-purple-800">{{ form.healthy_diet ? 'Yes' : 'No' }}</p>
    </div>

    <!-- Health Teachings -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">Breast feeding after delivery encouraged</p>
      <p class="text-lg font-medium text-purple-800">{{ form.breast_feeding ? 'Yes' : 'No' }}</p>
    </div>

    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">Compliance to Routine Immunization encouraged<</p>
      <p class="text-lg font-medium text-purple-800">{{ form.compliance_routine ? 'Yes' : 'No' }}</p>
    </div>

    <!-- Referred for UTZ -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">Referred for UTZ – evaluation of pregnancy</p>
      <p class="text-lg font-medium text-purple-800">{{ form.referred_utz ? 'Yes' : 'No' }}</p>
    </div>

    <!-- Folic Acid -->
    <div class="bg-white p-4 rounded-md shadow-sm border-l-4 border-purple-500">
      <p class="text-sm text-purple-500">FES04 + Folic Acid Given</p>
      <p class="text-lg font-medium text-purple-800">{{ form.fes04_folic ? `Yes, ${form.folic_acid} tabs` : 'No' }}</p>
    </div>
  </div>

  <!-- Edit Button -->
  <div class="mt-6 flex justify-end">
    <button
      @click="enableEditing"
      class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition">
      Edit
    </button>
  </div>
    </div>

    <form v-else @submit.prevent="submitForm" class="space-y-4">
      <!-- Date of Visit -->
      <div>
        <label for="date_of_visit" class="block text-sm font-medium text-gray-700">Date of Visit</label>
        <input v-model="form.date_of_visit" type="date" id="date_of_visit" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required />
      </div>

      <!-- Weight -->
      <div>
        <label for="weight" class="block text-sm font-medium text-gray-700">Weight (kg)</label>
        <input v-model="form.weight" type="number" step="0.1" id="weight" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required />
      </div>

      <!-- Blood Pressure and Heart Rate -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="bp" class="block text-sm font-medium text-gray-700">BP</label>
          <input v-model="form.bp" type="text" id="bp" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required />
        </div>
        <div>
          <label for="heart_rate" class="block text-sm font-medium text-gray-700">Heart Rate</label>
          <input v-model="form.heart_rate" type="number" id="heart_rate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required />
        </div>
      </div>

      <!-- Age of Gestation -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="aog_months" class="block text-sm font-medium text-gray-700">AOG (Months)</label>
          <input v-model="form.aog_months" type="number" id="aog_months" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>
        <div>
          <label for="aog_days" class="block text-sm font-medium text-gray-700">AOG (Days)</label>
          <input v-model="form.aog_days" type="number" id="aog_days" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>
      </div>

      <!-- Trimester Visit -->
      <div>
        <label for="trimester" class="block text-sm font-medium text-gray-700">Trimester Visit</label>
        <input v-model="form.trimester" type="text" id="trimester" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
      </div>

      <!-- Additional Checkboxes -->
      <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">Check Items</label>
        <div class="flex items-center">
          <input v-model="form.prenatal_record" type="checkbox" id="prenatal_record" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="prenatal_record" class="ml-2 text-sm text-gray-600">Prenatal record/book updated</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.reminded_importance" type="checkbox" id="reminded_importance" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="reminded_importance" class="ml-2 text-sm text-gray-600">Reminded on importance of FBD</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.health_teachings" type="checkbox" id="health_teachings" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="health_teachings" class="ml-2 text-sm text-gray-600">Health teachings given</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.reminded_dangers" type="checkbox" id="reminded_dangers" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="reminded_dangers" class="ml-2 text-sm text-gray-600">Reminded on the Dangers Signs of Pregnancy</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.healthy_diet" type="checkbox" id="healthy_diet" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="healthy_diet" class="ml-2 text-sm text-gray-600">Healthy diet and increase fluid intake encouraged</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.breast_feeding" type="checkbox" id="breast_feeding" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="breast_feeding" class="ml-2 text-sm text-gray-600">Breast feeding after delivery encouraged</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.compliance_routine" type="checkbox" id="compliance_routine" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="compliance_routine" class="ml-2 text-sm text-gray-600">Compliance to Routine Immunization encouraged</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.referred_utz" type="checkbox" id="referred_utz" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="referred_utz" class="ml-2 text-sm text-gray-600">Referred for UTZ – evaluation of pregnancy</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.fes04_folic" type="checkbox" id="fes04_folic" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="fes04_folic" class="ml-2 text-sm text-gray-600">FES04 + folic acid given - # tabs <input v-model="form.folic_acid" type="number" id="folic_acid" class="h-4 w-10 text-xs text-blue-600 border-gray-300 rounded" >     -given</input> </label>
        </div>
        <!-- Checkbox Items-->
      </div>

      <!-- Submit Button -->
      <button @click="submitForm" type="submit"class="bg-purple-600 text-white px-4 py-2 rounded-md mr-2 hover:bg-purple-700">
        Submit
        </button>
    </form>
  </div>
</template>

<script>
// import { ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';

export default {
  props: {
    prenatalId: {
      type: Number,
      required: true,
    },
    trimester: {
      type: String,
      required: true,
    },
    prefilledData: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      isSubmitting: false,
      form: {
        date_of_visit: '',
        weight: '',
        bp: '',
        heart_rate: '',
        aog_months: '',
        aog_days: '',
        trimester: '2',
        prenatal_record: false,
        reminded_importance: false,
        health_teachings: false,
        reminded_dangers: false,
        healthy_diet: false,
        breast_feeding: false,
        compliane_routine: false,
        referred_utz: false,
        fes04_folic: false,
        folic_acid: '',
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
      immediate: true,
    },
  },
  methods: {
    enableEditing() {
      this.isEditing = true;
    },
    populateForm(data) {
      if (!data) return;

      if (data.generalTrimester) {
        const gt = data.generalTrimester;
        this.form = {
          ...this.form,
          date_of_visit: gt.date_of_visit || '',
          weight: gt.weight || '',
          bp: gt.bp || '',
          heart_rate: gt.heart_rate || '',
          aog_months: gt.aog_months || '',
          aog_days: gt.aog_days || '',
          trimester: gt.trimester || '2',
        };
      }

      const checkbox2Data = data.checkbox2 || (data.generalTrimester && data.generalTrimester.checkbox2);
      if (checkbox2Data) {
        this.form = {
          ...this.form,
          prenatal_record: checkbox2Data.prenatal_record || false,
          reminded_importance: checkbox2Data.reminded_importance || false,
          health_teachings: checkbox2Data.health_teachings || false,
          reminded_dangers: checkbox2Data.reminded_dangers || false,
          healthy_diet: checkbox2Data.healthy_diet || false,
          breast_feeding: checkbox2Data.breast_feeding || false,
          compliane_routine: checkbox2Data.compliane_routine || false,
          referred_utz: checkbox2Data.referred_utz || false,
          fes04_folic: checkbox2Data.fes04_folic || false,
          folic_acid: checkbox2Data.folic_acid || '',
        };
      }
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

      return isValid;
    },
    submitForm() {
      if (this.isSubmitting) return; // Prevent duplicate submissions
      this.isSubmitting = true;
      if (this.validateForm()) {
        const formData = { ...this.form, prenatalId: this.prenatalId };

        Inertia.post('/trimester2/store', formData, {
          onSuccess: () => {
            this.isSubmitting = false;
            alert('Form submitted successfully!');
            this.resetForm();
          },
          onError: (errors) => {
            this.isSubmitting = false;
            this.errors = errors;
          },
        });
      } else {
        alert('Please complete all required fields before submitting.');
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
        trimester: '2',
        prenatal_record: false,
        reminded_importance: false,
        health_teachings: false,
        reminded_dangers: false,
        healthy_diet: false,
        breast_feeding: false,
        compliane_routine: false,
        referred_utz: false,
        fes04_folic: false,
        folic_acid: '',

      };
      this.errors = {}; // Reset errors
    },
  },
  mounted() {
    if (this.prefilledData && Object.keys(this.prefilledData).length) {
      this.populateForm(this.prefilledData);
    }
  },
};
</script>


<style scoped>
/* Add any custom styling here */
/* Chrome, Safari, Edge, Opera */
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
