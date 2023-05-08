<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import BrandsTable from "@/Pages/Admin/AuxiliaryTables/Partials/BrandsTable.vue";
import CategoriesTable from "@/Pages/Admin/AuxiliaryTables/Partials/CategoriesTable.vue";

import {Head} from "@inertiajs/vue3";
import {ref} from "vue";


const currentTable = ref('brands');

const changeTable = (table) => {
    currentTable.value = table;
}
</script>

<template>
    <Head>
        <title>Auxiliar tables</title>
    </Head>

    <MainLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Auxiliary tables</h2>
        </template>

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6 py-12">
            <select v-model="currentTable" @change="changeTable($event.target.value)"
                    class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="brands">Brands</option>
                <option value="categories">Categories</option>
            </select>

            <div v-if="currentTable === 'brands'">
                <BrandsTable/>
            </div>
            <div v-else>
                <CategoriesTable/>
            </div>
        </div>
    </MainLayout>
</template>
