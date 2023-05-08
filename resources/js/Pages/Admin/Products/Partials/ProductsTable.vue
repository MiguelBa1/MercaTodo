<script setup>
import {Link} from '@inertiajs/vue3'
import {TailwindPagination} from 'laravel-vue-pagination';
import {useToast} from "vue-toast-notification";
import axios from "axios";
import {onMounted, ref} from 'vue';

const $toast = useToast();
const productsData = ref({});
const pageNumber = ref(1);

const getProducts = async (page = 1) => {
    const response = await fetch(route('admin.api.products.index', {page: page}));
    productsData.value = await response.json();
    pageNumber.value = page;
}

const manageProductStatus = async (id) => {
    $toast.clear();
    const response = await axios.patch(route('admin.api.products.updateStatus', id));
    if (response.status === 200) {
        await getProducts(pageNumber.value);
        $toast.success(`Product status has been updated successfully`);
    } else {
        $toast.error(`Something went wrong, please try again later`);
    }
}

onMounted(() => {
    getProducts();
})
</script>

<template>
    <div class="p-4 sm:p-6 shadow sm:rounded-lg">
        <div class="overflow-auto">
            <!-- Table of products from props -->
            <table class="table-auto mx-auto w-full border">
                <thead>
                <tr>
                    <th class="px-4 py-2 border">SKU</th>
                    <th class="px-4 py-2 border">Name</th>
                    <th class="px-4 py-2 border">Price</th>
                    <th class="px-4 py-2 border">Stock</th>
                    <th class="px-4 py-2 border">Category</th>
                    <th class="px-4 py-2 border">Brand</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="product in productsData.data" :key="product.id">
                    <td class="border px-4 py-2 text-center">{{ product.sku }}</td>
                    <td class="border px-4 py-2">{{ product.name }}</td>
                    <td class="border px-4 py-2 text-center">{{ product.price }} $</td>
                    <td class="border px-4 py-2 text-center">{{ product.stock }}</td>
                    <td class="border px-4 py-2">{{ product.category_name }}</td>
                    <td class="border px-4 py-2">{{ product.brand_name }}</td>
                    <td class="border px-4 py-2 text-center">{{ product.status }}</td>
                    <td class="border px-4 py-2">
                        <div class="grid grid-rows-2 xl:grid-cols-2 xl:grid-rows-1 gap-1">
                            <Link :href="route('admin.products.edit', product.id)" title="Edit Product"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-2 rounded text-center"
                            >
                                Edit
                            </Link>
                            <button v-if="product.status === 'Active'"
                                    @click="manageProductStatus(product.id)"
                                    title="Disable user"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-2 rounded text-center"
                            >
                                Disable
                            </button>
                            <button v-if="product.status === 'Inactive'"
                                    @click="manageProductStatus(product.id)"
                                    title="Enable user"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-2 rounded text-center"
                            >
                                Enable
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="flex justify-center mt-3">
            <TailwindPagination :data="productsData" @pagination-change-page="getProducts" :limit="1"
                                :keepLength="true"/>
        </div>
    </div>
</template>

