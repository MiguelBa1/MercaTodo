<script setup>
import {Head, Link, usePage} from '@inertiajs/vue3'
import MainLayout from "@/Layouts/MainLayout.vue";
import ProductsTable from "@/Components/Tables/ProductsTable.vue";
import ProductExport from "@/Pages/Admin/Products/Partials/ProductsExport.vue";
import ProductsImport from "@/Pages/Admin/Products/Partials/ProductsImport.vue";
import {useToast} from "vue-toast-notification";

const toast = useToast()
const {products} = usePage().props

</script>

<template>
    <Head>
        <title>Product Administration</title>
    </Head>

    <MainLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Product Administration</h2>
        </template>

        <div class="max-w-[100rem] mx-auto sm:px-6 lg:px-8 space-y-6 py-12">
            <div class="flex flex-col gap-5 sm:flex-row px-5">
                <Link :href="route('admin.products.create')"
                      class="flex items-center px-4 py-2 bg-gray-800 rounded-md text-white hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Create Product
                </Link>
                <ProductsImport/>
                <ProductExport :products="products"/>
            </div>
            <ProductsTable :products="products"/>
        </div>
    </MainLayout>
</template>
