<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Table with View More Popup</h1>
    <!-- Table -->
    <table class="min-w-full bg-white">
      <thead>
        <tr class="w-full bg-gray-200 text-left">
          <th class="py-2 px-4">ID</th>
          <th class="py-2 px-4">Name</th>
          <th class="py-2 px-4">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in tableData" :key="index" class="border-b">
          <td class="py-2 px-4">{{ item.id }}</td>
          <td class="py-2 px-4">{{ item.name }}</td>
          <td class="py-2 px-4">
            <button @click="openModal(item)" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
              View More
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Modal -->
    <div v-if="isModalOpen" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center">
      <div class="bg-white w-1/3 rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4">Details of {{ selectedItem.name }}</h2>
        <p><strong>ID:</strong> {{ selectedItem.id }}</p>
        <p><strong>Name:</strong> {{ selectedItem.name }}</p>

        <!-- Example form inside the modal -->
        <form @submit.prevent="submitForm">
          <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Message</label>
            <textarea v-model="formData.message" class="w-full border border-gray-300 rounded-lg p-2 mt-1"
              rows="3"></textarea>
          </div>
          <div class="mt-4">
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700">Submit</button>
            <button @click="closeModal"
              class="bg-gray-500 text-white px-4 py-2 ml-2 rounded hover:bg-gray-700">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PopupTable',
  data() {
    return {
      // Table data
      tableData: [
        { id: 1, name: 'John Doe' },
        { id: 2, name: 'Jane Smith' },
        { id: 3, name: 'Mark Johnson' },
      ],
      // Modal state
      isModalOpen: false,
      selectedItem: {},
      formData: {
        message: ''
      }
    };
  },
  methods: {
    // Open the modal and set the selected item
    openModal(item) {
      this.selectedItem = item;
      this.isModalOpen = true;
    },
    // Close the modal
    closeModal() {
      this.isModalOpen = false;
    },
    // Handle form submission
    submitForm() {
      console.log('Form submitted with message:', this.formData.message);
      this.closeModal(); // Close the modal after submission
    }
  }
};
</script>

<style scoped>
/* Add any custom styles you need */
</style>