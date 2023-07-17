<template>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <ProductCard v-if="products && products.length > 0" v-for="product in products"
                     :key="product.id" :product="product"/>
        <div v-else class="text-center col-span-full">
            <p class="text-xl font-semibold">No products found</p>
        </div>
    </div>
</template>

<script setup>
import ProductCard from "@/Components/ProductCard.vue"

const { products } = defineProps(
    {
        products: {
            type: Array,
            required: true
        }
    }
)

</script>
