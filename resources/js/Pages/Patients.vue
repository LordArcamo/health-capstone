<template>
  <div>
    <WelcomeModal :patients="patients" />
  </div>
</template>

<script>
import WelcomeModal from './WelcomeModal.vue';
import { Inertia } from '@inertiajs/inertia';

export default {
  components: {
    WelcomeModal,
  },
  data() {
    return {
      patients: [], // Initialize patients as an empty array
    };
  },
  mounted() {
    // Fetch initial patients if needed or leave empty for now
    Inertia.get(route('patients.index'), {
      onSuccess: (page) => {
        this.patients = page.props.patients; // Assuming you get initial patients here
      },
    });
  },
};
</script>
