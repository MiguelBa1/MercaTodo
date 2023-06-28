<template>
    <form @submit.prevent="fetchProducts(1)" class="grid grid-cols-1 gap-2 justify-evenly px-7 lg:flex lg:flex-row">
        <div class="flex items-center justify-between">
            <label for="category" class="mr-2">Category:</label>
            <select id="category"
                    class="p-2 border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    :value="category_id"
                    @change="$emit('update:category_id', $event.target.value)">
                <option selected value="">All</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.name }}
                </option>
            </select>
        </div>

        <div class="flex items-center justify-between">
            <label for="brand" class="mr-2">Brand:</label>
            <select id="brand"
                    class="p-2 border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    :value="brand_id"
                    @change="$emit('update:brand_id', $event.target.value)">
                <option selected value="">All</option>
                <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                    {{ brand.name }}
                </option>
            </select>
        </div>

        <input type="text"
               class="p-2 border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
               placeholder="Search..."
               :value="searchQuery"
               @change="$emit('update:searchQuery', $event.target.value)">

        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">
            Search
        </button>
    </form>
</template>

<script setup>

const {brands, categories, category_id, brand_id, searchQuery, fetchProducts} = defineProps({
    brands: Array,
    categories: Array,
    category_id: {
        type: String,
        required: false,
    },
    brand_id: {
        type: String,
        required: false,
    },
    searchQuery: {
        type: String,
        required: false,
    },
    fetchProducts: {
        type: Function,
        required: true,
    }
});

</script>
