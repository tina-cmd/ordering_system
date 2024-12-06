<template>
    <div class="bg-gray-100 h-dvh">
        <!-- Header -->
        <header class="bg-blue-600 text-white py-4 shadow-md mt-0">
            <div
                class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex justify-between items-center"
            >
                <h1 class="text-xl font-semibold">Cashier Dashboard</h1>
                <p class="text-sm">Manage unpaid orders seamlessly</p>
            </div>
        </header>

        <!-- Main Content -->
        <div class="py-12 bg-gray-100 h-full">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 h-5/6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 h-full">
                    <!-- Left Column: Unpaid Orders -->
                    <div
                        class="md:col-span-2 bg-white p-6 rounded-lg shadow-md h-full"
                    >
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-800">
                                Unpaid Orders
                            </h2>
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search by Order/Table #"
                                class="px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 focus:outline-none"
                            />
                        </div>
                        <div
                            v-for="order in filteredOrders"
                            :key="order.id"
                            @click="selectOrder(order)"
                            class="cursor-pointer p-4 border rounded-lg shadow-sm bg-gray-50 hover:bg-gray-100 transition duration-200"
                        >
                            <p class="text-gray-700">
                                <strong>Order #:</strong>
                                {{ order.orderNumber }}
                            </p>
                            <p class="text-gray-700">
                                <strong>Table #:</strong> {{ order.table }}
                            </p>
                        </div>
                    </div>

                    <!-- Right Column: Order Details -->
                    <div
                        class="bg-white p-6 rounded-lg shadow-md flex flex-col h-full"
                    >
                        <div v-if="selectedOrder" class="flex flex-col h-full">
                            <!-- Order Info -->
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">
                                    Order Details
                                </h3>
                                <p>
                                    <strong>Order #:</strong>
                                    {{ selectedOrder.orderNumber }}
                                </p>
                                <p>
                                    <strong>Table #:</strong>
                                    {{ selectedOrder.table }}
                                </p>
                            </div>

                            <!-- Items Section -->
                            <div class="flex-grow overflow-auto">
                                <table class="w-full text-left text-gray-700">
                                    <thead>
                                        <tr>
                                            <th class="p-2">Qty</th>
                                            <th class="p-2">Item Name</th>
                                            <th class="p-2">Price</th>
                                            <th class="p-2">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="item in selectedOrder.items"
                                            :key="item.id"
                                            class="border-t"
                                        >
                                            <td class="p-2">
                                                {{ item.quantity }}
                                            </td>
                                            <td class="p-2">{{ item.name }}</td>
                                            <td class="p-2">
                                                ₱{{ item.price }}
                                            </td>
                                            <td class="p-2">
                                                ₱{{
                                                    item.quantity * item.price
                                                }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Total and Button -->
                            <div class="mt-4">
                                <p class="font-semibold text-gray-800">
                                    <strong>Total:</strong> ₱{{
                                        calculateTotal(selectedOrder.items)
                                    }}
                                </p>
                                <button
                                    @click="markAsPaid"
                                    class="mt-4 w-full bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 transition duration-200"
                                >
                                    Mark as Paid
                                </button>
                            </div>
                        </div>
                        <div v-else class="text-gray-500 text-center mt-20">
                            <p>Select an order to view details</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";

// Sample Data
const orders = ref([
    {
        id: 1,
        orderNumber: "ORD001",
        table: 5,
        items: [
            { id: 1, name: "Burger", price: 100, quantity: 2 },
            { id: 2, name: "Fries", price: 50, quantity: 1 },
        ],
    },
    {
        id: 2,
        orderNumber: "ORD002",
        table: 3,
        items: [
            { id: 1, name: "Burger", price: 100, quantity: 2 },
            { id: 2, name: "Fries", price: 50, quantity: 1 },
            { id: 1, name: "Burger", price: 100, quantity: 2 },
            { id: 2, name: "Fries", price: 50, quantity: 1 },
            { id: 1, name: "Burger", price: 100, quantity: 2 },
            { id: 2, name: "Fries", price: 50, quantity: 1 },
            { id: 1, name: "Burger", price: 100, quantity: 2 },
            { id: 2, name: "Fries", price: 50, quantity: 1 },
            { id: 1, name: "Burger", price: 100, quantity: 2 },
            { id: 2, name: "Fries", price: 50, quantity: 1 },
            { id: 1, name: "Burger", price: 100, quantity: 2 },
            { id: 2, name: "Fries", price: 50, quantity: 1 },
        ],
    },
]);

const searchQuery = ref("");
const selectedOrder = ref(null);

// Filter orders based on search query
const filteredOrders = computed(() => {
    if (!searchQuery.value.trim()) return orders.value;

    return orders.value.filter(
        (order) =>
            order.orderNumber
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase()) ||
            order.table.toString().includes(searchQuery.value)
    );
});

// Select an order to view details
const selectOrder = (order) => {
    selectedOrder.value = order;
};

// Mark the selected order as paid
const markAsPaid = () => {
    if (selectedOrder.value) {
        orders.value = orders.value.filter(
            (order) => order.id !== selectedOrder.value.id
        );
        selectedOrder.value = null;
    }
};

// Calculate total price for an order
const calculateTotal = (items) => {
    return items.reduce((total, item) => total + item.price * item.quantity, 0);
};
</script>

<style scoped>
/* Additional Styling */
header {
    background-color: #2563eb; /* Tailwind blue-600 */
}

input {
    width: 100%;
    max-width: 250px;
}

.cursor-pointer {
    transition: transform 0.2s;
}

.cursor-pointer:hover {
    transform: scale(1.02);
    background-color: #f9fafb;
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead th {
    text-align: left;
    font-weight: 600;
}

tbody td {
    padding: 0.5rem;
}

/* Fixed height for table container with overflow */
.flex-grow {
    height: 300px; /* Set a fixed height for the table section */
    overflow-y: auto; /* Enable vertical scrolling if content exceeds height */
}
</style>
