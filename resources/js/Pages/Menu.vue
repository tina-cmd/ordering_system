<template>
    <Head title="Menu" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Menu
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="space-y-6">
                    <h1 class="text-3xl font-semibold text-gray-900">
                        Restaurant Menu
                    </h1>

                    <button
                        @click="showModal"
                        class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition duration-200 mb-6"
                    >
                        Add Menu Item
                    </button>

                    <div
                        v-for="category in categories"
                        :key="category.id"
                        class="mb-8"
                    >
                        <h2 class="text-lg font-semibold text-gray-700 mb-4">
                            {{ category.name }}
                        </h2>
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6"
                        >
                            <div
                                v-for="item in filteredItems(category.id)"
                                :key="item.id"
                                class="p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition duration-200"
                            >
                                <img
                                    src="../../../storage/app/public/uploads/download.jpg"
                                    alt="Item Image"
                                    class="w-full h-40 object-cover rounded-md mb-4"
                                />
                                <h2 class="text-lg font-semibold text-gray-800">
                                    {{ item.name }}
                                </h2>
                                <p class="text-gray-600">
                                    Price: ${{ item.price }}
                                </p>
                                <div class="text-right mt-4 space-x-2">
                                    <button
                                        @click="editItem(item)"
                                        class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        @click="deleteItem(item.id)"
                                        class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600"
                                    >
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
        <div
            v-if="modalVisible"
            class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center"
        >
            <div
                class="bg-white p-6 rounded-lg shadow-lg w-11/12 sm:w-2/3 md:w-1/3"
            >
                <h2 class="text-xl font-semibold text-gray-800 mb-4">
                    {{ isEditing ? "Edit Item" : "Add Item" }}
                </h2>
                <form @submit.prevent="saveItem">
                    <div class="mb-4">
                        <label for="name" class="block text-sm text-gray-700"
                            >Name</label
                        >
                        <input
                            type="text"
                            id="name"
                            v-model="currentItem.name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md"
                            required
                        />
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block text-sm text-gray-700"
                            >Price</label
                        >
                        <input
                            type="number"
                            id="price"
                            v-model="currentItem.price"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md"
                            required
                        />
                    </div>

                    <div class="mb-4">
                        <label
                            for="category"
                            class="block text-sm text-gray-700"
                            >Category</label
                        >
                        <select
                            id="category"
                            v-model="currentItem.categoryId"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md"
                            required
                        >
                            <option
                                v-for="category in categories"
                                :key="category.id"
                                :value="category.id"
                            >
                                {{ category.name }}
                            </option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="image" class="block text-sm text-gray-700"
                            >Image URL</label
                        >
                        <input
                            type="text"
                            id="image"
                            v-model="currentItem.image"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md"
                            required
                        />
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button
                            type="button"
                            @click="closeModal"
                            class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
                        >
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

const categories = ref([
    { id: 1, name: "Drinks" },
    { id: 2, name: "Main Course" },
]);

const items = ref([
    {
        id: 1,
        name: "Coke",
        price: "50",
        categoryId: 1,
        image: "../../../storage/app/public/uploads/download.jpg",
    },
    {
        id: 2,
        name: "Pepsi",
        price: "55",
        categoryId: 1,
        image: "../../../storage/app/public/uploads/download.jpg",
    },
    {
        id: 3,
        name: "Pepsi",
        price: "55",
        categoryId: 1,
        image: "../../../storage/app/public/uploads/download.jpg",
    },
    {
        id: 3,
        name: "Steak",
        price: "500",
        categoryId: 2,
        image: "../../../storage/app/public/uploads/download.jpg",
    },
]);

const modalVisible = ref(false);
const isEditing = ref(false);
const currentItem = ref(null);

const showModal = () => {
    currentItem.value = {
        id: null,
        name: "",
        price: "",
        categoryId: null,
        image: "",
    };
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

const saveItem = () => {
    if (isEditing.value) {
        const index = items.value.findIndex(
            (i) => i.id === currentItem.value.id
        );
        items.value.splice(index, 1, { ...currentItem.value });
    } else {
        currentItem.value.id = items.value.length + 1;
        items.value.push({ ...currentItem.value });
    }
    closeModal();
};

const filteredItems = (categoryId) => {
    return items.value.filter((item) => item.categoryId === categoryId);
};
</script>

<style scoped>
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
