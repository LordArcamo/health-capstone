<template>
    <section id="contact" class="py-16 bg-gradient-to-br from-green-50 to-white">
      <div class="container mx-auto px-6 lg:px-20">
        <h2 class="text-4xl lg:text-5xl font-extrabold text-center text-green-600 mb-20">
          Contact Us
        </h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
          <!-- Left Column - Description and Logo -->
          <div class="flex flex-col items-center justify-center space-y-6">
            <img 
              src="/images/RHU Logo.png" 
              alt="RHU Logo"
              class="w-36 h-36 rounded-full shadow-xl border-4 border-green-100"
            />
            <p class="text-lg text-gray-800 text-center max-w-md mx-auto leading-relaxed">
              The Initao Regional Health Office is committed to enhancing the well-being of the community by providing excellent healthcare. Feel free to reach out with any inquiries.
            </p>
          </div>
          
          <!-- Right Column - Contact Form -->
          <div class="p-8 bg-white shadow-2xl rounded-xl">
            <form action="#" method="POST" class="space-y-6">
              <!-- Name and Email -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label for="name" class="block text-lg font-semibold text-gray-700 mb-2">Name</label>
                  <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    placeholder="Enter your name"
                    class="w-full px-5 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent text-lg"
                  />
                </div>
                <div>
                  <label for="email" class="block text-lg font-semibold text-gray-700 mb-2">Email Address</label>
                  <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="Enter your email"
                    class="w-full px-5 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent text-lg"
                  />
                </div>
              </div>
  
              <!-- Message -->
              <div>
                <label for="message" class="block text-lg font-semibold text-gray-700 mb-2">Message</label>
                <textarea 
                  id="message" 
                  name="message" 
                  rows="6" 
                  placeholder="Write your message here..."
                  class="w-full px-5 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent text-lg"
                ></textarea>
              </div>
  
              <!-- Submit Button -->
              <button 
                type="submit" 
                class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold text-lg shadow-md hover:bg-green-700 transition-all focus:outline-none focus:ring-2 focus:ring-green-500"
              >
                Send Message
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>
  </template>
<script>
export default {
  name: "ContactUs",
};
</script>
<style scoped>
/* Custom Background */
.bg-gradient-to-br {
  background: linear-gradient(to bottom right, #f0fdf4, #ffffff);
}

/* Form Elements */
input, textarea {
  font-size: 1.125rem; /* 18px for better readability */
}

input::placeholder, textarea::placeholder {
  color: #9ca3af; /* Tailwind gray-400 */
}

/* Button Styling */
button {
  font-size: 1.125rem; /* 18px */
  transition: all 0.3s ease;
}

button:hover {
  transform: translateY(-2px);
}

/* Logo Styling */
img {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

img:hover {
  transform: scale(1.05);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}
</style>
