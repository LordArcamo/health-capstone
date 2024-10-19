<template>
  <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100">
    <div class="bg-white shadow-md rounded-lg p-8 max-w-4xl w-full">
      <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">National Immunization Form</h2>

      <form @submit.prevent="submitForm">
        <!-- Step 1: Patient Information -->
        <div v-if="step === 1">
          <h3 class="text-lg font-semibold mb-4">Patient Information</h3>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block">First Name:</label>
              <input type="text" v-model="form.firstName" class="input" required />
            </div>
            <div>
              <label class="block">Last Name:</label>
              <input type="text" v-model="form.lastName" class="input" required />
            </div>
            <div>
              <label class="block">Middle Name:</label>
              <input type="text" v-model="form.middleName" class="input" />
            </div>
            <div>
              <label class="block">Suffix:</label>
              <input type="text" v-model="form.suffix" class="input"/>
            </div>
            <div>
              <label class="block">Residential Address:</label>
              <input type="text" v-model="form.address" class="input" required />
            </div>
            <div>
              <label class="block">Age:</label>
              <input type="number" v-model="computedAge" class="input" readonly />
            </div>
            <div>
              <label class="block">Birthdate:</label>
              <input type="date" v-model="form.birthdate" class="input" required />
            </div>
            <div>
              <label class="block">Contact Number:</label>
              <input type="text" v-model="form.contact" class="input" />
            </div>
            <div>
              <label class="block">Gender:</label>
              <select v-model="form.sex" class="input" required >
                <option>Male</option>
                <option>Female</option>
              </select>
            </div>
          </div>
          <div class="mt-6 flex justify-center text-right">
            <button @click="nextStep" class="bg-gradient-to-r from-[#0F8F46] to-[#FED035] text-white font-semibold py-2 px-4 rounded shadow hover:from-green-700 hover:to-yellow-500 transition-colors duration-300">Next</button>
          </div>
        </div>

        <!-- Step 2: Consultation Details -->
        <div v-if="step === 2">
          <h3 class="text-lg font-semibold mb-4">Consultation Details</h3>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block">Birth Place:</label>
              <input type="text" v-model="form.birthplace" class="input" required />
            </div>
            <div>
              <label class="block">Blood Type:</label>
              <input type="text" v-model="form.bloodtype" class="input" required />
            </div>
            <div>
              <label class="block">Mother's Name:</label>
              <input type="text" v-model="form.mothername" class="input" required />
            </div>
            <div>
              <label class="block">DSWD NHTS:</label>
              <select v-model="form.dswdNhts" class="input">
                <option>Yes</option>
                <option>No</option>
              </select>
            </div>

            <div class="gap-5">
              <label class="block">Facility Household No.:</label>
              <input type="text" v-model="form.facilityHouseholdno" class="input"/>

              <div>
                <label class="block">Household No.:</label>
                <input type="text" v-model="form.houseHoldno" class="input"/>
              </div>
              
              <div>
                <label class="block">4Ps Member?:</label>
              <select v-model="form.fourpsmember" class="input">
                <option>Yes</option>
                <option>No</option>
              </select>
              </div>

              <div>
                <label class="block">Primary Care Benefit (PCB) Member?:</label>
              <select v-model="form.PCBMember" class="input">
                <option>Yes</option>
                <option>No</option>
              </select>
              </div>
            </div>
           
            <div>
              <label class="block">Philhealth Member:</label>
              <select v-model="form.philhealthMember" class="input">
                <option>Yes</option>
                <option>No</option>
              </select>

              <label class="block">Status Type:</label>
              <select v-model="form.statusType" class="input">
                <option>Member</option>
                <option>Dependent</option>
              </select>

              <label class="block">Philhealth No.:</label>
              <input type="text" v-model="form.philhealthNo" class="input"/>

              <div>
              <label class="block">If Member, please indicate category:</label>
              <select v-model="form.ifMember" class="input">
                <option>FE - Private</option>
                <option>FE - Government</option>
                <option>IE</option>
                <option>Others</option>
              </select>
            </div>
            </div>
          </div>

          <div>
              <label class="block">Family Member</label>
              <select v-model="form.familyMember" class="input">
                <option>FE - Private</option>
                <option>FE - Government</option>
                <option>IE</option>
                <option>Others</option>
              </select>
            </div>
        
          <div class="mt-6 flex justify-between">
            <button @click="prevStep" class="bg-gradient-to-r from-[#FF6B6B] to-[#FF8E53] text-white font-semibold py-2 px-4 rounded shadow hover:from-red-700 hover:to-orange-500 transition-colors duration-300"
            >Back</button>
            <button @click="nextStep" class="bg-gradient-to-r from-[#0F8F46] to-[#FED035] text-white font-semibold py-2 px-4 rounded shadow hover:from-green-700 hover:to-yellow-500 transition-colors duration-300">Next</button>
          </div>
        </div>

        <!-- Step 3: Visit Information -->
        <div v-if="step === 3">
          <h3 class="text-lg font-semibold mb-4">Consultation Details</h3>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <h4 class="block">CPAB</h4>
              <label class="block">TT Status of Mother:</label>
              <input type="text" v-model="form.ttstatus" class="input" />
              <label class="block">Date Assesed:</label>
              <input type="date" v-model="form.dateAssesed" class="input" />
            </div>
           
            <div>
              <h4 class="block">Newborn Screening</h4>
              <label class="block">Date:</label>
              <input type="date" v-model="form.date" class="input" />
              <label class="block">Place:</label>
              <input type="text" v-model="form.place" class="input" />
            </div>
          </div>
          <div>
            <label for="" class="block">Guardian</label>
            <input type="text" v-model="form.guardian" class="input"/>
          </div>
          <div class="mt-6 flex justify-between">
            <button @click="prevStep" class="bg-gradient-to-r from-[#FF6B6B] to-[#FF8E53] text-white font-semibold py-2 px-4 rounded shadow hover:from-red-700 hover:to-orange-500 transition-colors duration-300">Back</button>
            <button type="submit" class="bg-gradient-to-r from-[#0F8F46] to-[#FED035] text-white font-semibold py-2 px-4 rounded shadow hover:from-green-700 hover:to-yellow-500 transition-colors duration-300">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

