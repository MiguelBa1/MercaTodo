<template>
    <div class="flex items-center p-4 border rounded shadow-md">
        <div class="mr-4">
            <Link :href="`/products/${product.id}`">
                <img :src="'/storage/images/' + product.image" :alt="product.name" class="w-32 h-32 object-cover rounded">
            </Link>
        </div>
        <div class="flex-1">
            <Link :href="`/products/${product.id}`" class="text-lg font-medium text-gray-900">{{ product.name }}</Link>
            <div class="flex items-center mt-1 text-sm">
                <span class="text-gray-600 mr-1">Stock:</span>
                <span class="text-gray-900 font-semibold">{{ product.stock }}</span>
            </div>
            <button @click="removeProduct(product.id)" class="text-red-500 flex items-center mt-2">
                <svg class="h-5 w-5 mr-1" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Remove
            </button>
            <div class="flex items-center mt-4">
                <div class="flex items-center mr-4">
                    <button @click="updateQuantity(product.id, quantity - 1)" class="text-gray-500 focus:outline-none focus:text-gray-600">
                        <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M20 12H4"></path>
                        </svg>
                    </button>
                    <span class="text-gray-700 mx-2">{{ quantity }}</span>
                    <button @click="updateQuantity(product.id, quantity + 1)" class="text-gray-500 focus:outline-none focus:text-gray-600">
                        <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </button>
                </div>
                <span class="text-gray-600">$ {{ (product.price * quantity).toFixed(2) }}</span>
            </div>
        </div>
    </div>
</template>


<script setup>
import {useCartStore} from "@/store/cart";
import {Link} from "@inertiajs/vue3";
import {useToast} from "vue-toast-notification";

const store = useCartStore();
const $toast = useToast();

const {product, quantity, fetchProducts} = defineProps({
    product: {
        type: Object,
        required: true,
    },
    quantity: {
        type: Number,
        required: true
    },
    fetchProducts: {
        type: Function,
        required: true
    }
});

const updateQuantity = async (product_id, quantity) => {
    $toast.clear();
    if (quantity === 0) {
        await removeProduct(product_id);
        return;
    }
    if (quantity > product.stock) {
        $toast.error('Not enough stock!');
        return;
    }
    $toast.default('Updating quantity...')
    try {
        await store.addToCart(product_id, quantity);
        await fetchProducts();
        $toast.success('Quantity updated!')
    } catch (e) {
        $toast.error('Something went wrong!')
    }
};

const removeProduct = async (product_id) => {
    $toast.clear();
    $toast.default('Removing product...')
    try {
        await store.removeFromCart(product_id);
        await fetchProducts();
        $toast.success('Product removed!')
    } catch (e) {
        $toast.error('Something went wrong!')
    }
};

</script>
