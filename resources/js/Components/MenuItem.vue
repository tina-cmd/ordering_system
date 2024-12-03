<template>
    <div class="bg-white border border-gray-300 shadow-sm rounded-md p-4 flex flex-col items-center justify-between hover:shadow-lg transition duration-200 ease-in-out w-full">
      <img
        src="../../../storage/app/public/uploads/istockphoto-458464735-612x612.jpg"
        :alt="item.name"
        class="w-full h-48 object-cover rounded-md mb-4"
      />
      <h3 class="text-lg font-semibold text-gray-800">{{ item.name }}</h3>
      <p class="text-gray-600 text-sm mb-4">{{ item.price }} USD</p>
    
      <!-- Flex container for buttons -->
      <div class="flex w-full justify-between mt-4">
        <!-- Edit Button -->
        <button
          @click="editItem"
          class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-900 transition duration-200"
        >
          Edit
        </button>
  
        <!-- Delete Button -->
        <button
          @click="deleteItem"
          class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition duration-200"
        >
          Delete
        </button>
      </div>
    </div>
  </template>
    
  <script setup>
  import { defineProps, defineEmits } from 'vue';
    
  const props = defineProps({
    item: {
      type: Object,
      required: true
    }
  });
    
  const emit = defineEmits(['edit', 'delete']);
    
  const editItem = () => {
    emit('edit', props.item);
  };
    
  const deleteItem = () => {
    emit('delete', props.item.id);
  };
  </script>
    
  <style scoped>
  /* Ensure the container doesn't get squeezed in smaller screens */
  .w-full {
    width: 100%;
  }
  
  /* Adjusting flex layout for smaller screen */
  @media (max-width: 640px) {
    .flex {
      flex-direction: column;
      align-items: center;
    }
  
    .space-x-3 {
      margin-bottom: 10px; /* Adds spacing between buttons on mobile */
    }
  }
  </style>
  