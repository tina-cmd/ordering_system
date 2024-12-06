<template>
    <AuthenticatedLayout>
        <Head title="Analytics"/>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Analytics
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="space-y-6">
                    <h1 class="text-3xl font-semibold text-gray-900">
                        Sales Analytics
                    </h1>

                    <!-- Graph Section -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">
                            Sales Overview
                        </h2>
                        <canvas ref="salesGraph" class="w-full h-4/6"></canvas>
                    </div>

                    <!-- Sales Filters Section -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">
                            Filter Sales
                        </h2>
                        <div
                            class="flex flex-wrap space-y-4 md:space-y-0 md:space-x-4"
                        >
                            <select
                                v-model="selectedFilter"
                                @change="applyFilter"
                                class="px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400"
                            >
                                <option value="day">Sales Today</option>
                                <option value="week">Sales This Week</option>
                                <option value="month">Sales This Month</option>
                            </select>

                            <input
                                type="date"
                                v-model="startDate"
                                class="px-4 py-2 border border-gray-300 rounded-md"
                            />
                            <input
                                type="date"
                                v-model="endDate"
                                class="px-4 py-2 border border-gray-300 rounded-md"
                            />
                            <button
                                @click="filterByDateRange"
                                class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
                            >
                                View
                            </button>
                        </div>

                        <div class="mt-6">
                            <h3
                                class="text-md font-semibold text-gray-700 mb-2"
                            >
                                Sales Data
                            </h3>
                            <div v-if="filteredSales.length > 0">
                                <div
                                    v-for="sale in filteredSales"
                                    :key="sale.id"
                                    class="p-4 bg-gray-100 rounded-md mb-2"
                                >
                                    <p class="text-gray-800">
                                        {{ sale.date }} - ${{ sale.amount }}
                                    </p>
                                </div>
                            </div>
                            <div v-else>
                                <p class="text-gray-500">
                                    No sales data available.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { Head } from "@inertiajs/vue3";
import Chart from "chart.js/auto";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

// Sales Data
const salesData = ref([
    { id: 1, date: "2024-11-01", amount: 120 },
    { id: 2, date: "2024-11-02", amount: 200 },
    { id: 3, date: "2024-11-03", amount: 150 },
    { id: 4, date: "2024-11-10", amount: 100 },
    { id: 5, date: "2024-11-15", amount: 250 },
    { id: 6, date: "2024-12-01", amount: 300 },
]);

const filteredSales = ref([]);
const selectedFilter = ref("day");
const startDate = ref("");
const endDate = ref("");

const applyFilter = () => {
    const now = new Date();
    filteredSales.value = salesData.value.filter((sale) => {
        const saleDate = new Date(sale.date);
        if (selectedFilter.value === "day") {
            return saleDate.toDateString() === now.toDateString();
        }
        if (selectedFilter.value === "week") {
            const startOfWeek = new Date(
                now.setDate(now.getDate() - now.getDay())
            );
            const endOfWeek = new Date(now.setDate(now.getDate() + 6));
            return saleDate >= startOfWeek && saleDate <= endOfWeek;
        }
        if (selectedFilter.value === "month") {
            return (
                saleDate.getMonth() === now.getMonth() &&
                saleDate.getFullYear() === now.getFullYear()
            );
        }
        return false;
    });
};

const filterByDateRange = () => {
    const start = new Date(startDate.value);
    const end = new Date(endDate.value);
    filteredSales.value = salesData.value.filter((sale) => {
        const saleDate = new Date(sale.date);
        return saleDate >= start && saleDate <= end;
    });
};

// Chart
const salesGraph = ref(null);
const createGraph = () => {
    const labels = salesData.value.map((sale) => sale.date);
    const data = salesData.value.map((sale) => sale.amount);

    new Chart(salesGraph.value, {
        type: "line",
        data: {
            labels,
            datasets: [
                {
                    label: "Sales ($)",
                    data,
                    borderColor: "rgba(59, 130, 246, 0.7)",
                    backgroundColor: "rgba(59, 130, 246, 0.1)",
                    fill: true,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
            },
        },
    });
};

onMounted(() => {
    applyFilter();
    createGraph();
});
</script>

<style scoped>
/* Layout adjustments */
button {
    display: inline-block;
    margin-top: 20px;
}

.grid {
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
}

@media (max-width: 640px) {
    .grid {
        grid-template-columns: 1fr;
    }
}

@media (min-width: 641px) and (max-width: 768px) {
    .grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 769px) {
    .grid {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>
