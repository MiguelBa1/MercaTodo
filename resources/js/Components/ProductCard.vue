<template>
    <Link class="grid bg-white rounded-lg shadow-lg p-4 hover:scale-105 duration-75"
          :href="route('products.show', product.id)">
        <div class="w-full max-w-xs max-h-xs">
            <img :src="'/storage/images/' + product.image" :alt="product.name"
                 class="w-full h-full object-cover">
        </div>
        <h2 class="text-xl font-semibold">{{ product.name }}</h2>
        <div class="flex justify-between">
            <p class="text-gray-600 text-sm">{{ product.category_name }}</p>
        </div>
        <p class="text-md font-bold">Price: $ {{ product.price }}</p>
    </Link>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

const { product } = defineProps({
    product: {
        type: Object,
        required: true
    }
})

</script>
