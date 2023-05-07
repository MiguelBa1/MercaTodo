<script setup lang="ts">
import {usePage} from '@inertiajs/vue3'
import MainLayout from "@/Layouts/MainLayout.vue";
import ProductsTable from "@/Pages/Admin/Products/Partials/ProductsTable.vue";
import {Link, Head} from "@inertiajs/vue3";

defineProps({
    auth: {
        type: Object,
        required: true
    },
})

const {auth} = usePage().props

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
            <div class="flex justify-center sm:justify-end">
                <Link :href="route('admin.products.create')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Create Product</Link>
            </div>
            <ProductsTable />
        </div>
    </MainLayout>
</template>
