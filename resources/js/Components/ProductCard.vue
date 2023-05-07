<template>
    <Link class="bg-white rounded-lg shadow-lg p-4 hover:scale-105 duration-75"
          :href="route('products.show', product.id)">
        <div class="w-full h-40">
            <img :src="'/storage/images/' + product.image" :alt="product.name"
                 class="w-full h-full object-cover mb-2">
        </div>
        <h2 class="text-xl font-semibold mb-2">{{ product.name }}</h2>
        <p class="text-gray-600 text-sm mb-2">Price: $ {{ product.price }}</p>
        <div class="flex justify-between">
            <p class="text-gray-600 text-sm">{{ product.category_name }}</p>
        </div>
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
