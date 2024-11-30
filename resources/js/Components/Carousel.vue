<template>
    <div 
      class="relative w-full overflow-hidden h-[500px] md:h-[400px] lg:h-[600px]"
      @mouseover="pauseCarousel"
      @mouseleave="resumeCarousel"
    >
      <!-- Carousel Slides -->
      <div
        class="flex transition-transform duration-700 ease-in-out"
        :style="{ transform: `translateX(-${currentSlide * 100}%)` }"
      >
        <div
          v-for="(image, index) in images"
          :key="index"
          class="relative w-full h-full flex-shrink-0"
        >
          <!-- Image -->
          <img
            :src="image"
            alt="Slide"
            class="w-full h-full object-cover"
          />
          
          <!-- Learn More Button -->
          <div 
            class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 opacity-0 hover:opacity-100 transition-opacity duration-300"
          >
            <button 
              class="px-6 py-2 bg-green-600 text-white text-lg font-semibold rounded-lg shadow-md hover:bg-green-700 transition"
            >
              Learn More
            </button>
          </div>
        </div>
      </div>
  
      <!-- Left Arrow -->
      <button 
        @click="prevSlide"
        class="absolute top-1/2 left-4 transform -translate-y-1/2 text-white bg-black bg-opacity-50 p-2 rounded-full focus:outline-none hover:bg-opacity-70"
      >
        &#10094;
      </button>
  
      <!-- Right Arrow -->
      <button 
        @click="nextSlide"
        class="absolute top-1/2 right-4 transform -translate-y-1/2 text-white bg-black bg-opacity-50 p-2 rounded-full focus:outline-none hover:bg-opacity-70"
      >
        &#10095;
      </button>
  
      <!-- Indicators -->
      <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
        <span
          v-for="(_, index) in images"
          :key="index"
          @click="goToSlide(index)"
          :class="`h-3 w-3 rounded-full ${currentSlide === index ? 'bg-white' : 'bg-gray-400'}`"
          class="cursor-pointer"
        ></span>
      </div>
    </div>
  </template>
<script>
export default {
  name: "Carousel",
  data() {
    return {
      currentSlide: 0,
      interval: null,
      images: [
        "/images/initao-helping-1.png",
        "/images/initao-helping-2.png",
        "/images/initao-helping-3.png",
      ],
    };
  },
  methods: {
    goToSlide(index) {
      this.currentSlide = index;
    },
    prevSlide() {
      this.currentSlide = (this.currentSlide - 1 + this.images.length) % this.images.length;
    },
    nextSlide() {
      this.currentSlide = (this.currentSlide + 1) % this.images.length;
    },
    pauseCarousel() {
      clearInterval(this.interval);
    },
    resumeCarousel() {
      this.startCarousel();
    },
    startCarousel() {
      this.interval = setInterval(() => {
        this.currentSlide = (this.currentSlide + 1) % this.images.length;
      }, 3000);
    },
  },
  mounted() {
    this.startCarousel();
  },
  beforeUnmount() {
    clearInterval(this.interval);
  },
};
</script>
<style scoped>
/* Default heights for images */
img {
  object-fit: cover; /* Ensure the image covers the container proportionally */
}

/* Custom heights for specific breakpoints */
@media (max-width: 768px) {
  .relative {
    height: 300px; /* Smaller height for mobile devices */
  }
}

@media (min-width: 1024px) {
  .relative {
    height: 600px; /* Larger height for desktop screens */
  }
}
</style>
  