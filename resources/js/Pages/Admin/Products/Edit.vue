<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import UpdateProductInformationForm from "@/Pages/Admin/Products/Partials/UpdateProductInformationForm.vue";
import {usePage, Head} from "@inertiajs/vue3";
import DeleteProductForm from "@/Pages/Admin/Products/Partials/DeleteProductForm.vue";

const {product} = usePage().props;
</script>

<template>
    <Head>
        <title>Edit product: {{ product.sku }}</title>
    </Head>

    <MainLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit product: {{ product.sku }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <UpdateProductInformationForm :product="product"/>
            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <DeleteProductForm :product="product"/>
            </div>
        </div>

    </MainLayout>
</template>

