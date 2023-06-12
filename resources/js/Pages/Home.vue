<script setup>
import {Head} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import {onMounted, ref} from 'vue'
import {TailwindPagination} from 'laravel-vue-pagination';
import axios from "axios";
import ProductFilter from "@/Pages/Products/Partials/ProductFilter.vue";
import ProductGallery from "@/Pages/Products/Partials/ProductGallery.vue";
import LoadingSpinner from "@/Components/LoadingSpinner.vue";

const productsData = ref({});
const brands = ref([]);
const categories = ref([]);

const category_id = ref('');
const brand_id = ref('');
const searchQuery = ref('');
const isLoading = ref(true);

const fetchProducts = async (pageNumber = 1) => {
    isLoading.value = true;
    const response = await axios.get(route('api.home.index', {
        page: pageNumber,
        category_id: category_id.value,
        brand_id: brand_id.value,
        search: searchQuery.value
    }));
    productsData.value = await response.data;
    isLoading.value = false;
}

const fetchBrands = async () => {
    const response = await axios.get(route('api.brands.index'))
    brands.value = response.data.brands
}

const fetchCategories = async () => {
    const response = await axios.get(route('api.categories.index'))
    categories.value = response.data.categories
}

onMounted(async () => {
    await Promise.all([
        fetchProducts(),
        fetchBrands(),
        fetchCategories()
    ])
    isLoading.value = false;
})
</script>

<template>
    <MainLayout>
        <Head>
            <title>Home</title>
        </Head>

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Home</h2>
        </template>

        <div class="py-12">
            <div>
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    <ProductFilter v-model:category_id="category_id" v-model:brand_id="brand_id"
                                   v-model:searchQuery="searchQuery" :fetchProducts="fetchProducts"
                                   :categories="categories" :brands="brands"/>
                    <div v-if="isLoading">
                        <LoadingSpinner/>
                    </div>
                    <div v-else>
                        <ProductGallery :products="productsData.data"/>
                    </div>
                </div>
                <div class="flex justify-center mt-3">
                    <TailwindPagination :data="productsData" @pagination-change-page="fetchProducts" :limit="1"
                                        :keepLength="true"/>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
