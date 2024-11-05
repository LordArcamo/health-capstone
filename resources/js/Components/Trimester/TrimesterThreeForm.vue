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
          <input v-model="form.prenatal_record_updated" type="checkbox" id="prenatal_record_updated" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="prenatal_record_updated" class="ml-2 text-sm text-gray-600">Prenatal record/book updated</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.reminded_importance_fbd" type="checkbox" id="reminded_importance_fbd" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="reminded_importance_fbd" class="ml-2 text-sm text-gray-600">Reminded on importance of FBD</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.health_teachings_given_light_exercise" type="checkbox" id="health_teachings_given_light_exercise" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="health_teachings_given_light_exercise" class="ml-2 text-sm text-gray-600">Health teachings given, light exercise daily</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.reminded_dangers_signs_pregnancy" type="checkbox" id="reminded_dangers_signs_pregnancy" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="reminded_dangers_signs_pregnancy" class="ml-2 text-sm text-gray-600">Reminded on the Dangers Signs of Pregnancy</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.healthy_diet_increase_fluid_intake_encouraged" type="checkbox" id="healthy_diet_increase_fluid_intake_encouraged" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="healthy_diet_increase_fluid_intake_encouraged" class="ml-2 text-sm text-gray-600">Healthy diet and increase fluid intake encouraged</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.breast_feeding_after_delivery_encouraged" type="checkbox" id="breast_feeding_after_delivery_encouraged" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="breast_feeding_after_delivery_encouraged" class="ml-2 text-sm text-gray-600">Breast feeding after delivery encouraged</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.compliance_routine_immunization_encouraged" type="checkbox" id="compliance_routine_immunization_encouraged" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="compliance_routine_immunization_encouraged" class="ml-2 text-sm text-gray-600">Compliance to Routine Immunization encouraged</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.referred_utz_evaluation_pregnancy" type="checkbox" id="referred_utz_evaluation_pregnancy" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="referred_utz_evaluation_pregnancy" class="ml-2 text-sm text-gray-600">Referred for UTZ – evaluation of pregnancy</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.information_newborn_screening_given" type="checkbox" id="information_newborn_screening_given" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="information_newborn_screening_given" class="ml-2 text-sm text-gray-600">Information on Newborn Screening given</label>
        </div>
        <div class="flex items-center">
          <input v-model="form.nkfda" type="checkbox" id="nkfda" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="birth_plan_done" class="ml-2 text-sm text-gray-600">FES04 + folic acid given - # tabs <input v-model="form.nkfda" type="number" id="nkfda" class="h-4 w-10 text-xs text-blue-600 border-gray-300 rounded" >     -given</input> </label>
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
      <!-- <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white font-medium rounded-md shadow-sm hover:bg-blue-700">Submit</button> -->
    </form>
  </div>
</template>

<script>
import { ref } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';

export default {
  setup() {
    const form = useForm({
      date_of_visit: '',
      weight: '',
      bp: '',
      heart_rate: '',
      aog_months: '',
      aog_days: '',
      trimester: '',
      prenatal_checkup: false,
      birth_plan_done: false,
      fhb: '',
      position: '',
      presentation: '',
      fundal_height: '',
    });

    const submitForm = () => {
      form.post(route('prenatal.store'));
    };

    return { form, submitForm };
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
