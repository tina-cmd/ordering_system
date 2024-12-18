<script setup>
import { ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ClipboardList, CheckCircle, XCircle, Clock } from 'lucide-vue-next';
import { defineProps } from 'vue';
import { onMounted } from 'vue';


const props = defineProps({
  orders: {
    type: Object,
    default: () => ({}),
  }
})

const listOrders = ref([])
const pendingOrder = ref([])
const cancelledOrder = ref([])
const completedOrder = ref([])
const readyOrder = ref([])



//data mounting
onMounted(() => {
  props.orders.forEach(element => {
    if (!listOrders.value.includes(element.order_number)) {
      listOrders.value.push(element.order_number)
      if (!pendingOrder.value.includes(element.order_number) && element.status === 'Pending') {
        pendingOrder.value.push(element.order_number)
      }
      if (!cancelledOrder.value.includes(element.order_number) && element.status === 'Cancelled') {
        cancelledOrder.value.push(element.order_number)
      }
      if (!completedOrder.value.includes(element.order_number) && element.status === 'Completed') {
        completedOrder.value.push(element.order_number)
      }
      if (!readyOrder.value.includes(element.order_number) && element.status === 'Ready') {
        readyOrder.value.push(element.order_number)
      }
    }
  });

  // props.orders.forEach(e => console.log(e))
})


const statusColors = {
  Pending: 'bg-yellow-100 text-yellow-800',
  Completed: 'bg-green-100 text-green-800',
  Cancelled: 'bg-red-100 text-red-800',
  Ready: 'bg-blue-100 text-blue-800',
};



function updateStatus(orderNumber, status) {
  const form = useForm({
    order_number: orderNumber,
    status: status,
  });

  form.post('/order/update', {
    onSuccess: () => {
      alert('Order status updated successfully!');
    },
    onError: (errors) => {
      // Display error messages from the server
      const errorMessages = Object.values(errors).join('\n');
      alert(`Failed to update the order:\n${errorMessages}`);
    },
    onFinish: () => {
      console.log('Request completed');
    }
  });
}

function getStatusIcon(status) {
  switch (status) {
    case 'Pending':
      return Clock;
    case 'Completed':
      return CheckCircle;
    case 'Cancelled':
      return XCircle;
    case 'Ready':
      return CheckCircle; // Reusing the check icon for "Ready"
    default:
      return null;
  }
}

</script>

<template>

  <Head title="Kitchen Orders" />

  <div class="min-h-screen bg-gray-100">
    <header class="bg-indigo-600 text-white py-6 shadow-lg">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold">Kitchen Order Interface</h1>
      </div>
    </header>

    <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg bg-white p-6 shadow-md">
          <div class="flex items-center">
            <ClipboardList class="h-10 w-10 text-indigo-500" />
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">Total Orders</p>
              <p class="text-2xl font-semibold text-gray-900">{{ listOrders.length }}</p>
            </div>
          </div>
        </div>
        <div class="rounded-lg bg-white p-6 shadow-md">
          <div class="flex items-center">
            <Clock class="h-10 w-10 text-yellow-500" />
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">Active Orders</p>
              <p class="text-2xl font-semibold text-gray-900">{{ pendingOrder.length }}</p>
            </div>
          </div>
        </div>
        <div class="rounded-lg bg-white p-6 shadow-md">
          <div class="flex items-center">
            <CheckCircle class="h-10 w-10 text-green-500" />
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">Completed Orders</p>
              <p class="text-2xl font-semibold text-gray-900">{{ completedOrder.length }}</p>
            </div>
          </div>
        </div>
        <div class="rounded-lg bg-white p-6 shadow-md">
          <div class="flex items-center">
            <XCircle class="h-10 w-10 text-red-500" />
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">Cancelled Orders</p>
              <p class="text-2xl font-semibold text-gray-900">{{ cancelledOrder.length }}</p>
            </div>
          </div>
        </div>
      </div>

      <div v-if="pendingOrder.length === 0 && readyOrder.length === 0" class="text-center py-12">
        <ClipboardList class="mx-auto h-16 w-16 text-gray-400" />
        <h3 class="mt-2 text-sm font-medium text-gray-900">No active orders</h3>
        <p class="mt-1 text-sm text-gray-500">All orders have been completed or cancelled.</p>
      </div>

      <div v-else class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        <div v-for="(pending, index) in [...pendingOrder, ...readyOrder]" :key="index" :id="pending"
          class="rounded-lg bg-white p-6 shadow-lg transition duration-300 ease-in-out hover:shadow-xl">
          <h2 class="mb-4 text-xl font-bold text-gray-800">Order #{{ pending }}</h2>

          <div class="mb-4 overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col"
                    class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    Item
                  </th>
                  <th scope="col"
                    class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    Qty
                  </th>
                  <th scope="col"
                    class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    Status
                  </th>
                  <th scope="col"
                    class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    Change
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 bg-white">
                <tr v-for="item in props.orders.filter((item) => item.order_number === pending)" :key="item.id">
                  <td class="px-4 py-2 whitespace-normal break-words">
                    <div class="text-sm font-medium text-gray-900">{{ item.item_name }}</div>
                  </td>
                  <td class="px-4 py-2 whitespace-normal">
                    <div class="text-sm text-gray-500">{{ item.quantity }}</div>
                  </td>
                  <td class="px-4 py-2 whitespace-normal">
                    <span :class="[
                      'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                      statusColors[item.status],
                    ]">
                      <component :is="getStatusIcon(item.status)" class="mr-1 h-4 w-4" />
                      {{ item.status }}
                    </span>
                  </td>
                  <td class="px-4 py-2 whitespace-normal">
                    <button v-if="item.status === 'Pending'" @click="updateStatus(item.order_id, 'Ready')"
                      class="inline-flex items-center rounded-md bg-blue-100 px-2 py-1 text-xs font-medium text-blue-700 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                      Ready
                    </button>
                    <span v-else-if="item.status === 'Ready'"
                      class="inline-flex items-center rounded-md bg-blue-200 px-2 py-1 text-xs font-medium text-blue-800">
                      <CheckCircle class="mr-1 h-4 w-4" />
                      Ready
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="flex justify-end space-x-2">
            <button @click="updateStatus(pending, 'Cancelled')"
              class="inline-flex items-center rounded-md border border-transparent bg-red-100 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
              <XCircle class="mr-2 h-4 w-4" />
              Cancel
            </button>
            <button @click="updateStatus(pending, 'Completed')"
              class="inline-flex items-center rounded-md border border-transparent bg-green-100 px-4 py-2 text-sm font-medium text-green-700 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
              <CheckCircle class="mr-2 h-4 w-4" />
              Complete
            </button>
          </div>
        </div>
      </div>

    </main>
  </div>
</template>