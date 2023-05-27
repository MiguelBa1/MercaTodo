<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, Link} from '@inertiajs/vue3';
import {useCartStore} from "@/store/cart";
import {computed, onMounted, ref} from "vue";
import ProductCard from "@/Pages/Cart/Partials/ProductCard.vue";
import LoadingSpinner from "@/Components/LoadingSpinner.vue";

const store = useCartStore();
const productsInformation = ref([]);

const total = computed(() => {
    return productsInformation.value.reduce((total, product) => {
        return total + (product.price * product.quantity);
    }, 0);
});

const isLoading = ref(true);

const getProductsInformation = async () => {
    if (store.cartItemsCount === 0) {
        productsInformation.value = [];
        return;
    }
    const {data} = await axios.post(route('api.cart.products.show'), {products: store.cart})
    productsInformation.value = data;
};

onMounted(async () => {
    await store.syncCart();
    await getProductsInformation();
    isLoading.value = false;
});
</script>

<template>
    <MainLayout>
        <Head>
            <title>Cart</title>
        </Head>

        <template #header>
            <h2>Cart items</h2>
        </template>
        <div v-if="!isLoading" class="mt-12 max-w-7xl mx-auto">
            <div v-if="store.cartItemsCount > 0" class="flex flex-col lg:flex-row gap-6">
                <div
                    class="w-full sm:px-6 lg:px-8 p-4 sm:p-8 shadow sm:rounded-lg grid grid-cols-1 gap-2">
                    <ProductCard v-for="product in productsInformation" :key="product.id" :product="product"
                                 :quantity="product.quantity"
                                 @getProductsInformation="getProductsInformation"
                    />
                </div>

                <div class="max-w-md h-fit w-full sm:px-6 lg:px-8 p-4 sm:p-8 shadow sm:rounded-lg">
                    <div class="flex justify-between font-bold">
                        <span>Total:</span>
                        <span>$ {{ total.toFixed(2) }}</span>
                    </div>
                    <span>Item(s): {{ store.cartItemsCount }}</span>
                    <div class="mt-4">
                        <button class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Proceed to checkout
                        </button>
                    </div>
                </div>
            </div>
            <div v-else class="py-12 flex justify-between">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="text-2xl font-bold">Your cart is empty</h2>
                    <p class="text-gray-500">Looks like you haven't added any items to your cart yet.</p>
                    <div class="mt-4">
                        <Link :href="route('home')"
                              class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Continue shopping
                        </Link>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="py-12 flex justify-between">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <LoadingSpinner/>
            </div>
        </div>
    </MainLayout>
</template>
