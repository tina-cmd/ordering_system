<template>
    <AuthenticatedLayout>
      <template #header>
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          Dashboard
        </h2>
      </template>
  
      <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
          <div class="space-y-6">
            <h1 class="text-3xl font-semibold text-gray-900">Restaurant Menu</h1>
            <button
              @click="showAddModal"
              class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-200"
            >
              Add Menu Item
            </button>
  
            <div v-for="category in categories" :key="category.id" class="mb-8">
              <h2 class="text-lg font-semibold text-gray-700 mb-4">{{ category.name }}</h2>
              <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <MenuItem
                  v-for="item in filteredItems(category.id)"
                  :key="item.id"
                  :item="item"
                  @edit="editItem"
                  @delete="deleteItem"
                />
              </div>
            </div>
  
            <MenuModal
              v-if="modalVisible"
              :isEditing="isEditing"
              :currentItem="currentItem"
              :categories="categories"
              @close="closeModal"
              @save="saveItem"
            />
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import { ref } from "vue";
  import MenuItem from "../Components/MenuItem.vue";
  import MenuModal from "../Components/MenuModal.vue";
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
  
  const categories = ref([
    { id: 1, name: "Drinks" },
    { id: 2, name: "Main Course" },
  ]);
  
  const items = ref([
    { id: 1, name: "Coke", price: "50", categoryId: 1, image: "path/to/image" },
    { id: 1, name: "Coke", price: "50", categoryId: 1, image: "path/to/image" },
    { id: 1, name: "Coke", price: "50", categoryId: 1, image: "path/to/image" },
    { id: 1, name: "Coke", price: "50", categoryId: 1, image: "path/to/image" },
    { id: 1, name: "Coke", price: "50", categoryId: 1, image: "path/to/image" },
    { id: 1, name: "Coke", price: "50", categoryId: 1, image: "path/to/image" },
    { id: 2, name: "Steak", price: "500", categoryId: 2, image: "path/to/image" },
  ]);
  
  const modalVisible = ref(false);
  const isEditing = ref(false);
  const currentItem = ref(null);
  
  const showAddModal = () => {
    currentItem.value = { id: null, name: "", price: "", categoryId: null, image: "" };
    isEditing.value = false;
    modalVisible.value = true;
  };
  
  const editItem = (item) => {
    currentItem.value = { ...item };
    isEditing.value = true;
    modalVisible.value = true;
  };
  
  const deleteItem = (id) => {
    items.value = items.value.filter((item) => item.id !== id);
  };
  
  const closeModal = () => {
    modalVisible.value = false;
  };
  
  const saveItem = (item) => {
    if (isEditing.value) {
      const index = items.value.findIndex((i) => i.id === item.id);
      items.value.splice(index, 1, item);
    } else {
      item.id = items.value.length + 1;
      items.value.push(item);
    }
    closeModal();
  };
  
  const filteredItems = (categoryId) => {
    return items.value.filter((item) => item.categoryId === categoryId);
  };
  </script>
  
  <style scoped>
  /* Improve button visibility */
  button {
    display: inline-block;
    margin-top: 20px;
  }
  
  /* Responsive grid layout for menu items */
  .grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
  }
  
  @media (max-width: 768px) {
    /* Smaller screens should have 2 items per row */
    .grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }
  
  @media (max-width: 640px) {
    /* Mobile screens, single item per row */
    .grid {
      grid-template-columns: 1fr;
    }
  }
  </style>
  