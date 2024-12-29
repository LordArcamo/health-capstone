<template>
  <div class="max-h-screen overflow-y-scroll p-6 bg-white shadow rounded-lg">
    <form @submit.prevent="submitForm" class="space-y-4">
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
          <input v-model="form.prenatal_checkup" type="checkbox" id="prenatal_checkup" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="prenatal_checkup" class="ml-2 text-sm text-gray-600">In for prenatal check-up</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.pe_done" type="checkbox" id="pe_done" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="pe_done" class="ml-2 text-sm text-gray-600">PE done</label>
        </div>
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
          <label for="health_teachings" class="ml-2 text-sm text-gray-600">Health teachings given, light exercise daily</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.reminded_dangers" type="checkbox" id="reminded_dangers" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="reminded_dangers  " class="ml-2 text-sm text-gray-600">Reminded on the Dangers Signs of Pregnancy</label>
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
          <input v-model="form.information_newborn" type="checkbox" id="information_newborn" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="information_newborn" class="ml-2 text-sm text-gray-600">Information on Newborn Screening given</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.fes04_folic" type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="fes04_folic" class="ml-2 text-sm text-gray-600">FES04 + folic acid given - # tabs <input v-model="form.folic_acid" type="number" id="folic_acid" class="h-4 w-10 text-xs text-blue-600 border-gray-300 rounded" >     -given</input> </label>
        </div>
        <!-- Checkbox Items-->
      </div>

      <!-- Observations: FHB, Position, Presentation, Fundal Height -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="fhb" class="block text-sm font-medium text-gray-700">FHB</label>
          <input v-model="form.fhb" type="text" id="fhb" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>
        <div>
          <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
          <input v-model="form.position" type="text" id="position" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>
        <div>
          <label for="presentation" class="block text-sm font-medium text-gray-700">Presentation</label>
          <input v-model="form.presentation" type="text" id="presentation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>
        <div>
          <label for="fundal_height" class="block text-sm font-medium text-gray-700">Fundal Height</label>
          <input v-model="form.fundal_height" type="text" id="fundal_height" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
        </div>
      </div>

      <!-- Submit Button -->
      <button @click="submitForm" type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md mr-2 hover:bg-green-700">
        Submit
        </button>
    </form>
  </div>
</template>

<script>
import { ref, watch } from 'vue';
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
      default: null
    }
  },
  setup(props) {
    const form = ref({
      date_of_visit: '',
      weight: '',
      bp: '',
      heart_rate: '',
      aog_months: '',
      aog_days: '',
      trimester: '3',
      prenatal_record: false,
      reminded_importance: false,
      health_teachings: false,
      reminded_dangers: false,
      healthy_diet: false,
      breast_feeding: false,
      compliance_routine: false,
      referred_utz: false,
      fhb: '',
      position: '',
      presentation: '',
      fundal_height: ''
    });

    const errors = ref({});
    const loading = ref(false);

    const populateForm = (data) => {
      if (!data) return;

      if (data.generalTrimester) {
        const gt = data.generalTrimester;
        form.value = {
          ...form.value,
          date_of_visit: gt.date_of_visit || '',
          weight: gt.weight || '',
          bp: gt.bp || '',
          heart_rate: gt.heart_rate || '',
          aog_months: gt.aog_months || '',
          aog_days: gt.aog_days || '',
          trimester: gt.trimester || '3'
        };
      }

      const checkbox3Data = data.checkbox3 || (data.generalTrimester && data.generalTrimester.checkbox3);
      if (checkbox3Data) {
        form.value = {
          ...form.value,
          prenatal_checkup: checkbox3Data.prenatal_checkup || false,
          pe_done: checkbox3Data.pe_done || false,
          prenatal_record: checkbox3Data.prenatal_record || false,
          reminded_importance: checkbox3Data.reminded_importance || false,
          health_teachings: checkbox3Data.health_teachings || false,
          reminded_dangers: checkbox3Data.reminded_dangers || false,
          healthy_diet: checkbox3Data.healthy_diet || false,
          breast_feeding: checkbox3Data.breast_feeding || false,
          compliance_routine: checkbox3Data.compliance_routine || false,
          referred_utz: checkbox3Data.referred_utz || false,
          information_newborn: checkbox3Data.information_newborn || false,
          fes04_folic: checkbox3Data.fes04_folic || false,
          information_family: checkbox3Data.information_family || false,
          folic_acid: checkbox3Data.folic_acid || '',
          fhb: checkbox3Data.fhb || '',
          position: checkbox3Data.position || '',
          presentation: checkbox3Data.presentation || '',
          fundal_height: checkbox3Data.fundal_height || ''
        };
      }
    };

    const validateForm = () => {
      errors.value = {};
      let isValid = true;

      if (!form.value.date_of_visit) {
        errors.value.date_of_visit = 'Date of Visit is required.';
        isValid = false;
      }
      if (!form.value.weight) {
        errors.value.weight = 'Weight is required.';
        isValid = false;
      }
      if (!form.value.bp) {
        errors.value.bp = 'Blood Pressure is required.';
        isValid = false;
      }
      if (!form.value.heart_rate) {
        errors.value.heart_rate = 'Heart Rate is required.';
        isValid = false;
      }

      return isValid;
    };

    const submitForm = () => {
      if (validateForm()) {
        loading.value = true;
        const formData = { ...form.value, prenatalId: props.prenatalId };

        Inertia.post('/trimester3/store', formData, {
          onSuccess: () => {
            loading.value = false;
            alert('Form submitted successfully!');
          },
          onError: (errors) => {
            loading.value = false;
            errors.value = errors;
          }
        });
      } else {
        alert('Please complete all required fields before submitting.');
      }
    };

    const resetForm = () => {
      form.value = {
        date_of_visit: '',
        weight: '',
        bp: '',
        heart_rate: '',
        aog_months: '',
        aog_days: '',
        trimester: '3',
        prenatal_record: false,
        reminded_importance: false,
        health_teachings: false,
        reminded_dangers: false,
        healthy_diet: false,
        breast_feeding: false,
        compliance_routine: false,
        referred_utz: false,
        fhb: '',
        position: '',
        presentation: '',
        fundal_height: ''
      };
      errors.value = {};
    };

    watch(() => props.prefilledData, (newData) => {
      if (newData) {
        populateForm(newData);
      }
    }, { immediate: true });

    return {
      form,
      errors,
      loading,
      submitForm,
      resetForm
    };
  }
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
