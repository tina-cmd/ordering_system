<template>
    <AuthenticatedLayout>
      <Head title="Analytics" />
      <template #header>
        <h2 class="text-2xl font-bold leading-tight text-gray-800">
          Analytics Dashboard
        </h2>
      </template>
  
      <div class="py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-3xl font-bold text-indigo-800">
              Sales Analytics
            </h1>
            <div class="mt-4 flex space-x-2 sm:mt-0">
              <select
                v-model="selectedFilter"
                @change="applyFilter"
                class="rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
              >
                <option value="day">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
              </select>
              <button
                @click="toggleDateRangePicker"
                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
              >
                <CalendarIcon class="mr-2 h-5 w-5 text-gray-400" />
                Custom Range
              </button>
            </div>
          </div>
  
          <div class="grid gap-6 lg:grid-cols-3">
            <!-- Summary Cards -->
            <div v-for="(stat, index) in summaryStats" :key="index" class="rounded-lg bg-white p-6 shadow-md">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <component :is="stat.icon" class="h-8 w-8 text-indigo-600" />
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="truncate text-sm font-medium text-gray-500">{{ stat.name }}</dt>
                    <dd class="text-2xl font-semibold text-gray-900">{{ stat.value }}</dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>
  
          <!-- Graph Section -->
          <div class="mt-8 rounded-lg bg-white p-6 shadow-md">
            <h2 class="mb-4 text-xl font-semibold text-gray-800">Sales Overview</h2>
            <div class="h-80 w-full">
              <canvas ref="salesGraph"></canvas>
            </div>
          </div>
  
          <!-- Date Range Picker Modal -->
          <TransitionRoot appear :show="isDateRangePickerVisible" as="template">
            <Dialog as="div" @close="toggleDateRangePicker" class="relative z-10">
              <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
              >
                <div class="fixed inset-0 bg-black bg-opacity-25" />
              </TransitionChild>
  
              <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                  <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0 scale-95"
                    enter-to="opacity-100 scale-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100 scale-100"
                    leave-to="opacity-0 scale-95"
                  >
                    <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                      <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                        Select Date Range
                      </DialogTitle>
                      <div class="mt-4 space-y-4">
                        <div>
                          <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>
                          <input
                            type="date"
                            id="startDate"
                            v-model="startDate"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                          />
                        </div>
                        <div>
                          <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
                          <input
                            type="date"
                            id="endDate"
                            v-model="endDate"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                          />
                        </div>
                      </div>
                      <div class="mt-6">
                        <button
                          type="button"
                          @click="filterByDateRange"
                          class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                        >
                          Apply Filter
                        </button>
                      </div>
                    </DialogPanel>
                  </TransitionChild>
                </div>
              </div>
            </Dialog>
          </TransitionRoot>
  
          <!-- Sales Data Table -->
          <div class="mt-8 rounded-lg bg-white p-6 shadow-md">
            <h2 class="mb-4 text-xl font-semibold text-gray-800">Sales Data</h2>
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Amount</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                  <tr v-for="sale in filteredSales" :key="sale.id">
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ formatDate(sale.date) }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">${{ sale.amount.toFixed(2) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div v-if="filteredSales.length === 0" class="py-4 text-center text-gray-500">
              No sales data available for the selected period.
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import { ref, onMounted, computed } from 'vue'
  import { Head } from '@inertiajs/vue3'
  import Chart from 'chart.js/auto'
  import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
  import { CalendarIcon, DollarSignIcon, ShoppingCartIcon, TrendingUpIcon } from 'lucide-vue-next'
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
  
  // Sales Data
  const salesData = ref([
    { id: 1, date: '2024-11-01', amount: 120 },
    { id: 2, date: '2024-11-02', amount: 200 },
    { id: 3, date: '2024-11-03', amount: 150 },
    { id: 4, date: '2024-11-10', amount: 100 },
    { id: 5, date: '2024-11-15', amount: 250 },
    { id: 6, date: '2024-12-01', amount: 300 },
  ])
  
  const filteredSales = ref([])
  const selectedFilter = ref('day')
  const startDate = ref('')
  const endDate = ref('')
  const isDateRangePickerVisible = ref(false)
  const salesGraph = ref(null)
  
  const summaryStats = computed(() => [
    {
      name: 'Total Sales',
      value: `₱${filteredSales.value.reduce((sum, sale) => sum + sale.amount, 0).toFixed(2)}`,
      icon: DollarSignIcon,
    },
    {
      name: 'Number of Sales',
      value: filteredSales.value.length,
      icon: ShoppingCartIcon,
    },
    {
      name: 'Average Sale',
      value: filteredSales.value.length > 0
        ? `$${(filteredSales.value.reduce((sum, sale) => sum + sale.amount, 0) / filteredSales.value.length).toFixed(2)}`
        : '$0.00',
      icon: TrendingUpIcon,
    },
  ])
  
  const applyFilter = () => {
    const now = new Date()
    filteredSales.value = salesData.value.filter((sale) => {
      const saleDate = new Date(sale.date)
      if (selectedFilter.value === 'day') {
        return saleDate.toDateString() === now.toDateString()
      }
      if (selectedFilter.value === 'week') {
        const startOfWeek = new Date(now.setDate(now.getDate() - now.getDay()))
        const endOfWeek = new Date(now.setDate(now.getDate() + 6))
        return saleDate >= startOfWeek && saleDate <= endOfWeek
      }
      if (selectedFilter.value === 'month') {
        return saleDate.getMonth() === now.getMonth() && saleDate.getFullYear() === now.getFullYear()
      }
      return false
    })
    updateChart()
  }
  
  const filterByDateRange = () => {
    const start = new Date(startDate.value)
    const end = new Date(endDate.value)
    filteredSales.value = salesData.value.filter((sale) => {
      const saleDate = new Date(sale.date)
      return saleDate >= start && saleDate <= end
    })
    updateChart()
    toggleDateRangePicker()
  }
  
  const toggleDateRangePicker = () => {
    isDateRangePickerVisible.value = !isDateRangePickerVisible.value
  }
  
  const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' }
    return new Date(dateString).toLocaleDateString(undefined, options)
  }
  
  const createGraph = () => {
    const ctx = salesGraph.value.getContext('2d')
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: filteredSales.value.map(sale => formatDate(sale.date)),
        datasets: [{
          label: 'Sales ($)',
          data: filteredSales.value.map(sale => sale.amount),
          borderColor: 'rgb(79, 70, 229)',
          backgroundColor: 'rgba(79, 70, 229, 0.1)',
          fill: true,
          tension: 0.4,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: 'rgb(17, 24, 39)',
            titleColor: 'rgb(243, 244, 246)',
            bodyColor: 'rgb(243, 244, 246)',
            borderColor: 'rgb(75, 85, 99)',
            borderWidth: 1,
            displayColors: false,
          },
        },
        scales: {
          x: {
            grid: {
              display: false,
            },
          },
          y: {
            beginAtZero: true,
            grid: {
              borderDash: [2],
              drawBorder: false,
            },
          },
        },
      },
    })
  }
  
  const updateChart = () => {
    if (salesGraph.value) {
      const chart = Chart.getChart(salesGraph.value)
      if (chart) {
        chart.data.labels = filteredSales.value.map(sale => formatDate(sale.date))
        chart.data.datasets[0].data = filteredSales.value.map(sale => sale.amount)
        chart.update()
      }
    }
  }
  
  onMounted(() => {
    applyFilter()
    createGraph()
  })
  </script>