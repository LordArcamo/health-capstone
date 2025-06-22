<template>
  <div class="min-h-screen flex flex-col justify-center items-center">
    <div class="bg-white shadow-md rounded-lg p-8 max-w-4xl w-full">
      <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">National Immunization Form</h2>

      <!-- Step Navigation -->
      <div class="relative z-10 flex justify-between items-center pb-4 border-b">
        <div class="absolute inset-0 flex items-center justify-between px-4">
          <!-- Connecting lines -->
          <div v-for="(_, index) in stepTitles.length - 1" :key="index" class="flex-1 h-0.5 bg-gray-300">
            <div v-if="step > index + 1" class="h-0.5 bg-gradient-to-r from-[#0F8F46] to-[#FED035]"
              :style="{ width: '100%' }"></div>
          </div>
        </div>
        <div v-for="(stepTitle, index) in stepTitles" :key="index" class="relative z-10">
          <button type="button" class="px-4 py-2 rounded-sm text-sm font-semibold flex items-center justify-center"
            :class="{
              'bg-gradient-to-r from-[#0F8F46] to-[#FED035] text-white': step === index + 1,
              'bg-gray-200 text-gray-800': step !== index + 1,
            }" @click="navigateToStep(index + 1)">
            {{ stepTitle }}
          </button>
        </div>
      </div>

      <!-- Alert -->
      <div v-if="alertMessage"
        class="fixed top-16 left-1/2 transform -translate-x-1/2 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg flex items-center z-50"
        role="alert">
        <svg class="fill-current w-6 h-6 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
          <path
            d="M10 15a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm.883-9.885a.4.4 0 00-.766 0l-3.5 9.5a.4.4 0 00.766.27l3.5-9.5a.4.4 0 00-.344-.54h-.07zm-.883 2.55a.6.6 0 100 1.2.6.6 0 000-1.2z" />
        </svg>
        <span>{{ alertMessage }}</span>
        <button @click="closeAlert" class="ml-4 bg-transparent border-0 text-red-700 font-bold focus:outline-none">
          ✕
        </button>
      </div>

      <!-- Form Content -->
      <form @submit.prevent="triggerSubmit">
        <!-- Step 1: Patient Information -->
        <div v-if="step === 1" class="mt-5">
          <div class="grid grid-cols-2 gap-4">
            <!-- Add all Step 1 fields here -->
            <div>
              <label class="block">First Name:</label>
              <input type="text" v-model="form.firstName" class="input" @input="capitalizeName('firstName')" placeholder="Example: Juan" required />
              <span v-if="errors.firstName" class="text-red-600 text-sm">{{ errors.firstName }}</span>
            </div>
            <div>
              <label class="block">Last Name:</label>
              <input type="text" v-model="form.lastName" class="input" @input="capitalizeName('lastName')" placeholder="Example: Dela Cruz" required />
              <span v-if="errors.lastName" class="text-red-600 text-sm">{{ errors.lastName }}</span>
            </div>
            <div>
              <label class="block">Middle Name:</label>
              <input type="text" v-model="form.middleName" class="input" @input="capitalizeName('middleName')" placeholder="Example: Penduko" required />
              <span v-if="errors.middleName" class="text-red-600 text-sm">{{ errors.middleName }}</span>
            </div>
            <div>
              <label for="suffix" class="block text-sm font-medium text-gray-700">Suffix:</label>
              <select v-model="form.suffix"
                class="input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <!-- Placeholder option for selecting suffix -->
                <option value="" disabled selected>Select a Suffix</option>
                <option value="None">None</option>
                <option value="Jr.">Jr.</option>
                <option value="Sr.">Sr.</option>
                <option value="I">I</option>
                <option value="II">II</option>
                <option value="III">III</option>
                <option value="IV">IV</option>
                <option value="V">V</option>
              </select>

              <!-- Error message if no suffix is selected -->
              <span v-if="errors.suffix" class="text-red-600 text-sm">{{ errors.suffix }}</span>
            </div>

            <div>
              <label for="barangay" class="block mb-1">Barangay:</label>
              <select id="barangay" v-model="form.barangay" class="input" required>
                <option disabled value="">Select a Barangay</option>
                <option value="Apas">Apas</option>
                <option value="Aluna">Aluna</option>
                <option value="Andales">Andales</option>
                <option value="Calacapan">Calacapan</option>
                <option value="Gimangpang">Gimangpang</option>
                <option value="Jampason">Jampason</option>
                <option value="Kamelon">Kamelon</option>
                <option value="Kanitoan">Kanitoan</option>
                <option value="Oguis">Oguis</option>
                <option value="Pagahan">Pagahan</option>
                <option value="Poblacion">Poblacion</option>
                <option value="Pontacon">Pontacon</option>
                <option value="San Pedro">San Pedro</option>
                <option value="Sinalac">Sinalac</option>
                <option value="Tawantawan">Tawantawan</option>
                <option value="Tubigan">Tubigan</option>
              </select>
              <span v-if="errors.barangay" class="text-red-600 text-sm">{{ errors.barangay }}</span>
            </div>


            <div>
              <label class="block">Purok:</label>
              <input type="text" v-model="form.purok" class="input" @input="capitalizeName('purok')"
              placeholder="Example: Purok 1A" required />
              <span v-if="errors.purok" class="text-red-600 text-sm">{{ errors.purok }}</span>
            </div>

            <div>
              <label class="block">Birthdate:</label>
              <input type="date" v-model="form.birthdate" class="input" @input="restrictBirthdateInput"
              @change="validateBirthdate" required />
              <span v-if="errors.birthdate" class="text-red-600 text-sm">{{ errors.birthdate }}</span>
            </div>
            <div>
              <label class="block">Age:</label>
              <input type="number" v-model="computedAge" class="input" readonly />
            </div>
            <!-- Contact Number -->
            <div>
              <label class="block mb-1 font-medium text-gray-700">Contact Number:</label>
              <div class="relative">
                <input type="tel" v-model="form.contact" @input="formatContact"
                  class="pl-14 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 w-full"
                  placeholder="Enter 10 digits" required />
                <span
                  class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-sm pointer-events-none">
                  +63
                </span>
              </div>
            </div>

            <div>
              <label class="block">Gender</label>
              <select v-model="form.sex" class="input" required>
                <!-- Placeholder option for selecting suffix -->
                <option value="" disabled selected>Select a Gender</option>
                <option>Male</option>
                <option>Female</option>
              </select>
            </div>
            <!-- Continue the rest of the Step 1 fields... -->
          </div>
          <div class="mt-6 flex justify-center">
            <button @click="nextStep" class="btn bg-gradient-to-r from-[#0F8F46] to-[#FED035] text-white">Next</button>
          </div>
        </div>

        <!-- Step 2: Consultation Details -->
        <div v-if="step === 2" class="mt-5">
          <div class="grid grid-cols-2 gap-4">
            <!-- Birth Place -->
            <div>
              <label class="block">Birth Place:</label>
              <input type="text" v-model="form.birthplace"
              placeholder="Example: Initao"
              @input="capitalizeName('birthplace')"
              class="input">
              <span v-if="errors.birthplace" class="text-red-600 text-sm">{{ errors.birthplace }}</span>
            </div>

            <!-- Blood Type -->
            <div>
              <label class="block">Blood Type:</label>
              <select v-model="form.bloodtype" class="input" required>
                <option value="">Select Blood Type</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
              </select>
              <span v-if="errors.bloodtype" class="text-red-600 text-sm">{{ errors.bloodtype }}</span>
            </div>

            <!-- Mother's Name -->
            <div>
              <label class="block">Mother's Name:</label>
              <input type="text" v-model="form.mothername"
              @input="capitalizeName('mothername')"
              class="input"
              placeholder="Example: Maria Cruz" required />
              <span v-if="errors.mothername" class="text-red-600 text-sm">{{ errors.mothername }}</span>
            </div>

            <!-- DSWD NHTS -->
            <div>
              <label class="block">DSWD NHTS:</label>
              <select v-model="form.dswdNhts" class="input">
                <option>Yes</option>
                <option>No</option>
              </select>
              <span v-if="errors.dswdNhts" class="text-red-600 text-sm">{{ errors.dswdNhts }}</span>
            </div>

            <div class="grid gird-cols-1 gap-5">
              <!-- Facility Household No. -->
              <div>
                <label class="block">Facility Household No.:</label>
                <input type="text" v-model="form.facilityHouseholdno" class="input"
                  placeholder="Enter Facility Household No." />
              </div>

              <!-- Household No. -->
              <div>
                <label class="block">Household No.:</label>
                <input type="text" v-model="form.houseHoldno" class="input" placeholder="Enter Household No." />
              </div>

              <!-- 4Ps Member -->
              <div>
                <label class="block">4Ps Member?:</label>
                <select v-model="form.fourpsmember" class="input">
                  <option>Yes</option>
                  <option>No</option>
                </select>
                <span v-if="errors.fourpsmember" class="text-red-600 text-sm">{{ errors.fourpsmember }}</span>
              </div>

              <!-- PCB Member -->
              <div>
                <label class="block">Primary Care Benefit (PCB) Member?:</label>
                <select v-model="form.PCBMember" class="input">
                  <option>Yes</option>
                  <option>No</option>
                </select>
                <span v-if="errors.PCBMember" class="text-red-600 text-sm">{{ errors.PCBMember }}</span>
              </div>
            </div>

            <div class="flex flex-col gap-5">
              <!-- Family Member -->
              <div>
                <label class="block">Family Member</label>
                <select v-model="form.familyMember" class="input">
                  <option>FE - Private</option>
                  <option>FE - Government</option>
                  <option>IE</option>
                  <option>Others</option>
                </select>
              </div>

              <!-- Philhealth Member -->

              <!-- Philhealth Status -->
              <div class="flex flex-col gap-5">
                <div>
                  <label for="philhealthStatus" class="block font-medium text-gray-700">Philhealth Status:</label>
                  <select id="philhealthStatus" v-model="form.philhealthStatus"
                    class="input border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required>
                    <option value="" disabled selected>Select Philhealth Status</option>
                    <option value="Member">Member</option>
                    <option value="Dependent">Dependent</option>
                  </select>
                  <span v-if="errors.philhealthStatus" class="text-red-600 text-sm">{{ errors.philhealthStatus }}</span>
                </div>

                <!-- Philhealth ID -->
                <div v-if="form.philhealthStatus === 'Member'">
                  <label for="philhealthNo" class="block font-medium text-gray-700">Philhealth ID Number:</label>
                  <input id="philhealthNo" v-model="form.philhealthNo" type="text"
                    class="input border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Example: 1234-5678-9101" />
                </div>
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
          </div>

          <div class="mt-6 flex justify-between">
            <button @click="prevStep" class="btn bg-gradient-to-r from-[#FF6B6B] to-[#FF8E53]">Back</button>
            <button @click="nextStep" class="btn bg-gradient-to-r from-[#0F8F46] to-[#FED035]">Next</button>
          </div>
        </div>

        <!-- Step 3: Visit Information -->
        <div v-if="step === 3" class="mt-5">
          <div class="grid grid-cols-2 gap-4">
            <!-- CPAB Section -->
            <div class="flex flex-col gap-5">
              <label class="block text-lg font-semibold">CPAB</label>
              <div>
                <label class="block">TT Status of Mother:</label>
                <input type="text" v-model="form.ttstatus"
                @input="capitalizeName('ttstatus')"
                class="input"
                placeholder="Example: Completed or Pending" />
                <span v-if="errors.ttstatus" class="text-red-600 text-sm">{{ errors.ttstatus }}</span>

              </div>
              <div>
                <label class="block">Date Assessed:</label>
                <input type="date" v-model="form.dateAssesed" class="input" placeholder="Select the assessment date" />
                <span v-if="errors.dateAssesed" class="text-red-600 text-sm">{{ errors.dateAssesed }}</span>
              </div>
            </div>

            <!-- Newborn Screening Section -->
            <div class="flex flex-col gap-5">
              <h4 class="block text-lg font-semibold">Newborn Screening</h4>
              <div>
                <label class="block">Date:</label>
                <input type="date" v-model="form.date" class="input" placeholder="Select the newborn screening date" />
                <span v-if="errors.date" class="text-red-600 text-sm">{{ errors.date }}</span>
              </div>
              <div>
                <label class="block">Place:</label>
                <input type="text" v-model="form.place"
                @input="capitalizeName('place')"
                class="input"
                placeholder="Example: Barangay Health Center" />
                <span v-if="errors.place" class="text-red-600 text-sm">{{ errors.place }}</span>
              </div>
            </div>
          </div>

          <!-- Guardian Information -->
          <div class="mt-5">
            <label for="guardian" class="block">Guardian</label>
            <input id="guardian" type="text" v-model="form.guardian"
            @input="capitalizeName('guardian')"
            class="input"
              placeholder="Example: John Brawl (Father)" />
              <span v-if="errors.guardian" class="text-red-600 text-sm">{{ errors.guardian }}</span>
          </div>

          <!-- Navigation Buttons -->
          <div class="mt-6 flex justify-between">
            <button @click="prevStep" class="btn bg-gradient-to-r from-[#FF6B6B] to-[#FF8E53]">Back</button>
            <button @click="nextStep" class="btn bg-gradient-to-r from-[#0F8F46] to-[#FED035]">Next</button>
          </div>
        </div>

        <!-- Step 4: Review Submitted Data -->
        <div v-if="step === 4">
          <h3 class="text-lg font-semibold mb-4">Review Your Information</h3>
          <div class="grid grid-cols-2 gap-4">
            <!-- Loop through form fields to display data -->
            <div v-for="(value, key) in form" :key="key">
              <label class="block">{{ formatLabel(key) }}:</label>
              <p class="input">{{ key === 'age' && value === 0 ? 'Less than 1 year' : (value || 'Not Provided') }}</p>
            </div>
          </div>
          <div class="mt-6 flex justify-between">
            <button @click="prevStep" class="btn">Back</button>
            <button type="submit" class="btn">Submit</button>
          </div>
        </div>
      </form>
    </div>
    <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
      <!-- Modal Content -->
      <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <!-- Modal Header -->
        <h2 class="text-xl font-bold text-gray-800 mb-4">Are you sure?</h2>
        <!-- Modal Body -->
        <p class="text-gray-600 mb-6">Do you want to submit the form?</p>
        <!-- Modal Actions -->
        <div class="flex justify-end space-x-3">
          <button @click="cancelSubmit"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
            Cancel
          </button>
          <button @click="confirmSubmit"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
            Yes, Submit
          </button>
        </div>
      </div>
    </div>
  </div>
