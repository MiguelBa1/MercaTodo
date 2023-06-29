<script setup>
import {Head, router, usePage} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import {ref} from 'vue'
import {TailwindPagination} from 'laravel-vue-pagination';
import ProductFilter from "@/Pages/Home/Partials/ProductFilter.vue";
import ProductGallery from "@/Pages/Home/Partials/ProductGallery.vue";

const productsData = ref(usePage().props.products);
const brands = ref(usePage().props.brands);
const categories = ref(usePage().props.categories);

const filters = usePage().props.filters;

const category_id = ref(filters.category_id ?? '');
const brand_id = ref(filters.brand_id ?? '');
const searchQuery = ref(filters.searchQuery ?? '');

const fetchProducts = async (page = 1) => {
    router.visit(route('home'), {
        data: {
            page,
            category_id: category_id.value,
            brand_id: brand_id.value,
            searchQuery: searchQuery.value
        },
        only: ['products', 'filters'],
    });
}

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
                    <ProductGallery :products="productsData.data"/>
                </div>
                <div class="flex justify-center mt-3">
                    <TailwindPagination :data="productsData" @pagination-change-page="fetchProducts" :limit="1"
                                        :keepLength="true"/>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
