<script setup>
import axios from "axios";
import {onMounted, ref} from 'vue';
import {useToast} from "vue-toast-notification";
import {TailwindPagination} from 'laravel-vue-pagination';
import LoadingSpinner from "@/Components/LoadingSpinner.vue";

import Modal from "@/Components/Modal.vue";

const $toast = useToast();
const brandsData = ref({});
const pageNumber = ref(1);
const isLoading = ref(true);

const editingBrand = ref({});
const creatingBrand = ref({});

const getBrands = async (page = 1) => {
    const response = await fetch(route('api.admin.brands.index', {page: page}));
    brandsData.value = await response.json();
    pageNumber.value = page;
}

const createBrand = async () => {
    $toast.clear();
    try {
        await axios.post(route('api.admin.brands.store'), creatingBrand.value);
        await getBrands(pageNumber.value);
        $toast.success(`Brand has been created successfully`);
    } catch (e) {
        if (e.value.response.status === 422) {
            $toast.error(`This name is already taken`);
        } else {
            $toast.error(`Something went wrong, please try again later`);
        }
    }
}

const editBrand = async () => {
    $toast.clear();
    try {
        await axios.patch(route('api.admin.brands.update', {brand: editingBrand.value.id}), editingBrand.value);
        await getBrands(pageNumber.value);
        $toast.success(`Brand has been updated successfully`);
    } catch (e) {
        if (e.response.status === 422) {
            $toast.error(`This name is already taken`);
        } else {
            $toast.error(`Something went wrong, please try again later`);
        }
    }
}

onMounted(async () => {
    await getBrands();
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
                <tr v-for="brand in brandsData.data" :key="brand.id">
                    <td class="border px-4 py-2 text-center">{{ brand.id }}</td>
                    <td class="border px-4 py-2">{{ brand.name }}</td>
                    <td class="border px-4 py-2">
                        <div class="flex justify-center">
                            <!-- button to trigger the Edit brand modal -->
                            <button @click="editingBrand = {...brand}"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>

            <Modal :show="Object.keys(editingBrand).length > 0" @close="editingBrand = {}">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Edit Brand
                    </h2>

                    <form @submit.prevent="editBrand">
                        <div class="mt-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                ID
                            </label>
                            <div class="mt-1">
                                <input type="text" name="brand-id" id="brand-id" :value="editingBrand.id" disabled
                                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 bg-gray-100 cursor-not-allowed rounded-md"
                                >
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Name
                            </label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" v-model="editingBrand.name"
                                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">
                                Edit Brand
                            </button>
                        </div>
                    </form>
                </div>
            </Modal>

            <Modal :show="Object.keys(creatingBrand).length > 0" @close="creatingBrand = {}">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Create Brand
                    </h2>

                    <form @submit.prevent="createBrand">
                        <div class="mt-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Name
                            </label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" v-model="creatingBrand.name"
                                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md">
                                Create Brand
                            </button>
                        </div>
                    </form>
                </div>
            </Modal>

        </div>
        <!-- Pagination -->
        <div class="flex justify-center mt-3">
            <TailwindPagination :data="brandsData" @pagination-change-page="getBrands" :limit="1"
                                :keepLength="true"/>
        </div>
    </div>
    <!-- button to trigger the Create brand modal -->
    <div class="flex justify-center mt-3">
        <button @click="creatingBrand.name = ''"
                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md">
            Create Brand
        </button>
    </div>
</template>

