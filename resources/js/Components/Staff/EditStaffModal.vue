<template>
  <div
    v-if="show"
    class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 p-4"
  >
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6 relative">
      <!-- Close Button -->
      <button
        @click="$emit('close')"
        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition duration-200"
      >
        <font-awesome-icon icon="times" class="w-6 h-6" />
      </button>

      <!-- Modal Header -->
      <h3 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
        Edit Staff Member
      </h3>

      <!-- Form -->
      <form @submit.prevent="submitForm" class="space-y-6">
        <div class="flex flex-row justify-start gap-10" >
                <!-- First Name -->
        <div>
          <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
            First Name
          </label>
          <input
            id="first_name"
            type="text"
            v-model="form.first_name"
            class="input-field"
            placeholder="Enter first name"
            required
          />
        </div>

        <!-- Middle Name -->
        <div>
          <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1">
            Middle Name
          </label>
          <input
            id="middle_name"
            type="text"
            v-model="form.middle_name"
            class="input-field"
            placeholder="Enter middle name"
          />
        </div>
        </div>
        <div class="flex flex-row justify-start gap-10">
               <!-- Last Name -->
        <div>
          <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
            Last Name
          </label>
          <input
            id="last_name"
            type="text"
            v-model="form.last_name"
            class="input-field"
            placeholder="Enter last name"
            required
          />
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
            Email Address
          </label>
          <input
            id="email"
            type="email"
            v-model="form.email"
            class="input-field"
            placeholder="Enter email address"
            required
          />
        </div>
        </div>

        <!-- Role Selection -->
        <div>
          <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
            Role
          </label>
          <select
            id="role"
            v-model="form.role"
            @change="handleRoleChange"
            class="input-field"
            required
          >
            <option value="" disabled>Select role</option>
            <option value="staff">Staff</option>
            <option value="doctor">Doctor</option>
            <option value="admin">Admin</option>
          </select>
        </div>

        <!-- PRC Fields for Doctor -->
        <div v-if="form.role === 'doctor'" class="space-y-4">
          <div>
            <label for="prc_number" class="block text-sm font-medium text-gray-700 mb-1">
              PRC Number
            </label>
            <input
              id="prc_number"
              type="text"
              v-model="form.prc_number"
              class="input-field"
              placeholder="Enter PRC number"
            />
          </div>

          <div>
            <label for="specialization" class="block text-sm font-medium text-gray-700 mb-1">
              Specialization
            </label>
            <input
              id="specialization"
              type="text"
              v-model="form.specialization"
              class="input-field"
              placeholder="Enter specialization"
            />
          </div>

          <div>
            <label for="prc_validity" class="block text-sm font-medium text-gray-700 mb-1">
              PRC Validity
            </label>
            <input
              id="prc_validity"
              type="date"
              v-model="form.prc_validity"
              class="input-field"
            />
          </div>
        </div>

        <!-- Permissions -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Permissions
          </label>
          <div v-for="permission in roleSpecificPermissions" :key="permission.value" class="flex items-center space-x-3">
            <input
              type="checkbox"
              :id="permission.value"
              v-model="form.permissions"
              :value="permission.value"
              class="form-checkbox h-5 w-5 text-indigo-600"
            />
            <label :for="permission.value" class="text-gray-700">
              {{ permission.label }}
            </label>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-3 mt-6">
          <button
            type="button"
            @click="$emit('close')"
            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition duration-200"
          >
            Cancel
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200"
          >
            Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { computed, ref } from "vue";
import { router } from "@inertiajs/vue3";

export default {
  props: {
    show: Boolean,
    staff: Object,
  },
  setup(props, { emit }) {
    const form = ref({
      id: props.staff?.id || null,
      first_name: props.staff?.first_name || "",
      middle_name: props.staff?.middle_name || "",
      last_name: props.staff?.last_name || "",
      email: props.staff?.email || "",
      role: props.staff?.role || "",
      permissions: props.staff?.permissions || [],
      prc_number: props.staff?.prc_number || "",
      specialization: props.staff?.specialization || "",
      prc_validity: props.staff?.prc_validity || "",
    });

    const availablePermissions = {
      staff: [
        { value: "view_patients", label: "View Patients" },
        { value: "create_records", label: "Create Records" },
      ],
      doctor: [
        { value: "view_patients", label: "View Patients" },
        { value: "edit_patients", label: "Edit Patients" },
        { value: "create_records", label: "Create Records" },
      ],
      admin: [
        { value: "view_patients", label: "View Patients" },
        { value: "edit_patients", label: "Edit Patients" },
        { value: "create_records", label: "Create Records" },
        { value: "manage_users", label: "Manage Users" },
      ],
    };

    const roleSpecificPermissions = computed(() =>
      form.value.role ? availablePermissions[form.value.role] : []
    );

    const handleRoleChange = () => {
      form.value.permissions = availablePermissions[form.value.role]?.map(
        (perm) => perm.value
      );
    };

    const submitForm = () => {
      router.put(`/admin/staff/${form.value.id}`, form.value, {
        onSuccess: () => {
          emit("close");
          emit("updated");
        },
      });
    };

    return {
      form,
      roleSpecificPermissions,
      handleRoleChange,
      submitForm,
    };
  },
};
</script>

<style scoped>
.input-field {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 1px solid #d1d5db;
  border-radius: 0.5rem;
  background-color: #f9fafb;
  transition: all 0.3s ease;
  font-size: 1rem;
  color: #374151;
}

.input-field:focus {
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
  outline: none;
}

.form-checkbox {
  cursor: pointer;
}

button {
  cursor: pointer;
}
</style>
