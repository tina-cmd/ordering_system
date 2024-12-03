<template>
    <div class="container mx-auto p-4">
      <h1 class="text-2xl font-bold mb-4 text-center text-gray-800">Orders</h1>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div
          v-for="(items, orderNumber) in groupedOrders"
          :key="orderNumber"
          :id="`order-${orderNumber}`"
          class="bg-white border border-gray-200 shadow-md rounded-lg p-4 relative pb-16"
        >
          <h2 class="text-lg font-bold text-gray-700 mb-4 text-center">
            Order #{{ orderNumber }}
          </h2>
          <table class="min-w-full bg-white border-collapse border border-gray-300">
            <thead>
              <tr class="bg-gray-800">
                <th
                  class="border border-gray-300 px-4 py-2 text-center text-sm font-medium text-white uppercase"
                >
                  Item Name
                </th>
                <th
                  class="border border-gray-300 px-4 py-2 text-center text-sm font-medium text-white uppercase"
                >
                  Quantity
                </th>
                <th
                  class="border border-gray-300 px-4 py-2 text-center text-sm font-medium text-white uppercase"
                >
                  Status
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="item in items"
                :key="item.id"
                class="border-t hover:bg-gray-200 odd:bg-gray-50 even:bg-gray-100"
              >
                <td class="border border-gray-300 px-4 py-2 text-center text-gray-700">
                  {{ item.itemname }}
                </td>
                <td class="border border-gray-300 px-4 py-2 text-center text-gray-700">
                  {{ item.quantity }}
                </td>
                <td
                  class="border border-gray-300 px-4 py-2 text-center text-gray-700"
                >
                  {{ item.status }}
                </td>
              </tr>
            </tbody>
          </table>
  
          <!-- Cancel and Done buttons at the bottom-right -->
          <div class="absolute bottom-4 right-4 flex space-x-2">
            <button
              @click="updateStatus(orderNumber, 'Cancelled')"
              class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition duration-200"
            >
              Cancel
            </button>
            <button
              @click="updateStatus(orderNumber, 'Completed')"
              class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-200"
            >
              Done
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from "vue";
  
  // Replace with data fetched from the Laravel backend.
  const groupedOrders = ref({
    1: [
      { id: 1, itemname: "Item A", quantity: 2, status: "Pending" },
      { id: 2, itemname: "Item B", quantity: 1, status: "Pending" },
    ],
    2: [
      { id: 3, itemname: "Item C", quantity: 4, status: "Pending" },
      { id: 4, itemname: "Item D", quantity: 2, status: "Pending" },
    ],
  });
  
  function updateStatus(orderNumber, status) {
    // Update the status for all items in the order
    groupedOrders.value[orderNumber] = groupedOrders.value[orderNumber].map(
      (item) => ({
        ...item,
        status,
      })
    );
  }
  </script>
  
  <style>
  /* Add any custom styles here, or rely entirely on Tailwind CSS. */
  </style>
  