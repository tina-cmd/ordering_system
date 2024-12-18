<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Leaf } from 'lucide-vue-next'

const appName = ref('EcoEats')
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-green-50 to-green-100 flex flex-col items-center justify-center p-4">
    <div class="w-full max-w-md">
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center p-4 bg-gray-200 rounded-full shadow-lg transition-transform duration-300 hover:scale-105">
            <div class="flex shrink-0 items-center">
                <img src="../../../storage/app/public/uploads/icons8-vegan-food-50.png" class="block h-14 w-auto fill-current text-white">
            </div>
        </div>
        <h1 class="mt-4 text-3xl font-bold text-green-800">{{ appName }}</h1>
      </div>

      <div class="bg-white rounded-xl shadow-xl overflow-hidden transition-all duration-300 hover:shadow-2xl">
        <div class="p-8">
          <slot />
        </div>
      </div>
    </div>
  </div>
</template>