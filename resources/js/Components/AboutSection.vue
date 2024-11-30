<template>
    <section id="about" class="py-16 bg-gray-50">
      <div class="container mx-auto px-6">
        <!-- About Us Header -->
        <h2 class="text-4xl font-bold text-center text-green-700 mb-8">About Us</h2>
        <p class="text-center text-gray-700 max-w-2xl mx-auto mb-12 leading-relaxed">
          The Initao Regional Health Office is dedicated to enhancing the well-being of our community through accessible and high-quality healthcare services. Our mission is to promote health, prevent diseases, and ensure a safe and healthy environment for everyone.
        </p>
  
        <!-- Mission, Vision, and Goals Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
          <!-- Mission -->
          <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-lg transition-all">
            <img src="https://via.placeholder.com/200x200/27AE60/FFFFFF?text=Mission" alt="Mission" class="mb-6 rounded-full mx-auto w-28 h-28">
            <h3 class="text-2xl font-semibold text-green-700 mb-4 text-center">Mission</h3>
            <p class="text-gray-600 text-center leading-relaxed">
              To provide excellent health services through efficient, compassionate, and sustainable healthcare programs and initiatives aimed at improving the quality of life for all.
            </p>
          </div>
  
          <!-- Vision -->
          <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-lg transition-all">
            <img src="https://via.placeholder.com/200x200/2ECC71/FFFFFF?text=Vision" alt="Vision" class="mb-6 rounded-full mx-auto w-28 h-28">
            <h3 class="text-2xl font-semibold text-green-700 mb-4 text-center">Vision</h3>
            <p class="text-gray-600 text-center leading-relaxed">
              A healthier, well-informed community where every individual has access to quality healthcare services and the resources to lead a healthy life.
            </p>
          </div>
  
          <!-- Goals -->
          <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-lg transition-all">
            <img src="https://via.placeholder.com/200x200/1E824C/FFFFFF?text=Goals" alt="Goals" class="mb-6 rounded-full mx-auto w-28 h-28">
            <h3 class="text-2xl font-semibold text-green-700 mb-4 text-center">Goals</h3>
            <ul class="list-disc pl-6 text-gray-600 leading-relaxed">
              <li>Promote public health education and awareness.</li>
              <li>Ensure access to quality healthcare services for all.</li>
              <li>Strengthen healthcare infrastructure and resources.</li>
              <li>Prevent and manage diseases through proactive measures.</li>
            </ul>
          </div>
        </div>
  
        <!-- Accordion Section -->
        <div class="mt-16 space-y-6">
          <h3 class="text-2xl font-semibold text-green-700 text-center mb-6">More About Us</h3>
          <div v-for="(section, index) in accordionSections" :key="index">
            <div 
              @click="toggleAccordion(index)" 
              class="cursor-pointer bg-green-600 text-white px-6 py-4 rounded-lg shadow-md flex justify-between items-center hover:bg-green-700 transition-all"
            >
              <h4 class="text-lg font-semibold">{{ section.title }}</h4>
              <span :class="activeIndex === index ? 'rotate-180' : 'rotate-0'" class="transition-transform transform">
                &#x25BC;
              </span>
            </div>
            <div 
              v-show="activeIndex === index" 
              class="bg-white p-6 border border-gray-300 rounded-lg shadow-md transition-all duration-300"
            >
              <p class="text-gray-700 leading-relaxed">{{ section.content }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script>
  export default {
    name: "AboutUs",
    data() {
      return {
        activeIndex: null, // To track which accordion section is active
        accordionSections: [
          {
            title: "History",
            content: "The Initao Regional Health Office has a rich history of serving the community with quality healthcare services. Established in 1985, we have grown to meet the needs of an expanding population, adapting our services to modern healthcare challenges."
          },
          {
            title: "Our Approach",
            content: "We adopt a patient-centered approach to healthcare, ensuring that every individual receives personalized care. Our services are designed to cater to the diverse needs of our community, from preventive care to specialized treatments."
          },
          {
            title: "Our Future Plans",
            content: "Looking ahead, we aim to expand our healthcare facilities, enhance our community outreach programs, and continuously improve our services through technological advancements and training for our healthcare professionals."
          }
        ]
      };
    },
    methods: {
      toggleAccordion(index) {
        this.activeIndex = this.activeIndex === index ? null : index;
      }
    }
  };
  </script>
  
  <style scoped>
  /* Additional Styles */
  h2 {
    font-family: 'Poppins', sans-serif;
  }
  
  ul li {
    margin-bottom: 8px;
  }
  
  .transition-transform {
    transition: transform 0.3s ease;
  }
  </style>
  