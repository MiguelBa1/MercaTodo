<script setup>
import axios from "axios";
import {onMounted, ref} from 'vue';
import {useToast} from "vue-toast-notification";
import {TailwindPagination} from 'laravel-vue-pagination';
import LoadingSpinner from "@/Components/LoadingSpinner.vue";

import Modal from "@/Components/Modal.vue";

const $toast = useToast();
const categoriesData = ref({});
const pageNumber = ref(1);
const isLoading = ref(true);

const editingCategory = ref({});
const creatingCategory = ref({});

const getCategories = async (page = 1) => {
    const response = await fetch(route('admin.api.categories.index', {page: page}));
    categoriesData.value = await response.json();
    pageNumber.value = page;
    isLoading.value = false;
}

const createCategory = async () => {
    $toast.clear();
    try {
        await axios.post(route('admin.api.categories.store'), creatingCategory.value);
        await getCategories(pageNumber.value);
        $toast.success(`Category has been created successfully`);
    } catch (e) {
        if (e.value.response.status === 422) {
            $toast.error(`This name is already taken`);
        } else {
            $toast.error(`Something went wrong, please try again later`);
        }
    }
}

const editCategory = async () => {
    $toast.clear();
    try {
        await axios.patch(route('admin.api.categories.update', {category: editingCategory.value.id}), editingCategory.value);
        await getCategories(pageNumber.value);
        $toast.success(`Category has been updated successfully`);
    } catch (e) {
        if (e.response.status === 422) {
            $toast.error(`This name is already taken`);
        } else {
            $toast.error(`Something went wrong, please try again later`);
        }
    }
}

onMounted(async () => {
    await getCategories();
    isLoading.value = false;
})
</script>

<template>
    <div v-if="isLoading">
        <LoadingSpinner/>
    </div>
    <div v-else class="p-4 sm:p-6 shadow sm:rounded-lg">
        <div class="overflow-auto">
            <!-- Table of products from props -->
            <table class="table-auto mx-auto w-full border">
                <thead>
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Name</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="category in categoriesData.data" :key="category.id">
                    <td class="border px-4 py-2 text-center">{{ category.id }}</td>
                    <td class="border px-4 py-2">{{ category.name }}</td>
                    <td class="border px-4 py-2">
                        <div class="flex justify-center">
                            <!-- button to trigger the Edit category modal -->
                            <button @click="editingCategory = {...category}"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>

            <Modal :show="Object.keys(editingCategory).length > 0" @close="editingCategory = {}">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Edit Category
                    </h2>

                    <form @submit.prevent="editCategory">
                        <div class="mt-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                ID
                            </label>
                            <div class="mt-1">
                                <input type="text" name="category-id" id="category-id" :value="editingCategory.id" disabled
                                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 bg-gray-100 cursor-not-allowed rounded-md"
                                >
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Name
                            </label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" v-model="editingCategory.name"
                                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">
                                Edit Category
                            </button>
                        </div>
                    </form>
                </div>
            </Modal>

            <Modal :show="Object.keys(creatingCategory).length > 0" @close="creatingCategory= {}">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Create Category
                    </h2>

                    <form @submit.prevent="createCategory">
                        <div class="mt-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Name
                            </label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" v-model="creatingCategory.name"
                                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md">
                                Create Category
                            </button>
                        </div>
                    </form>
                </div>
            </Modal>

        </div>
        <!-- Pagination -->
        <div class="flex justify-center mt-3">
            <TailwindPagination :data="categoriesData" @pagination-change-page="getCategories" :limit="1"
                                :keepLength="true"/>
        </div>
        <!-- button to trigger the Create category modal -->
        <div class="flex justify-center mt-3">
            <button @click="creatingCategory.name = ''"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md">
                Create Category
            </button>
        </div>
    </div>
</template>

