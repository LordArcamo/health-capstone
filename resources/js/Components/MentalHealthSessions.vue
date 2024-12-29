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
      isSidebarOpen ? 'w-100' : 'w-16'
    ]">
      <!-- Sidebar Header -->
      <div class="flex items-center justify-between px-3 py-3">
        <h2 v-if="isSidebarOpen" class="text-lg font-semibold tracking-wide uppercase">
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
      <div v-if="isSidebarOpen" class="px-3 py-3 space-y-4">
        <!-- Add Session -->
        <button @click="openModal"
          class="w-full flex items-center gap-2 py-2 px-3 bg-white text-green-700 rounded-lg shadow-md hover:bg-gray-50 transition text-sm font-medium">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
              d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 010-2h3V6a1 1 0 011-1z"
              clip-rule="evenodd" />
          </svg>
          Add Session
        </button>

        <!-- Active Sessions -->
        <div>
          <h3 class="text-base font-medium mb-2">Active Sessions</h3>
          <ul class="space-y-2">
            <li v-for="session in activeSessions" :key="session.id"
              class="session-card bg-white text-gray-900">
              <div class="flex items-center justify-between">
                <div class="session-content">
                  <p class="session-title">{{ session.title }}</p>
                  <p class="session-date text-gray-500">{{ formatDate(session.date) }}</p>
                </div>
                <div class="button-group">
                  <button @click="markAsFinished(session.id)"
                    class="action-button bg-blue-600 hover:bg-blue-700">
                    Finish
                  </button>
                  <button @click="editSession(session)"
                    class="action-button bg-green-600 hover:bg-green-700">
                    Edit
                  </button>
                  <button @click="deleteSession(session.id)"
                    class="action-button bg-red-600 hover:bg-red-700">
                    Delete
                  </button>
                </div>
              </div>
            </li>
          </ul>
        </div>

        <!-- Finished Sessions -->
        <div>
          <h3 class="text-base font-medium mb-2">Finished Sessions</h3>
          <ul class="space-y-2">
            <li v-for="session in finishedSessions" :key="session.id"
              class="session-card bg-gray-100">
              <div class="flex items-center justify-between">
                <div class="session-content">
                  <p class="session-title text-gray-900">{{ session.title }}</p>
                  <p class="session-date text-gray-700">{{ formatDate(session.date) }}</p>
                </div>
                <div class="button-group">
                  <button @click="markAsActive(session.id)"
                    class="action-button bg-green-600 hover:bg-green-700 text-black">
                    Restore
                  </button>
                  <button @click="deleteSession(session.id)"
                    class="action-button bg-red-600 hover:bg-red-700 text-black">
                    Delete
                  </button>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

export default {
  props: {
    initialSessions: {
      type: Array,
      required: true,
      default: () => []
    }
  },
  data() {
    return {
      sessions: this.initialSessions,
      isSidebarOpen: true,
      isModalOpen: false,
      isEditing: false,
      showAlert: false,
      alertMessage: "",
      alertType: "",
      alertAction: null,
      form: {
        id: null,
        title: "",
        date: "",
        status: "active"
      },
    };
  },
  watch: {
    initialSessions: {
      handler(newSessions) {
        this.sessions = newSessions;
      },
      deep: true
    }
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
    formatDate(date) {
      if (!date) return '';
      const [day, month, year] = date.split('-');
      return `${day}-${month}-${year}`;
    },
    formatDateForInput(date) {
      if (!date) return '';
      const [day, month, year] = date.split('-');
      return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
    },
    toggleSidebar() {
      this.isSidebarOpen = !this.isSidebarOpen;
    },
    openModal() {
      this.isEditing = false;
      this.form = { id: null, title: "", date: "", status: "active" };
      this.isModalOpen = true;
    },
    closeModal() {
      this.isModalOpen = false;
      this.form = { id: null, title: "", date: "", status: "active" };
    },
    saveSession() {
      if (this.isEditing) {
        router.put(`/mental-health-sessions/${this.form.id}`, this.form, {
          preserveScroll: true,
          preserveState: true,
          onSuccess: (page) => {
            this.sessions = page.props.sessions;
            this.closeModal();
          },
          onError: (errors) => {
            console.error(errors);
          }
        });
      } else {
        router.post('/mental-health-sessions', this.form, {
          preserveScroll: true,
          preserveState: true,
          onSuccess: (page) => {
            this.sessions = page.props.sessions;
            this.closeModal();
          },
          onError: (errors) => {
            console.error(errors);
          }
        });
      }
    },
    editSession(session) {
      this.alertMessage = `Are you sure you want to edit the session titled "${session.title}"?`;
      this.alertType = "edit";
      this.alertAction = () => {
        this.isEditing = true;
        this.form = { 
          ...session,
          date: this.formatDateForInput(session.date)
        };
        this.isModalOpen = true;
      };
      this.showAlert = true;
    },
    deleteSession(id) {
      const session = this.sessions.find(session => session.id === id);
      if (!session) return;

      this.alertMessage = `Are you sure you want to delete the session titled "${session.title}"?`;
      this.alertType = "delete";
      this.alertAction = () => {
        router.delete(`/mental-health-sessions/${id}`, {
          preserveScroll: true,
          preserveState: true,
          onSuccess: (page) => {
            this.sessions = page.props.sessions;
            this.closeAlert();
          },
          onError: (errors) => {
            console.error(errors);
          }
        });
      };
      this.showAlert = true;
    },
    markAsFinished(id) {
      router.put(`/mental-health-sessions/${id}/status`, 
        { status: 'finished' },
        {
          preserveScroll: true,
          preserveState: true,
          onSuccess: (page) => {
            this.sessions = page.props.sessions;
          },
          onError: (errors) => {
            console.error(errors);
          }
        }
      );
    },
    markAsActive(id) {
      router.put(`/mental-health-sessions/${id}/status`, 
        { status: 'active' },
        {
          preserveScroll: true,
          preserveState: true,
          onSuccess: (page) => {
            this.sessions = page.props.sessions;
          },
          onError: (errors) => {
            console.error(errors);
          }
        }
      );
    },
    confirmAlert() {
      if (this.alertAction) this.alertAction();
      this.closeAlert();
    },
    closeAlert() {
      this.showAlert = false;
      this.alertMessage = "";
      this.alertAction = null;
      this.alertType = "";
    },
  },
};
</script>

<style scoped>
.w-100 {
  width: 28rem;
}

.session-card {
  @apply p-3 rounded-lg shadow hover:shadow-lg transition duration-200;
  min-height: 3.5rem;
  min-width: 26rem;
}

.session-content {
  @apply flex-grow pr-4;
}

.session-title {
  @apply text-sm font-medium mb-0.5;
  max-width: 15rem;
}

.session-date {
  @apply text-xs;
}

.button-group {
  @apply flex items-center space-x-1.5 flex-shrink-0;
}

.action-button {
  @apply px-2.5 py-1 rounded text-white transition duration-200 text-xs font-medium whitespace-nowrap;
}
</style>
