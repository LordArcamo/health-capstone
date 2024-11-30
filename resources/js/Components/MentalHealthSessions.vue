<template>
  <div class="flex">
    <!-- Sidebar -->
    <div
      class="pr-5"
      :class="[
        'h-screen bg-white border-black transition-all duration-300',
        isSidebarOpen ? 'w-96' : 'w-16'
      ]"
    >
      <div class="flex items-center justify-between p-6">
        <!-- Sidebar Title -->
        <h2
          v-if="isSidebarOpen"
          class="text-2xl font-semibold text-green-700 whitespace-nowrap"
        >
          Mental Health Program
        </h2>
        <!-- Collapse Button -->
        <button
          @click="toggleSidebar"
          class="text-gray-500 hover:text-green-700 focus:outline-none"
        >
          {{ isSidebarOpen ? '←' : '→' }}
        </button>
      </div>

      <!-- Collapsible Content -->
      <div
        v-if="isSidebarOpen"
        class="transition-opacity duration-300 opacity-100"
      >
        <!-- Add Session Button -->
        <button
          @click="openModal"
          class="w-full bg-green-700 text-white py-3 px-5 rounded-lg shadow-md hover:bg-green-800 transition duration-300"
        >
          + Add Session
        </button>

        <!-- Active Sessions -->
        <div v-if="activeSessions.length > 0" class="mt-8">
          <h3 class="text-lg font-medium text-gray-800 mb-4">
            Active Sessions
          </h3>
          <ul class="space-y-4">
            <li
              v-for="session in activeSessions"
              :key="session.id"
              class="flex items-center justify-between p-4 border rounded-lg shadow-sm hover:shadow-md transition duration-300"
            >
              <div>
                <p class="text-lg font-medium text-gray-900">
                  {{ session.title }}
                </p>
                <p class="text-sm text-gray-600">
                  📅 {{ session.date }}
                </p>
              </div>
              <div class="flex items-center space-x-4">
                <button
                  @click="markAsFinished(session.id)"
                  class="text-blue-600 hover:text-blue-800 font-medium"
                >
                  Finish
                </button>
                <button
                  @click="editSession(session)"
                  class="text-green-700 hover:text-green-900 font-medium"
                >
                  Edit
                </button>
                <button
                  @click="deleteSession(session.id)"
                  class="text-red-600 hover:text-red-800 font-medium"
                >
                  Delete
                </button>
              </div>
            </li>
          </ul>
        </div>

        <!-- Finished Sessions -->
        <div v-if="finishedSessions.length > 0" class="mt-8">
          <h3 class="text-lg font-medium text-gray-800 mb-4">
            Finished Sessions
          </h3>
          <ul class="space-y-4">
            <li
              v-for="session in finishedSessions"
              :key="session.id"
              class="flex items-center justify-between p-4 border rounded-lg shadow-sm hover:shadow-md transition duration-300 bg-gray-100"
            >
              <div>
                <p class="text-lg font-medium text-gray-900">
                  {{ session.title }}
                </p>
                <p class="text-sm text-gray-600">
                  📅 {{ session.date }}
                </p>
              </div>
              <div class="flex items-center space-x-4">
                <button
                  @click="markAsActive(session.id)"
                  class="text-green-600 hover:text-green-800 font-medium"
                >
                  Restore
                </button>
                <button
                  @click="deleteSession(session.id)"
                  class="text-red-600 hover:text-red-800 font-medium"
                >
                  Delete
                </button>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Add/Edit Modal -->
    <div
      v-if="isModalOpen"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
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
            <input
              v-model="form.title"
              type="text"
              id="title"
              class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
              placeholder="Enter session title"
              required
            />
          </div>

          <!-- Date -->
          <div>
            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
              Date
            </label>
            <input
              v-model="form.date"
              type="date"
              id="date"
              class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
              required
            />
          </div>

          <!-- Buttons -->
          <div class="flex items-center justify-end space-x-4">
            <button
              type="button"
              @click="closeModal"
              class="px-5 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-5 py-2 bg-green-700 text-white rounded-lg shadow-md hover:bg-green-800"
            >
              {{ isEditing ? 'Update Session' : 'Add Session' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      sessions: this.$page.props.sessions || [], // Data passed directly from Laravel
      isSidebarOpen: true,
      isModalOpen: false,
      isEditing: false,
      form: {
        id: null,
        title: "",
        date: "",
      },
    };
  },
  computed: {
    activeSessions() {
      return this.sessions.filter(session => session.status === "active");
    },
    finishedSessions() {
      return this.sessions.filter(session => session.status === "finished");
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
      const sessionData = { ...this.form };
      if (this.isEditing) {
        this.$inertia.put(route('sessions.update', sessionData.id), sessionData);
      } else {
        this.$inertia.post(route('sessions.store'), sessionData);
      }
      this.closeModal();
    },
    editSession(session) {
      this.isEditing = true;
      this.form = { ...session };
      this.isModalOpen = true;
    },
    deleteSession(id) {
      this.$inertia.delete(route('sessions.destroy', id));
    },
    markAsFinished(id) {
      this.$inertia.put(route('sessions.update', id), { status: 'finished' });
    },
    markAsActive(id) {
      this.$inertia.put(route('sessions.update', id), { status: 'active' });
    },
  },
};
</script>


<style scoped>
/* Optional: Add styling for visual enhancements */
</style>
