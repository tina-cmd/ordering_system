<template>
    
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
  