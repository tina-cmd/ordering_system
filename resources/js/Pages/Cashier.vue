<template>

  <Head title="Cashier" />

  <div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <header class="bg-indigo-700 text-white py-4 shadow-lg">
      <div class="container mx-auto px-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold flex items-center">
          <Coins class="w-8 h-8 mr-2" />
          Cashier Dashboard
        </h1>
        <p class="text-sm hidden sm:block bg-indigo-600 px-3 py-1 rounded-full">Manage Orders</p>
      </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
      <div class="flex flex-col lg:flex-row gap-8">
        <!-- Left Column: Unpaid Orders -->
        <div class="w-full lg:w-1/2 bg-white rounded-lg shadow-lg p-6">
          <div class="mb-4">
            <h2 class="text-xl font-semibold text-gray-800 mb-2 flex items-center">
              <ClipboardListIcon class="w-6 h-6 mr-2 text-indigo-600" />
              Unpaid Orders
            </h2>
            <div class="relative">
              <input v-model="searchQuery" type="text" placeholder="Search by Order/Table #"
                class="w-full px-4 py-2 pl-10 border rounded-md focus:ring-2 focus:ring-indigo-400 focus:outline-none" />
              <SearchIcon class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
            </div>
          </div>
          <div class="space-y-4 max-h-[calc(100vh-300px)] overflow-y-auto pr-2">
            <div v-for="(order, index) in filteredOrders" :key="index"
              @click="selectOrder(order.order_number, order.table_number)"
              class="cursor-pointer p-4 border rounded-lg shadow-sm hover:bg-indigo-50 transition duration-200"
              :class="{ 'border-indigo-500 border-2 bg-indigo-50': selectedOrder.order == order.order_number }">
              <div class="flex justify-between items-center">
                <div>
                  <p class="font-semibold text-lg text-indigo-700">Order #{{ order.order_number }}</p>
                  <p class="text-gray-600 flex items-center">
                    <UsersIcon class="w-4 h-4 mr-1" />
                    Table #{{ order.table_number }}
                  </p>
                </div>
                <p class="text-lg font-bold text-indigo-600">₱{{ calculateTotal(order.order_number).toLocaleString() }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column: Order Details -->
        <div class="w-full lg:w-1/2 bg-white rounded-lg shadow-lg p-6">
          <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <FileTextIcon class="w-6 h-6 mr-2 text-indigo-600" />
            Order Details
          </h2>
          <div v-if="selectedOrder.order" class="space-y-4">
            <div class="flex justify-between items-center bg-indigo-50 p-4 rounded-lg">
              <div>
                <p class="font-semibold text-lg text-indigo-700">Order #{{ selectedOrder.order }}</p>
                <p class="text-gray-600 flex items-center">
                  <UsersIcon class="w-4 h-4 mr-1" />
                  Table #{{ selectedOrder.table }}
                </p>
              </div>
              <p class="text-2xl font-bold text-indigo-600">₱{{ calculateTotal(selectedOrder.order).toLocaleString() }}
              </p>
            </div>

            <div class="max-h-[calc(100vh-450px)] overflow-y-auto">
              <table class="w-full text-left">
                <thead class="bg-gray-100">
                  <tr>
                    <th class="p-2 text-indigo-700">Qty</th>
                    <th class="p-2 text-indigo-700">Item</th>
                    <th class="p-2 text-indigo-700">Price</th>
                    <th class="p-2 text-indigo-700">Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in props.orders.filter(item => item.order_number == selectedOrder.order)"
                    :key="item.id" class="border-b">
                    <td class="p-2">{{ item.quantity }}</td>
                    <td class="p-2">{{ item.item_name }}</td>
                    <td class="p-2">₱{{ item.price.toLocaleString() }}</td>
                    <td class="p-2">₱{{ (item.quantity * item.price).toLocaleString() }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <button @click="markAsPaid(selectedOrder.order)"
              class="w-full bg-green-500 text-white px-6 py-3 rounded-md text-lg font-semibold hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 transition duration-200 flex items-center justify-center">
              <CheckCircleIcon class="w-6 h-6 mr-2" />
              Mark as Paid
            </button>
          </div>
          <div v-else class="text-gray-500 text-center mt-20">
            <ClipboardIcon class="w-16 h-16 mx-auto mb-4 text-gray-400" />
            <p class="text-xl">Select an order to view details</p>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue'
import {
  Coins,
  ClipboardListIcon,
  SearchIcon,
  UsersIcon,
  FileTextIcon,
  CheckCircleIcon,
  ClipboardIcon
} from 'lucide-vue-next'

import { defineProps } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { onMounted } from 'vue';


const props = defineProps({
  orders: {
    type: Object,
    default: () => ({}),
  }
});


const orderNumber = ref({
  order_number: [],
  table_number: [],
});

onMounted(() => {
  props.orders.forEach(e => {
    // Check if the order_number is not already in the order_number array
    if (!orderNumber.value.order_number.includes(e.order_number)) {
      orderNumber.value.order_number.push(e.order_number);
      orderNumber.value.table_number.push(e.table_number);
    }
  });

  // orderNumber.value.order_number.forEach(e => console.log(e));
});



const searchQuery = ref('')
const selectedOrder = ref({
  order: null,
  table: null,
})
const filteredOrders = computed(() => {
  const query = searchQuery.value.toLowerCase().trim();
  if (!query) {
    return orderNumber.value.order_number.map((order, index) => ({
      order_number: order,
      table_number: orderNumber.value.table_number[index],
    }));
  }

  return orderNumber.value.order_number
    .map((order, index) => ({
      order_number: order,
      table_number: orderNumber.value.table_number[index],
    }))
    .filter(
      (item) =>
        item.order_number.toString().includes(query) ||
        item.table_number.toString().includes(query)
    );
});


// Select an order to view details
const selectOrder = (order, table) => {
  selectedOrder.value.order = order;
  selectedOrder.value.table = table;
}

// Mark the selected order as paid
const markAsPaid = (order) => {
  const form = useForm({
    order_num: order,  // Assuming order_number is the key you're sending
  });

  form.post('/cashier/paid', {
    onSuccess: () => {
      alert('Order Paid!');
    },
    onError: (errors) => {
      // Display error messages from the server
      const errorMessages = Object.values(errors).join('\n');
      alert(`Failed to update the order:\n${errorMessages}`);
    },
    onFinish: () => {
      console.log('Request completed');
    }
  })
};


// Calculate total price for an order
const calculateTotal = (order_number) => {
  let total = 0;
  props.orders.forEach(item => {
    if (item.order_number == order_number) {
      let num = total;
      num += (item.price * item.quantity);
      total = num;
    }
  })
  return total;
}
</script>