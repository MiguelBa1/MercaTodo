<script setup>
import {Head, usePage, Link} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import {onMounted, ref, computed} from 'vue'
import {TailwindPagination} from 'laravel-vue-pagination';

const {brands, categories, products} = usePage().props;

const productsData = ref(products.data);
const category_id = ref(usePage().props.ziggy.query.category_id ?? '');
const brand_id = ref(usePage().props.ziggy.query.brand_id ?? '');
const searchQuery = ref(usePage().props.ziggy.query.search ?? '');

const changePage = (page) => {
    window.location.href = route('home', {
        page: page,
        category_id: category_id.value,
        brand_id: brand_id.value,
        search: searchQuery.value
    });
}

const search = () => {
    // TODO: CAMBIAR A AXIOS
    window.location.href = route('home', {
        category_id: category_id.value,
        brand_id: brand_id.value,
        search: searchQuery.value
    });
}

</script>

<template>
    <Head>
        <title>Home</title>
    </Head>

    <MainLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Home</h2>
        </template>

        <div class="py-12">
            {{ usePage().props.errors }}
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <form @submit.prevent="search" class="flex flex-col justify-evenly px-7 sm:flex-row">
                    <div class="flex items-center mb-4 justify-between">
                        <label for="category" class="mr-2">Category:</label>
                        <select id="category" class="p-2 rounded border" v-model="category_id">
                            <option selected value="">All</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                    </div>
                    <div class="flex items-center mb-4 justify-between">
                        <label for="brand" class="mr-2">Brand:</label>
                        <select id="brand" class="p-2 rounded border" v-model="brand_id">
                            <option value="" selected>All</option>
                            <option v-for="brand in brands" :key="brand.id" :value="brand.id">{{ brand.name }}</option>
                        </select>
                    </div>

                    <!-- Search input -->
                    <div class="flex items-center mb-4 justify-between">
                        <label for="search" class="mr-2">Search:</label>
                        <input type="text" id="search" class="p-2 rounded border" v-model="searchQuery" placeholder="Search..." >
                    </div>

                    <!-- Search Button -->
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                            type="submit">
                        Search
                    </button>
                </form>
                <!-- Product gallery -->
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    <Link v-if="productsData.length > 0" v-for="product in productsData" :key="product.id"
                          class="bg-white rounded-lg shadow-lg p-4 hover:scale-105 duration-75"
                          :href="route('products.show', product.id)"
                    >
                        <div class="w-full h-40">
                            <img :src="'/storage/images/' + product.image" :alt="product.name"
                                 class="w-full h-full object-cover mb-2">
                        </div>
                        <h2 class="text-xl font-semibold mb-2">{{ product.name }}</h2>
                        <p class="text-gray-600 text-sm mb-2">Price: $ {{ product.price }}</p>
                        <div class="flex justify-between">
                            <p class="text-gray-600 text-sm">{{ product.category_name }}</p>
                        </div>
                    </Link>
                    <div v-else class="text-center col-span-full">
                        <p class="text-xl font-semibold">No products found</p>
                    </div>
                </div>

            </div>
            <div class="flex justify-center mt-3">
                <TailwindPagination :data="products" @pagination-change-page="changePage" :limit="1"
                                    :keepLength="true"/>
            </div>
        </div>
    </MainLayout>
</template>
