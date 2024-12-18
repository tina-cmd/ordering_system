<template>
  <div class="min-h-screen bg-gray-100">
    <Head title="Inventory" />

    <AuthenticatedLayout>
      <template #header>
        <h2 class="text-2xl font-bold text-gray-800">
          Inventory Management
        </h2>
      </template>

      <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <h1 class="text-3xl font-bold text-indigo-800 mb-4 sm:mb-0">
              Stock Overview
            </h1>
            <div class="w-full sm:w-auto">
              <label for="category-filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Category:</label>
              <select
                id="category-filter"
                v-model="selectedCategory"
                class="block w-full sm:w-auto px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
              >
                <option v-for="category in categories" :key="category" :value="category">
                  {{ category }}
                </option>
              </select>
            </div>
          </div>

          <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="item in paginatedItems" :key="item.inventory_id">
                    <td class="px-6 py-4 whitespace-normal">
                      <div class="text-sm font-medium text-gray-900">{{ item.item_name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-normal">
                      <div class="text-sm text-gray-500">{{ item.category_name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ item.quantity_in_stock }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <button
                        @click="editQuantity(item)"
                        class="text-indigo-600 hover:text-indigo-900 focus:outline-none focus:underline px-3 py-1 rounded-md bg-indigo-100 hover:bg-indigo-200 transition-colors duration-200"
                      >
                        Edit
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Pagination -->
          <div class="mt-4 flex justify-between items-center">
            <div>
              <p class="text-sm text-gray-700">
                Showing
                <span class="font-medium">{{ paginationStart + 1 }}</span>
                to
                <span class="font-medium">{{ paginationEnd }}</span>
                of
                <span class="font-medium">{{ filteredItems.length }}</span>
                results
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button
                  @click="prevPage"
                  :disabled="currentPage === 1"
                  class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                >
                  Previous
                </button>
                <button
                  @click="nextPage"
                  :disabled="currentPage === totalPages"
                  class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                >
                  Next
                </button>
              </nav>
            </div>
          </div>
        </div>
      </div>

      <!-- Edit Quantity Modal -->
      <TransitionRoot appear :show="modalVisible" as="template">
        <Dialog as="div" @close="closeModal" class="relative z-10">
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
                    Edit Quantity for {{ currentItem.item_name }}
                  </DialogTitle>
                  <form @submit.prevent="saveQuantity(currentItem)" class="mt-4">
                    <div class="mb-4">
                      <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                      <input
                        type="number"
                        id="quantity"
                        v-model="editForm.quantity"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        required
                        min="0"
                      />
                    </div>

                    <div class="mt-6 flex justify-end space-x-4">
                      <button
                        type="button"
                        @click="closeModal"
                        class="inline-flex justify-center rounded-md border border-transparent bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2"
                      >
                        Cancel
                      </button>
                      <button
                        type="submit"
                        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                      >
                        Save
                      </button>
                    </div>
                  </form>
                </DialogPanel>
              </TransitionChild>
            </div>
          </div>
        </Dialog>
      </TransitionRoot>
    </AuthenticatedLayout>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  inventory: {
    type: Object,
    default: () => ({}),
  },
  menu: {
    type: Object,
    default: () => ({}),
  }
})

const categories = ref(['all'])
const modalVisible = ref(false)
const currentItem = ref(null)
const selectedCategory = ref('all')
const editForm = ref({ quantity: 0 })
const currentPage = ref(1)
const itemsPerPage = 10

const combined = computed(() => {
  const mergedItems = []

  // Create a map for quick lookup of menu items by item_name
  const menuMap = new Map()
  props.menu.forEach(item => {
    menuMap.set(item.item_name, item)
  })

  // Iterate over inventory and merge data
  props.inventory.forEach(invItem => {
    const menuItem = menuMap.get(invItem.item_name) // Find matching menu item by item_name

    if (menuItem) {
      // Merge properties into a single object
      mergedItems.push({
        item_name: invItem.item_name,
        quantity_in_stock: invItem.quantity_in_stock,
        category_name: menuItem.category_name, // Get category from menu
        price: menuItem.price || 0 // Optional: get price from menu if it exists
      })
    } else {
      // If no menu item exists, add inventory item without category_name
      mergedItems.push({
        item_name: invItem.item_name,
        quantity_in_stock: invItem.quantity_in_stock,
        category_name: 'Uncategorized', // Default value if category is missing
        price: 0
      })
    }
  })

  return mergedItems
})

const filteredItems = computed(() => {
  if (selectedCategory.value === 'all') {
    return combined.value
  }
  return combined.value.filter((item) => item.category_name === selectedCategory.value)
})

const totalPages = computed(() => Math.ceil(filteredItems.value.length / itemsPerPage))

const paginatedItems = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredItems.value.slice(start, end)
})

const paginationStart = computed(() => (currentPage.value - 1) * itemsPerPage)
const paginationEnd = computed(() => Math.min(currentPage.value * itemsPerPage, filteredItems.value.length))

onMounted(() => {
  const uniqueCategories = new Set(combined.value.map(item => item.category_name))
  categories.value = ['all', ...uniqueCategories]
})

const closeModal = () => {
  modalVisible.value = false
  setTimeout(() => {
    currentItem.value = null
    editForm.value.quantity = 0
  }, 300)
}

const editQuantity = (item) => {
  currentItem.value = { ...item }
  editForm.value.quantity = item.quantity_in_stock
  modalVisible.value = true
}

const form = useForm({
  item_name: '',
  quantity: '',
})

const saveQuantity = (item) => {
  if (editForm.value.quantity < 0) {
    alert('Quantity cannot be negative')
    return
  }
  form.item_name = item.item_name
  form.quantity = editForm.value.quantity

  form.post('/inventory/update', {
    onSuccess: () => {
      alert('Inventory updated successfully!');
    },
    onError: (errors) => {
      // Display error messages from the server
      const errorMessages = Object.values(errors).join('\n');
      alert(`Failed to update the inventory:\n${errorMessages}`);
    },
    onFinish: () => {
      console.log('Request completed');
    }
  })
  
  closeModal()
}

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--
  }
}

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++
  }
}
</script>

