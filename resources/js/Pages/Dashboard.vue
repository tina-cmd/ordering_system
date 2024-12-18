<script setup>
import { ref, onMounted, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { 
  ShoppingBag, 
  DollarSign, 
  Users, 
  AlertTriangle,
  TrendingUp,
  TrendingDown,
  ChevronRight,
  Sun,
  Cloud,
  CloudRain,
  Snowflake
} from 'lucide-vue-next';
import { defineProps } from 'vue';

const props = defineProps({
  menu: {
    type: Object,
    default: () => ({}),
  },
  order: {
    type: Object,
    default: () => ({}),
  },
  inventory: {
    type: Object,
    default: () => ({}),
  },
  user: {
    type: Object,
    default: () => ({}),
  }
});

const trend = (name, val) => {
  if(name === 'items') {
    return val > 50 ? 'up' : 'down';
  } else if(name === 'revenue') {
    return val > 5000 ? 'up' : 'down';
  } else if(name === 'active') {
    return val > 20 ? 'up' : 'down';
  } else {
    return val > 10 ? 'up' : 'down';
  }
};

const rev = ref(0);
const lowStock = ref([]);

const revenueToday = computed(() => {
  return props.order.reduce((total, e) => {
    return e.paid === 'Paid' ? total + Number(e.total_price) : total;
  }, 0);
});

const orderCount = computed(() => {
  return props.order.filter(e => e.status === 'Pending').length;
});

const stock = computed(() => {
  lowStock.value = props.inventory.filter(e => e.quantity_in_stock <= 20);
  return lowStock.value.length;
});

const stats = computed(() => [
  { name: 'Total Items', value: props.menu.length, icon: ShoppingBag, trend: trend('items', props.menu.length), color: 'bg-blue-500' },
  { name: 'Today\'s Revenue', value: `₱ ${revenueToday.value.toLocaleString()}`, icon: DollarSign, trend: trend('revenue', revenueToday.value), color: 'bg-green-500' },
  { name: 'Active Orders', value: orderCount.value, icon: Users, trend: trend('active', orderCount.value), color: 'bg-yellow-500' },
  { name: 'Low Stock Items', value: stock.value, icon: AlertTriangle, trend: 'neutral', color: 'bg-red-500' },
]);

const weather = ref({
  temp: null,
  description: '',
  icon: null
});

const getWeatherIcon = (description) => {
  if (description.includes('clear')) return Sun;
  if (description.includes('cloud')) return Cloud;
  if (description.includes('rain')) return CloudRain;
  if (description.includes('snow')) return Snowflake;
  return Cloud;
};

onMounted(() => {
  fetchWeather();
});

const currentTime = new Date();
const currentHour = currentTime.getHours();
let greeting = '';

if (currentHour < 12) {
  greeting = 'Good morning';
} else if (currentHour < 18) {
  greeting = 'Good afternoon';
} else {
  greeting = 'Good evening';
}
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Restaurant Dashboard
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8 overflow-hidden rounded-lg bg-white p-6 shadow-lg">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-3xl font-bold text-gray-900">{{ greeting }}, {{ props.user.name }}!</h2>
              <p class="mt-1 text-lg text-gray-600">Welcome to your restaurant dashboard. Here's an overview of your business today.</p>
            </div>
            <div v-if="weather.temp !== null" class="flex items-center space-x-2 bg-blue-100 px-4 py-2 rounded-full">
              <component :is="weather.icon" class="h-8 w-8 text-blue-500" />
              <div>
                <p class="text-lg font-semibold text-blue-800">{{ weather.temp }}°C</p>
                <p class="text-sm text-blue-600 capitalize">{{ weather.description }}</p>
              </div>
            </div>
          </div>
        </div>

        <div class="mb-8 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
          <div 
            v-for="item in stats" 
            :key="item.name" 
            class="overflow-hidden rounded-lg bg-white p-6 shadow-lg transition-all duration-300 hover:shadow-xl"
          >
            <div class="flex items-center">
              <div :class="`flex-shrink-0 rounded-full p-3 ${item.color}`">
                <component :is="item.icon" class="h-6 w-6 text-white" />
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="truncate text-sm font-medium text-gray-500">{{ item.name }}</dt>
                  <dd class="flex items-baseline">
                    <div class="text-2xl font-semibold text-gray-900">{{ item.value }}</div>
                    <div 
                      :class="[
                        item.trend === 'up' ? 'text-green-600' : '',
                        item.trend === 'down' ? 'text-red-600' : '',
                        'ml-2'
                      ]"
                    >
                      <TrendingUp v-if="item.trend === 'up'" class="h-5 w-5" />
                      <TrendingDown v-if="item.trend === 'down'" class="h-5 w-5" />
                    </div>
                  </dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-8 overflow-hidden rounded-lg bg-white shadow-lg">
          <div class="p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900 flex items-center">
              <AlertTriangle class="h-5 w-5 text-red-500 mr-2" />
              Low Stock Items
            </h3>
            <div class="mt-6 flow-root">
              <ul role="list" class="-my-5 divide-y divide-gray-200">
                <li v-for="item in lowStock" :key="item.inventory_id" class="py-5">
                  <div class="flex items-center space-x-4">
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium text-gray-900 truncate">
                        {{ item.item_name }}
                      </p>
                      <p class="text-sm text-gray-500 truncate">
                        Current Stock: {{ item.quantity_in_stock }} | Min Stock: 25
                      </p>
                    </div>
                    <div>
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        Low Stock
                      </span>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
            <div class="mt-6">
              <Link :href="route('inventory')" class="flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                View all inventory
                <ChevronRight class="ml-2 h-4 w-4" />
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.overflow-hidden {
  animation: fadeIn 0.5s ease-out;
}
</style>