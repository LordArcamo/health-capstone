<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import Box from '@/Components/Box.vue';
import chart from '../Components/TrendingChart.vue';
import ShortBox from '@/Components/ShortBox.vue';
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import DateCard from '@/Components/DateCard.vue';
import DiseaseCard from '@/Components/DiseaseCard.vue';
import DonutChart from '@/Components/DonutChart.vue';
import PatientChart from '@/Components/PatientChart.vue';

// Define the props that will be passed from the backend (Laravel)
const props = defineProps({
     totalPatients: Number,  // Assuming 'totalPatients' is a number
});

// Reactive variable for the date
const currentDate = ref('');

// Function to update the date in the Philippine timezone
const updateDate = () => {
     const options = {
          year: 'numeric',
          month: 'long',
          day: 'numeric',
          timeZone: 'Asia/Manila' // Philippine timezone
     };

     // Get the current date and time in the Philippine timezone
     const philippinesDate = new Date().toLocaleDateString('en-PH', options);

     // Update the currentDate reactive variable
     currentDate.value = philippinesDate;
};

// Update the date when the component is mounted
onMounted(() => {
     updateDate(); // Initial date update
     setInterval(updateDate, 1000 * 60 * 60); // Update every hour
});
</script>

<template>

     <Head title="Dashboard" />

     <NewLayout>
          <div class="overflow-y-auto w-full">
               <div class=" w-full gap-4 my-10 px-10 flex flex-row">


                    <Link href="/patients/itrtable" class="block w-full cursor-pointer">
                    <ShortBox>
                         <div class="flex flex-col items-start gap-2">
                              <h1 class="text-green">Total Patients</h1>
                              <p class="text-black text-lg text-center flex items-center justify-center">
                                   <font-awesome-icon :icon="['fas', 'user']" class="mr-3 avatar-icon" />
                                   {{ props.totalPatients }} <!-- Display the dynamic total patients count -->
                              </p>
                         </div>
                    </ShortBox>
                    </Link>

                    <DateCard />

                    <ShortBox>
                         <div class="flex flex-col items-start gap-2">
                              <h1 class="text-green">Referred Patients</h1>
                              <p class="text-black text-lg text-center flex items-center justify-center">
                                   <font-awesome-icon :icon="['fas', 'user']" class="mr-3 avatar-icon" />
                                   10
                              </p>
                         </div>
                    </ShortBox>

                    <DiseaseCard />

               </div>
               <div class="flex w-full gap-10 p-10">
                    <!-- Left Section (70%) -->
                    <div class="flex-[7] bg-white p-12 rounded-lg">
                         <PatientChart />
                    </div>

                    <!-- Right Section (30%) -->
                    <div class="flex-[3] flex bg-white p-6 items-center rounded-lg">
                         <!-- Add your content here -->
                         <DonutChart/>
                    </div>
               </div>

               <!-- Charts Section -->
               <div class="container w-full flex flex-col gap-10 my-10 px-10">

                    <Box class="p-10">
                         <chart />
                    </Box>
               </div>
          </div>
     </NewLayout>
</template>

<style scoped>
/* Optional styles for better design */
.avatar-icon {
     font-size: 24px;
}
</style>
