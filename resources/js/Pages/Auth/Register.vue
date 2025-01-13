<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref, watch, onMounted, computed } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faEye, faEyeSlash, faUpload, faTimes, faUserShield, faUserCog } from '@fortawesome/free-solid-svg-icons';

// Form State
const form = useForm({
    first_name: '',
    middle_name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: '',
    permissions: [],
    phone: '+63',
    purok: '',
    barangay: '',
    city: '',
    profile_picture: null,
    prc_number: '',
    specialization: '',
    prc_validity: '',
});

// UI State
const step = ref(1);
const maxStep = 4;
const showPassword = ref(false);
const showConfirmPassword = ref(false);
const showRoleModal = ref(false);
const profilePicturePreview = ref(null);
const permissionError = ref('');

// Define available permissions
const availablePermissions = {
    staff: [
        { value: 'view_patients', label: 'View Patients' },
        { value: 'create_records', label: 'Create Records' }
    ],
    doctor: [
        { value: 'view_patients', label: 'View Patients' },
        { value: 'edit_patients', label: 'Edit Patients' },
        { value: 'create_records', label: 'Create Records' }
    ],
    admin: [
        { value: 'view_patients', label: 'View Patients' },
        { value: 'edit_patients', label: 'Edit Patients' },
        { value: 'create_records', label: 'Create Records' },
        { value: 'manage_users', label: 'Manage Users' }
    ]
};

// Computed property for role-specific permissions
const roleSpecificPermissions = computed(() => {
    return form.role ? availablePermissions[form.role] : [];
});

// Role Change Handler
const handleRoleChange = () => {
    form.permissions = []; // Reset permissions when role changes
    if (form.role === 'doctor') {
        form.permissions = ['view_patients', 'edit_patients', 'create_records'];
    } else if (form.role === 'staff') {
        form.permissions = ['view_patients', 'create_records'];
    } else if (form.role === 'admin') {
        form.permissions = ['view_patients', 'edit_patients', 'create_records', 'manage_users'];
    }
    permissionError.value = ''; // Clear any previous permission errors
};

// Save permissions
const savePermissions = () => {
    if (form.permissions.length === 0) {
        permissionError.value = 'Please select at least one permission';
        return;
    }
    showRoleModal.value = false;
    permissionError.value = '';
};

// Add watcher for role changes
watch(() => form.role, (newRole) => {
    if (newRole) {
        handleRoleChange();
    }
});

// Password Visibility Toggles
const togglePasswordVisibility = () => showPassword.value = !showPassword.value;
const toggleConfirmPasswordVisibility = () => showConfirmPassword.value = !showConfirmPassword.value;

// 📞 Phone Number Validation Function
const validatePhoneNumber = () => {
    const phonePattern = /^\+63\d{10}$/;  // Must start with +63 and followed by exactly 10 digits


    if (!form.phone) {
        form.setError('phone', 'Mobile number is required.');
    } else if (!phonePattern.test(form.phone)) {
        form.setError('phone', 'Invalid format. Example: +63 912 345 6789');
    } else {
        form.clearErrors('phone');
    }
};

// Profile Picture Upload with Preview
const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file && file.type.startsWith('image/')) {
        form.profile_picture = file;
        profilePicturePreview.value = URL.createObjectURL(file);
    } else {
        alert("Please upload a valid image file.");
    }
};

const removeProfilePicture = () => {
    form.profile_picture = null;
    profilePicturePreview.value = null;
};

// Step Validation
const validateStep = () => {
    let isValid = true;
    form.clearErrors();

    if (step.value === 1) {
        if (!form.first_name) form.setError('first_name', 'First name is required');
        if (!form.last_name) form.setError('last_name', 'Last name is required');
        if (!form.email) form.setError('email', 'Email is required');
    }

    if (step.value === 2) {
        if (!form.purok) form.setError('purok', 'Purok is required');
        if (!form.barangay) form.setError('barangay', 'Barangay is required');
        if (!form.city) form.setError('city', 'City is required');
        if (!form.phone) form.setError('phone', 'Phone number is required');
    }

    if (step.value === 3 && !form.role) {
        form.setError('role', 'Role selection is required');
    }

    if (step.value === 4) {
        if (!form.password) form.setError('password', 'Password is required');
        if (form.password !== form.password_confirmation) {
            form.setError('password_confirmation', 'Passwords do not match');
        }
    }

    isValid = Object.keys(form.errors).length === 0;
    return isValid;
};

