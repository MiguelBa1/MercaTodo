<script setup>
import {useCartStore} from "@/store/cart";
import {Link} from "@inertiajs/vue3";

const store = useCartStore();

</script>

<template>
    <div class="flex items-center">
        <Link :href="route('cart.index')" class="relative">
            <svg class="h-6 w-6 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
            <span
                class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full"
            >
                {{ store.cartItemsCount }}
            </span>
        </Link>
    </div>
</template>
