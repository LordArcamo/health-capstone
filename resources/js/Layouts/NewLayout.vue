<script setup>
import TopBar from '../Components/TopBar.vue';
import Sidebar from '../Components/SideBar.vue';
</script>

<style>
    .row-container {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap; /* Allow wrapping on smaller screens */
    }
    .main {
        width: 20%;
        flex: 1;
    }
    .content {
        width: 80%;
        flex: 3;
    }
    .border-container {
        background-color: white;
        padding: 20px; /* Reduced padding for smaller screens */
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        align-items: start;
        gap: 20px;
        margin: 0 auto; /* Centering the container */
        max-width: 1200px; /* Max width for larger screens */
    }

    button {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px; /* Reduced padding */
        width: auto; /* Auto width for responsiveness */
        max-width: 100%; /* Ensure it doesn’t overflow */
        color: rgb(0, 0, 0);
        border-radius: 20px;
        transition: 0.5s;
    }
    button:hover {
        color: white;
        background-color: rgb(188, 188, 188);
    }
    svg:hover {
        color: white;
    }
    h1 {
        padding: 0;
        color: rgb(0, 189, 0);
        font-size: 24px; /* Adjusted for smaller screens */
        font-weight: bolder;
    }
    h2 {
        padding: 10px; /* Adjusted for smaller screens */
        font-weight: bolder;
        font-size: 18px; /* Adjusted for smaller screens */
    }
    .nice {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px; /* Reduced padding */
        width: auto;
        max-width: 100%; /* Ensure it doesn’t overflow */
        color: rgb(0, 0, 0);
        border-radius: 20px;
        transition: 0.5s;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .row-container {
            flex-direction: column;
        }
        .main, .content {
            width: 100%;
        }
        .border-container {
            padding: 10px;
        }
        h1 {
            font-size: 20px;
        }
        h2 {
            font-size: 16px;
        }
    }
    
    @media (max-width: 480px) {
        button, .nice {
            flex-direction: column;
            align-items: center;
            padding: 10px;
        }
        h1 {
            font-size: 18px;
        }
        h2 {
            font-size: 14px;
        }
    }
</style>

<template>
    <div class="row-container">
        <Sidebar class="h-screen" />
        <div class="content bg-gray-100 min-h-screen">
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="flex flex-row max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>
            <main>
                <TopBar />
                <slot />
            </main>
        </div>
    </div>
</template>
