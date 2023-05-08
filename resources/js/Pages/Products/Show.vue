<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head} from '@inertiajs/vue3';
import ProductCard from "@/Components/ProductCard.vue";
import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'

const {product, relatedProducts} = defineProps({
    product: {
        type: Object,
        required: true
    },
    relatedProducts: {
        type: Array,
        required: true,
        attributes: {
            id: Number,
            name: String,
            price: Number,
            image: String,
        }
    }
})

// carousel settings
const settings = {
    itemsToShow: 1,
    snapAlign: 'center',
}

const breakpoints = {
    640: {
        itemsToShow: 2,
        pagination: false,
    },
    768: {
        itemsToShow: 3,
        pagination: false,
    },
    1024: {
        itemsToShow: 4,
        pagination: false,
    },
}
</script>

<template>
    <MainLayout>
        <Head>
            <title>{{ product.name }}</title>
        </Head>

        <div class="pt-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 p-8 md:p-12 border max-w-5xl gap-10 mx-auto shadow">
                <img :src="'/storage/images/' + product.image" :alt="product.name"
                     class="">
                <div class="grid grid-cols-1 gap-4">
                    <div class="grid-cols-1 gap-2">
                        <h1 class="text-3xl font-bold">{{ product.name }}</h1>
                        <p class="text-gray-600 text-sm">{{ product.brand.name }}</p>
                    </div>
                    <p class="">{{ product.description }}</p>
                    <p class="font-bold text-xl">$ {{ product.price }}</p>
                    <div class="grid gap-2 ">
                        <input type="number" name="quantity" id="quantity" class="border rounded-md"
                               min="1" max="10"
                               :placeholder="`${product.stock} in stock`"
                        >
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <!--                                @click="addToCart(product.id, quantity)">-->
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related products -->
        <div class="pt-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold">Related Products</h2>
                <Carousel :items-to-show="3.5" :wrap-around="true">
                    <Slide v-for="relatedProduct in relatedProducts" :key="relatedProduct.id">
                        <ProductCard :product="relatedProduct" class="h-full" />
                    </Slide>
                    <template #addons>
                        <Navigation />
                    </template>
                </Carousel>
            </div>
        </div>
    </MainLayout>
</template>
