<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import NewLayout from '@/Layouts/MainLayout.vue';
import ITRFormDoctor from '@/Components/ITRFormDoctor.vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
  consultationDetails: {
    type: Object,
    required: false,
    default: () => ({}),
  },
  auth: {
    type: Object,
    required: true
  }
});

// Simply pass through the user data
const loggedInUser = computed(() => {
  const user = props.auth?.user;
  if (!user) return null;

  return {
    first_name: user.first_name || '',
    middle_name: user.middle_name || '',
    last_name: user.last_name || '',
    specialization: user.specialization || '',
    prc_number: user.prc_number || '',
  };
});

console.log('Auth user:', props.auth?.user);

function submitForm(payload) {
  console.log("Submitting from parent:", payload);

  Inertia.post("/store/itr", payload, {
    onSuccess: () => {
      alert("Record saved successfully!");
    },
    onError: (errors) => {
      console.error("Error saving record:", errors);
      alert("Failed to save record.");
    },
  });
}
</script>

<template>
  <Head title="Individual Treatment Record" />
  <NewLayout>
    <div v-if="loggedInUser">
      <ITRFormDoctor
        :consultationDetails="consultationDetails || {}"
        :loggedInUser="loggedInUser"
        @submitForm="submitForm"
      />
    </div>
    <div v-else class="p-4 text-red-500 bg-red-50 rounded">
      <p class="font-medium">Error: User data not available</p>
      <p class="text-sm">Please ensure you are logged in with a doctor account.</p>
    </div>
  </NewLayout>
</template>
