<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import CreateProductForm from "@/Pages/Admin/Products/Partials/CreateProductForm.vue";
import {Head} from "@inertiajs/vue3";

</script>

<template>
    <Head>
        <title>Create Product</title>
    </Head>

    <MainLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Product</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <CreateProductForm />
            </div>
        </div>

    </MainLayout>

</template>