// Navigation
const nextStep = () => {
    if (validateStep() && step.value < maxStep) {
        step.value++;
    }
};

const prevStep = () => {
    if (step.value > 1) step.value--;
};

// Submit Form
const submit = () => {
    if (validateStep()) {
        // Create FormData object for file upload
        const formData = new FormData();
        
        // Add all form fields to FormData
        Object.keys(form).forEach(key => {
            if (key === 'profile_picture' && form[key]) {
                formData.append(key, form[key]);
            } else if (key === 'permissions') {
                formData.append(key, JSON.stringify(form[key]));
            } else {
                formData.append(key, form[key] || '');
            }
        });

        form.post(route('admin.register.store'), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                alert('User Registered Successfully!');
                form.reset();
                router.visit(route('admin.register.staff'));
            },
            onError: (errors) => {
                console.error('Registration errors:', errors);
                alert('Please check the form for errors.');
            },
        });
    }
};
</script>


<template>

    <Head title="Register New User" />
    <MainLayout>
        <div class="max-w-4xl mx-auto p-10 bg-white rounded-xl shadow-xl mt-10">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">
                <font-awesome-icon :icon="['fas', 'user-shield']" class="text-indigo-500 mr-2" />
                Register New User
            </h2>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Step 1: Basic Information -->
                <div v-if="step === 1" class="p-8 rounded-xl ">
                    <h3 class="text-2xl font-bold text-gray-800 flex items-center mb-6">
                        <font-awesome-icon :icon="['fas', 'user']" class="text-indigo-500 mr-2" />
                        Step 1: Basic Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div class="relative">
                            <input type="text" id="first_name" v-model="form.first_name" class="input-field pl-10"
                                placeholder=" " />
                            <label for="first_name" class="floating-label">First Name</label>
                            <InputError :message="form.errors.first_name" />
                        </div>

                        <!-- Middle Name -->
                        <div class="relative">
                            <input type="text" id="middle_name" v-model="form.middle_name" class="input-field pl-10"
                                placeholder=" " />
                            <label for="middle_name" class="floating-label">Middle Name</label>
                            <InputError :message="form.errors.middle_name" />
                        </div>

                        <!-- Last Name -->
                        <div class="relative">
                            <input type="text" id="last_name" v-model="form.last_name" class="input-field pl-10"
                                placeholder=" " />
                            <label for="last_name" class="floating-label">Last Name</label>
                            <InputError :message="form.errors.last_name" />
                        </div>

                        <!-- Email -->
                        <div class="relative">
                            <input type="email" id="email" v-model="form.email" class="input-field pl-10"
                                placeholder="" />
                            <label for="email" class="floating-label">Email Address</label>
                            <InputError :message="form.errors.email" />
                        </div>
                    </div>
                </div>


                <!-- Step 2: Address & Contact -->
                <div v-if="step === 2">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                        <font-awesome-icon :icon="['fas', 'address-book']" class="text-indigo-500 mr-2" />
                        Contact & Address Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Purok -->
                        <div class="relative">
                            <input type="text" id="purok" v-model="form.purok" class="input-field pl-12"
                                placeholder=" " />
                            <label for="purok" class="floating-label">Purok</label>
                            <InputError :message="form.errors.purok" />
                        </div>

                        <!-- Barangay -->
                        <div class="relative">
                            <input type="text" id="barangay" v-model="form.barangay" class="input-field pl-12"
                                placeholder=" " />
                            <label for="barangay" class="floating-label">Barangay</label>
                            <InputError :message="form.errors.barangay" />
                        </div>

                        <!-- City -->
                        <div class="relative">
                            <input type="text" id="city" v-model="form.city" class="input-field pl-12"
                                placeholder=" " />
                            <label for="city" class="floating-label">City</label>
                            <InputError :message="form.errors.city" />
                        </div>

                        <!-- Phone Number -->
                        <div class="relative">
                            <input type="tel" id="phone" v-model="form.phone" @input="validatePhoneNumber"
                                class="input-field pl-12"   maxlength="13" placeholder="+63 912 345 6789" />
                            <label for="phone" class="floating-label">Mobile Number</label>
                            <InputError :message="form.errors.phone" />
                        </div>
                    </div>
                </div>

                <!-- Step 3: Role & Permissions -->
                <div v-if="step === 3" class="space-y-6">
                    <h3 class="text-2xl font-semibold text-gray-800 flex items-center">
                        <font-awesome-icon :icon="['fas', 'user-cog']" class="mr-2 text-indigo-500" />
                        Step 3: Role & Permissions
                    </h3>

                    <!-- Role Selection with Modal Trigger -->
                    <div>
                        <InputLabel for="role" value="Select Role" />
                        <select v-model="form.role" @change="handleRoleChange"
                            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="" disabled>Select Role</option>
                            <option value="staff">Staff</option>
                            <option value="doctor">Doctor</option>
                            <option value="admin">Admin</option>
                        </select>
                        <InputError :message="form.errors.role" />
                    </div>

                    <!-- Doctor-Specific Fields -->
                    <transition name="fade">
                        <div v-if="form.role === 'doctor'" class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <InputLabel for="prc_number" value="PRC Number" />
                                <TextInput id="prc_number" v-model="form.prc_number" placeholder="PRC-123456"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                <InputError :message="form.errors.prc_number" />
                            </div>

                            <div>
                                <InputLabel for="specialization" value="Specialization" />
                                <TextInput id="specialization" v-model="form.specialization" placeholder="Cardiology"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                <InputError :message="form.errors.specialization" />
                            </div>

                            <div>
                                <InputLabel for="prc_validity" value="PRC Validity Date" />
                                <input type="date" v-model="form.prc_validity"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                <InputError :message="form.errors.prc_validity" />
                            </div>
                        </div>
                    </transition>

                    <!-- Permissions Button -->
                    <div class="text-right">
                        <button type="button" @click.prevent="showRoleModal = true"
                            class="px-6 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 flex items-center">
                            <font-awesome-icon :icon="['fas', 'check-square']" class="mr-2" />
                            Set Permissions
                        </button>
                    </div>
                </div>


                <!-- Step 4: Security & Upload -->
                <div v-if="step === 4">
                    <h3 class="text-2xl font-bold text-gray-800 mb-8 flex items-center">
                        <font-awesome-icon :icon="['fas', 'lock']" class="text-indigo-500 mr-3 text-xl" />
                        Step 4: Security & Upload
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Profile Picture Upload with Remove Button -->
                        <div class="flex flex-col items-center justify-center relative">
                            <!-- Upload Button OR Image Preview -->
                            <label for="upload" class="cursor-pointer">
                                <div v-if="!profilePicturePreview"
                                    class="flex items-center justify-center w-40 h-40 bg-gradient-to-r from-indigo-500 to-blue-500 text-white rounded-full shadow-lg hover:scale-105 transform transition-all duration-300">
                                    <font-awesome-icon :icon="['fas', 'upload']" class="text-4xl" />
                                    <span class="absolute bottom-2 text-xs">Click to Upload</span>
                                </div>

                                <!-- Replace Upload Button with Image -->
                                <div v-else class="relative">
                                    <img :src="profilePicturePreview"
                                        class="h-40 w-40 rounded-full object-cover shadow-lg border-2 border-indigo-500 hover:opacity-80 transition-opacity duration-300" />

                                    <!-- X Button to Remove the Image -->
                                    <button @click="removeProfilePicture" type="button"
                                        class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full p-1 shadow hover:bg-red-700 hover:scale-110 transition">
                                        <font-awesome-icon :icon="['fas', 'times']" />
                                    </button>
                                </div>
                            </label>

                            <!-- Hidden File Input -->
                            <input id="upload" type="file" @change="handleFileUpload" class="hidden" />

                            <!-- Error Message -->
                            <InputError :message="form.errors.profile_picture" />
                        </div>


                        <!-- Password Fields -->
                        <div class="space-y-6">
                            <!-- Password -->
                            <div>
                                <InputLabel for="password" value="Password" />
                                <div class="relative">
                                    <TextInput :type="showPassword ? 'text' : 'password'" v-model="form.password"
                                        placeholder="••••••••"
                                        class="w-full px-4 py-2 pr-12 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500" />
                                    <!-- Eye Icon Button -->
                                    <button @click="togglePasswordVisibility" type="button"
                                        class="absolute right-0 top-0 bottom-0 px-3 flex items-center text-gray-500 hover:text-indigo-600">
                                        <font-awesome-icon :icon="showPassword ? faEyeSlash : faEye" />
                                    </button>
                                </div>
                                <InputError :message="form.errors.password" />
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <InputLabel for="password_confirmation" value="Confirm Password" />
                                <div class="relative">
                                    <TextInput :type="showConfirmPassword ? 'text' : 'password'"
                                        v-model="form.password_confirmation" placeholder="••••••••"
                                        class="w-full px-4 py-2 pr-12 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500" />
                                    <!-- Eye Icon Button -->
                                    <button @click="toggleConfirmPasswordVisibility" type="button"
                                        class="absolute right-0 top-0 bottom-0 px-3 flex items-center text-gray-500 hover:text-indigo-600">
                                        <font-awesome-icon :icon="showConfirmPassword ? faEyeSlash : faEye" />
                                    </button>
                                </div>
                                <InputError :message="form.errors.password_confirmation" />
                            </div>
                        </div>

                    </div>

                </div>



                <!-- Navigation Buttons -->
                <div class="flex justify-between">
                    <PrimaryButton v-if="step > 1" @click="prevStep">Previous</PrimaryButton>
                    <PrimaryButton v-if="step < 4" @click="nextStep">Next</PrimaryButton>
                    <PrimaryButton v-if="step === 4" type="submit">Submit</PrimaryButton>
                </div>
                <!-- Role Permissions Modal -->
                <transition name="fade">
                    <div v-if="showRoleModal"
                        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                        <div class="bg-white p-8 rounded-lg shadow-lg w-96 relative">

                            <!-- Close Button -->
                            <button type="button" @click.prevent="showRoleModal = false"
                                class="absolute top-3 right-3 text-gray-500 hover:text-red-600">
                                <font-awesome-icon :icon="['fas', 'times']" />
                            </button>

                            <!-- Modal Header -->
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <font-awesome-icon :icon="['fas', 'user-shield']" class="mr-2 text-indigo-500" />
                                {{ form.role ? `${form.role.charAt(0).toUpperCase() + form.role.slice(1)} Permissions` : 'Select Role First' }}
                            </h3>

                            <!-- Permissions Checklist -->
                            <div v-if="form.role" class="space-y-3">
                                <div v-for="permission in roleSpecificPermissions" :key="permission.value"
                                    class="flex items-center space-x-3">
                                    <input type="checkbox" 
                                        :id="permission.value"
                                        v-model="form.permissions"
                                        :value="permission.value"
                                        class="form-checkbox h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500" />
                                    <label :for="permission.value" class="text-gray-700">{{ permission.label }}</label>
                                </div>
                            </div>
                            <div v-else class="text-gray-500 italic">
                                Please select a role first to view available permissions.
                            </div>

                            <!-- Error Message -->
                            <p v-if="permissionError" class="text-red-500 text-sm mt-2">
                                {{ permissionError }}
                            </p>

                            <!-- Save Button -->
                            <div class="flex justify-end mt-6">
                                <button type="button" @click.prevent="savePermissions"
                                    class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors duration-200">
                                    <font-awesome-icon :icon="['fas', 'save']" class="mr-2" />
                                    Save Permissions
                                </button>
                            </div>
                        </div>
                    </div>
                </transition>

            </form>
        </div>
    </MainLayout>
</template>
<style scoped>
/* Input Field Styling */
.input-field {
    width: 100%;
    padding: 0.75rem 1rem;
    padding-left: 3rem;
    /* Adjusted for icon */
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    background-color: #f9fafb;
    transition: all 0.3s ease;
    font-size: 1rem;
    color: #374151;
}

.input-field:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
    outline: none;
}

.floating-label {
    position: absolute;
    left: 3rem;
    top: 50%;
    transform: translateY(-50%);
    background-color: #f9fafb;
    padding: 0 0.25rem;
    transition: all 0.3s ease;
    pointer-events: none;
    color: #9ca3af;
    font-size: 1rem;
}

.input-field:focus+.floating-label,
.input-field:not(:placeholder-shown)+.floating-label {
    top: -0.5rem;
    left: 2.75rem;
    font-size: 0.75rem;
    color: #6366f1;
    background-color: white;
}

/* Icon Styling */
.icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 1.2rem;
}

/* Error Message Styling */
input:invalid {
    border-color: #ef4444;
}

input:invalid+.floating-label {
    color: #ef4444;
}

.input-field {
    @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400;
}

button {
    @apply bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition;
}

button:disabled {
    @apply bg-gray-400 cursor-not-allowed;
}
</style>
