<script setup lang="ts">
import {usePage} from '@inertiajs/vue3'
import MainLayout from "@/Layouts/MainLayout.vue";
import ProductsTable from "@/Pages/Admin/Products/Partials/ProductsTable.vue";

defineProps({
    auth: {
        type: Object,
        required: true
    },
})

const {auth} = usePage().props

</script>

<template>
    <MainLayout>

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Product Administration</h2>
        </template>

        <div class="max-w-[100rem] mx-auto sm:px-6 lg:px-8 space-y-6 py-12">
            <ProductsTable />
        </div>
    </MainLayout>
</template>
