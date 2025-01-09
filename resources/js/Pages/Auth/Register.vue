<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, usePage, router } from '@inertiajs/vue3';
import { onMounted, onBeforeMount } from 'vue';

const props = defineProps({
    auth: Object
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'staff',
});

const { auth } = usePage().props;

// Function to check if user is authorized
const checkAuthorization = () => {
    const user = auth?.user;
    if (!user || user.role !== 'admin') {
        router.visit('/dashboard'); // Redirect to dashboard if not admin
        return false;
    }
    return true;
};

// Check authorization before mounting
onBeforeMount(() => {
    if (!checkAuthorization()) {
        return;
    }
});

// Double-check authorization on mount
onMounted(() => {
    if (!checkAuthorization()) {
        return;
    }
});

const submit = () => {
    // Check authorization before submitting
    if (!checkAuthorization()) {
        return;
    }

    form.post(route('admin.register.store'), {
        preserveScroll: true,
        onSuccess: () => {
            // Show success alert
            alert('Successfully registered!');
            form.reset();
            router.visit(route('admin.register.staff'));
        },
        onError: () => {
            // Handle errors if needed
        },
    });
};
</script>

<template>
    <div v-if="auth?.user?.role === 'admin'">
        <Head title="Register New User" />

        <MainLayout>
            <div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-lg mt-10">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Register New User</h2>

                <form @submit.prevent="submit">
                    <div>
                        <InputLabel for="name" value="Name" />
                        <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="email" value="Email" />
                        <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password" value="Password" />
                        <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                            autocomplete="new-password" />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password_confirmation" value="Confirm Password" />
                        <TextInput id="password_confirmation" type="password" class="mt-1 block w-full"
                            v-model="form.password_confirmation" required autocomplete="new-password" />
                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="role" value="Role" />
                        <select id="role" v-model="form.role"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="staff">Staff</option>
                            <option value="doctor">Doctor</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.role" />
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <Link :href="route('admin.dashboard')"
                            class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4">
                            Cancel
                        </Link>

                        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Register
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </MainLayout>
    </div>
    <div v-else>
        <!-- This will never be shown because of the redirects, but it's good practice -->
        <Head title="Unauthorized" />
        <div class="min-h-screen flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-red-600">Unauthorized Access</h1>
                <p class="mt-2">You do not have permission to view this page.</p>
                <Link :href="route('dashboard')" class="mt-4 text-blue-600 hover:underline">Return to Dashboard</Link>
            </div>
        </div>
    </div>
</template>