// Define props
const props = defineProps({
  onSubmit: {
    type: Function,
    required: true,
  },
});

// Reactive form data
const step = ref(1);
const form = ref({
  firstName: '',
  lastName: '',
  middleName: '',
  suffix: '',
  address: '',
  age: '',
  birthdate: '',
  contact: '',
  sex: '',
  birthplace: '',
  bloodtype: '',
  mothername: '',
  dswdNhts: '',
  facilityHouseholdno: '',
  houseHoldno: '',
  fourpsmember: '',
  PCBMember: '',
  philhealthMember: '',
  statusType: '',
  philhealthNo: '',
  ifMember: '',
  familyMember: '',
  ttstatus: '',
  dateAssesed: '',
  date: '',
  place: '',
  guardian: '',
});

// Computed for age
const computedAge = computed(() => calculateAge());

function calculateAge() {
  if (!form.value.birthdate) return '';
  const birthdate = new Date(form.value.birthdate);
  const today = new Date();
  let age = today.getFullYear() - birthdate.getFullYear();
  const monthDiff = today.getMonth() - birthdate.getMonth();
  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
    age--;
  }
  form.value.age = age; // Set age in the reactive form
  return age;
}

function submitForm() {
  try {
    console.log('Submitting form with data:', form.value);
    alert('Form submitted');
    props.onSubmit(form.value); // Pass all form data to the parent
  } catch (error) {
    console.error('Unexpected error during form submission:', error);
  }
}

function nextStep() {
  if (step.value < 3) step.value++;
}

function prevStep() {
  if (step.value > 1) step.value--;
}
</script>

<style>
.input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  margin-top: 0.5rem;
}

.btn {
  background-color: #1d72b8;
  color: white;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn:hover {
  background-color: #155a8a;
}
</style>
