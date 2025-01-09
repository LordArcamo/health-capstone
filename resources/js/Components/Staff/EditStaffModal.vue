<template>
  <div
    v-if="show"
    class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 p-4"
  >
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
      <!-- Close Button -->
      <button
        @click="$emit('close')"
        class="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      <!-- Edit Form -->
      <div class="mt-3">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Staff Member</h3>
        
        <form @submit.prevent="submitForm" class="space-y-4">
          <!-- Name Input -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input
              type="text"
              v-model="form.name"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
              required
            />
          </div>

          <!-- Email Input -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input
              type="email"
              v-model="form.email"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
              required
            />
          </div>

          <!-- Role Select -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Role</label>
            <select
              v-model="form.role"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
              required
            >
              <option value="staff">Staff</option>
              <option value="doctor">Doctor</option>
            </select>
          </div>

          <!-- Form Actions -->
          <div class="mt-6 flex justify-end space-x-3">
            <button
              type="button"
              @click="$emit('close')"
              class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600"
            >
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { router } from '@inertiajs/vue3';

export default {
  props: {
    show: {
      type: Boolean,
      required: true
    },
    staff: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      form: {
        id: this.staff?.id || null,
        name: this.staff?.name || '',
        email: this.staff?.email || '',
        role: this.staff?.role || 'staff'
      }
    }
  },

  watch: {
    staff(newStaff) {
      if (newStaff) {
        this.form = {
          id: newStaff.id,
          name: newStaff.name,
          email: newStaff.email,
          role: newStaff.role
        }
      }
    }
  },

  methods: {
    submitForm() {
      router.put(`/admin/staff/${this.form.id}`, this.form, {
        onSuccess: () => {
          this.$emit('close');
          this.$emit('updated');
        }
      });
    }
  }
}
</script>
