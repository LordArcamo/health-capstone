<script setup>
import NewLayout from '@/Layouts/NewLayout.vue';
import { Head } from '@inertiajs/vue3';
import Box from '../Components/Box.vue';
import longbox from '../Components/LongBox.vue';
import chart from '../Components/BarChart.vue';
import linechart from '../Components/LineChart.vue'


</script>

<style>
/* .container{
          background-color: white;
          padding: 20px;
          gap: 50px;
     }  */
/* .patient-card{
          display: flex;
          flex-direction: column;
          gap: 10px;
          padding: 50px 50px 50px 50px;
          background-color: black;
          border-radius: 20px;
          color: white;
          width: 50%;
     }
     .date-card{
          display: flex;
          flex-direction: column;
          gap: 10px;
          padding: 50px 50px 50px 50px;
          background-color: rgb(85, 57, 222);
          border-radius: 20px;
          color: white;
          width: 50%;
     }
     .cases-card{
          padding: 50px 50px 50px 50px;
          background-color: rgb(194, 231, 241);
          border-radius: 20px;
          color: white;
          width: 100%;
     } */

p {
     display: flex;
     gap: 10px;
}
</style>

<template>

     <Head title="Dashboard" />

     <NewLayout>
          <div class="overflow-y-auto">
               <div class="container gap-4 my-10 px-10 flex flex-row">

                    <Box>
                         <h1 class="text-green">Total Patients</h1>
                         <p class="text-black text-lg"><svg xmlns="http://www.w3.org/2000/svg" height="20px"
                                   viewBox="0 0 448 512">
                                   <path fill="#ffffff"
                                        d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z" />
                              </svg>100,000</p>
                    </Box>
                    <Box>
                         <h1>Date</h1>
                         <p><svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 448 512">
                                   <path fill="#ffffff"
                                        d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z" />
                              </svg>100,000</p>
                    </Box>

               </div>

               <div class="container gap-10 my-10 px-10 flex flex-col overflow-x-auto">
                    <longbox>
                         <chart />
                    </longbox>

                    <Box>
                         <linechart />
                    </Box>
               </div>


          </div>
     </NewLayout>
</template>
