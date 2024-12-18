<template>
  <div class="min-h-screen bg-gray-100">

    <Head title="Menu" />

    <AuthenticatedLayout>
      <template #header>
        <h2 class="text-2xl font-bold text-gray-800">
          Restaurant Menu
        </h2>
      </template>

      <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="mb-8 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-indigo-800">
              Our Delicious Offerings
            </h1>
            <button @click="showModal"
              class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out transform hover:scale-105 flex items-center">
              <PlusIcon class="w-5 h-5 mr-2" />
              Add Menu Item
            </button>
          </div>

          <div v-if="props.data.length === 0" class="flex items-center justify-center h-full">
            <p class="text-xl font-semibold text-gray-500">No Items Yet</p>
          </div>


          <div v-for="(category, i) in categories" :key="i" class="mb-12">
            <h2 class="text-2xl font-semibold text-indigo-700 mb-6 flex items-center">
              {{ category }}
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
              <div v-for="item in filteredItems(category)" :key="item.category_id"
                class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300 ease-in-out transform hover:-translate-y-1">
                <img :src="`/storage/${item.image}`" :alt="item.item_name" class="w-full h-48 object-cover" />
                <div class="p-6">
                  <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ item.item_name }}</h3>
                  <p class="text-indigo-600 font-bold text-lg mb-4">₱ {{ item.price }}</p>
                  <div class="flex justify-end space-x-2">
                    <button @click="editItem(item)"
                      class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-full transition duration-300 ease-in-out flex items-center">
                      <PencilIcon class="w-4 h-4 mr-1" />
                      Edit
                    </button>
                    <button @click="confirmDeleteItem(item.category_id)"
                      class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-full transition duration-300 ease-in-out flex items-center">
                      <TrashIcon class="w-4 h-4 mr-1" />
                      Delete
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <!-- Delete Confirmation Modal -->
      <TransitionRoot appear :show="deleteConfirmationModalVisible" as="template">
        <Dialog as="div" @close="closeDeleteModal" class="relative z-10">
          <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
            leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
            <div class="fixed inset-0 bg-black bg-opacity-25" />
          </TransitionChild>

          <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
              <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
                leave-to="opacity-0 scale-95">
                <DialogPanel
                  class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                  <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                    Are you sure you want to delete this item?
                  </DialogTitle>

                  <div class="mt-4 text-gray-700">
                    <p>This action cannot be undone.</p>
                  </div>

                  <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" @click="closeDeleteModal"
                      class="inline-flex justify-center rounded-md border border-transparent bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2">
                      Cancel
                    </button>
                    <button type="button" @click="deleteItemConfirmed"
                      class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2">
                      Delete
                    </button>
                  </div>
                </DialogPanel>
              </TransitionChild>
            </div>
          </div>
        </Dialog>
      </TransitionRoot>

      <TransitionRoot appear :show="modalVisible" as="template">
        <Dialog as="div" @close="closeModal" class="relative z-10">
          <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
            leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
            <div class="fixed inset-0 bg-black bg-opacity-25" />
          </TransitionChild>

          <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
              <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
                leave-to="opacity-0 scale-95">
                <DialogPanel
                  class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                  <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                    {{ isEditing ? "Edit Item" : "Add Item" }}
                  </DialogTitle>
                  <form @submit.prevent="saveItem" class="mt-4">
                    <div class="mb-4">
                      <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                      <input type="text" id="name" v-model="currentItem.item_name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        required />
                    </div>

                    <div class="mb-4">
                      <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                      <input type="number" id="price" v-model="currentItem.price"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        required />
                    </div>

                    <div class="mb-4">
                      <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                      <select id="category" v-model="currentItem.category_name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        required>
                        <option v-for="(category, i) in categories" :key="i" :value="category">
                          {{ category }}
                        </option>
                      </select>
                    </div>

                    <div class="mb-4">
                      <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                      <input type="file" id="image" @change="handleFileChange"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        required />
                    </div>

                    <div class="mt-6 flex justify-end space-x-4">
                      <button type="button" @click="closeModal"
                        class="inline-flex justify-center rounded-md border border-transparent bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2">
                        Cancel
                      </button>
                      <button type="submit"
                        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2">
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
import { ref, computed } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { defineProps } from 'vue'
import { onMounted } from 'vue'

// Props
const props = defineProps({
  data: {
    type: Object,
    default: () => [],
  }
});

// Reactive references
const categories = ref([]);  // Store category names
const modalVisible = ref(false);  // Modal visibility state
const isEditing = ref(false);  // Flag to track if we're editing
const currentItem = ref({
  id: null,
  item_name: '',
  price: '',
  category_name: '',
  image: null
});  // Store the current item data for add/edit

// Deletion Confirmation Modal
const deleteConfirmationModalVisible = ref(false);  // State to show/hide delete confirmation modal
const itemToDelete = ref(null);  // Store the item to delete

// Initialize categories
onMounted(() => {
  props.data.forEach(element => {
    if (!categories.value.includes(element.category_name)) {
      categories.value.push(element.category_name);
    }
  });


  props.data.forEach(element => {
    console.log(element.image)
  });
});

// Handle the file change event
const handleFileChange = (event) => {
  const file = event.target.files[0]; // Get the first file selected
  if (file) {
    currentItem.value.image = file; // Save the file object
  }
};

// Function to show the modal
const showModal = () => {
  modalVisible.value = true;
  isEditing.value = false;  // Reset edit state for new item
  currentItem.value = {
    id: null,
    item_name: '',
    price: '',
    category_name: categories.value[0],  // Set default category
    image: null
  };
};

// Function to close the modal
const closeModal = () => {
  modalVisible.value = false;
  currentItem.value = {
    id: null,
    item_name: '',
    price: '',
    category_name: categories.value[0],
    image: null
  };
};

// Function to set the current item for editing
const editItem = (item) => {
  modalVisible.value = true;
  isEditing.value = true;
  currentItem.value = { ...item };
};

// Function to handle the form submission for adding or editing an item
const saveItem = () => {
  const form = useForm({
    id: currentItem.value.category_id,
    item_name: currentItem.value.item_name,
    price: currentItem.value.price,
    category_name: currentItem.value.category_name,
    image: currentItem.value.image
  });

  // Perform the correct action based on whether we're editing or adding
  if (isEditing.value) {
    // Send POST request to update the item
    form.post('/menu/update/');
  } else {
    form.id = props.data.length + 1
    // Send POST request to add a new item
    form.post('/menu/add');
  }

  closeModal();  // Close the modal after submission
};

// Function to filter items by category
const filteredItems = (category) => {
  return props.data.filter(item => item.category_name === category);
};

// Function to open the delete confirmation modal
const confirmDeleteItem = (item) => {
  itemToDelete.value = item;
  deleteConfirmationModalVisible.value = true;
};

// Function to close the delete confirmation modal
const closeDeleteModal = () => {
  deleteConfirmationModalVisible.value = false;
  itemToDelete.value = null;
};

// Function to delete the item
const deleteItemConfirmed = () => {
  // Make an API call to delete the item
  const form = useForm({
    id: null,
  })
  if (itemToDelete.value) {
    form.id = itemToDelete.value

    form.delete('/menu/delete/');
    // alert(itemToDelete.value);
  }

  closeDeleteModal();  // Close the modal after deleting the item
};
</script>
