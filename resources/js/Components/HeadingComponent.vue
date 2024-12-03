<template>
    <header class="bg-blue-900 text-white py-4 rounded-lg shadow-md">
      <h1 class="text-3xl font-semibold">{{ projectName }}</h1>
    </header>
  </template>
  
  <script setup>
  import { defineProps } from 'vue';
  
  const props = defineProps({
    projectName: {
      type: String,
      required: true
    }
  });
  </script>
  
  <style scoped>
  header {
    text-align: center;
    margin: 20px auto;
  }
  h1 {
    font-size: 2.25rem;
  }
  </style>
  