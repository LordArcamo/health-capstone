<template>
  <div class="flex">

  <!-- Custom Alert -->
<div v-if="showAlert" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-xl max-w-sm p-6 text-center">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Confirm Action</h3>
    <p class="text-sm text-gray-600 mb-6">{{ alertMessage }}</p>
    <div class="flex items-center justify-center gap-4">
      <button
        @click="confirmAlert"
        :class="[
          'px-5 py-2 rounded-lg hover:opacity-90 text-white',
          alertType === 'edit' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700'
        ]"
      >
        Confirm
      </button>
      <button
        @click="closeAlert"
        class="px-5 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400"
      >
        Cancel
      </button>
    </div>
  </div>
</div>


    <!-- Modal -->
    <div v-if="isModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-8">
        <h3 class="text-xl font-bold text-green-700 mb-6">
          {{ isEditing ? 'Edit Session' : 'Add New Session' }}
        </h3>
        <form @submit.prevent="saveSession" class="space-y-6">
          <!-- Title -->
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
              Session Title
            </label>
            <input v-model="form.title" type="text" id="title"
              class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
              placeholder="Enter session title" required />
          </div>
          <!-- Date -->
          <div>
            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
              Date
            </label>
            <input v-model="form.date" type="date" id="date"
              class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
              required />
          </div>
          <!-- Buttons -->
          <div class="flex items-center justify-end space-x-4">
            <button type="button" @click="closeModal"
              class="px-5 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">
              Cancel
            </button>
            <button type="submit" class="px-5 py-2 bg-green-700 text-white rounded-lg shadow-md hover:bg-green-800">
              {{ isEditing ? 'Update Session' : 'Add Session' }}
            </button>
          </div>
        </form>
      </div>
    </div>
    <!-- Sidebar -->
    <div :class="[
      'h-screen transition-all duration-300 shadow-lg bg-gradient-to-b from-green-500 to-green-700 text-white',
      isSidebarOpen ? 'w-100' : 'w-20'
    ]">
      <!-- Sidebar Header -->
      <div class="flex items-center justify-between px-4 py-6">
        <h2 v-if="isSidebarOpen" class="text-xl font-semibold tracking-wide uppercase">
          Mental Health Program
        </h2>
        <button @click="toggleSidebar" class="text-white bg-white/10 p-2 rounded-lg hover:bg-white/20 transition">
          <svg v-if="isSidebarOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
            fill="currentColor">
            <path fill-rule="evenodd"
              d="M15.293 4.707a1 1 0 010 1.414L11.414 10l3.879 3.879a1 1 0 01-1.414 1.414l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 0z"
              clip-rule="evenodd" />
          </svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
              d="M4.707 15.293a1 1 0 010-1.414L8.586 10 4.707 6.121a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z"
              clip-rule="evenodd" />
          </svg>
        </button>
      </div>

      <!-- Sidebar Content -->
      <div v-if="isSidebarOpen" class="px-4 py-6 space-y-6 text-sm tracking-wide">
        <!-- Add Session -->
        <button @click="openModal"
          class="w-full flex items-center gap-2 py-3 px-4 bg-white text-green-700 rounded-lg shadow-md hover:bg-gray-200 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
              d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 010-2h3V6a1 1 0 011-1z"
              clip-rule="evenodd" />
          </svg>
          Add Session
        </button>

        <!-- Active Sessions -->
        <div>
          <h3 class="text-lg font-medium mb-4">Active Sessions</h3>
          <ul class="space-y-4">
            <li v-for="session in activeSessions" :key="session.id"
              class="flex items-center justify-between bg-white text-gray-900 p-4 rounded-lg shadow hover:shadow-lg transition">
              <div>
                <p class="text-base font-medium">{{ session.title }}</p>
                <p class="text-xs text-gray-500">📅 {{ session.date }}</p>
              </div>
              <div class="flex items-center space-x-2">
                <button @click="markAsFinished(session.id)"
                  class="p-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                  Finish
                </button>
                <button @click="editSession(session)"
                  class="p-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                  Edit
                </button>
                <button @click="deleteSession(session.id)"
                  class="p-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                  Delete
                </button>
              </div>
            </li>
          </ul>
        </div>

        <!-- Finished Sessions -->
        <div>
          <h3 class="text-lg font-medium mb-4">Finished Sessions</h3>
          <ul class="space-y-4">
            <li v-for="session in finishedSessions" :key="session.id"
              class="flex items-center justify-between bg-gray-200 p-4 rounded-lg shadow hover:shadow-lg transition">
              <div>
                <p class="text-base text-gray-600 font-medium">{{ session.title }}</p>
                <p class="text-xs text-gray-600">📅 {{ session.date }}</p>
              </div>
              <div class="flex items-center space-x-2">
                <button @click="markAsActive(session.id)"
                  class="p-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                  Restore
                </button>
                <button @click="deleteSession(session.id)"
                  class="p-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                  Delete
                </button>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      sessions: [
        {
          id: 1,
          title: "Stress Management Workshop",
          date: "2024-12-01",
          status: "active",
        },
        {
          id: 2,
          title: "Coping Mechanisms Seminar",
          date: "2024-11-28",
          status: "finished",
        },
        {
          id: 3,
          title: "Mindfulness Session",
          date: "2024-11-15",
          status: "finished",
        },
      ],
      isSidebarOpen: true,
      isModalOpen: false,
      isEditing: false,
      showAlert: false,
      alertMessage: "",
      alertType: "", // New property to determine the action type (edit or delete)
      alertAction: null,
      form: {
        id: null,
        title: "",
        date: "",
      },
    };
  },
  computed: {
    activeSessions() {
      return this.sessions.filter((session) => session.status === "active");
    },
    finishedSessions() {
      return this.sessions.filter((session) => session.status === "finished");
    },
  },
  methods: {
    toggleSidebar() {
      this.isSidebarOpen = !this.isSidebarOpen;
    },
    openModal() {
      this.isEditing = false;
      this.form = { id: null, title: "", date: "" };
      this.isModalOpen = true;
    },
    closeModal() {
      this.isModalOpen = false;
    },
    saveSession() {
      const sessionData = { ...this.form, id: Date.now(), status: "active" };
      if (this.isEditing) {
        const index = this.sessions.findIndex(
          (session) => session.id === this.form.id
        );
        if (index !== -1) this.sessions.splice(index, 1, sessionData);
      } else {
        this.sessions.push(sessionData);
      }
      this.closeModal();
    },
    editSession(session) {
      this.alertMessage = `Are you sure you want to edit the session titled "${session.title}"?`;
      this.alertType = "edit"; // Set the alert type to edit
      this.alertAction = () => {
        this.isEditing = true;
        this.form = { ...session }; // Prefill form with session data
        this.isModalOpen = true; // Open modal for editing
      };
      this.showAlert = true; // Display confirmation dialog
    },
    deleteSession(id) {
      const session = this.sessions.find((session) => session.id === id);
      if (!session) return;

      this.alertMessage = `Are you sure you want to delete the session titled "${session.title}"?`;
      this.alertType = "delete"; // Set the alert type to delete
      this.alertAction = () => {
        this.sessions = this.sessions.filter((session) => session.id !== id);
      };
      this.showAlert = true; // Display confirmation dialog
    },
    markAsFinished(id) {
      const session = this.sessions.find((session) => session.id === id);
      if (session) session.status = "finished";
    },
    markAsActive(id) {
      const session = this.sessions.find((session) => session.id === id);
      if (session) session.status = "active";
    },
    confirmAlert() {
      if (this.alertAction) this.alertAction();
      this.closeAlert();
    },
    closeAlert() {
      this.showAlert = false;
      this.alertMessage = "";
      this.alertAction = null;
      this.alertType = ""; // Reset the alert type
    },
  },
};


</script>



<style scoped>
/* Optional: Add styling for visual enhancements */
</style>
