<template>
    <div v-if="isVisible" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-5/6 sm:w-1/2">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">{{ isEditing ? 'Edit Item' : 'Add Item' }}</h2>

            <form @submit.prevent="saveItem">
                <div class="mb-4">
                    <label for="name" class="block text-sm text-gray-700">Item Name</label>
                    <input type="text" id="name" v-model="currentItem.name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required />
                </div>

                <div class="mb-4">
                    <label for="price" class="block text-sm text-gray-700">Price</label>
                    <input type="number" id="price" v-model="currentItem.price"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required />
                </div>

                <div class="mb-4">
                    <label for="category" class="block text-sm text-gray-700">Category</label>
                    <select id="category" v-model="currentItem.categoryId"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-sm text-gray-700">Image URL</label>
                    <input type="text" id="image" v-model="currentItem.image"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>

                <div class="flex w-full justify-between mt-4 bg-gray-100 p-4">
                    <button @click="deleteItem"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                        Edit
                    </button>

                    <button @click="deleteItem"
                        class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition duration-200">
                        Delete
                    </button>
                </div>

            </form>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits, ref, watch } from 'vue';

const props = defineProps({
    isEditing: Boolean,
    currentItem: Object,
    categories: Array
});

const emit = defineEmits(['close', 'save']);

const isVisible = ref(true);

const saveItem = () => {
    emit('save', { ...props.currentItem });
    isVisible.value = false;
};

const closeModal = () => {
    isVisible.value = false;
    emit('close');
};

watch(() => props.isEditing, (newVal) => {
    if (!newVal) {
        isVisible.value = false;
    }
});
</script>

<style scoped>
/* Add custom styles here */
</style>