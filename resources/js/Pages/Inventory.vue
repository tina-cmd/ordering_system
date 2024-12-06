<template>
    <Head title="Inventory" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Inventory
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="space-y-6">
                    <h1 class="text-3xl font-semibold text-gray-900">
                        Inventory Management
                    </h1>

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
                                    Quantity: {{ item.quantity }}
                                </p>
                                <div class="text-right mt-4">
                                    <button
                                        @click="editQuantity(item)"
                                        class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600"
                                    >
                                        Edit Quantity
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
                    Edit Quantity
                </h2>
                <form @submit.prevent="saveQuantity">
                    <div class="mb-4">
                        <label
                            for="quantity"
                            class="block text-sm text-gray-700"
                            >Quantity</label
                        >
                        <input
                            type="number"
                            id="quantity"
                            v-model="currentItem.quantity"
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

const inventory = ref([
    { id: 1, name: "Coke", quantity: 100, price: 1.5, categoryId: 1 },
    { id: 2, name: "Pepsi", quantity: 200, price: 1.6, categoryId: 1 },
    { id: 3, name: "Steak", quantity: 50, price: 20, categoryId: 2 },
]);

const modalVisible = ref(false);
const currentItem = ref(null);

const filteredItems = (categoryId) => {
    return inventory.value.filter((item) => item.categoryId === categoryId);
};

const editQuantity = (item) => {
    currentItem.value = { ...item };
    modalVisible.value = true;
};

const saveQuantity = () => {
    const index = inventory.value.findIndex(
        (i) => i.id === currentItem.value.id
    );
    if (index !== -1) {
        inventory.value[index].quantity = currentItem.value.quantity;
    }
    closeModal();
};

const closeModal = () => {
    modalVisible.value = false;
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
