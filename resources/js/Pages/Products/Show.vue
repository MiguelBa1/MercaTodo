<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head} from '@inertiajs/vue3';
import ProductCard from "@/Components/ProductCard.vue";
import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide, Navigation, Pagination } from 'vue3-carousel'
import {useCartStore} from "@/store/cart";
import {ref} from "vue";
import {useToast} from "vue-toast-notification";
import {usePage} from "@inertiajs/vue3";
import {getProductImage} from "@/Utils/getProductImage";

const toast = useToast();
const quantity = ref(1);

const page = usePage();

const {product, relatedProducts} = defineProps({
    product: {
        type: Object,
        required: true
    },
    relatedProducts: {
        type: Object,
        required: true,
        attributes: {
            id: Number,
            name: String,
            price: Number,
            image: String,
        }
    }
})

const breakpoints = {
    640: {
        itemsToShow: 1,
    },
    768: {
        itemsToShow: 2,
    },
    1024: {
        itemsToShow: 3,
    }
}

const store = useCartStore();

const addToCart = async () => {
    if (!page.props.auth.user){
        toast.error('You need to login first');
        return;
    }

    const response = await store.addToCart(product.id, quantity.value);
    if (response.status === 200) {
        toast.success('Product added to cart');
        await store.syncCart();
    } else {
        toast.error('Something went wrong');
    }
}

</script>

<template>
    <MainLayout>
        <Head>
            <title>{{ product.name }}</title>
        </Head>

        <div class="pt-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 p-8 md:p-12 border max-w-5xl gap-10 mx-auto shadow">
                <img :src="getProductImage(product.image)" :alt="product.name"
                     class="">
                <div class="grid grid-cols-1 gap-4">
                    <div class="grid-cols-1 gap-2">
                        <h1 class="text-3xl font-bold">{{ product.name }}</h1>
                        <p class="text-gray-600 text-sm">{{ product.brand.name }}</p>
                    </div>
                    <p class="">{{ product.description }}</p>
                    <p class="font-bold text-xl">$ {{ product.price }}</p>
                    <div class="grid gap-2 ">
                        <div class="flex justify-between">
                            <div>
                                <button class="bg-gray-200 text-gray-600 rounded-md px-4 py-2"
                                        @click="quantity = quantity - 1"
                                        :disabled="quantity <= 1">-
                                </button>
                                <span class="px-4 py-2">{{ quantity }}</span>
                                <button class="bg-gray-200 text-gray-600 rounded-md px-4 py-2"
                                        @click="quantity = quantity + 1"
                                        :disabled="quantity >= product.stock">+
                                </button>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Stock: {{ product.stock }}</p>
                            </div>
                        </div>

                        <button class="bg-blue-500 text-white rounded-md px-4 py-2"
                                @click="addToCart">Add to cart
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related products -->
        <div class="pt-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold">Related Products</h2>
                <Carousel :breakpoints="breakpoints" :wrap-around="true" class="mt-8">
                    <Slide v-for="relatedProduct in relatedProducts" :key="relatedProduct.id">
                        <ProductCard :product="relatedProduct" class="h-full" />
                    </Slide>
                    <template #addons>
                        <Navigation/>
                        <Pagination/>
                    </template>
                </Carousel>
            </div>
        </div>
    </MainLayout>
</template>