</template>



<script>
export default {
  props: {
    selectedPatient: {
    type: Object,
    required: false,
    default: () => ({}),
    },
    onSubmit: Function,
  },
  data() {
    return {
      alertMessage: '',
      showModal: false,
      stepTitles: ['Patient Information', 'Consultation Details', 'Visit Information', 'Review Information'], // Step titles for navigation
      step: 1, // Current step in the form process
      form: {
        firstName: '',
        lastName: '',
        middleName: '',
        suffix: '',
        purok: '',
        barangay: '',
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
        philhealthStatus: '',
        philhealthNo: '',
        ifMember: '',
        familyMember: '',
        ttstatus: '',
        dateAssesed: '',
        date: '',
        place: '',
        guardian: '',
      },
      errors: {
        contact: '',
        birthdate: '',
      },
    };
  },
  computed: {
    computedAge() {
      if (!this.form.birthdate) return '';
      const birthDate = new Date(this.form.birthdate);
      const today = new Date();

      let age = today.getFullYear() - birthDate.getFullYear();
      const hasBirthdayOccurred =
        today.getMonth() > birthDate.getMonth() ||
        (today.getMonth() === birthDate.getMonth() && today.getDate() >= birthDate.getDate());

      if (!hasBirthdayOccurred) {
        age--;
      }

      // Return 0 if the calculated age is less than 1
      return age < 1 ? 0 : age;
    },
  },
  watch: {
    selectedPatient: {
      immediate: true,
      handler(newPatient) {
        if (newPatient && newPatient.personalId === null) {
          // Clear the form for a new patient
          this.resetForm();
        } else if (newPatient && Object.keys(newPatient).length > 0) {
          // Populate the form with selected patient data
          this.populateForm(newPatient);
        }
      },
    },
    // Watch for changes in birthdate and update the age field
    computedAge(newAge) {
      this.form.age = newAge;
    },
    'form.birthdate': function () {
      this.form.age = this.computedAge; // Update age when birthdate changes
    },
  },
  'form.philhealthStatus': function (newVal) {
        if (newVal === 'Member') {
            // Reset the fields to empty when Referral is selected
            this.form.philhealthNo = '';
        } else {
            // Set the fields to "None" when not Referral
            this.form.philhealthNo = 'None';
        }
    },
  methods: {
    resetForm() {
      this.form = {
        firstName: '',
        lastName: '',
        middleName: '',
        suffix: '',
        purok: '',
        barangay: '',
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
        philhealthStatus: '',
        philhealthNo: '',
        ifMember: '',
        familyMember: '',
        ttstatus: '',
        dateAssesed: '',
        date: '',
        place: '',
        guardian: '',
      };
      this.errors = {};
    },
    populateForm(patient) {
      console.log("Populating form with patient:", patient);
      this.form = {
        firstName: this.selectedPatient?.firstName || '',
        lastName: this.selectedPatient?.lastName || '',
        middleName: this.selectedPatient?.middleName || '',
        suffix: this.selectedPatient?.suffix || '',
        purok: this.selectedPatient?.purok || '',
        barangay: this.selectedPatient?.barangay || '',
        age: '',
        birthdate: this.selectedPatient?.birthdate || '',
        contact: this.selectedPatient?.contact || '',
        sex: this.selectedPatient?.sex || '',

      };
      this.form.age = this.computedAge;

    },
    formatLabel(key) {
      // Convert camelCase keys to readable labels
      return key
        .replace(/([A-Z])/g, " $1") // Add spaces before uppercase letters
        .replace(/^./, (str) => str.toUpperCase()); // Capitalize the first letter
    },
    capitalizeName(field) {
      if (this.form[field]) {
        this.form[field] = this.form[field]
          .toLowerCase() // Convert entire string to lowercase first
          .replace(/\b\w/g, char => char.toUpperCase()); // Capitalize first letter of each word
      }
    },
    restrictBirthdateInput(event) {
      const input = event.target.value;

      // Restrict year part to a maximum of 4 digits
      const parts = input.split('-');
      if (parts[0] && parts[0].length > 4) {
        parts[0] = parts[0].slice(0, 4); // Limit the year to 4 digits
      }

      // Reassemble the date and update the form value
      this.form.birthdate = parts.join('-');
    },
    validateBirthdate() {
      this.errors.birthdate = ''; // Reset error message

      if (!this.form.birthdate) {
        this.errors.birthdate = 'Birthdate is required.';
        return;
      }

      const birthDate = new Date(this.form.birthdate);
      const today = new Date();
      const maxDate = new Date(today.getFullYear() - 200, today.getMonth(), today.getDate());

      if (birthDate < maxDate) {
        this.errors.birthdate = 'Please enter a birthdate that is valid.';
      }
    },

    handleSubmit(event) {
      event.preventDefault();
      this.triggerSubmit();
    },

    // Validation for Step 1
    validateStep1() {
      this.errors = {};
      let valid = true;

      if (!this.form.firstName) {
        this.errors.firstName = 'First name is required.';
        valid = false;
      }
      if (!this.form.lastName) {
        this.errors.lastName = 'Last name is required.';
        valid = false;
      }
      if (!this.form.middleName) {
        this.errors.middleName = 'Middle name is required.';
        valid = false;
      }
      if (!this.form.purok) {
        this.errors.purok = 'Purok is required.';
        valid = false;
      }
      if (!this.form.barangay) {
        this.errors.barangay = 'Barangay is required.';
        valid = false;
      }
      if (!this.form.birthdate) {
        this.errors.birthdate = 'Birthdate is required.';
        valid = false;
      }
      return valid;
    },

    // Validation for Step 2
    validateStep2() {
      this.errors = {};
      let valid = true;

      // Additional validation logic can be added here
      if (!this.form.birthplace) {
        this.errors.birthplace = 'Birth Place is required.';
        valid = false;
      }
      if (!this.form.bloodtype) {
        this.errors.bloodtype = 'Blood Type is required.';
        valid = false;
      }
      if (!this.form.mothername) {
        this.errors.mothername = 'Mother Name is required.';
        valid = false;
      }
      if (!this.form.dswdNhts) {
        this.errors.dswdNhts = 'DSWD NHTS is required.';
        valid = false;
      }
      if (!this.form.fourpsmember) {
        this.errors.fourpsmember = 'Four Ps Member is required.';
        valid = false;
      }
      if (!this.form.PCBMember) {
        this.errors.PCBMember = 'PCB Member is required.';
        valid = false;
      }
      if (!this.form.philhealthStatus) {
        this.errors.philhealthStatus = 'Philhealth Status is required.';
        valid = false;
      }
      return valid;
    },

    // Validation for Step 3
    validateStep3() {
      this.errors = {};
      let valid = true;

      if (!this.form.ttstatus) {
        this.errors.ttstatus = 'TT status is required.';
        valid = false;
      }
      if (!this.form.dateAssesed) {
        this.errors.dateAssesed = 'Date is required.';
        valid = false;
      }
      if (!this.form.date) {
        this.errors.date = 'Date is required.';
        valid = false;
      }
      if (!this.form.place) {
        this.errors.place = 'Place is required.';
        valid = false;
      }
      if (!this.form.guardian) {
        this.errors.guardian = 'guardian is required.';
        valid = false;
      }
      return valid;
    },

    // Move to the next step if the current step is valid
    nextStep() {
      if (this.step === 1 && this.validateStep1()) {
        this.step++;
      } else if (this.step === 2 && this.validateStep2()) {
        this.step++;
      } else if (this.step === 3 && this.validateStep3()) {
        this.step++; // This should correctly move to step 4
      }
    },
    // Move to the previous step
    prevStep() {
      if (this.step > 1) {
        this.step--;
      }
    },

    // Format the contact field to only allow digits and limit to 10 characters
    formatContact() {
      this.form.contact = this.form.contact.replace(/[^0-9]/g, '').slice(0, 10);
    },
    navigateToStep(targetStep) {
      if (targetStep > this.step) {
        // Validate the current step before proceeding
        const isCurrentStepValid =
          (this.step === 1 && this.validateStep1()) ||
          (this.step === 2 && this.validateStep2()) ||
          (this.step === 3 && this.validateStep3());

        if (!isCurrentStepValid) {
          this.alertMessage = 'Please Fill In the Needed Details Before Proceeding to the Next Step.';

          // Automatically close the alert after 2 seconds
          setTimeout(() => {
            this.alertMessage = '';
          }, 2000);

          return; // Prevent navigation
        }
      }
      // If validation passes or moving backward, update the step
      this.step = targetStep;
    },
    closeAlert() {
      this.alertMessage = ''; // Close the alert
    },
    formatContact() {
      // Ensure the input starts with "09", remove non-numeric characters, and limit to 10 digits
      if (!this.form.contact.startsWith('0')) {
        this.form.contact = '0' + this.form.contact.replace(/[^0-9]/g, '');
      } else {
        this.form.contact = this.form.contact.replace(/[^0-9]/g, '').slice(0, 11);
      }
    },

    // Submit the form after validating all steps
    triggerSubmit() {
      if (this.validateStep1() && this.validateStep2() && this.validateStep3()) {
        this.showModal = true; // Show confirmation modal
      } else {
        this.successMessage = 'Please complete all required fields before submitting.';
      }
    },
    confirmSubmit() {
      // Prepare the form data and parse weight and height as numbers
      const payload = {
        personalId: this.selectedPatient?.personalId || null,
        id: this.id,
        firstName: this.form.firstName,
        lastName: this.form.lastName,
        middleName: this.form.middleName,
        suffix: this.form.suffix,
        purok: this.form.purok,
        barangay: this.form.barangay,
        age: this.form.age,
        birthdate: this.form.birthdate,
        contact: this.form.contact,
        sex: this.form.sex,
        birthplace: this.form.birthplace,
        bloodtype: this.form.bloodtype,
        mothername: this.form.mothername,
        dswdNhts: this.form.dswdNhts,
        facilityHouseholdno: this.form.facilityHouseholdno,
        houseHoldno: this.form.houseHoldno,
        fourpsmember: this.form.fourpsmember,
        PCBMember: this.form.PCBMember,
        philhealthStatus: this.form.philhealthStatus,
        philhealthNo: this.form.philhealthNo || 'None',
        ifMember: this.form.ifMember,
        familyMember: this.form.familyMember,
        ttstatus: this.form.ttstatus,
        dateAssesed: this.form.dateAssesed,
        date: this.form.date,
        place: this.form.place,
        guardian: this.form.guardian,

      };

      if (!this.selectedPatient?.personalId) {
        Object.assign(payload, {
          firstName: this.form.firstName,
          lastName: this.form.lastName,
          middleName: this.form.middleName,
          suffix: this.form.suffix,
          purok: this.form.purok,
          barangay: this.form.barangay,
          age: this.form.age,
          birthdate: this.form.birthdate,
          contact: this.form.contact,
          sex: this.form.sex,
        });
      }
      console.log('Submitting form with payload:', payload);
      // Emit the parsed form data
      this.$emit('submitForm', payload);

      // Display success message
      this.successMessage = 'Form submitted successfully!';
      this.showModal = false; // Hide modal
      this.errors = {};
      this.resetForm();
    },
    cancelSubmit() {
      this.showModal = false; // Hide modal
    },
  },
  mounted() {
    console.log('Received personalInfo:', this.personalInfo);
    if (!this.selectedPatient || Object.keys(this.selectedPatient).length === 0) {
      console.log('Rendering empty form for new patient');
    } else {
      console.log('Rendering form for existing patient:', this.selectedPatient);
      this.populateForm(this.selectedPatient);
    }
  },
};
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
