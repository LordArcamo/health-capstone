<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<style>
     .container{
          display: flex;
          flex-direction: row;
          background-color: white;
          color: black;
          padding: 20px;
          gap: 50px;
     }
     .patient-card{
          padding: 50px 50px 50px 50px;
          background-color: black;
          border-radius: 20px;
          color: white;
          width: 50%;
     }
     .date-card{
          padding: 50px 50px 50px 50px;
          background-color: rgb(47, 0, 134);
          border-radius: 20px;
          color: white;
          width: 50%;
     }
     .cases-card{
          padding: 50px 50px 50px 50px;
          background-color: rgb(13, 203, 255);
          border-radius: 20px;
          color: white;
          width: 100%;
     }
</style>

<template>
    <Head title="Dashboard" />

   <NewLayout>
        <div>
               <div class="container">
                    <div class="patient-card">
                         Total Patients
                    </div>
                    <div class="date-card">
                         Date
                    </div>
               </div>
               <div class="container">
                    <div class="cases-card">
                         Cases
               </div>
               </div>
        </div>
   </NewLayout>
</template>
