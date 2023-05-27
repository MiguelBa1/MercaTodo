<template>
    <div class="flex justify-between">
        <div class="flex items-center">
            <Link :href="route('products.show', product.id)">
                <img :src="'/storage/images/' + product.image" class="h-32 w-32 rounded" :alt="product.name">
            </Link>
            <div class="ml-4">
                <Link :href="route('products.show', product.id)" class="text-lg font-medium text-gray-900">
                    {{ product.name }}
                </Link>
                <button @click="removeProduct(product.id)"
                        class="text-red-500 flex items-center">
                    <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round"
                         stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Remove
                </button>
            </div>
        </div>
        <div class="flex flex-col items-center justify-center">
            <span class="text-gray-600">$ {{ (product.price * quantity).toFixed(2) }}</span>
            <div class="flex items-center justo mt-1 border px-2 py-1">
                <button @click="updateQuantity(product.id, quantity - 1)"
                        class="text-gray-500 focus:outline-none focus:text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round"
                         stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            d="M20 12H4"/>
                    </svg>
                </button>
                <span class="text-gray-700 mx-2">{{ quantity }}</span>
                <button @click="updateQuantity(product.id, quantity + 1)"
                        :class="[quantity >= product.stock ? 'cursor-not-allowed' : 'text-gray-500 focus:outline-none focus:text-gray-600']"
                        :disabled="quantity >= product.stock"
                >
                    <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round"
                         stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </button>
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

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
    quantity: {
        type: Number,
        required: true
    }
});

const emit = defineEmits(['getProductsInformation']);

const updateQuantity = async (product_id, quantity) => {
    $toast.clear();
    if (quantity === 0) {
        await removeProduct(product_id);
        return;
    }
    $toast.default('Updating quantity...')
    try {
        await store.addToCart(product_id, quantity);
        await emit('getProductsInformation');
        $toast.success('Quantity updated!')
    } catch (e) {
        const {message} = e.response.data;
        $toast.error(message);
    }
};

const removeProduct = async (product_id) => {
    $toast.clear();
    $toast.default('Removing product...')
    try {
        await store.removeFromCart(product_id);
        await emit('getProductsInformation');
        $toast.success('Product removed!')
    } catch (e) {
        $toast.error('Something went wrong!')
    }
};

</script>
