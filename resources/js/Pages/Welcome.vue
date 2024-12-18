<template>
    <Head title="Welcome" />

    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center p-4">
      <div class="max-w-md w-full bg-white rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105">
        <div class="p-8 text-center space-y-6">
          <div class="flex justify-center">
            <ShoppingCart class="w-16 h-16 text-blue-600" />
          </div>
          <h1 class="text-3xl sm:text-4xl font-bold text-blue-800">
            Welcome to QuickOrder
          </h1>
          <p class="text-gray-600">
            Streamline your ordering process and delight your customers!
          </p>
  
          <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 justify-center">
            <Link
              href="/login"
              class="flex items-center justify-center px-6 py-3 text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors duration-300 transform hover:scale-105"
            >
              <LogIn class="w-5 h-5 mr-2" />
              Login
            </Link>
            <Link
                :href="route('register')"
              class="flex items-center justify-center px-6 py-3 text-blue-600 border border-blue-600 hover:bg-blue-50 rounded-md transition-colors duration-300 transform hover:scale-105"
            >
              <UserPlus class="w-5 h-5 mr-2" />
              Register
            </Link>
          </div>
        </div>
  
        <div class="bg-blue-600 p-4">
          <nav class="flex justify-center space-x-4">
            <Link :href="route('cashier')" class="text-blue-100 hover:text-white transition-colors duration-300">Cashier</Link>
            <Link :href="route('order')" class="text-blue-100 hover:text-white transition-colors duration-300">Proceed to Orders</Link>
          </nav>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ShoppingCart, LogIn, UserPlus } from 'lucide-vue-next'
  import { Head, Link } from "@inertiajs/vue3";
  </script>