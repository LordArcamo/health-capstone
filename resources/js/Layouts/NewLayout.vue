<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

</script>

<style>
    .row-container{
        display: flex;
        flex-direction: row;
        height: 100vh;
    }    
    .main{
        width: 20%;
    }
    .content{
        width: 80%;
    }
    .border-container{
        height: 100vh;
        background-color: white;
        border-radius: 20px;
        margin:0px 10px 20px 10px;
        padding: 40px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        display: flex;
        flex-direction: column;
        align-items: start;
        gap: 20px;
    }

    button{
        display: flex;
        align-items: start;
        padding: 20px;
        width: 200px;
        color:rgb(0, 0, 0);
        border-radius: 20px;
        transition: 0.5s;
    }
    button:hover{
        color: white;
        background-color: rgb(188, 188, 188);
    }
    h2{
        padding:20px;
        font-weight: bolder;
    }

</style>

<template>
    <div class="row-container">
        <div class="main">
            <div class="border-container">
                <img src={} alt="logo">
                <i class="fa-solid fa-house"></i>
                <button onclick="document.location='/dashboard'">Dashboard</button>
                <button onclick="document.location='#'">Patients</button>
                <button onclick="document.location='/checkup'">Check Up</button>
                <button onclick="document.location='#'">Mortality</button>

                <h2>Account Details</h2>
                <button onclick="document.location='#'">Accounts</button>
                <button onclick="document.location='#'">Settings</button>
                <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Sign Out
                            </ResponsiveNavLink>

            </div>
        </div>
        <div class="content">
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>